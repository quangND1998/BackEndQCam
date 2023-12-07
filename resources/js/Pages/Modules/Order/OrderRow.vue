<script setup>
import { ref } from "vue";
import OrderAction from "@/Pages/Modules/Order/OrderAction.vue"
import { Head, Link } from "@inertiajs/vue3";
import BaseButton from "@/Components/BaseButton.vue";
import BaseIcon from "@//Components/BaseIcon.vue"
import {
    mdiEye,
    mdiPencil ,
    mdiAccountLockOpen,
    mdiPlus,
    mdiFilter,
    mdiMagnify,
    mdiFileDocumentOutline,
} from "@mdi/js";
const showContent = ref(false);
// Hàm để toggle trạng thái của nội dung
const toggleContent = () => {
    showContent.value = !showContent.value;
    console.log(showContent.value)
};
const props = defineProps({
    order: Object,
    status: String
})
</script>

<template>
    <div>
        <div @click.prevent="toggleContent" class=" grid grid-cols-9 px-3 py-1 mt-2 mb-2 text-sm  text-gray-400">
            <div>
                <Link class="flex items-center" :href="route('admin.payment.orderCashBankingPayment', order.id)">
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0 mr-2" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>{{ order.order_number }}</Link>
            </div>
            <div>
                <p>{{ order.customer?.name }}</p>
            </div>
            <div>
                <p>{{ order.customer?.phone_number }}</p>
            </div>
            <div>
                <p>{{ formatTimeDayMonthyear(order.created_at) }}</p>
            </div>
            <div>
                <p class="btn_label mr-2 paid">{{ order.status }}</p>
            </div>

          
          
            <div>
                <p v-if="order.type == 'gift_delivery'" class="btn_label mr-2 paid" 
                  > Không cần thành toán</p>
                <p v-else class="btn_label mr-2" 
                    :class="order.payment_status  ==  0 ? 'unpaid' : 'paid'">
                    {{ order.payment_status  ==  0
                        ? 'Chưa thanh toán' : 'Đã thanh toán' }}</p>
            </div>
            <div>
                <p>{{ order.type == 'gift_delivery' ? 'Giao quà' : 'Đơn lẻ' }}</p>
            </div>
            <div>
                <p v-if="order.saler">{{ order.saler.name }}</p>
            </div>

            <div>
                <Link
                    :href="route('admin.payment.orderCashBankingPayment', order.id)"
                   >
                <BaseIcon  :path="mdiEye" :size="16" />
                </Link>

                <Link  v-if="order.status =='pending'"
                    :href="route('admin.orders.update', [order.id])"
                   >
                <BaseIcon  :path="mdiPencil" :size="16" />
                </Link>
           
              
            </div>
        </div>

        <div class="grid grid-cols-1  bg-gray-200 p-3 border rounded-xl" v-if="showContent">

            <div class="mt-1 rounded-lg border">
                <div class="title_information flex justify-between p-2">
                    <h3>Thông tin khách hàng</h3>
                </div>
                <div class="grid-cols-4 gap-4 flex justify-between bg-white py-3 px-3">
                    <div class="block">
                        <p class="text-gray-500">Số nhà/ Địa chỉ cụ thể</p>
                        <div class="item_information p-2 bg-gray-100 rounded-lg">
                            <p class="text-gray-600 font-semibold text-sm"> {{ order.address + ',' + order.wards + ',' + order.district + ',' +
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
            </div>
            <table class="table table-striped w-full text-sm text-left text-gray-500 text-gray-400 rounded-lg">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50  text-gray-400">
                    <tr class="info">
                        <th>Sản phẩm</th>
                        <th>SL</th>
                        <th>Giá </th>
                        <th>Thành tiền </th>
                        <th></th>
                        <!-- <th>Chiết khấu</th>
                        <th>Giá sau chiết khấu</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr class="mt-1 bg-white border-gray-700  hover:bg-gray-600"
                        v-for="(item, index) in order.order_items" :key="index">
                        <td class="px-3 flex py-2 font-medium text-gray-900 whitespace-nowrap ">
                            <!-- <img src="/img/xop.png" alt="" /> -->
                            <div class="ml-0">
                                <h4>
                                    {{ item.product.name }}
                                </h4>

                            </div>
                        </td>
                        <td class="px-3 py-2 border-0"> {{ item.quantity }}</td>
                        <td class="px-3 py-2 border-0">{{ formatPrice(item.price) }}đ</td>
                        <td class="px-3 py-2 border-0">{{ formatPrice(item.total_price) }}đ</td>
                        <td class="border-0 text-center">
                            <Link v-if="order.payment_method == 'cash' || order.payment_method == 'banking'"
                                :href="route('admin.payment.orderCashBankingPayment', order.id)"
                                class=" text-sm text-blue hover:opacity-0.8 mx-1">
                            Chi tiết
                            </Link>
                        </td>
                        <!-- <td class="px-6 py-4">-10%</td>
                        <td class="px-6 py-4">225.000 đ7</td> -->
                    </tr>

                </tbody>
            </table>
            <div class="flex justify-between mx-2  border-gray-300 text-sm">
                <p>Cộng tiền hàng</p>
                <p>{{ formatPrice(order.grand_total) }}₫</p>

            </div>
            <div v-if="order.discount" class="flex justify-between mx-2  border-gray-300 text-sm">
                <p>Voucher</p>
                <input v-if="order.discount" :value="formatPrice(order.discount.discount_mount)"
                    class="px-3 py-2 border border-gray-400 rounded-lg w-24" readonly />
            </div>
            <div class="flex justify-between mx-2  border-gray-300 text-sm">
                <p>Ưu đãi</p>
                <p v-if="order.discount_deal > 0">{{ order.discount_deal }} %</p>
                <p v-else>0%</p>
            </div>
            <div class="flex justify-between mx-2  border-gray-300 text-sm">
                <p>VAT</p>
                <p v-if="order.vat > 0">{{ order.vat }} %</p>
                <p v-else>0%</p>
            </div>
            <div class="flex justify-between mx-2  border-gray-300 text-sm">
                <p>Phí ship</p>
                <p class="text-red-600 text-lg">{{ formatPrice(order.shipping_fee) }} ₫</p>
            </div>
            <div class="flex justify-between mx-2  border-gray-300 text-sm">
                <p>Tổng giá trị</p>
                <p class="text-red-600 text-lg">{{ formatPrice(order.last_price) }} ₫</p>
            </div>

            <!-- <div class="flex justify-between mx-2 border-b border-gray-400 ">
                <p>Khách đã trả</p>
                <p class="text-red-600 text-xl">{{ formatPrice(order.amount_paid) }} ₫</p>

            </div> -->

            <div v-if="order.amount_unpaid" class="flex justify-between mx-2 border-gray-300 text-sm">
                <p>Khách phải trả</p>
                <p class="text-red-600 text-lg">{{ formatPrice(order.amount_unpaid) }} ₫</p>

            </div>
            <OrderAction :order="order" :status="status"></OrderAction>
        </div>
    </div>
</template>



<style>


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
}</style>
