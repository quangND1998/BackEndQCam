<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import OrderBar from "@/Pages/Modules/Order/OrderBar.vue";

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
import { initFlowbite } from 'flowbite'
import Contract from '@/Pages/Test/Contract.vue'


const props = defineProps({
    orders: Object,
    status: String,
    status: String,
    from: String,
    to: String,
    statusGroup: Array
});


</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đơn hàng" />
        <SectionMain>
            <Contract />
            <div class="min-[320x]:w-full grid grid-cols-3 gap-4">
                <div class=" col-span-2 mt-2 w-full">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-5 mt-4">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Số lượng
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Giá
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Tổng
                                    </th>
                                    <th scope="col" class="px-6 py-3">

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b ">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                        <span></span>
                                    </th>
                                    <td class="px-6 py-4 ">
                                        <select id="countries"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-44 p-2.5 ">
                                            <option value="Tiền"></option>
                                            <option value="%">%</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number"
                                            class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 hover:border-gray-500 w-28">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number"
                                            class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 hover:border-gray-500 w-28">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number"
                                            class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 hover:border-gray-500 w-32">
                                    </td>
                                    <td class="px-6 py-4">
                                        <svg @click="deletetable(value.id)" width="15" height="15" viewBox="0 0 15 15"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M1 14L7.50002 7.50003M7.50002 7.50003L14 1M7.50002 7.50003L1 1M7.50002 7.50003L14 14"
                                                stroke="#4E4E4E" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                        classSize="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-2/5 xl:w-8/12 z-50 overflow-auto"
                        :title="editMode ? '' : 'Thêm sản phẩm'">


                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-full">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-4">
                                            <div class="flex items-center">
                                                <input id="checkbox-all-search" type="checkbox"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Hình ảnh
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Giá
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Mô tả
                                        </th>

                                        <th scope="col" class="px-6 py-3">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="w-4 p-4">
                                            <div class="flex items-center">
                                                <input id="checkbox-table-search-1" type="checkbox"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                            </div>
                                        </td>
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            Apple MacBook Pro 17"
                                        </th>
                                        <td class="px-6 py-4">
                                            Silver
                                        </td>
                                        <td class="px-6 py-4">
                                            Laptop
                                        </td>
                                        <td class="px-6 py-4">
                                            Yes
                                        </td>

                                        <td class="flex items-center px-6 py-4">
                                            <a href="#"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                            <a href="#"
                                                class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3">Remove</a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </CardBoxModal>
                    <!-- <div class="min-[320px]:block md:flex w-full">
                        <div class="min-[320px]:w-full md:w-1/2">
                            <BaseButton color="info" @click="
                                isModalActive = true;" class="bg-btn_green text-white p-2 hover:bg-[#008000]"
                                :icon="mdiPlus" small label="Thêm sản phẩm" />
                        </div>

                        <div class="min-[320px]:w-full md:w-1/2 min-[320px]:mt-3 md:mt-0">
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868] font-bold">Tổng</p>
                                <p class="text-sm text-[#686868] font-bold">1.000.000đ</p>
                            </div>
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868] font-bold">VAT(x%)</p>
                                <p class="text-sm text-[#686868] font-bold">100.000đ</p>
                            </div>
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868] font-bold">Vận chuyển</p>
                                <p class="text-sm text-[#686868] font-bold">Miễn phí</p>
                            </div>
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868] font-bold">Ưu đãi</p>
                                <p class="text-sm text-[#686868] font-bold">100.000đ</p>
                            </div>
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868] font-bold">Tổng cộng</p>
                                <p class="text-sm text-[#686868]">1.000.000đ</p>
                            </div>
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868]">Đã thanh toán</p>
                                <p class="text-sm text-[#686868] font-bold">1.000.000đ</p>
                            </div>
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868] font-bold">Còn lại</p>
                                <p class="text-sm text-[#686868] font-bold">1.000.000đ</p>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div>
                    <div class="my-3">
                        <BaseButton color="info"
                            class="bg-orange-500 hover:bg-orange-600 text-white p-2 w-full text-center justify-center rounded-lg"
                            :icon="mdiContentSaveMove" small label="Lưu đơn hàng" />
                    </div>
                    <div class="my-3">
                        <BaseButton color="info"
                            class="bg-lime-600 hover:bg-lime-700 text-white p-2 w-full text-center justify-center"
                            :icon="mdiEye" small label="Xem đơn hàng" />
                    </div>
                    <div class="my-3">
                        <BaseButton color="info"
                            class="bg-blue-900 hover:bg-blue-900 text-white p-2 w-full text-center justify-center"
                            :icon="mdiCreditCardSettingsOutline" small label="Thanh toán ngay" />
                    </div>
                    <div class="my-3">
                        <BaseButton color="info"
                            class="bg-gray-700 hover:bg-gray-800 text-white p-2 w-full text-center justify-center"
                            :icon="mdiContentCopy" small label="Sao chép đường dẫn" />
                    </div>
                    <div class="my-3">
                        <BaseButton color="info" class="bg-black text-white p-2 w-full text-center justify-center"
                            :icon="mdiImport" small label="In đơn hàng" />
                    </div>
                </div>
            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>












































