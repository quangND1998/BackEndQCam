<script setup>
import { computed, ref, inject } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import {
    mdiPlus,
    mdiFilter, mdiPencilOutline, mdiTrashCan
} from '@mdi/js'
import BaseButton from "@/Components/BaseButton.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import Breadcrumb from '@/Components/Breadcrumb.vue';
const props = defineProps({
    land: Object,
    payments: Object
});
const searchVal = ref("");
const swal = inject("$swal");


const searchTree = () => {
    router.get(route(`admin.land.payment.index`, props.land.id),
        { search: searchVal.value },
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
</script>
<template>
    <LayoutAuthenticated>

        <Breadcrumb :crumbs="crumbs" />
        <SectionMain class="p-3">
            <SectionTitleLineWithButton title="Lịch sự thành toán đơn hàng" main></SectionTitleLineWithButton>


          
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 my mr-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" @keyup="searchTree" placeholder="Search" aria-label="Search"
                            size="24" />
                    </div>
                </div>
              
            </div>
          
            <div class="overflow-x-auto relative  sm:rounded-lg mt-2">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700  bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6 text-xs">STT</th>
                            <th scope="col" class="py-3 px-6 text-xs">Mã đơn hàng</th>
                            <th scope="col" class="py-3 px-6 text-xs">Tiền thanh toán</th>
                            <th scope="col" class="py-3 px-6 text-xs">Trạng thái thanh toán</th>
                            <th scope="col" class="py-3 px-6 text-xs">Phương thức thành toán</th>
                            <th scope="col" class="py-3 px-6 text-xs">Ngày thanh toán</th>
                            <th scope="col" class="py-3 px-6 text-xs">Ngân hàng</th>
                            <th scope="col" class="py-3 px-6 text-xs">Số thẻ</th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(payment, index) in payments.data" :key="index"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                index + 1 }}</th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                payment.OrderNo }}</th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                formatPrice(payment.OrderCash) }}</th>
                            <th scope="row"
                                class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white flex flex-wrap">
                                <p class="btn_label mr-2"
                                    :class="payment.PaymentStatus  ==  0 ? 'unpaid' : 'paid'">
                                    {{ payment.PaymentStatus  ==  0
                                        ? 'Chưa thanh toán' : 'Đã thanh toán' }}</p> 
                            </th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <strong>{{ payment.PaymentMethod==  1
                                        ? 'Ví điện tử' : payment.PaymentMethod==  2 ? 'Thẻ quốc tế' : payment.PaymentMethod==  3 ? 'Thẻ nội địa' : payment.PaymentMethod==  4 ? 'Thanh toán tại cửa hàng':
                                        payment.PaymentMethod==  5 ? 'Thanh toán QRcode': payment.PaymentMethod==  6 ? 'Trả góp' : payment.PaymentMethod==  7 ? 'Thanh toán khi nhân hàng':  payment.PaymentMethod==  9 ? 'Mua trước trả sau':'' }}</strong>
                            </th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <strong>{{
                                    payment.PurchaseDate }}</strong>
                            </th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <strong>{{
                                payment.BankName }}
                                    </strong>
                            </th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <strong>{{
                                payment.CardNumber }}
                                   </strong>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :links="payments.meta.links" />
        </SectionMain>
    </LayoutAuthenticated>
</template>

