<script setup>
import { computed, ref, inject, reactive, toRef, watch } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Pages/Modules/CSKH/Pagination.vue";
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
import BaseButton from "@/Components/BaseButton.vue";
import BaseIcon from "@/Components/BaseIcon.vue";
import "vue-search-input/dist/styles.css";

import { emitter } from "@/composable/useEmitter";
import OrderStatusBar from "./OrderStatusBar.vue";
import { usePopOverStore } from '@/stores/popover.js';
import Icon from '@/Components/Icon.vue'
import OrderCancel from '@/Pages/Modules/CSKH/Dialog/OrderCancel.vue';
import { useOrderStore } from '@/stores/order.js'
import StateDocument from '@/Pages/Modules/CSKH/Status/StateDocument.vue';
import OrderTransportStatus from '@/Pages/Modules/CSKH/Status/OrderTransportStatus.vue'
const props = defineProps({
    order_transports: Object,
    status: String,
    status: String,
    from: String,
    to: String,
    statusGroup: Array,
    shippers: Array,
    count_orders: Number
});

const { openPopover,
    closePopover } = usePopOverStore();
const { showDetailOrder, showDetailOrderTransport } = useOrderStore();

const filter = reactive({
    customer: null,
    name: null,
    fromDate: null,
    toDate: null,
    search: null,
    market: null,
    payment_status: null,
    payment_method: null,
    type: null,
    per_page: 10,
    selectedDate: null
});

const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    state: null,
    selectedDate: [new Date(), new Date(new Date().getTime() + 9 * 24 * 60 * 60 * 1000)],
});





watch(() => [form.selectedDate], (newVal) => {
    // console.log(newVal[0][0])
    filter.fromDate = newVal[0][0]
    filter.toDate = newVal[0][1]
    search()
});
const search = () => {
    router.get(route('admin.cskh.pending'), filter, {
        preserveState: true,
        preserveScroll: true,
    });
};

watch(() => [filter.market], (newVal) => {
    search()
});

watch(() => [filter.type], (newVal) => {
    search()
});
watch(() => [filter.per_page], (newVal) => {
    search()
});




const packedOrders = () => {
    let query = {
        ids: selected.value,
    };
    swal
        .fire({
            title: "Thông báo?",
            text: "Bạn muốn đóng gói các đơn hàng này!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
        })
        .then((result) => {
            if (result.isConfirmed) {
                router.post(route("admin.cskh.packedOrder"), query, {
                    onError: () => { },
                    onSuccess: () => {
                        form.reset();
                    },
                });
            } else {
                return;
            }
        });
};
const openOrderCancel = (order_transport) => {

    console.log(order_transport)
    showDetailOrderTransport(order_transport)
    emitter.emit("OrderCancel", order_transport);
};

