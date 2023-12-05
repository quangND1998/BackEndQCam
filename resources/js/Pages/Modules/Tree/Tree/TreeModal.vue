
<script setup>
import { computed, ref, inject, watch, toRef, onMounted, onUnmounted, reactive } from 'vue'
import { mdiMinus, mdiPlus, mdiChevronDown, mdiChevronUp } from '@mdi/js'

import { useForm } from '@inertiajs/vue3';
import CardBoxModal from '@/Components/CardBoxModal.vue'
import { router } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { useTreeStore } from '@/stores/tree.js'
import { emitter } from '@/composable/useEmitter';
import MazInputPrice from 'maz-ui/components/MazInputPrice';
import BaseIcon from '@/Components/BaseIcon.vue'


const swal = inject('$swal')
const store = useTreeStore()
const form = useForm({
    id: null,
    name: null,
    images: null,
    images_thumb: null,
    description: null,
    address: null,
    status: null,
    state: "public",
    user_manual: null,
    numberOfBathrooms: null,
    terms_policy: null,
    price: null,
    transfer_value : null,
    price_origin: null

});
onMounted(() => {
    console.log('mounted!')

})
// const editorConfig = reactive({
//     // The configuration of the editor.
// })
const editMode = computed(
    {
        get() {
            return store.editMode;
        },
        set(value) {
            store.changeEditMode(value)
        }
    }
)
const formattedPrice = ref()
onMounted(() => {
    emitter.on('editTree', async (data) => {

        form.id = data.id
        form.name = data.name;
        form.address = data.address;
        form.price = data.price;
        form.transfer_value = data.transfer_value;
        form.price_origin = data.price_origin;
        form.state = data.state;
        form.status = data.status;
        form.description = data.description;
        form.user_manual = data.user_manual;
    });


})
onUnmounted(() => {
    emitter.off('editTree', listener)
})
const listener = (data) => {

}
const isModalTree = computed({

    get() {
        return store.isModalTree;
    },
    set(value) {
        store.changeisModalTree(value)
        store.setTree(null)
    }

})
const changeStatus = () => {
    store.changeStatus()
}
const isModalActive = ref(false)
const isImport = ref(false)
const props = defineProps({
    land: Object,
})


const editorData = ref('<p>Content of the editor.</p>')
// const editorConfig = ref({})

const saveTree = () => {
    if (editMode.value == true) {
        form.post(route('admin.land.tree.update', form.id), {
            onError: () => {

                isModalTree.value = true;
                editMode.value = true
            },
            onSuccess: () => {
                form.reset()
                isModalTree.value = false;
                editMode.value = false
            }
        });
    }
    else {
        form.post(route('admin.land.tree.store', props.land.id), {
            onError: () => {
                isModalTree.value = true;
                editMode.value = false
            },
            onSuccess: () => {
                form.reset()
                isModalTree.value = false;
                editMode.value = false
            },
        });
    }

}
// const editorConfig = {
//   toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
//   style:['height:200px'],
//   heading: {
//     options: [
//       { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
//       { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
//       // ... add more heading options as needed
//     ],
//   },
// };

const state = reactive({
    content: '<p>2333</p>',
    _content: '',
    editorOption: {
        placeholder: 'core',
        modules: {

        },

    },
    disabled: false
})

</script>

