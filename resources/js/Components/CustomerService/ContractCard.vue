<script setup>
import { computed } from 'vue';
import moment from 'moment';

import { CYCLE_TIME } from './stuffs/constants';
import BookingDialog from "@/Components/CustomerService/Dialog/BookingDialog.vue";
import RemindDialog from '@/Components/CustomerService/Dialog/RemindDialog.vue';

const props = defineProps({
  index: Number,
  orderPackage: Object,
});

const emits = defineEmits(['onOpenOrderDialog']);

const delivered = computed(() => {
  return props.orderPackage.product_service_owner.number_deliveries_current || 0;
});
const numberOfNeededDeliveried = computed(() => {
  return Math.floor(moment().diff(props.orderPackage.product_service_owner.time_approve, 'day') / CYCLE_TIME);
});
const outOfDateCount = computed(() => {
  return numberOfNeededDeliveried.value - delivered.value;
});
const nextDeliveryDate = computed(() => {
  return moment(props.orderPackage.product_service_owner.time_approve, 'YYYY-MM-DD HH:mm:ss')
    .add((numberOfNeededDeliveried.value + 1) * CYCLE_TIME, 'days')
    .format('DD/MM/YYYY');
});
const numberOfDelivery = computed(() => {
  return props.orderPackage.product_service.number_deliveries;
});
const numberOfActiveOrder = computed(() => {
  const orders = props.orderPackage.product_service_owner.orders;
  return orders.filter((order) => ['pending', 'packing', 'shipping'].includes(order.status)).length;
});
const numberOfCreatableOrder = computed(() => {
  return Math.min(...[numberOfDelivery.value - numberOfNeededDeliveried.value, 12, 12 - numberOfActiveOrder.value]);
});

const onOpenOrderDialog = () => {
  emits('onOpenOrderDialog', props.orderPackage);
}
</script>

<template>
  <div class="bg-white rounded-xl max-w-[300px] border mb-3 text-sm">
    <p class="font-semibold text-white bg-red-600 rounded-t-lg leading-8 pl-3">
      Hợp đồng {{ orderPackage.idPackage }}
    </p>
    <div class="px-3 py-2">
      <p class="mb-1">
        <span class="font-semibold">Đã nhận quà: </span>
        {{ orderPackage.product_service_owner.number_deliveries_current || 0 }}/{{ numberOfDelivery }}
      </p>
      <p class="mb-1"><span class="font-semibold">Quá hạn: </span>{{ outOfDateCount }}</p>
      <p><span class="font-semibold">Lần kế tiếp: </span>{{ nextDeliveryDate }} (lần {{ numberOfNeededDeliveried + 1 }})</p>
      <p class="mt-3 mb-2">Khách hàng có nhận quà lần kế tiêp không?</p>
      <div class="flex justify-between">
        <button
          class="rounded-full bg-emerald-600 text-white font-medium px-2 py-2"
          @click="onOpenOrderDialog"
        >
          Tạo đơn ({{ numberOfCreatableOrder }})
        </button>
        <RemindDialog :packageId="orderPackage.idPackage" :productServiceOwnerId="orderPackage.product_service_owner.id" />
      </div>
      <p class="mt-3 mb-2">Khách hàng muốn booking tham quan?</p>
      <BookingDialog :index="index" :packageId="orderPackage.idPackage" :productServiceOwnerId="orderPackage.product_service_owner.id" />
    </div>
  </div>
</template>
