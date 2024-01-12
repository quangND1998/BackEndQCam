<script setup>
import CycleTime from '@/Components/CustomerService/CycleTime.vue';

const props = defineProps({
  orderPackages: Object,
});

const fillMissingOrders = (orders) => {
  return [...orders, ...(new Array(12 - orders.length).fill(undefined))];
}
</script>

<template>
  <div>
    <div class="grid grid-cols-12 text-sm gap-4 mb-1 items-end">
        <p class="font-bold col-span-8">Quá trình booking thăm vườn</p>
        <div class="col-span-4 gap-4 justify-stretch grid grid-cols-3">
          <div class="flex items-center gap-1">
            <div class="w-6 h-6 bg-red-600" />
            <p>Hủy</p>
          </div>
          <div class="flex items-center gap-1">
            <div class="w-6 h-6 bg-yellow-600" />
            <p>Lên lịch</p>
          </div>
          <div class="flex items-center gap-1">
            <div class="w-6 h-6 bg-emerald-600" />
            <p>Đã thăm</p>
          </div>
        </div>
      </div>
      <div class="bg-gray-400 text-white font-bold grid grid-cols-[repeat(18,_minmax(0,_1fr))] divide-x">
        <div class="text-center">STT</div>
        <div class="text-center col-span-2">Mã HĐ</div>
        <div class="text-center">Số năm</div>
        <div v-for="n in 12" class="text-center">L{{n}}</div>
        <div></div>
      </div>
      <div
        v-for="(orderPackage, index) in orderPackages"
        :key="orderPackage.id"
        class="grid grid-cols-[repeat(18,_minmax(0,_1fr))] divide-x divide-gray-400 border-gray-400 border-b border-x text-sm"
      >
        <div class="text-center">{{ index + 1 }}</div>
        <div class="col-span-2 pl-1">{{ orderPackage.idPackage }}</div>
        <div class="text-center">{{ orderPackage.product_service.life_time }}</div>
        <div v-for="(order, index) in fillMissingOrders(orderPackage.product_service_owner.orders)" class="text-center">
          <CycleTime :order="order" :position="index" :startDate="orderPackage.product_service_owner.time_approve" />
        </div>
        <div class="bg-zinc-700 col-span-2" />
      </div>
      <div class="w-full border-b border-x h-5 !border-gray-400"></div>
  </div>
</template>
