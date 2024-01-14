<script setup>
import { computed } from 'vue';

import { getCycleYear } from '@/Components/CustomerService/stuffs/helpers';
import CycleTime from '@/Components/CustomerService/CycleTime.vue';

  const props = defineProps({
    orderPackage: Object,
    index: Number,
  });

  const orders = computed(() => {
    return [
      ...props.orderPackage.product_service_owner.orders,
      ...(new Array(12 - props.orderPackage.product_service_owner.orders.length).fill(undefined))
    ];
  });

  const cycleYear = computed(() => getCycleYear(
    props.orderPackage.product_service.life_time,
    props.orderPackage.product_service_owner.time_approve
  ));
</script>

<template>
  <div class="grid grid-cols-[repeat(18,_minmax(0,_1fr))] divide-x divide-gray-400 border-gray-400 border-b border-x text-sm">
    <div class="text-center">{{ index + 1 }}</div>
    <div class="col-span-2 pl-1">{{ orderPackage.idPackage }}</div>
    <div class="text-center">{{ orderPackage.product_service.life_time }} {{ cycleYear }}</div>
    <div v-for="(order, index) in orders" class="text-center">
      <CycleTime :data="order" :position="index" :startDate="orderPackage.product_service_owner.time_approve" />
    </div>
    <div class="bg-zinc-700 col-span-2" />
  </div>
</template>
