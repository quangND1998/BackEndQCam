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
        <p class="text-black text-sm font-semibold col-span-2">Lịch phân phối quà</p>
        <div class="col-span-3 flex gap-4">
          <p class="text-black text-xs">Hoạt động: {{ orderPackages.length || 0 }}</p>
          <p class="text-black text-xs">Hợp đồng đã dừng: {{ declineOrderPackageCount || 0 }}</p>
        </div>
        <p class="col-span-3 text-black text-xs">Chú thích: 5(1) chu kỳ 12 lần năm thứ nhất</p>
        <div class="col-span-4 grid grid-cols-3 gap-4 justify-stretch">
          <div class="flex items-center gap-1">
            <div class="w-5 h-5 bg-[#FF0000]" />
            <p class="font-[13px] text-black">Quá hạn</p>
          </div>
          <div class="flex items-center gap-1">
            <div class="w-5 h-5 bg-[#CAAA00]" />
            <p class="font-[13px] text-black">Dự kiến 10 ngày</p>
          </div>
          <div class="flex items-center gap-1">
            <div class="w-5 h-5 bg-[#4F8D06]" />
            <p class="font-[13px] text-black">Thành công</p>
          </div>
        </div>
      </div>
      <div class="bg-[#AEAEAE] text-white font-bold grid grid-cols-[repeat(18,_minmax(0,_1fr))] divide-x">
        <div class="text-center text-[13px] leading-5">STT</div>
        <div class="text-center text-[13px] col-span-2 leading-5">Mã HĐ</div>
        <div class="text-center text-[13px] leading-5">Số năm</div>
        <div v-for="n in 12" :key="n" class="text-center text-[13px]">L{{n}}</div>
        <div></div>
      </div>
      <OrderTable v-for="(orderPackage, index) in orderPackages" :key="orderPackage.id" :index="index" :orderPackage="orderPackage" />
      <div class="w-full border-b border-x h-5 !border-[#AEAEAE] bg-white"></div>
  </div>
</template>
