<script setup>
defineProps({
  index: Number,
  plan: Object,
});
</script>

<template>
  <div
    class="grid grid-cols-[repeat(28,_minmax(0,_1fr))] divide-x text-sm bg-white border-x border-b !border-gray-400 divide-gray-400 text-center items-center">
    <div class="leading-10">{{ index + 1 }}</div>
    <div class="col-span-3 leading-10">{{ plan.orderPackageId }}</div>
    <div class="col-span-2 leading-10">{{ plan.orderPackage.lifeTime }} năm</div>
    <div class="col-span-4 leading-10">{{ plan.orderPackage.customerName }}</div>
    <div class="col-span-2 leading-10">{{ moment(plan.orderPackage.activeDate, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY')}}</div>
    <template v-for="n in 7" :key="n">
      <div v-if="plan.plans[n]" class="col-span-2 text-center leading-10">
        <div v-if="plan.plans[n] === 'pending'" class="w-4 h-4 rounded-sm bg-gray-400" />
        <div v-if="plan.plans[n] === 'not_called'" class="w-4 h-4 rounded-sm bg-red-600" />
        <div v-if="plan.plans[n] === 'not_answerd'" class="w-4 h-4 rounded-sm bg-sky-600" />
        <div v-if="plan.plans[n] === 'completed'" class="w-4 h-4 rounded-sm bg-emerald-600" />
      </div>
      <div v-else>&#8203;</div>
    </template>
    <div class="col-span-2 grid items-center justify-center h-10">
      <a :href="`/customer-service/customer/${plan.orderPackage.customer.id}/order-packages`" class="flex items-center cursor-pointer">
        <i class="fa fa-phone text-2xl text-emerald-600" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</template>