const selected = ref([]);
const selectAll = computed({
    get() {
        return props.order_transports.data ? selected.value.length == props.order_transports.data : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            props.order_transports.data.forEach(function (order_transport) {
                array_selected.push(order_transport.id);
            });
        }
        selected.value = array_selected;
    },
});
const canceldeliveryNoOrder = (order_transport) => {
    let query = {
        ids: [order.id],
    };
    swal
        .fire({
            title: "Thông báo?",
            text: "Bạn muốn muốn xóa Mã vận đơn!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
        })
        .then((result) => {
            if (result.isConfirmed) {
                router.post(route("admin.cskh.packedOrder"), query, {
                    onError: () => { },
                    onSuccess: () => {
                        form.reset();
                    },
                });
            } else {
                return;
            }
        });
}
</script>
<template>
    <LayoutAuthenticated>
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

                <OrderStatusBar :statusGroup="statusGroup" :count_orders="count_orders" state="state"></OrderStatusBar>
                <div class="my-3 w-full flex justify-between">
                    <button v-if="selected.length > 0" @click="packedOrders()"
                        class="px-2 py-2 text-sm bg-[#27AE60] hover:bg-[#27AE60] text-white p-2 rounded-lg border mx-1">
                        Xác nhận đóng gói hàng loạt ({{ selected.length }})
                    </button>
                    <!-- <div class="flex">
                        <BaseButton :icon="mdiLayersTripleOutline" icon-w="w-4" icon-h="h-4" color="lightDark" class="mr-2"
                            label="Tất cả (11)" />
                        <BaseButton :icon="mdiLayersTripleOutline" icon-w="w-4" icon-h="h-4" color="text-[#FF6100]"
                            label="Pending" />
                    </div> -->
                </div>

                <div class="w-full mt-2">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto inline-block min-w-full sm:px-6 lg:px-8 m-0 p-0 h-[60vh]">
                            <table class="table_stripe min-w-full text-center text-sm font-light overflow-x-auto">
                                <thead class="font-medium">
                                    <tr>
                                        <th scope="col" class="px-6 py-2 text-left">
                                            <input id="default-checkbox" type="checkbox" v-model="selectAll"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2" />


                                        </th>
                                        <th scope="col" class="px-3 py-2 text-left">STT</th>
                                        <th scope="col" class="px-3 py-2 text-left">Mã HĐ</th>
                                        <th scope="col" class="px-3 py-2 text-left">Loại HĐ</th>
                                        <th scope="col" class="px-3 py-2 text-left">Tên KH</th>
                                        <th scope="col" class="px-3 py-2 text-left">SĐT</th>
                                        <th scope="col" class="px-3 py-2 text-left">Sản phẩm</th>
                                        <th scope="col" class="px-3 py-2 text-left">Tình Trạng</th>
                                        <th scope="col" class="px-3 py-2 text-left">Shipper</th>
                                        <th scope="col" class="px-3 py-2 text-left">Hẹn giao</th>
                                        <th scope="col" class="px-3 py-2 text-left">Địa bàn</th>
                                        <th scope="col" class="px-3 py-2 text-left">KV</th>
                                        <th scope="col" class="px-3 py-2 text-left">Chi tiết</th>
                                        <th scope="col" class="px-3 py-2 text-left">Hồ sơ</th>
                                        <th scope="col" class="px-3 py-2 text-left">CS</th>
                                        <th scope="col" class="px-3 py-2 text-left">Mã đơn hàng</th>
                                        <th scope="col" class="px-3 py-2 text-left">Mã vận đơn</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order_transport, index) in order_transports?.data" :key="index">
                                        <th scope="col" class="px-6 py-3">
                                            <div class="flex items-center">
                                                <input id="default-checkbox" type="checkbox" v-model="selected"
                                                    :value="order_transport.id"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2" />
                                                <button v-tooltip="'Hủy mã vận đơn'"
                                                    @click="openOrderCancel(order_transport)" data-toggle="modal"
                                                    data-target="#OrderCancel">
                                                    <Icon icon="cancel"></Icon>
                                                </button>
                                            </div>
                                        </th>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order_transport.order?.product_service?.order_package?.idPackage }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order_transport.order?.product_service?.product?.name }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order_transport.order?.customer?.name }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <p class="flex items-center">
                                                {{
                                                    hasAnyPermission(["super-admin"])
                                                    ? order_transport.order?.customer?.phone_number
                                                    : hidePhoneNumber(order_transport.order?.customer?.phone_number)
                                                }}
                                                <!-- mdiPhone  -->
                                                <BaseIcon :path="mdiPhone"
                                                    class="rounded-lg mr-2 text-[#4F8D06] hover:text-[#4F8D06]"
                                                    v-tooltip.top="'gọi điện'" size="22">
                                                </BaseIcon>
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <p v-for="(item, index2) in order_transport.order.order_items" :key="index2">
                                                {{ item?.product?.name }} {{ item?.product?.unit }}X{{ item?.quantity }}
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <OrderTransportStatus :order_transport="order_transport" />
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order_transport.order?.shipper ? order_transport.order?.shipper?.name : "NA"
                                            }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{
                                                order_transport.delivery_appointment
                                                ? formatTimeDayMonthyear(order_transport.delivery_appointment)
                                                : "Chưa cập nhật"
                                            }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order_transport.order.address + ',' + order_transport.order.wards + ',' +
                                                order_transport.order.district +
                                                ',' +
                                                order_transport.order.city }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{
                                                order_transport.order.product_service.order_package.market

                                            }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <button @mouseover="openPopover(order_transport)" @mouseleave="closePopover">
                                                xem
                                            </button>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <StateDocument :order="order_transport.order" />
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order_transport.order?.saler.name }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order_transport.order?.order_number }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order_transport.order_transport_number }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="w-full flex  justify-between items-center">
                        <div class="flex items-center">
                            <span class="mr-2">Hiển thị</span>
                            <select v-model="filter.per_page"
                                class="bg-gray-50 border   text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  mx-auto px-4 py-1   dark:bg-gray-700 dark:border-gray-600 ">
                                <option :value="50">50</option>
                                <option :value="100">100</option>
                                <option :value="200">200</option>
                            </select>
                        </div>

                        <Pagination :links="order_transports.links" />
                    </div>
                </div>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
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
