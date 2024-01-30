<script setup>
import moment from 'moment';

defineProps({
  index: Number,
  plan: Object,
});
</script>

<template>
  <div
    class="grid grid-cols-[repeat(28,_minmax(0,_1fr))] divide-x text-sm bg-white border-x border-b !border-[#AEAEAE] divide-[#AEAEAE] text-black text-center items-center">
    <div class="leading-10 font-semibold">{{ index + 1 }}</div>
    <div class="col-span-4 leading-10 font-semibold text-[#FF0000]">{{ plan.orderPackageId }}</div>
    <div class="col-span-2 leading-10">{{ plan.lifeTime }} năm</div>
    <div class="col-span-5 leading-10">{{ plan.customerName }}</div>
    <div class="col-span-2 leading-10">{{ moment(plan.activeDate, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY')}}</div>
    <template v-for="n in 6" :key="n">
      <div v-if="plan.plans[`day_${n}`]" class="col-span-2 text-center grid h-full grid-cols-1 items-center justify-center">
        <div v-if="plan.plans[`day_${n}`]?.call === 'pending'" class="w-4 h-4 rounded-sm mx-auto bg-[#3D3C3C]" />
        <div v-else-if="plan.plans[`day_${n}`]?.call === 'notCalled'" class="w-4 h-4 rounded-sm mx-auto bg-[#FF0303]" />
        <div v-else-if="plan.plans[`day_${n}`]?.call === 'dontAnswer'" class="w-4 h-4 rounded-sm mx-auto bg-[#1D75FA]" />
        <div v-else-if="plan.plans[`day_${n}`]?.call === 'called'" class="w-4 h-4 rounded-sm mx-auto bg-[#4F8D06]" />
        <div v-if="plan.plans[`day_${n}`]?.order === true">
          SHIP
        </div>
      </div>
      <div v-else class="col-span-2 text-center leading-10">&#8203;</div>
    </template>
    <div class="col-span-2 grid items-center justify-center h-10">
      <a :href="`/customer-service/customer/${plan.customerId}/order-packages`" class="flex items-center cursor-pointer">
        <i class="fa fa-phone text-2xl text-[#4F8D06]" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</template>
