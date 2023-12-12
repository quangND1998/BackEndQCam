<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";
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

import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'

defineProps({
    lands: Object,
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    state: null,
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
const selected = ref([])
const selectAll = computed({
    get() {
        return props.customers.data
            ? selected.value.length == props.customers.data
            : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            props.customers.data.forEach(function (image) {
                array_selected.push(image.id);
            });
        }
        selected.value = array_selected;
    }
});
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Product " />
        <SectionMain>
            <SectionTitleLineWithButton title="Product " main></SectionTitleLineWithButton>

            <!-- Modal -->
            <CardBoxModal v-model="isModalDangerActive" title="Please confirm" button="danger" has-cancel>
                <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
                <p>This is sample modal</p>
            </CardBoxModal>
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 m-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" placeholder="Search" aria-label="Search" size="24" />
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 hover:bg-[#008000]" :icon="mdiPlus" small
                        @click="
                            isModalActive = true;
                        form.reset();
                        " label="Thêm sản phẩm" />
                </div>
            </div>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                classSize="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-3/5 xl:w-8/12 z-50 overflow-auto"
                :title="editMode ? 'Sửa Sản Phẩm' : 'Thêm sản phẩm'">
                <div class="w-3/12">
                    <form>
                        <label for="default-search"
                            class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="default-search"
                                class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search" required>

                        </div>
                    </form>
                </div>
                <div v-if="selected>1">
                    <p class="text-end text-blue-700">Thêm (15) sản phẩm đã chọn vào chương trình </p>
                </div>
                <div class="relative mt-5 ">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 flex items-center">
                                    <input type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                    #
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tên
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    Giá
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Chiết Khấu
                                </th>



                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>
                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>
                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>
                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>
                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>
                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>
                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>
                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>
                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>
                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>
                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>
                            </tr><tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>
                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>
                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>
                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </CardBoxModal>
            <!-- End Modal -->
            <div class="mt-5">

                <div class="relative mt-5 ">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 flex items-center">
                                    <input type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                    #
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tên
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    Giá
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Chiết Khấu
                                </th>

                                <th scope="col" class="px-6 py-3">

                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        1
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    Product 1
                                </td>

                                <td class="px-6 py-4">
                                    150.000 VND
                                </td>
                                <td class="px-6 py-4">
                                    hhhh
                                </td>

                                <td class="px-6 py-4 ">
                                    <div class="flex ">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer">
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                            </div>
                                        </label>
                                        <Dropdown align="right" width="40" class="ml-5">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <BaseButton class="bg-[#D9D9D9] border-[#D9D9D9]"
                                                        :icon="mdiDotsVertical" small />
                                                </span>
                                            </template>

                                            <template #content>
                                                <div class="w-40">
                                                    <div @click="edit(image)"
                                                        class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                                        <p class="hover:text-blue-700"> Edit</p>
                                                        <BaseButton :icon="mdiPencil" small class="text-[#2264E5]"
                                                            type="button" data-toggle="modal" data-target="#exampleModal" />
                                                    </div>
                                                    <div @click="Delete(image.id)"
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

                </div>
            </div>

        </SectionMain>
</LayoutAuthenticated></template>

