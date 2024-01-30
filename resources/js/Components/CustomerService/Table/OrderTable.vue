<script setup>
import { computed } from 'vue';
import moment from 'moment';

import { getCycleYear } from '@/Components/CustomerService/stuffs/helpers';
import CycleTime from '@/Components/CustomerService/CycleTime.vue';

  const props = defineProps({
    orderPackage: Object,
    index: Number,
  });

  const orders = computed(() => {
    if (props.orderPackage.product_service_owner.orders.length < 12)
      return [
        ...props.orderPackage.product_service_owner.orders,
        ...(new Array(12 - props.orderPackage.product_service_owner.orders.length).fill(undefined))
      ];

    return props.orderPackage.product_service_owner.orders.slice(-12);
  });

  const lifeTime = computed(() => {
    return props.orderPackage.product_service.life_time;
  });

  const cycleYear = computed(() => getCycleYear(
    lifeTime.value,
    props.orderPackage.product_service_owner.time_approve
  ));
  const multipler = computed(() => {
    const orderLength = props.orderPackage.product_service_owner.orders.length;
    return orderLength === 0 ? 0 : Math.ceil(orderLength / 12) - 1;
  })
</script>

<template>
  <div class="grid grid-cols-[repeat(18,_minmax(0,_1fr))] divide-x divide-[#AEAEAE] border-[#AEAEAE] border-b border-x bg-white">
    <div class="text-center text-xs text-black font-semibold leading-5">{{ index + 1 }}</div>
    <div class="col-span-2 pl-1 text-[#FF0000] text-xs font-semibold leading-5">{{ orderPackage.idPackage }}</div>
    <div class="text-center text-xs text-black leading-5">{{ orderPackage.product_service.life_time }} {{ cycleYear }}</div>
    <div v-for="(order, orderIndex) in orders" :key="order" class="text-center">
      <CycleTime :data="order" :position="orderIndex" :packageIndex="index"  :startDate="orderPackage.time_approve" :allowPopover="true" :multipler="multipler" />
    </div>
    <div v-if="lifeTime === 1 || cycleYear === lifeTime" class="bg-[#3D3C3C] col-span-2" />
    <div v-else class="col-span-2">
      <CycleTime :data="undefined" :position="12" :packageIndex="index" :startDate="orderPackage.time_approve" :allowPopover="true" class="indent-2" />
    </div>
  </div>
</template>
