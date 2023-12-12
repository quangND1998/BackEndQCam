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
import InvoiceInformation from '@/Pages/Test/InvoiceInformation.vue'


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
            <div class="mx-5 mt-10">
                <div class="min-[320px]:block sm:block md:grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                       <InvoiceInformation />
                        <div class="mt-5">
                            <h3 class="text-[17px] font-bold">Thông tin liên hệ</h3>
                            <p class="text-[#686868] text-base my-2">Khách hàng: <strong class="ml-3">(Ông) Nguyễn
                                    Mạnh
                                    Hùng</strong></p>
                            <p class="text-[#686868] text-base my-2">Số điện thoại: <strong class="ml-3">0988 198
                                    554</strong></p>
                            <p class="text-[#686868] text-base my-2">Địa chỉ: <strong class="ml-3">Thôn A, xã B,
                                    huyện D,
                                    tỉnh E</strong></p>
                        </div>
                        <div class="mt-5">
                            <div class="relative overflow-x-auto">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-[#F0F0F0] dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Sản phẩm
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Thời gian áp dụng
                                            </th>

                                            <th scope="col" class="px-6 py-3">
                                                Tổng
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <th scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                Gói nhận nuôi cây 1 năm
                                            </th>
                                            <td class="px-6 py-4">
                                                20/11/2023 - 20/11/2024
                                            </td>

                                            <td class="px-6 py-4">
                                                1.000.000đ
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex w-full justify-end mt-5">
                                <!-- <div class="w-1/2"></div> -->
                                <div class="min-[320px]:w-full md:w-1/2">
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
                                        <p class="text-sm text-[#686868] font-bold">1.000.000đ</p>
                                    </div>
                                    <div class="flex justify-between my-2">
                                        <p class="text-sm text-[#686868] font-bold">Đã thanh toán</p>
                                        <p class="text-sm text-[#686868] font-bold">1.000.000đ</p>
                                    </div>
                                    <div class="flex justify-between my-2">
                                        <p class="text-sm text-[#686868] font-bold">Còn lại</p>
                                        <p class="text-sm text-[#686868] font-bold">1.000.000đ</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="text-center ">
                        <h1 class="my-2">Đơn hàng hết hạn trong</h1>
                        <h1 class="text-[30px] font-semibold text-[#686868] my-2">3:30</h1>
                        <p class="font-semibold text-base text-[#FF0000]">Chưa thanh toán</p>
                        <div class="mt-5">
                            <img src="/assets/images/qr.png" class="w-44 h-44 m-auto" alt="">
                            <p class="text-base my-1 ">Nội dung:  12535</p>
                            <p class="text-base my-1 ">STK: 9999999</p>
                            <p class="text-base my-1 ">CTK: CÔNG TY ABC</p>
                            <p class="text-base my-1 ">NGÂN HÀNG: ABC</p>
                        </div>
                        <BaseButton color="info"
                                class="bg-black text-white p-2 mt-3 w-3/5 text-center justify-center rounded-lg"
                                :icon="mdiContentSaveMove" small label="In đơn hàng" />
                    </div>

                </div>

            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>

