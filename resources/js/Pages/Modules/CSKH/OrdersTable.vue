<script setup>
import { computed, ref, inject, reactive, toRef, watch } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import VueDatepickerUi from "vue-datepicker-ui";
import "vue-datepicker-ui/lib/vuedatepickerui.css";
import ModelShipping from "./ModelShipping.vue";
import OrderStatus from "./OrderStatus.vue";
import {

    mdiLayersTripleOutline,
    mdiPhone,
} from "@mdi/js";

import BaseIcon from "@/Components/BaseIcon.vue";
import "vue-search-input/dist/styles.css";
import { initFlowbite } from "flowbite";
import { emitter } from "@/composable/useEmitter";
import OrderStatusBar from "./OrderStatusBar.vue";
import StatusBar from '@/Pages/Modules/CSKH/Status/StatusBar.vue'
import { usePopOverStore } from '@/stores/popover.js';
import Icon from '@/Components/Icon.vue'
import OrderCancel from '@/Pages/Modules/CSKH/Dialog/OrderCancel.vue';
import { useOrderStore } from '@/stores/order.js'
import { useCSKHStore } from '@/stores/cskh.js'
import DialogLoading from '@/Components/CustomerService/Dialog/DialogLoading.vue';
import Pagination from '@/Pages/Modules/CSKH/Components/Pagination.vue'
const { openPopover,
    closePopover } = usePopOverStore();
const { showDetailOrder } = useOrderStore();

const orders = computed(() => {
    return store.orders;
});
const statusGroup = computed(() => {
    return store.statusGroup;
});
const count_orders = computed(() => {
    return store.count_orders;
});
const isLoading = computed(() => {
    return store.isLoading;
});
const store = useCSKHStore();
const fetchOrders = () => {

    store.fetchOrders();
}
const fetchStatusOrders = () => {
    store.fetchStatusOrders();
}
fetchStatusOrders();
fetchOrders();
const filter = reactive({
    customer: null,
    name: null,
    fromDate: null,
    toDate: null,
    search: null,
    payment_status: null,
    payment_method: null,
    type: null,
    per_page: 10,
    selectedDate: null,
    status: null,
    market: null,
    page: null
});

const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    state: null,
    selectedDate: [new Date(), new Date(new Date().getTime() + 9 * 24 * 60 * 60 * 1000)],
});

initFlowbite();

const searchCustomer = () => {
    store.fetchOrders(filter)
};


const search = () => {
    store.fetchOrders(filter)
};


const changeDate = () => {
    router.get(route(`admin.orders.${props.status}`), filter, {
        preserveState: true,
        preserveScroll: true,
    });
};


const changeStatus = (status) => {
    filter.status = status
    store.fetchOrders(filter)
}
watch(() => [filter.per_page], (newVal) => {
    store.fetchOrders(filter)
});
watch(() => [filter.type], (newVal) => {
    store.fetchOrders(filter)
});

watch(() => [form.selectedDate], (newVal) => {
    // console.log(newVal[0][0])
    filter.fromDate = newVal[0][0]
    filter.toDate = newVal[0][1]
    store.fetchOrders(filter)
});
watch(() => [filter.market], (newVal) => {
    store.fetchOrders(filter)
});

const changePage = (page) => {
    filter.page = page;
    store.fetchOrders(filter)
}

