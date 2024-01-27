<script setup>
  import { inject } from 'vue';
  import moment from 'moment';

  import TableRow from '@/Components/CustomerService/Table/PlanRow.vue';
  import SpinnerIcon from '@/Components/CustomerService/SpinnerIcon.vue';

  const { orderPackagePlans, isLoading } = inject('PLAN');
</script>

<template>
  <div
    class="grid grid-cols-[repeat(28,_minmax(0,_1fr))] divide-x text-sm bg-gray-400 text-white divide-white items-stretch font-semibold">
    <div class="grid items-center justify-center">STT</div>
    <div class="col-span-3 grid items-center justify-center">Mã HĐ</div>
    <div class="col-span-2 grid items-center justify-center">Loại HĐ</div>
    <div class="col-span-4 grid items-center justify-center">Tên KH</div>
    <div class="col-span-2 grid items-center justify-center">Ngày kích hoạt</div>
    <div v-for="n in 7" class="col-span-2 text-center py-1">
      <p>{{ n === 7 ? 'Chủ nhật' : `Thứ ${n + 1}` }}</p>
      <p class="font-normal">{{ moment().weekday(n).format('DD/MM') }}</p>
    </div>
    <div class="col-span-2 grid items-center justify-center">Chăm sóc</div>
  </div>
  <div v-if="isLoading" class="bg-white text-center border-x border-b border-gray-400 flex items-center justify-center h-28">
    <SpinnerIcon class="!m-0 !w-10 !h-10" />
  </div>
  <template v-else>
    <TableRow v-for="(plan, index) in orderPackagePlans" :index="index" :plan="plan"/>
  </template>
  <div>
    <div v-for="n in Math.abs(orderPackagePlans.length - 4)" :key="n"
      class="grid grid-cols-[repeat(28,_minmax(0,_1fr))] divide-x text-sm bg-white border-x last:border-b !border-gray-400 divide-gray-400 items-center text-center leading-10">
      <div>&#8203;</div>
      <div class="col-span-3">&#8203;</div>
      <div class="col-span-2">&#8203;</div>
      <div class="col-span-4">&#8203;</div>
      <div class="col-span-2">&#8203;</div>
      <div v-for="n in 7" :key="n" class="col-span-2 text-center">&#8203;</div>
      <div class="col-span-2">&#8203;</div>
    </div>
  </div>
</template>
