<script setup>
import { ref, inject } from "vue";
import OrderAction from "@/Pages/Modules/Order/Package/OrderAction.vue"
import { Head, Link, useForm } from "@inertiajs/vue3";
import BaseButton from "@/Components/BaseButton.vue";
import CardBoxModal from '@/Components/CardBoxModalFull.vue'
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import InfiniteLoading from "v3-infinite-loading";
import "v3-infinite-loading/lib/style.css";
import { vFullscreenImg } from 'maz-ui'
import UploadImage from '@/Components/UploadImage.vue'
import UploadImageAuto from '@/Components/UploadImageAuto.vue'

import BaseIcon from '@/Components/BaseIcon.vue'
import {
    mdiSquareEditOutline,mdiDeleteOutline , mdiCashMultiple ,mdiEyeOutline  ,mdiCheckCircle,mdiCheckAll
} from '@mdi/js'
const showContent = ref(false);
// Hàm để toggle trạng thái của nội dung
const toggleContent = () => {
    showContent.value = !showContent.value;
    console.log(showContent.value)
};
const props = defineProps({
    index: Object,
    order: Object,
    status: String,
})
const isModalActive = ref(false);
const isModalHistoryActive = ref(false);
const showAction = ref(false);
const images = ref([]);
const swal = inject("$swal");
const showButton = () =>{
    showAction.value = true
}
const hideButton = () =>{
    showAction.value = false
}
const form = useForm({
    id: null,
    payment_method: 'cash',
    payment_date: new Date(),
    amount_received: null,
    amount_unpaid: null,
    status: null,
    note: null,
    order: null,
    images: [],
});
const detail = (order) => {
    isModalActive.value = true;
    isModalHistoryActive.value = false;
    form.order = order
    form.amount_unpaid = order.grand_total - order.price_percent
    form.amount_received = order.grand_total - order.price_percent
};
const edit = (history) => {
    isModalActive.value = false;
    isModalHistoryActive.value = true;
};
const deleteHistory = (id) => {
    swal
        .fire({
            title: "Are you sure?",
            text: "Delete this History Payment!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.post(route("admin.orders.package.deleteHistoryPayment", [form.order?.id, id]), { preserveState: false }, {
                    onSuccess: () => {
                        swal.fire(
                            "Deleted!",
                            "Your tree has been deleted.",
                            "success"
                        );
                    },
                });
            }
        });
};
const openAcceptPayment = (id) => {
    let query = {
        status: "complete"
    };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Duyệt khoản thanh toán này không`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {
                form.post(route("admin.orders.package.setPaymentComplete", [form.order?.id, id]), { preserveState: false }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã thêm hợp đồng với khách hàng.", "success");
                    },
                });

            }
        });
}

const save = () => {
    console.log(form);
    form.post(route("admin.orders.package.historyPayment", form.order?.id), {
        onError: () => {
            isModalActive.value = true;
            editMode.value = false;
        },
        onSuccess: () => {
            form.reset('id', 'payment_method', 'payment_date', 'amount_received', 'note');
            isModalActive.value = false;
            editMode.value = false;
        },
    });
};
const orderChangePacking = () => {
    let query = {
        status: "complete"
      };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Duyệt gói, thiết lập hợp đồng`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {
                router.post(route("admin.orders.package.orderComplete", props.order.id), query,
                    {
                        preserveState: true,
                        preserveScroll: true
                    }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã thêm hợp đồng với khách hàng.", "success");
                    },
                });

            }
        });
}
const orderChangePending = () => {
    let query = {
        status: "pending"
      };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Làm mới gói dịch vụ ${props.order.order_number} !`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {

                router.post(route("admin.orders.package.orderChangeStatus", props.order.id), query,
                    {
                        preserveState: true,
                        preserveScroll: true
                    }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã làm mới hợp đồng, chuyển sang trạng thái chờ duyệt.", "success");
                    },
                });
            }
        });
}
</script>

<template>
    <LayoutAuthenticated>


        <SectionMain class="p-3 mt-16 rounded-xl">
            <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                <div>
                    <h2 class="font-semibold flex mr-2">
                        Quản lý đơn hợp đồng
                    </h2>
                </div>
                <div class="flex">
                    <div>
                        <Link v-if="hasAnyPermission(['add-new-package'])" :href="route('admin.orders.package.create')"
                            class="px-2 py-2 rounded-2xl text-sm text-white  rounded-lg border mx-1 bg-btn_green hover:bg-[#318f02] hover:bg-[#008000]">
                            <font-awesome-icon :icon="['fas', 'plus']" />Thêm đơn hàng hợp đồng
                        </Link>
                    </div>
                </div>
            </div>
            <div>
                <PackageBar :statusGroup="statusGroup"></PackageBar>
                <ModalDecline></ModalDecline>
                <ModelRefund></ModelRefund>
                <div class="w-full flex justify-between">
                        <div class="flex mr-2">
                            <div class="mr-4">
                                <div class="w-full  mr-3 text-gray-500">
                                    <label for>Mã đơn hàng</label>
                                </div>
                                <div class="min-[320px]:w-full form_search">
                                    <form v-on:submit.prevent>
                                        <div class="relative">
                                            <div class="absolute inset-y-1 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg aria-hidden="true" class="w-5 h-5 text-sm text-gray-500 text-gray-400"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                            <input type="search" id="default-search" name="search" data-toggle="hideseek"
                                                laceholder="Search Menus" data-list=".menu-category" v-model="filter.search"
                                                @keyup="search"
                                                class="block w-full p-2.5 pl-5 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Nhập mã đơn hàng" required />
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="mr-4 w-[320px]">
                                <div class=" text-gray-500">
                                    <label for>Ngày tạo đơn</label>
                                </div>
                                <div class="w-full flex flex-wrap">
                                    <div class="flex ">
                                        <div class="relative">
                                            <VueDatePicker v-model="filter.fromDate" time-picker-inline />
                                        </div>
                                        <span class="mx-1 text-gray-500">đến</span>
                                        <div class="relative">
                                            <VueDatePicker v-model="filter.toDate" time-picker-inline />
                                        </div>
                                        <button @click.prevent="changeDate" name="search"
                                        class="block p-2 ml-3 text-xs text-gray-900 border border-gray-300 rounded-lg  focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-blue-500 text-white">Search</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="mr-4 flex-col flex">
                                <div class=" text-gray-500">
                                    <label for>Phương thức TT</label>
                                </div>
                                <div class="">
                                    <select id="countries"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                        <option selected>Tất cả</option>
                                        <option value="US">Tiền mặt</option>
                                        <option value="CA">Chuyển khoản</option>
                                        <option value="CA">Payoo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mr-4 flex-col flex">
                                <div class=" text-gray-500">
                                    <label for>Trạng thái TT</label>
                                </div>
                                <div class="">
                                    <select id="countries"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                        <option :value="null">Tình trạng</option>
                                        <option :value="1">Đã thanh toán</option>
                                        <option :value="0">Chưa thanh toán</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                </div>
                <div class="w-full mt-6 ">
                    <div class="panel panel-default">
                        <div class="tableFixHead panel-body relative overflow-x-auto ">
                                <div class="mr-2 px-2 mb-2 text-xs  uppercase grid grid-cols-11 bg-gray-200   py-2  text-gray-400">
                                    <div class="text-left">
                                        <p>Mã phiếu</p>
                                    </div>
                                    <div class="text-left">
                                        <p>Ngày lập phiếu</p>
                                    </div>
                                    <!-- <div class="text-left">
                                        <p>Mã phiếu</p>
                                    </div> -->
                                    <div class="text-left">
                                        <p>Sản phẩm</p>
                                    </div>
                                    <div class="text-left">
                                        <p>Ngày áp dụng</p>
                                    </div>
                                    <div class="text-left ">
                                        <p>Số tiền</p>
                                    </div>
                                    <div class="text-left">
                                        <p>Loại</p>
                                    </div>
                                    <div class="text-left">
                                        <p>Khách hàng</p>
                                    </div>
                                    <div class="text-left">
                                        <p>Trạng thái</p>
                                    </div>
                                    <div class="text-left">
                                        <p>TT duyệt</p>
                                    </div>
                                    <div>
                                        <p>Trạng thái gói</p>
                                    </div>
                                    <div>
                                        <p>Hành động</p>
                                    </div>
                                </div>

                                <div class=" overflow-auto">
                                    <div  v-for="(order, index) in orders?.data" :key="index">
                                    <OrderRow :order="order" :status="status" />

                                    </div>
                                </div>

                        </div>
                    </div>
                </div>



            </div>


        </SectionMain>
    </LayoutAuthenticated>
        <!-- Modal -->
        <CardBoxModal class="w-full" v-model="isModalActive" buttonLabel="Thêm và cập nhật"
        
            :hasSave="form?.amount_unpaid > 0 ? true : false" has-cancel @confirm="save"
            :title="`Thanh toán cho ${form.order?.order_number}`">
            <div class="p-6 flex-auto">
                <div v-if="form?.amount_unpaid > 0" class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="amount_received" value="Số tiền" />
                        <MazInputPrice v-model="form.amount_received" currency="VND" locale="vi-VN" :min="0"
                            :max="(form?.order?.grand_total - form?.order?.price_percent)"
                            @formatted="formattedPrice = $event" />
                        <InputError class="mt-2" :message="form.errors.amount_received" />
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <InputLabel for="payment_method" value="Hình thức thanh toán" />
                        <select id="payment" v-model="form.payment_method"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected value="cash">Tiền mặt</option>
                            <option value="banking">Chuyển khoản</option>
                            <option value="payoo">Payoo</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <InputLabel for="owner" value="Ngày thanh toán" />
                        <div date-rangepicker class="flex items-center">
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                </div>
                                <VueDatePicker v-model="form.payment_date" placeholder="date" />
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <InputLabel for="note" value="Ghi chú" />
                        <TextInput id="note" v-model="form.note" type="text" class="mt-1 block w-full"
                            :class="form.errors.note ? 'border-red-500' : ''" autocomplete="name" />
                        <InputError class="mt-2" :message="form.errors.note" />
                    </div>
                    <div class="w-full px-3">
                        <UploadImage :max_files="4" v-model="form.images" :multiple="true" :label="`Chứng từ liên quan`" />
                    </div>
                </div>

                <p class="my-2 mt-5 font-semibold">Lịch sử thanh toán</p>
                <table class="table table-striped w-full text-sm text-left text-gray-500 bg-white rounded-lg">
                    <thead class="text-xs justify-between text-gray-700 uppercase">
                        <tr class="info">
                            <th>#</th>
                            <th>Loại</th>
                            <th>Ngày</th>
                            <th>Số tiền</th>
                            <th>Trạng thái duyệt</th>
                            <th>Proof</th>
                            <th>Người duyệt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="justify-between border-0 bg-white info"
                            v-for="(payment, index) in form?.order?.history_payment" :key="payment.id">
                            <td class="border-0">
                                {{ index + 1 }}
                            </td>
                            <td class="border-0">
                                {{ payment?.payment_method == 'banking' ? 'Chuyển khoản' : payment?.payment_method == "cash"
                                    ? 'Tiền mặt' : 'Payoo' }}
                            </td>
                            <td class="border-0">{{ payment?.payment_date }}</td>
                            <td class="border-0">{{ formatPrice(payment?.amount_received) }}₫</td>
                            <td class="border-0">
                                <p class="btn_label" :class="payment?.status != 'complete' ? 'partiallyPaid' : 'paid'">
                                    {{ payment?.status != 'complete' ? 'Chờ duyệt' : 'Đã duyệt' }}</p>
                            </td>
                            <td class="border-0 flex m-0 p-0">
                                <UploadImageAuto :idPayment="payment?.id" :max_files="1" v-model="form.images" :multiple="false" :old_images="payment?.order_package_payment" class="justify-start" />
                            </td>
                            <td class="border-0">
                                {{ payment.status == 'complete' ? payment.user?.name : null }}
                                <button
                                    v-if="payment.status != 'complete' && hasAnyPermission(['create-contract-complete'])"
                                    class="px-3 py-2 ml-3 text-white border rounded-lg bg-primary"
                                    @click="openAcceptPayment(payment.id)">Duyệt </button>
                                <button v-else-if="payment.status != 'complete'"
                                    class="px-3 py-2 ml-3 border-gray-black text-gray-400 border rounded-lg" disable>Duyệt
                                </button>
                            </td>
                            <td class="border-0">
                                <BaseButton v-if="payment.status != 'complete'" color="gray"
                                    class="border-0 text=gray=300 hover:text-black" :icon="mdiTrashCanOutline" small
                                    @click="deleteHistory(payment.id)" />
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </CardBoxModal>
        <div  @mouseover="showButton" @mouseout="hideButton"  class="grid grid-cols-11  text-sm px-3 p-1 text-gray-400 border-b-[1px] border-[#f5f5f5] text-left items-center"
            >
            <div @click.prevent="toggleContent">
                <a class="flex items-center text-blue-600 text-[10px]">
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0 mr-2" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>{{ order?.order_number }}</a>
            </div>
            <div class="text-left">
                <p>{{ formatDateOnly(order?.created_at) }}</p>
            </div>
            <div class="text-left">
                <p>{{ order?.idPackage }}</p>
            </div>
            <div class="text-left text-[12px]">
                <p>{{ order?.product_service?.name }}</p>
            </div>
            <div class="text-left text-[12px]">
                <p>{{ formatDateOnly(order?.time_approve) }}</p>
            </div>
            <div class="text-left ">
                <p>{{ formatPrice(order?.grand_total) }}</p>
            </div>
            <div class="text-left">
                <p>{{ order?.type == "new" || 0 ? "tạo mới" : order?.type }}</p>
            </div>
            <div class="text-left text-[12px]">
                <p>{{ order?.customer?.name }}</p>
            </div>
            <div class="text-left text-[10px] ">
                <p class="btn_label text-[10px]"
                    :class="order?.price_percent < order?.grand_total ? 'partiallyPaid' : order?.price_percent == 0 ? 'unpaid' : 'paid'">
                    {{ order?.price_percent < order?.grand_total ? 'Thanh toán 1 phần' : order?.price_percent == 0
                        ? 'Chưa thanh toán' : 'Đã thanh toán' }}</p>

            </div>
            <div class="text-left ">
                <p class="btn_label text-[10px] " :class="order.payment_check ? 'paid' : 'partiallyPaid'">
                    {{ order.payment_check ? 'đã duyệt' : 'chờ duyệt' }}</p>
            </div>

            <div v-if="status != 'decline' && status != 'complete'">
                <p :class="order.product_service_owner?.state == 'active' ? 'text-green' : 'text-red'">
                    {{ order.product_service_owner?.state }}
                </p>
            </div>
            <div class="flex" v-if="status == 'decline' || status == 'complete'">
                <p v-if="status == 'decline'" class="text-[12px] text-left">
                    {{ order.reason }}
                </p>
                <p v-if="status == 'complete'">
                    {{ order.package_reviewer?.name }}
                </p>

            </div>
            <div v-if="status == 'decline' || status == 'complete'">
                <p v-if="status == 'decline'">
                    {{ order.package_reviewer ? order.package_reviewer?.name : 'hệ thống' }}
                </p>
                <p v-if="status == 'complete'">
                    <Link>
                    </Link>
                </p>
            </div>
            <div  class="flex">
                    <BaseIcon :path="mdiCashMultiple " class=" text-gray-400 rounded-lg mr-2 hover:text-blue-700" data-toggle="modal"
                        data-target="#exampleModal" @click="detail(order)" v-tooltip.top="'Thanh toán'"
                          size="20">
                    </BaseIcon>
                    <a   :href="route('admin.orders.package.detail', order?.id)" target="_blank"  v-tooltip.top="'Chi tiết gói'" >
                        <BaseIcon :path="mdiEyeOutline " class=" text-gray-400 rounded-lg  mr-2 hover:text-blue-700"

                            size="20">
                        </BaseIcon>
                    </a>
                    <a  v-if="status == 'pending'" :href="`/admin/orders/package/edit/${order.id}`" target="_blank" >
                        <BaseIcon :path="mdiSquareEditOutline " class=" text-gray-400 rounded-lg mr-2 hover:text-blue-700" v-tooltip.top="'Chỉnh sửa'"
                             size="20">
                        </BaseIcon>
                    </a>
                    <BaseIcon :path="mdiDeleteOutline " class=" text-gray-400 rounded-lg  mr-2 hover:text-red-700"  v-tooltip.top="'Hủy gói'"
                    data-toggle="modal" data-target="#exampleModalDecline" @click="openDecline(order)"
                        v-if="status == 'pending' || status == 'complete'"  size="20">
                    </BaseIcon>
                    <BaseIcon :path="mdiCheckAll " class=" text-gray-400 rounded-lg  mr-2 hover:text-blue-700"  v-tooltip.top="'Duyệt gói'"
                        v-if="hasAnyPermission(['create-contract-complete']) && status == 'pending' && (order.payment_check == true && (order.price_percent >= order.grand_total))"
                        @click="orderChangePacking(order)"  size="20">
                    </BaseIcon>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 bg-gray-200 p-3 border rounded-xl" v-if="showContent">

            <div class="my-2 rounded-lg border">
                <div class="title_information flex justify-between p-2">
                    <h3>Thông tin khách hàng</h3>
                    <button class="border  rounded-lg bg-[#5cb85c] text-white px-3 py-2" type="button" data-toggle="modal"
                        data-target="#exampleModal" @click="detail(order)">Thanh toán</button>
                </div>
                <div class=" grid-cols-4 gap-4 flex justify-between bg-white py-3 px-3">
                    <div class="block">
                        <p class="text-black text-sm">Số nhà/ Địa chỉ cụ thể</p>
                        <div class="item_information  rounded-lg">
                            <p class="text-gray-600 font-semibold text-sm"> {{ order.address + ',' + order.wards + ',' +
                                order.district + ',' +
                                order.city }}</p>
                        </div>
                    </div>
                    <div class="block">
                        <!-- <p class="text-black text-sm">Hình thức thanh toán</p> -->
                        <div class="text-sm rounded-lg">
                            <p class="text-sm"><strong>Ngày:</strong> {{ formatDate(order?.created_at) }} </p>
                            <p class="text-sm"><strong>Người tạo đơn:</strong> {{ order?.saler ? order?.saler?.name :
                                'Admin' }} </p>
                        </div>
                    </div>
                </div>
                <table class="table w-full text-sm text-left text-gray-500 bg-white rounded-lg">
                    <thead class="text-xs text-left text-gray-700 uppercase">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Ưu đãi</th>
                            <th>Vat</th>
                            <th>Tổng giá</th>
                            <th>Khách trả</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class=" border-0 bg-white">
                            <td class="border-0">
                                gói nhận nuôi {{ order?.product_service?.name }}
                            </td>
                            <td class="border-0">{{ order?.discount_deal }}</td>
                            <td class="border-0">{{ order?.vat }}</td>
                            <td class="border-0">{{ formatPrice(order.grand_total) }}₫</td>
                            <td class="border-0">{{ formatPrice(order.price_percent) }}</td>
                            <td class="border-0 text-right">
                                <Link :href="route('admin.orders.package.detail', order?.id)"
                                    class=" text-sm text-blue hover:opacity-0.8 mx-1">
                                Chi tiết hóa đơn
                                </Link>

                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <OrderAction :order="order" :status="order.status"></OrderAction>
        </div>
    
</template>