</script>
<template>
    <div>
        <ModelShipping></ModelShipping>
        <OrderCancel />



        <Head title="Quản lý đơn hàng" />
        <SectionMain class="p-3 mt-16">
            <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                <div>
                    <h2 class="font-semibold flex mr-2">
                        Quản lý đơn hàng
                        <!-- <p class="text-gray-400">( {{ $page.props.auth.total_order }} )</p> -->
                    </h2>
                </div>
            </div>

            <div class="mt-3">
                <div class="w-full flex justify-between">
                    <div class="flex mr-2">
                        <div class="mr-4">
                            <div class="min-[320px]:w-full form_search">
                                <form v-on:submit.prevent>
                                    <div class="relative">
                                        <input type="search" id="default-search" name="search" data-toggle="hideseek"
                                            data-list=".menu-category" v-model="filter.search" @keyup="search"
                                            class="block w-[200px] p-2.5 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 border-gray-600 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Tìm mã đơn hàng, SĐT   " required />
                                        <div
                                            class="absolute h-full inset-y-0 right-0 flex items-center pointer-events-none bg-[#FF6100] items-center p-1">
                                            <svg aria-hidden="true" class="w-6 h-6 text-sm text-gray-500 text-gray-400"
                                                fill="none" stroke="#ffffff" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="w-[320px]">

                            <VueDatepickerUi range v-model="form.selectedDate" lang="vn"></VueDatepickerUi>
                        </div>
                        <div class="mr-4 flex-col flex w-[160px]">
                            <div class="">
                                <select id="countries" v-model="filter.market"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 border-gray-600 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500">
                                    <option :value="null">Tất cả kho hàng</option>
                                    <option value="MB">Miền Bắc</option>
                                    <option value="MN">Miền Nam</option>
                                </select>
                            </div>
                        </div>
                        <div class="mr-4 flex-col flex w-[160px]">
                            <div class="">
                                <select id="countries" v-model="filter.type"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 border-gray-600 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500">
                                    <option :value="null">Tất cả</option>
                                    <option value="gift_delivery">Giao quà</option>
                                    <option value="order">Đơn lẻ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <StatusBar v-if="statusGroup" :statusGroup="statusGroup" :count_orders="count_orders"
                    :status="filter.status" @change-status="changeStatus"></StatusBar>


                <div class="w-full mt-2">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto inline-block min-w-full sm:px-6 lg:px-8 m-0 p-0 h-[60vh] relative">

                            <DialogLoading v-if="isLoading" text="Loading" />
                            <table class="table_stripe min-w-full text-center text-sm font-light overflow-x-auto" v-else>
                                <thead class="font-medium">
                                    <tr>

                                        <th scope="col" class="px-3 py-2 text-left">STT</th>
                                        <th scope="col" class="px-3 py-2 text-left">Mã HĐ</th>
                                        <th scope="col" class="px-3 py-2 text-left">Loại HĐ</th>
                                        <th scope="col" class="px-3 py-2 text-left">Tên KH</th>
                                        <th scope="col" class="px-3 py-2 text-left">SĐT</th>
                                        <th scope="col" class="px-3 py-2 text-left">Loại hàng</th>
                                        <th scope="col" class="px-3 py-2 text-left">SL</th>
                                        <th scope="col" class="px-3 py-2 text-left">DVT</th>
                                        <th scope="col" class="px-3 py-2 text-left">Trạng thái</th>

                                        <th scope="col" class="px-3 py-2 text-left">Shipper</th>
                                        <th scope="col" class="px-3 py-2 text-left">Hẹn giao</th>
                                        <th scope="col" class="px-3 py-2 text-left">Chi tiết</th>
                                        <th scope="col" class="px-3 py-2 text-left">Tạo đơn</th>
                                        <th scope="col" class="px-3 py-2 text-left">Mã đơn hàng</th>
                                        <th scope="col" class="px-3 py-2 text-left">Mã vận đơn </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order, index) in orders?.data" :key="index">

                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order?.product_service?.order_package?.idPackage }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order?.product_service?.product?.name }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order?.customer?.name }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <p class="flex items-center">
                                                {{
                                                    hasAnyPermission(["super-admin"])
                                                    ? order?.customer?.phone_number
                                                    : hidePhoneNumber(order?.customer?.phone_number)
                                                }}
                                                <!-- mdiPhone  -->
                                                <BaseIcon :path="mdiPhone"
                                                    class="rounded-lg mr-2 text-[#4F8D06] hover:text-[#4F8D06]"
                                                    v-tooltip.top="'gọi điện'" size="22">
                                                </BaseIcon>
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <p v-for="(item, index2) in order.order_items" :key="index2">
                                                {{ item?.product?.name }}
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <p v-for="(item, index2) in order.order_items" :key="index2">
                                                {{ item?.quantity }}
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            hộp
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <OrderStatus :order="order" />
                                        </td>

                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order?.shipper ? order?.shipper?.name : "NA" }}
                                        </td>

                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{
                                                order?.delivery_appointment
                                                ? formatTimeDayMonthyear(order?.delivery_appointment)
                                                : "Chưa cập nhật"
                                            }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <button @mouseover="openPopover(order)" @mouseleave="closePopover">
                                                xem
                                            </button>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order.saler?.name }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order?.order_number }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order?.order_transport_number }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full flex  justify-between items-center">
                <div class="flex items-center">
                    <span class="mr-2">Hiển thị</span>
                    <select v-model="filter.per_page"
                        class="bg-gray-50 border   text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  mx-auto px-4 py-1   dark:bg-gray-700 dark:border-gray-600 ">
                        <option :value="10">10</option>
                        <option :value="50">50</option>
                        <option :value="100">100</option>
                        <option :value="200">200</option>
                    </select>
                </div>

                <Pagination v-if="orders" :data="orders.meta" @change-page="changePage" />
            </div>
        </SectionMain>
    </div>
</template>
<style>
.list_icon_crud {
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;

    right: -40px;
    top: 20px;
    z-index: 111;
    display: inline-grid;
}

.btn_crud {
    font-size: 20px;
}

.title_information {
    background-color: #f3f4f6;
}

.item_information {
    height: 100px;
}

.collapse {
    visibility: inherit;
}

.partiallyPaid {
    background-color: rgb(254 252 232 / var(--tw-bg-opacity));
    border-color: rgb(254 240 138 / var(--tw-border-opacity));
    border-style: solid;
    border-width: 1px;
    color: rgb(202 138 4 / var(--tw-text-opacity));
}

.v-calendar .input-field input {
    height: 40px;
}

.v-calendar .input-field svg.datepicker {
    fill: #65716b;
}
</style>
