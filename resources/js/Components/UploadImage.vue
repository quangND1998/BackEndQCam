
<script setup>
import { computed, ref, inject, watch, toRef, onMounted, onUnmounted, reactive } from 'vue'
import { mdiMinus, mdiPlus, mdiChevronDown, mdiChevronUp, mdiDelete } from '@mdi/js'

import { useForm } from '@inertiajs/vue3';

import { router } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue';
import { useTreeStore } from '@/stores/tree.js'
import { emitter } from '@/composable/useEmitter';
import BaseIcon from '@/Components/BaseIcon.vue'
import { v4 as uuid } from 'uuid'
import axios from 'axios';

const swal = inject('$swal')
const store = useTreeStore();
const images = ref([]);
const form = useForm({
    id: null,
    name: null,
    images: [],
});
const props = defineProps({
    id: {
      type: String,
      default() {
        return uuid()
      },
    },
    max_files: {
        type: Number,
        required: false,
        default: 1
    },
    modelValue: Array|File,
    label: {
        type: String,
        required: false,
        default: 'Upload Ảnh'
    },
    multiple: {
        type: Boolean,
        required: false,
        default: false
    },
    old_images: {
        type: Array,
        required: false,

    }
})

const emit = defineEmits(['update:modelValue']);
const DeleteImage = (index) => {
    form.images.splice(index, 1);
    images.value.splice(index, 1);
}
onMounted(() => {
    console.log('mounted!')

})

onMounted(() => {
    emitter.on('editTree', async (data) => {

    });


})
onUnmounted(() => {
    emitter.off('editTree', listener)
})
const listener = (data) => {

}






const onFileChange = (e) => {
    const files = e.target.files;

    if (form.images.length == props.max_files) {
        return
    }
    if (files.length > 0) {
        if (props.max_files > 1) {
            setFiles(files)
        }
        else {
            emit('update:modelValue', files[0])
            form.images.push(files[0])
            images.value.push({
                name: files[0].name,
                image: URL.createObjectURL(files[0])
            });
        }

    }

}

const setFiles = (files) => {
    for (var i = 0; i < files.length; i++) {

        if (form.images.length < props.max_files) {

            form.images.push(files[i])
            emit('update:modelValue', form.images)
            images.value.push({
                name: files[i].name,
                image: URL.createObjectURL(files[i])
            });
        }

    }
}
const Delete = (img) => {
    swal
        .fire({
            title: "Bạn có muốn?",
            text: "Xóa ảnh này!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes!",
        })
        .then((result) => {
            if (result.isConfirmed) {

                axios.delete(`/media/delete/${img.id}`).then(res=>{
                    console.log(res);
                    let index = props.old_images.findIndex(e => e.id== res.data.id);
                    if(index !== -1){
                        props.old_images.splice(index, 1)
                    }
                })
            }
        });
}
</script>

<template>
    <div>
        <div class="">
            <InputLabel for="name_amenities" :value="label" />


            <div class="flex">
                <div class="flex flex-wrap">
                    <div class="w-16 h-14 relative m-1 border border-gray-400 rounded-lg"
                        v-for="(img, index) in old_images " :key="index">
                        <BaseIcon :path="mdiDelete" class="absolute right-0 top-0 text-red-600 cursor-pointer hover:text-red-700  " @click="Delete(img)"
                            size="16">
                        </BaseIcon>
                        <img :src="img.original_url" class="w-16 h-14 object-cover rounded-lg" alt="">
                    </div>
                    <div class="w-16 h-14 relative m-1 border border-gray-400 rounded-lg" v-for="(img, index) in images "
                        :key="index">
                        <BaseIcon :path="mdiDelete" @click="DeleteImage(index)"
                            class="absolute right-0 top-0 text-red-600 cursor-pointer hover:text-red-700  " size="17">
                        </BaseIcon>
                        <img :src="img.image" class="w-16 h-14 object-cover rounded-lg" alt="">
                    </div>
                  
                    <label :for="id" v-if="form.images.length < max_files"
                        class="cursor-pointer w-16 h-16 border-dashed items-center border-gray-500 mx-1 justify-center flex border rounded-lg">
                        <BaseIcon :path="mdiPlus" class="" :size="16" />
                    </label>
                    <input @change="onFileChange" v-if="form.images.length < max_files"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 hidden"
                        :id="id" type="file" :multiple="multiple" accept="image/*">

                </div>

            </div>

        </div>
    </div>
</template>


<style scoped></style>
