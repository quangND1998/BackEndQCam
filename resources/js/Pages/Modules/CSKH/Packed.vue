<script setup>
import { computed, ref, inject, reactive, toRef } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import OrderBar from "@/Pages/Modules/Order/OrderBar.vue";
import VueDatepickerUi from "vue-datepicker-ui";
import "vue-datepicker-ui/lib/vuedatepickerui.css";
import ModelShipping from "./ModelShipping.vue";
// import ModalDecline from "./ModalDecline.vue";
// import ModelRefund from "./ModelRefund.vue";
// import ModalShipping from "./ModalShipping.vue";
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
    mdiSquareEditOutline,
    mdiArrowLeftBoldCircleOutline,
    mdiLayersTripleOutline,
    mdiPhone,
    mdiPackageVariantClosed,
} from "@mdi/js";


import Dropdown from "primevue/dropdown";
import BaseIcon from "@/Components/BaseIcon.vue";
import "vue-search-input/dist/styles.css";
import { initFlowbite } from "flowbite";
import { emitter } from "@/composable/useEmitter";
import { usePopOverStore } from '@/stores/popover.js'
import OrderStatusBar from "./OrderStatusBar.vue";
import OrderStatus from "./OrderStatus.vue";
const props = defineProps({
    orders: Object,
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
const list_order = toRef(props.orders.data);
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
});
const customer = ref();
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    state: null,
    shipper: null,
    selectedDate: [new Date(), new Date(new Date().getTime() + 9 * 24 * 60 * 60 * 1000)],
});
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);

const state = reactive({
    content: "<p>2333</p>",
    _content: "",
    editorOption: {
        placeholder: "core",
        modules: {},
    },
    disabled: false,
});
initFlowbite();

const searchCustomer = () => {
    router.get(route(`admin.orders.${props.status}`), filter, {
        preserveState: true,
        preserveScroll: true,
    });
};

const Fillter = (event) => {
    router.get(route(`admin.orders.${props.status}`), filter, {
        preserveState: true,
        preserveScroll: true,
    });
};

const fillterPaymentMethod = (event) => {
    router.get(route(`admin.orders.${props.status}`), filter, {
        preserveState: true,
        preserveScroll: true,
    });
};
const search = () => {
    router.get(route(`admin.orders.${props.status}`), filter, {
        preserveState: true,
        preserveScroll: true,
    });
};

const contents = ref([
    { id: 1, text: "Content 1" },
    { id: 2, text: "Content 2" },
    { id: 3, text: "Content 3" },
]);

const changeDate = () => {
    router.get(route(`admin.orders.${props.status}`), filter, {
        preserveState: true,
        preserveScroll: true,
    });
};

