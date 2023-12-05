<script setup>
import { computed, ref, inject, reactive,onMounted } from "vue";
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
    mdiLandFields,
    mdiContentSaveMove
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
import { initFlowbite } from 'flowbite'
import Contract from '@/Pages/Test/Contract.vue'
import InvoiceInformation from './InvoiceInformation.vue'


const props = defineProps({
    order: Object,
    status: String,
    status: String,
    from: String,
    to: String,
    statusGroup: Array
});
onMounted(() => {
    getNow();
    setInterval(getNow(), 1000);
})
const  timestamp= ref('');
const getNow= ()=> {
      const today = new Date();
      timestamp.value = parseInt(today.getTime() / 1000);
}
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đơn hàng" />
        <SectionMain>
            <div class="mx-5">
                <div class="min-[320px]:block sm:block md:grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <InvoiceInformation :order="order" />
                       
                        <div class="mt-5">
                            <h3 class="text-[17px] font-bold">Thông tin liên hệ</h3>
                            <p class="text-[#686868] text-base my-2">Khách hàng: <strong class="ml-3">
                                    {{ order.customer ? order.customer.name : order.name }}

                                </strong></p>
                            <p class="text-[#686868] text-base my-2">Số điện thoại: <strong class="ml-3">
                                    {{ order.customer ? order.customer.phone_number : order.phone_number }}</strong></p>
                            <p class="text-[#686868] text-base my-2">Địa chỉ:
                                <strong class="ml-3">{{ order.address }}, {{ order.district }}, {{ order.wards }} ,{{
                                    order.city }} </strong>
                            </p>
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
                                            <td scope="row"
                                                class="px-6 py-4 border-0  whitespace-nowrap ">
                                                <p>Gói nhận nuôi {{ order?.product_service?.name }}</p>
                                                <p>Cây của bạn có mã là {{ order?.product_service_owner?.trees.length > 0 ? order?.product_service_owner.trees[0].address : null }}</p>
                                            </td>
                                            <td class="px-6 py-4 border-0">
                                                {{ order?.time_approve }} - {{ order?.time_end }}
                                            </td>

                                            <td class="px-6 py-4 border-0">
                                                {{ formatPrice(order?.product_service.price) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-sm text-[#686868] mt-3 mb-3 font-bold">Lịch sử thanh toán</p>
                            <div class="relative overflow-x-auto">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-[#F0F0F0] dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="border-0 px-6 py-3">
                                                #
                                            </th>
                                            <th scope="col" class="border-0 px-6 py-3">
                                                Loại
                                            </th>
                                            <th scope="col" class="border-0 px-6 py-3">
                                                Ngày
                                            </th>
                                            <th scope="col" class="px-6 py-3">Số tiền</th>
                                            <th scope="col" class="px-6 py-3">Trạng thái</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                            v-for="(payment, index) in order?.history_payment" :key="payment.id">
                                            <td class="border-0 px-6 py-3">
                                                {{ index + 1 }}
                                            </td>
                                            <td class="border-0 px-6 py-3">{{ payment?.payment_method == 'cash' ? 'tiền mặt' : "thẻ"}}</td>
                                            <td class="border-0 px-6 py-3">{{ payment?.payment_date }}</td>
                                            <td class="border-0 px-6 py-3">{{ formatPrice(payment?.amount_received) }}₫</td>
                                            <td class="border-0 px-6 py-3">
                                                <p class="btn_label"
                                                    :class="order?.price_percent < order?.grand_total ? 'partiallyPaid' : order?.price_percent == 0 ? 'unpaid' : 'paid'">
                                                    {{ order?.price_percent < order?.grand_total ? 'Thanh toán từng phần' :
                                                        order?.price_percent == 0 ? 'Chưa thanh toán' : 'Đã thanh toán' }}</p>
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
                                        <p class="text-sm text-[#686868] font-bold"> {{
                                            formatPrice(order?.product_service?.price) }} vnđ</p>
                                    </div>
                                    <div class="flex justify-between my-2">
                                        <p class="text-sm text-[#686868] font-bold">VAT({{ order?.vat }}%)</p>
                                        <p class="text-sm text-[#686868] font-bold">{{ formatPrice(order?.vat * (order?.product_service?.price + ((order?.discount_deal * order?.product_service?.price) / 100))/100 ) }} vnđ</p>
                                    </div>
                                    <div class="flex justify-between my-2">
                                        <p class="text-sm text-[#686868] font-bold">Vận chuyển</p>
                                        <p class="text-sm text-[#686868] font-bold">Miễn phí</p>
                                    </div>
                                    <div class="flex justify-between my-2">
                                        <p class="text-sm text-[#686868] font-bold">Ưu đãi</p>
                                        <p class="text-sm text-[#686868] font-bold">{{
                                            formatPrice(((order?.product_service?.price) * (order?.discount_deal)) / 100) }} vnđ</p>
                                    </div>
                                    <div class="flex justify-between my-2">
                                        <p class="text-sm text-[#686868] font-bold">Tổng cộng</p>
                                        <p class="text-sm text-[#686868] font-bold">{{ formatPrice(order?.grand_total) }}
                                            vnđ</p>
                                    </div>
                                    <div class="flex justify-between my-2">
                                        <p class="text-sm text-[#686868] font-bold">Đã thanh toán</p>
                                        <p class="text-sm text-[#686868] font-bold">{{ formatPrice(order?.price_percent) }}
                                            vnđ</p>
                                    </div>
                                    <div class="flex justify-between my-2">
                                        <p class="text-sm text-[#686868] font-bold">Còn lại</p>
                                        <p class="text-sm text-[#686868] font-bold">{{ formatPrice(order?.grand_total -
                                            order?.price_percent) }} vnđ</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="text-center ">
                        <h1 class="my-2">Đơn hàng hết hạn trong</h1>
                        <h1 class="text-[30px] font-semibold text-[#686868] my-2">
                          
                        <vue-countdown class="block" v-if="order.time_expried && timestamp != null && (order.time_expried - timestamp) > 0"
                          :time="(order.time_expried - timestamp) * 1000" 
                          v-slot="{days, hours, minutes, seconds }">
                          {{ days }}:{{ hours }} : {{ minutes }}: {{ seconds }}
                        </vue-countdown>  
                        <span class="font-semibold text-yellow-600 text-center"  v-if="order.time_expried && timestamp != null && (order.time - timestamp) < 0"> Hết hạn </span>                  
                        </h1>
                        <p class="font-semibold text-base text-[#FF0000]">Chưa thanh toán</p>
                        <div class="mt-5">
                            <img src="/assets/images/qr.png" class="w-44 h-44 m-auto" alt="">
                            <p class="text-base my-1 ">Nội dung: 12535</p>
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
</LayoutAuthenticated></template>
<style src="@vueform/multiselect/themes/default.css"></style>
