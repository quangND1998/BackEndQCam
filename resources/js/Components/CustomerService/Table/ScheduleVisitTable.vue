<script setup>
import { computed } from 'vue';
import moment from 'moment';

import { getCycleYear } from '@/Components/CustomerService/stuffs/helpers';
import CycleTime from '@/Components/CustomerService/CycleTime.vue';

  const props = defineProps({
    orderPackage: Object,
    index: Number,
  });

  const visits = computed(() => {
    const sortedVisit = props.orderPackage.product_service_owner.visit.sort((a, b) => {
      return moment(a.date_time, 'YYYY-MM-DD HH:mm:ss').diff(moment(b.date_time, 'YYYY-MM-DD HH:mm:ss'), 'seconds');
    });

    return [
      ...sortedVisit,
      ...(new Array(12 - props.orderPackage.product_service_owner.visit.length).fill(undefined))
    ];
  });

  const startDate = computed(() => {
    const scheduleVisits = props.orderPackage.product_service_owner.visit;
    return scheduleVisits.length > 0
      ? moment(scheduleVisits[scheduleVisits.length - 1].date_time, 'YYYY-MM-DD HH:mm:ss')
        .subtract(25, 'days')
        .format('YYYY-MM-DD HH:mm:ss')
      : props.orderPackage.product_service_owner.time_approve;
  });

  const subtractPosition = computed(() => {
    const scheduleVisits = props.orderPackage.product_service_owner.visit;
    return scheduleVisits.length > 0
      ? scheduleVisits.length - 1
      : 0;
  });

  const lifeTime = computed(() => {
    return props.orderPackage.product_service.life_time;
  });

  const cycleYear = computed(() => getCycleYear(
    lifeTime.value,
    props.orderPackage.product_service_owner.time_approve
  ));
</script>

<template>
  <div class="grid grid-cols-[repeat(18,_minmax(0,_1fr))] divide-x divide-gray-400 border-gray-400 border-b border-x text-sm bg-white">
      <div class="text-center">{{ index + 1 }}</div>
      <div class="col-span-2 pl-1">{{ orderPackage.idPackage }}</div>
      <div class="text-center">{{ orderPackage.product_service.life_time }} {{ cycleYear }}</div>
      <div v-for="(visit, visitIndex) in visits" :key="visitIndex" class="text-center">
        <CycleTime :data="visit" :position="visitIndex - subtractPosition" :packageIndex="index" :startDate="startDate"
          :allowEmpty="true" :showEmpty="true" />
      </div>
      <div class="bg-zinc-700 col-span-2" />
    </div>
</template>