const loadOrder = async ($state) => {
    // console.log("loading...");
    // router.get(route(`admin.orders.${props.status}`),
    //     filter,
    //     {
    //         preserveState: true,
    //         preserveScroll: true,
    //         onSuccess: page => {
    //             if (props.orders.current_page == props.orders.last_page) $state.complete();
    //             else {
    //                 $state.loaded();
    //             }
    //             filter.per_page += 10;
    //         },
    //         onError: errors => {
    //             $state.error();
    //         },
    //     },
    // );
};
const packedOrder = (order) => {
    let query = {
        ids: [order.id],
    };
    swal
        .fire({
            title: "Thông báo?",
            text: "Bạn muốn đóng gói đơn hàng này!",
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
const ownerOrders = () => {
    if (form.shipper == null) {
        swal.fire({
            title: "Error!",
            text: "Bạn chưa chọn Shipper",
            icon: "error",
            confirmButtonText: "Cool",
        });
    } else {
        swal
            .fire({
                title: "Thông báo?",
                text: "Bạn giao cho shipper các đơn hàng này!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
            })
            .then((result) => {
                let query = {
                    ids: selected.value,
                    shipper_id: form.shipper.id,
                };

                if (result.isConfirmed) {
                    router.post(route("admin.cskh.shipperOwner"), query, {
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
};
const openSHippingDetail = (order) => {
    console.log("ModelShipping");
    emitter.emit("ModelShipping", order);
};
const selected = ref([]);
const selectAll = computed({
    get() {
        return props.orders.data ? selected.value.length == props.orders.data : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            props.orders.data.forEach(function (order) {
                array_selected.push(order.id);
            });
        }
        selected.value = array_selected;
    },
});
</script>
<template>
    <LayoutAuthenticated>
        <ModelShipping></ModelShipping>

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
                                <select id="countries" v-model="filter.type" @change="fillterPaymentMethod"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 border-gray-600 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500">
                                    <option :value="null">Tất cả kho hàng</option>
                                    <option value="gift_delivery">Giao quà</option>
                                    <option value="order">Đơn lẻ</option>
                                </select>
                            </div>
                        </div>
                        <div class="mr-4 flex-col flex w-[160px]">
                            <div class="">
                                <select id="countries" v-model="filter.payment_method" @change="fillterPaymentMethod"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 border-gray-600 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500">
                                    <option :value="null">Tất cả</option>
                                    <option value="gift_delivery">Giao quà</option>
                                    <option value="order">Đơn lẻ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <OrderStatusBar :statusGroup="statusGroup" :count_orders="count_orders"></OrderStatusBar>
                <div class="my-3 w-full flex justify-between">
                    <div class="flex">
                        <Dropdown v-model="form.shipper" :options="shippers" optionLabel="name" filter
                            placeholder="Chọn shipper" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm">
                            <template #value="slotProps">
                                <div v-if="slotProps.value" class="flex align-items-center">
                                    <div>{{ slotProps.value.name }}</div>
                                </div>
                                <span v-else>
                                    {{ slotProps.placeholder }}
                                </span>
                            </template>
                            <template #option="slotProps">
                                <div class="flex align-items-center">
                                    <div>{{ slotProps.option.name }}</div>
                                </div>
                            </template>
                        </Dropdown>

                        <button @click="ownerOrders()" v-if="selected.length > 0"
                            class="px-2 py-2 text-sm bg-[#FF6100] hover:bg-[#FF6100] text-white p-2 rounded-lg border mx-1">
                            Giao shipper
                        </button>
                    </div>
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
                                        <th scope="col" class="px-3 py-2 text-left">Loại hàng</th>
                                        <th scope="col" class="px-3 py-2 text-left">SL</th>
                                        <th scope="col" class="px-3 py-2 text-left">DVT</th>
                                        <th scope="col" class="px-3 py-2 text-left">Trạng thái</th>
                                        <th scope="col" class="px-3 py-2 text-left">Hành động</th>
                                        <th scope="col" class="px-3 py-2 text-left">Shipper</th>
                                        <th scope="col" class="px-3 py-2 text-left">Hẹn giao</th>
                                        <th scope="col" class="px-3 py-2 text-left">Chi tiết</th>
                                        <th scope="col" class="px-3 py-2 text-left">Tạo đơn</th>
                                        <th scope="col" class="px-3 py-2 text-left">Mã đơn hàng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order, index) in orders?.data" :key="index">
                                        <th scope="col" class="px-6 py-3">
                                            <div class="flex items-center">
                                                <input id="default-checkbox" type="checkbox" v-model="selected"
                                                    :value="order.id"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2" />
                                            </div>
                                        </th>
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
                                            <BaseIcon :path="mdiPackageVariantClosed" @click="packedOrder(order)"
                                                v-if="order.status_transport == 'packing'"
                                                class="text-[#FF6100] rounded-lg mr-2 text-[#1D75FA] hover:text-blue-700"
                                                v-tooltip.top="'Đóng gói'" size="22">
                                            </BaseIcon>
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
                                            xem
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order?.order_number }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