<template>
    <div>
        <!-- {{ apartment }} -->
        <CardBoxModal v-model="isModalTree" buttonLabel="Save" has-cancel @confirm="saveTree" button="warning"
            classSize="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-2/5 xl:w-6/12 z-50 overflow-auto"
            :title="editMode ? 'Chỉnh sửa' : 'Tạo mới'">
            <div v-if="form.progress" class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                    :style="`width: ${form.progress.percentage}%`"> {{ form.progress.percentage }}%</div>
            </div>
            <div class="mt-0 pt-0 overflow-auto">


                <div
                    class="grid  gap-4 my-4 min-[320px]:grid-cols-2 min-[320px]:my-0 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-2">
                    <div>
                        <div class="my-4">
                            <label class="input w-full" for="recipient-name">
                                <input class="input__field border" type="text" placeholder="" v-model="form.name">
                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">
                                    Tên cây</span>
                            </label>
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="py-4">
                            <label class="input w-full" for="recipient-name">
                                <select id="project" v-model="form.state"
                                    class="h-[42px] bg-gray-50 border border-gray-300 text-gray-900 text-sm input__field rounded-lg focus:ring-blue-500 focus:border-blue-500 block  w-full">
                                    <option value="public"> Mở bán</option>
                                    <option value="private"> Chưa mở bán</option>

                                </select>
                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Mở bán</span>
                            </label>
                            <InputError class="mt-2" :message="form.errors.state" />
                        </div>


                        <div class="py-4">
                            <label class="input w-full" for="recipient-name">

                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Giá bán
                                </span>
                                <MazInputPrice v-model="form.price"  currency="VND" locale="vi-VN"
                                    :min="0" @formatted="formattedPrice = $event" />
                            </label>
                            <InputError class="mt-2" :message="form.errors.price" />
                        </div>
                        <div class=" ">
                            
                           
                            <InputLabel for="name_amenities" value="Ảnh đại diện" />
                            <label for="dropzone-images_thumb"
                                class="flex flex-col items-center justify-center w-full h-36 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 px-2">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m²-2 2 2" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 text-center"><span
                                            class="font-semibold">Click
                                            to upload</span> or drag and drop</p>
                                </div>

                                <input id="dropzone-images_thumb" type="file" class="hidden"
                                    @input="form.images_thumb = $event.target.files[0]" accept="image/*" />
                            </label>
                            <InputError class="mt-2" :message="form.errors.images" />
                        </div>

                    </div>

                    <div>
                        <div class="py-4">
                            <label class="input w-full" for="recipient-name">
                                <input class="input__field border" type="text" placeholder="" v-model="form.address">
                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Địa
                                    chỉ</span>
                            </label>
                            <InputError class="mt-2" :message="form.errors.address" />
                        </div>
                        <div class="my-4">
                            <label class="input w-full" for="recipient-name">
                                <input class="input__field border " type="text" placeholder="" v-model="form.status">
                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">
                                    Tình trạng cây</span>
                            </label>
                            <InputError class="mt-2" :message="form.errors.status" />
                        </div>
                        <div class="py-4">
                            <label class="input w-full" for="recipient-name">
                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Giá gốc
                                </span>
                                <MazInputPrice v-model="form.price_origin"  currency="VND" locale="vi-VN"
                                    :min="0" @formatted="price_origin = $event" />
                            </label>
                            <InputError class="mt-2" :message="form.errors.price_origin" />
                        </div>
                        <!-- <div class="py-4">
                            <label class="input w-full" for="recipient-name">

                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Giá chuyển nhượng
                                </span>
                                <MazInputPrice v-model="form.transfer_value"  currency="VND" locale="vi-VN"
                                    :min="0" @formatted="transfer_value = $event" />
                            </label>
                            <InputError class="mt-2" :message="form.errors.transfer_value" />
                        </div>
                        <div class="py-4">
                            <label class="input w-full" for="recipient-name">

                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Giá chuyển nhượng
                                </span>
                                <MazInputPrice v-model="form.transfer_value"  currency="VND" locale="vi-VN"
                                    :min="0" @formatted="transfer_value = $event" />
                            </label>
                            <InputError class="mt-2" :message="form.errors.transfer_value" />
                        </div> -->
                        <div class="">
                            <!-- <InputLabel for="name_amenities" value="Bộ sưu tập ảnh" />
                            <label for="dropzone-images"
                                class="flex flex-col items-center justify-center w-full h-36 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 px-2">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m²-2 2 2" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 text-center"><span
                                            class="font-semibold">Click
                                            to upload</span> or drag and drop</p>
                                </div>

                                <input id="dropzone-images" type="file" class="hidden"
                                    @input="form.images = $event.target.files" multiple accept="image/*" />
                            </label>
                            <InputError class="mt-2" :message="form.errors.images" /> -->
                            <div class="flex">
                                <div>

                                </div>
                                <div class="w-16 h-16 border-dashed items-center border-gray-500 mx-1 justify-center flex border rounded-lg">
                                    <BaseIcon :path="mdiPlus" :size="16" />
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <div>
                        <div class="my-4">
                            <label class="input w-full" for="recipient-name">

                                <quill-editor v-model:content="form.user_manual" contentType="html"></quill-editor>
                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Hướng dẫn sử dụng</span>
                            </label>
                            <InputError class="mt-2" :message="form.errors.user_manual" />
                        </div>
                    </div>
                    <div>
                        <div class="my-4">
                            <label class="input w-full" for="recipient-name">

                                <quill-editor v-model:content="form.description" contentType="html"></quill-editor>
                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Mô
                                    tả</span>
                            </label>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>
                    </div>

                </div>

            </div>
        </CardBoxModal>

    </div>
</template>


<style scoped>
.input {
    position: relative;
}

:root {
    --color-light: white;
    --color-dark: #212121;
    --color-signal: #252525;
    --color-background: var(--color-light);
    --color-text: var(--color-dark);
    --color-accent: var(--color-signal);
    --size-bezel: .5rem;
    --size-radius: 8px;
    line-height: 1.4;
    font-family: "Inter", sans-serif;
    font-size: calc(.6rem + .4vw);
    color: var(--color-text);
    background: var(--color-background);
    font-weight: 300;
    /* padding: 0 calc(var(--size-bezel) * 3); */
}

.input__field:not(:placeholder-shown)+.input__label {
    transform: translate(0.25rem, -65%) scale(0.8);
    color: var(--color-accent);
}

.input__field {
    box-sizing: border-box;
    display: block;
    width: 100%;
    border: 1px solid rgb(163, 163, 163);
    padding: 0.5rem;
    color: rgb(163, 163, 163);
    background: transparent;
    border-radius: 8px;
}

.input__label {
    color: rgb(32, 32, 32) !important;
    position: absolute;
    left: 0;
    top: 0;
    padding: calc(var(--size-bezel) * 0.75) calc(var(--size-bezel) * .5);
    margin: calc(var(--size-bezel) * 0.75 + 3px) calc(var(--size-bezel) * .5);

    white-space: nowrap;
    transform: translate(0, 0) !important;
    transform-origin: 0 0;
    background: #ffffff;
    transition: transform 120ms ease-in;

    line-height: 1.2;
    transform: translate(0.25rem, -65%) scale(0.8) !important;
}



.ck-editor__editable {
    min-height: 200px;
}
</style>
