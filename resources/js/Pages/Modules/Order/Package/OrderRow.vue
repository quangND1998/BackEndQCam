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
import {
    mdiPlus,
    mdiFilter, mdiPencilOutline, mdiTrashCan, mdiSquareEditOutline, mdiTrashCanOutline
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
const images = ref([]);
const swal = inject("$swal");
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
    form.amount_received =   order.grand_total - order.price_percent
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
                form.post(route("admin.orders.package.deleteHistoryPayment", [form.order?.id,id]), { preserveState: false }, {
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
                form.post(route("admin.orders.package.setPaymentComplete", [form.order?.id,id]),  { preserveState: false },{
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
</script>

<template>
    <div>
        <!-- Modal -->
        <CardBoxModal class="w-full" v-model="isModalActive" buttonLabel="Save" :hasSave="form?.amount_unpaid > 0 ? true  :false "   has-cancel @confirm="save"
            :title="`Thanh toán cho ${form.order?.order_number}`">
            <div class="p-6 flex-auto">
                <div v-if="form?.amount_unpaid > 0" class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="amount_received" value="Số tiền" />
                        <MazInputPrice  v-model="form.amount_received" currency="VND"  locale="vi-VN" :min="0" :max="(form?.order?.grand_total - form?.order?.price_percent)"
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
                                <VueDatePicker v-model="form.payment_date"
                                    placeholder="date" />
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
                        <UploadImage :max_files="4" v-model="form.images" :multiple="true"
                                :label="`Chứng từ liên quan`" />
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
                                {{ payment?.payment_method == 'banking' ? 'Chuyển khoản' : payment?.payment_method == "cash" ? 'Tiền mặt' : 'Payoo'}}
                            </td>
                            <td class="border-0">{{ payment?.payment_date }}</td>
                            <td class="border-0">{{ formatPrice(payment?.amount_received) }}₫</td>
                            <td class="border-0">
                                <p class="btn_label"
                                    :class="payment?.status != 'complete' ? 'partiallyPaid' : 'paid'">
                                    {{ payment?.status != 'complete' ? 'Chờ duyệt' : 'Đã duyệt' }}</p>
                            </td>
                            <td class="border-0 flex">

                                <div class="list_image flex mr-1" v-for="image in payment.order_package_payment" :key="image.id">
                                    <img  v-fullscreen-img class="w-[50px] h-[50px]" :src="image.original_url" alt="">
                                </div>
                            </td>
                            <td class="border-0">
                                {{ payment.status == 'complete' ? payment.user?.name : null}}
                                <button v-if="payment.status != 'complete'"
                                    class="px-3 py-2 ml-3 text-white border rounded-lg bg-primary"  @click="openAcceptPayment(payment.id)">Duyệt </button>
                            </td>
                            <td class="border-0">
                                <BaseButton v-if="payment.status != 'complete'" color="gray" class="border-0 text=gray=300 hover:text-black"
                                    :icon="mdiTrashCanOutline" small @click="deleteHistory(payment.id)" />
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </CardBoxModal>
        <div @click.prevent="toggleContent" class=" grid   text-sm px-3 mt-2 mb-1 text-gray-400" :class="status == 'pending' ? 'grid-cols-8' :  'grid-cols-10'">
            <div>
                <a class="flex items-center text-blue-600 text-[12px]">
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0 mr-2" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>{{ order?.order_number }}</a>
            </div>
            <div class="text-center">
                <p>{{ formatPrice(order?.grand_total) }}</p>
            </div >

            <div class="text-center">
                <p>{{ order?.product_service?.name }}</p>
            </div>
            <div class="text-center">
                <p>{{ order?.type == "new" || 0 ? "tạo mới" : order?.type }}</p>
            </div>
            <div class="text-center">
                <p>{{ formatTimeDayMonthyear(order?.created_at) }}</p>
            </div>
            <div class="text-center">
                <p>{{ order?.customer?.name }}</p>
            </div>
            <div class="text-center ">
                <p class="btn_label "
                    :class="order?.price_percent < order?.grand_total ? 'partiallyPaid' : order?.price_percent == 0 ? 'unpaid' : 'paid'">
                    {{ order?.price_percent < order?.grand_total ? 'Thanh toán 1 phần' : order?.price_percent == 0
                        ? 'Chưa thanh toán' : 'Đã thanh toán' }}</p>

            </div>
            <div class="text-center ">
                <p class="btn_label "
                    :class="order.payment_check ?  'paid' : 'partiallyPaid' ">
                    {{ order.payment_check ? 'đã duyệt' : 'chờ duyệt' }}</p>

            </div>


            <div class="flex">
                <p v-if="status == 'pending' "></p>
                <p v-if="status == 'decline' " class="text-[12px] text-center">
                    {{ order.reason }}
                </p>
                <p v-if="status == 'complete' ">
                    {{  order.package_reviewer?.name }}
                </p>

            </div>
            <div>
                <p v-if="status == 'decline' ">
                     {{ order.package_reviewer ? order.package_reviewer?.name : 'hệ thống' }}
                </p>
                <p v-if="status == 'complete' ">
                   <Link></Link>
                </p>
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
                            <p class="text-sm"><strong>Sale:</strong> {{ order?.saler ? order?.saler?.name : 'Admin' }} </p>
                        </div>
                    </div>
                </div>
                <table class="table w-full text-sm text-left text-gray-500 bg-white rounded-lg">
                    <thead class="text-xs text-center text-gray-700 uppercase">
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
                                <Link :href="route('admin.orders.package.detail', order?.id)" class=" text-sm text-blue hover:opacity-0.8 mx-1">
                                Chi tiết hóa đơn
                                </Link>

                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <OrderAction :order="order" :status="order.status"></OrderAction>
        </div>
    </div>
</template>



<style scope>
.partiallyPaid {
    background-color: rgb(254 252 232/var(--tw-bg-opacity));
    border-color: rgb(254 240 138/var(--tw-border-opacity));
    border-style: solid;
    border-width: 1px;
    color: rgb(202 138 4/var(--tw-text-opacity));
}

.paid {
    background-color: rgb(240 253 244/var(--tw-bg-opacity));
    border-color: rgb(187 247 208/var(--tw-border-opacity));
    border-style: solid;
    border-width: 1px;
    color: rgb(22 163 74/var(--tw-text-opacity));
}

.unpaid {
    background-color: rgb(254 242 242/var(--tw-bg-opacity));
    border-color: rgb(254 226 226/var(--tw-border-opacity));
    border-style: solid;
    border-width: 1px;
    color: rgb(220 38 38/var(--tw-text-opacity));
}

.btn_label {
    align-items: center;
    border-radius: 0.5rem;
    display: inline-flex;
    font-size: .75rem;
    font-weight: 500;
    line-height: 1rem;
    padding: 0.25rem 0.625rem;
}
</style>
