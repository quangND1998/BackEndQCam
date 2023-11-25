<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import {
    mdiEye,
    mdiAccountLockOpen,
    mdiPlus,
    mdiFilter,
    mdiMagnify,
    mdiDotsVertical,
    mdiTrashCanOutline,
    mdiCodeBlockBrackets,
    mdiPencil,
    mdiLandFields
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
// import Multiselect from '@vueform/multiselect'
import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
const props = defineProps({
    product_retails: Object,
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    description: null,
    price: null,
    images: null
});
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);

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

const edit = (product) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = product.id;
    form.name = product.name;
    form.price = product.price;
    form.description = product.description;
};

const save = () => {
    console.log(form);
    if (editMode.value == true) {
        form.post(route("admin.product-retail.update", form.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true;
            },
            onSuccess: () => {
                form.reset('id', 'name', 'price', 'description', 'images');
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    } else {
        form.post(route("admin.product-retail.store"), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = false;
            },
            onSuccess: () => {
                form.reset('id', 'name', 'price', 'description', 'images');
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    }

    // form.id = permission.id;
    // form.name = permission.name
};
const changeStatus = (data, event) => {
    let query = {
        id: data.id,
        status: event.target.checked
    };
    router.post(route("admin.product-retail.changeStatus"), query, {
        preserveState: false
        // only: ["image360s", "errors", 'flash'],
    });
}

const selected = ref([])
const selectAll = computed({
    get() {
        return props.product_retails.data
            ? selected.value.length == props.product_retails.data
            : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            props.product_retails.data.forEach(function (product) {
                array_selected.push(product.id);
            });
        }
        selected.value = array_selected;
    }
});
const searchFilter = () => {
    let query = {
        search: searchVal.value
    };
    router.get(route("admin.product-retail.index"), query, {
        preserveState: true
        // only: ["image360s", "errors", 'flash'],
    });
}
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Product Retail" />
        <SectionMain class="p-3 mt-8">
            <SectionTitleLineWithButton title="Product Retail" main></SectionTitleLineWithButton>

            <!-- Modal -->
            <CardBoxModal v-model="isModalDangerActive" title="Please confirm" button="danger" has-cancel>
                <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
                <p>This is sample modal</p>
            </CardBoxModal>
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 m-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" @keyup="searchFilter()" placeholder="Search" aria-label="Search"
                            size="24" />
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green hover:bg-[#318f02] text-white p-2 hover:bg-[#008000]" :icon="mdiPlus" small
                        @click="
                            isModalActive = true;
                            editMode= false;
                            form.reset('id', 'name', 'price', 'description', 'images');
                        " label="Create Product Retail" />
                </div>
            </div>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                classSize="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-2/5 xl:w-5/12 z-50 overflow-auto"
                :title="editMode ? 'Update Product Retail' : 'Create Product Retail'">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="name" value="Name" />
                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus
                            autocomplete="name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                        <div class="">
                            <label class="input w-full" for="recipient-name">

                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Giá
                                </span>
                                <MazInputPrice v-model="form.price" label="Enter your price" currency="VND" locale="vi-VN"
                                    :min="0" @formatted="formattedPrice = $event" />
                            </label>
                            <InputError class="mt-2" :message="form.errors.price" />
                        </div>
                        <div class="mt-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                for="multiple_files">Upload Images</label>
                            <input @input="form.images = $event.target.files" multiple accept="image/*"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                id="multiple_files" type="file">
                            <InputError class="mt-2" :message="form.errors.images" />
                        </div>

                    </div>
                    <div>
                        <div class="">
                            <InputLabel for="name" value="Description" />
                            <label class="input w-full" for="recipient-name">

                                <quill-editor v-model:content="form.description" contentType="html"></quill-editor>

                            </label>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>
                    </div>
                </div>





            </CardBoxModal>
            <!-- End Modal -->
            <div class="mt-5">
                <div class="relative mt-5 ">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 flex items-center">
                                    <input type="checkbox" v-model="selectAll"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                    #
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tên
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Hình ảnh
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Giá
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Mô Tả
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Trang thái
                                </th>
                                <th scope="col" class="px-6 py-3">

                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, index) in product_retails.data" :key="product.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox" v-model="selected" :value="product.id"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        {{ index++ }}
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    {{ product.name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap mx-1">
                                        <img v-for="(image, index) in product.images" :key="index" :src="image.original_url"
                                            class="w-16 h-16 object-cover mx-1 inline-block" alt="">
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ formatPrice(product.price) }}
                                </td>
                                <td class="px-6 py-4" v-html="product.description">
                                </td>
                                <td class="px-6 py-4">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" :checked="product.status"
                                            @change="changeStatus(product, $event)">
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                        </div>
                                    </label>
                                </td>
                                <td class="px-6 py-4 ">
                                    <div class="flex ">

                                        <Dropdown align="right" width="40" class="ml-5">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <BaseButton class="bg-[#D9D9D9] border-[#D9D9D9]"
                                                        :icon="mdiDotsVertical" small />
                                                </span>
                                            </template>

                                            <template #content>
                                                <div class="w-40">
                                                    <div @click="edit(product)"
                                                        class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                                        <p class="hover:text-blue-700"> Edit</p>
                                                        <BaseButton :icon="mdiPencil" small class="text-[#2264E5]"
                                                            type="button" data-toggle="modal" data-target="#exampleModal" />
                                                    </div>
                                                    <div @click="Delete(product.id)"
                                                        class="flex justify-between items-center px-4  text-sm text-[#D12953] cursor-pointer  font-semibold">
                                                        <p class="hover:text-red-700"> Delete</p>
                                                        <BaseButton :icon="mdiTrashCanOutline" small
                                                            class="text-[#D12953]" />
                                                    </div>

                                                </div>
                                            </template>
                                        </Dropdown>
                                    </div>

                                </td>
                            </tr>


                        </tbody>
                    </table>
                    <Pagination :links="product_retails.links" />
                </div>
            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
