
<script setup>

const props = defineProps({
    order: Object
})

</script>
<template>
    <div class="min-[320px]:block md:flex  border-b border-gray-300">
        <div class="min-[320px]:w-full md:w-1/2">
            <div class="pb-3 ">
                <img src="/assets/images/cammattroi.png" alt="">
                <h1 class="text-base font-semibold uppercase">CÔNG TY CỔ PHẦN {{ $page.props.company_infor?.name }}</h1>
                <p class="text-sm text-[#5F5F5F]">Địa chỉ: {{ $page.props.company_infor?.address }}</p>
                <p class="text-sm text-[#5F5F5F]">Farm:</p>
                <p class="text-sm text-[#5F5F5F]">Điện thoại: {{ $page.props.company_infor?.hotline }}</p>
                <p class="text-sm text-[#5F5F5F]">Email: {{ $page.props.company_infor?.email }}</p>
            </div>

        </div>
        <div class="min-[320px]:w-full md:w-1/2">
            <div class="flex my-2 items-center">
                <p class="text-sm text-[#5F5F5F] w-36">Số phiếu #</p>
                <p class="text-sm text-[#5F5F5F] ">{{ order.idPackage }}</p>
            </div>
            <div class="flex my-2 items-center">
                <p class="text-sm text-[#5F5F5F] w-36">Ngày</p>
                <p class="text-sm text-[#5F5F5F] ">{{ formatDate(order.created_at) }}</p>
            </div>
            <div class="flex my-2 items-center">
                <p class="text-sm text-[#5F5F5F] w-36">Thanh toán</p>
                <input v-if="order.payment_method == 'cash'" type="text" id="first_name" readonly
                    value="Thanh toán Tiền mặt"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="" required>
                <input v-if="order.payment_method == 'banking'" type="text" id="first_name" readonly
                    value="Thanh toán Chuyển khoản"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="" required>
                <input v-if="order.payment_method == 'payoo'" type="text" id="first_name" readonly
                    value="Thanh toán bằng Payoo"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="" required>
            </div>
        </div>
    </div>
</template>
