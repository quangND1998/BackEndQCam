<script setup>
import ScheduleVisitTable from '@/Components/CustomerService/Table/ScheduleVisitTable.vue';

const props = defineProps({
  orderPackages: Array,
});

</script>

<template>
  <div>
    <div class="grid grid-cols-12 text-sm gap-4 mb-1 items-end">
      <p class="col-span-8 text-black text-sm font-semibold">Quá trình booking thăm vườn</p>
      <div class="col-span-4 gap-4 justify-stretch grid grid-cols-3">
        <div class="flex items-center gap-1">
          <div class="w-6 h-6 bg-[#FF0000]" />
          <p class="font-[13px] text-black">Hủy</p>
        </div>
        <div class="flex items-center gap-1">
          <div class="w-5 h-5 bg-[#CAAA00]" />
          <p class="font-[13px] text-black">Lên lịch</p>
        </div>
        <div class="flex items-center gap-1">
          <div class="w-5 h-5 bg-[#4F8D06]" />
          <p class="font-[13px] text-black">Đã thăm</p>
        </div>
      </div>
    </div>
    <div class="bg-[#AEAEAE] text-white font-bold grid grid-cols-[repeat(18,_minmax(0,_1fr))] divide-x">
      <div class="text-center text-[13px] leading-5">STT</div>
      <div class="text-center text-[13px] leading-5 col-span-2">Mã HĐ</div>
      <div class="text-center text-[13px] leading-5">Số năm</div>
      <div v-for="n in 12" :key="n" class="text-center text-[13px]">L{{ n }}</div>
      <div></div>
    </div>
    <ScheduleVisitTable v-for="(orderPackage, index) in orderPackages" :key="orderPackage.id" :index="index" :orderPackage="orderPackage" />
    <div class="w-full border-b border-x h-5 !border-[#AEAEAE] bg-white"></div>
  </div>
</template>
