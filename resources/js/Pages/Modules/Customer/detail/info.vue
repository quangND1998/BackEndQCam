<script setup>
import { computed, ref, inject, watch, toRef } from 'vue'
import CRMLayout from '@/Layouts/CRMLayout.vue';
import { useForm } from '@inertiajs/vue3';
import SectionMain from '@/Components/SectionMain.vue'
import { Head } from '@inertiajs/vue3'
import CardBoxModal from '@/Components/CardBoxModal.vue'
import { mdiReceiptText, mdiPencilOutline, mdiPlus, mdiTrashCan } from '@mdi/js'
import BaseButton from '@/Components/BaseButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue';
const props = defineProps({
    project: Object,
    amenities: Array,
});
const swal = inject('$swal')
const form = useForm({
    id: null,
    // search: props.filters.search,
    name: '',
    image: null,

});
const isModalActive = ref(false)
const editMode = ref(false)
const edit = (amenities) => {
    isModalActive.value = true
    editMode.value = true
    form.id = amenities.id;
    form.name = amenities.name;

}
const crumbs = ref([

    {
        route: "admin.project.amenities.index",
        parma: props.project.id,
        name: props.project.name
    },
    {
        route: "admin.project.amenities.index",
        parma: props.project.id,
        name: "Amenities"
    }
])
const save = () => {
    if (editMode.value == true) {
        form.post(route('admin.amenities.update', form.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true
            },
            onSuccess: () => {
                form.reset('name', 'id', 'image')
                isModalActive.value = false;
                editMode.value = false
            }
        });
    }
    else {
        form.post(route('admin.project.amenities.store', props.project.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = false
            },
            onSuccess: () => {
                form.reset('name', 'id', 'image')
                isModalActive.value = false;
                editMode.value = false
            },
        });
    }

}
const Delete = (id) => {
    swal.fire({
        title: 'Are you sure?',
        text: "Delete this Amenities!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log(id)
            form.delete(route('admin.amenities.delete', id), {
                onSuccess: () => {
                    swal.fire(
                        'Deleted!',
                        'Your Amenities has been deleted.',
                        'success'
                    )
                }
            });

        }
    })
}
</script>

<template>
    <CRMLayout :project="project" :crumbs="crumbs">

        <Head title="Amenities" />
        <SectionMain>
            <div class=" p-6 flex-auto">
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <InputLabel for="Name" value="Name" />
                            <TextInput id="Name" v-model="form.name" type="text"
                                class="mt-1 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                :class="form.errors.name ? 'border-red-500' : ''" required autofocus autocomplete="name" />
                            <InputError class="mt-2" :message="form.errors.name" />
                            <InputLabel for="iamge" value="Image" />
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file"
                                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                                class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX.
                                            800x400px)</p>
                                    </div>
                                    <input id="dropzone-file" @input="form.image = $event.target.files[0]" type="file"
                                        class="hidden" accept="image/*" />
                                </label>
                            </div>
                        </div>
                    </div>
        </SectionMain>
    </CRMLayout>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
