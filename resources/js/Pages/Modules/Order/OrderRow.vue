<script setup>
import { ref } from "vue";

const showContent = ref(false);

// Hàm để toggle trạng thái của nội dung
const toggleContent = () => {
    showContent.value = !showContent.value;
    console.log(showContent.value)
};
const props = defineProps({
    order: Object
})
</script>

<template>
    <div>
        <div @click="toggleContent" class=" grid grid-cols-5 gap-4 text-sm px-3 py-3 text-gray-400">
            <div>
                <a class="flex items-center">
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0 mr-2" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>{{ order.order_number }}</a>
            </div>
            <div>
                <p>{{ order.customer.name }}</p>
            </div>
            <div>
                <p>{{ order.customer.phone_number }}</p>
            </div>
            <div>
                <p>{{ formatTimeDayMonthyear(order.created_at) }}</p>
            </div>
            <div>
                <p>{{ order.status }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 bg-gray-300 p-3 border rounded-lg  " v-if="showContent">

            <div class="my-3 rounded-lg border">
                <div class="title_information p-2">
                    <h3>Thông tin khách hàng</h3>
                </div>
                <div class="grid grid-cols-4 gap-4  bg-white py-3 px-3">

                    <div class="block">
                        <p class="text-gray-500">Số nhà/ Địa chỉ cụ thể</p>
                        <div class="item_information p-2 bg-gray-100 rounded-lg">
                            <p class="text-gray-600"> {{ order.customer.address }}</p>
                        </div>
                    </div>
                    <div class="block">
                        <p class="text-gray-500">Ghi chú</p>
                        <div class="item_information p-2 bg-gray-100 rounded-lg">
                            <p class="text-gray-600"> {{ order.note }}</p>
                        </div>
                    </div>
                    <!-- <div class="block">
                        <p class="text-gray-500">Hình thức thanh toán</p>
                        <div class="item_information p-2 bg-gray-100 rounded-lg">
                            <p class="text-gray-600">COD</p>
                        </div>
                    </div> -->
                </div>
            </div>
            <table class="table table-striped w-full text-sm text-left text-gray-500 text-gray-400 rounded-lg">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50  text-gray-400">
                    <tr class="info">
                        <th>Sản phẩm</th>
                        <th>SL</th>
                        <th>Giá </th>
                        <th>Thành tiền </th>
                        <!-- <th>Chiết khấu</th>
                        <th>Giá sau chiết khấu</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr class=" bg-white  bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600"
                        v-for="(item, index) in order.order_items" :key="index">
                        <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                            <!-- <img src="/img/xop.png" alt="" /> -->
                            <div class="ml-3">
                                <h4>
                                    {{ item.product.name }}
                                </h4>

                            </div>
                        </td>
                        <td class="px-6 py-4"> {{ item.quantity }}</td>
                        <td class="px-6 py-4">{{ formatPrice(item.price) }}đ</td>
                        <td class="px-6 py-4">{{ formatPrice(item.total_price) }}đ</td>
                        <!-- <td class="px-6 py-4">-10%</td>
                        <td class="px-6 py-4">225.000 đ7</td> -->
                    </tr>

                </tbody>
            </table>
            <div class="flex justify-between mx-2 border-b border-gray-400 pb-3">
                <p>Cộng tiền hàng</p>
                <p>450,000 ₫</p>

            </div>
            <div class="flex justify-between mx-2 border-b border-gray-400 pb-3">
                <p>Chiết khấu</p>
                <input class="px-3 py-2 border border-gray-400 rounded-lg w-24" readonly />
            </div>
            <div class="flex justify-between mx-2 border-b border-gray-400 pb-3">
                <p>Khách phải trả</p>
                <p class="text-red-600 text-xl">450,000 ₫</p>

            </div>
            <div class="flex justify-between ">
                <button class="border rounded-lg bg-gray-100 px-3 py-2">
                    Hủy đơn
                </button>
                <div class="flex">

                    <select id="countries"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4.5 py-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                        <option selected>Chưa thanh toán</option>

                        <option value="CA">Đã thanh toán</option>
                    </select>
                    <button class="px-3 py-2 ml-3  border rounded-lg bg-primary">
                        Đóng gói hàng
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>



<style></style>