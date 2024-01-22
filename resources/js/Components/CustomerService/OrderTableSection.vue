<script setup>
import OrderTable from '@/Components/CustomerService/Table/OrderTable.vue';

const props = defineProps({
  orderPackages: Array,
  declineOrderPackageCount: Number,
});
</script>

<template>
  <div>
    <div class="grid grid-cols-12 text-sm gap-4 mb-1 items-end">
        <p class="font-bold col-span-2">Lịch phân phối quà</p>
        <div class="col-span-3 flex gap-4">
          <p>Hoạt động: {{ orderPackages.length || 0 }}</p>
          <p>Hợp đồng đã dừng: {{ declineOrderPackageCount || 0 }}</p>
        </div>
        <p class="col-span-3">Chú thích: 5(1) chu kỳ 12 lần năm thứ nhất</p>
        <div class="col-span-4 grid grid-cols-3 gap-4 justify-stretch">
          <div class="flex items-center gap-1">
            <div class="w-6 h-6 bg-red-600" />
            <p>Quá hạn</p>
          </div>
          <div class="flex items-center gap-1">
            <div class="w-6 h-6 bg-yellow-600" />
            <p>Dự kiến 10 ngày</p>
          </div>
          <div class="flex items-center gap-1">
            <div class="w-6 h-6 bg-emerald-600" />
            <p>Thành công</p>
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
      <OrderTable v-for="(orderPackage, index) in orderPackages" :key="orderPackage.id" :index="index" :orderPackage="orderPackage" />
      <div class="w-full border-b border-x h-5 !border-gray-400 bg-white"></div>
  </div>
</template>
