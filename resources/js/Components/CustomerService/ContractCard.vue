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

const orders = computed(() => props.orderPackage.product_service_owner.orders);
const numberOfExistsOrder = computed(() => {
  return orders.value.length;
});
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
    .add((numberOfNeededDeliveried.value + numberOfExistsOrder.value + 1) * CYCLE_TIME, 'days')
    .format('DD/MM/YYYY');
});
const numberOfDelivery = computed(() => {
  return props.orderPackage.product_service.number_deliveries;
});
const numberOfActiveOrder = computed(() => {
  return orders.value.filter((order) => ['pending', 'packing', 'shipping'].includes(order.status)).length;
});
const numberOfCreatableOrder = computed(() => {
  return Math.min(...[numberOfDelivery.value - numberOfNeededDeliveried.value, 12, 12 - numberOfActiveOrder.value]);
});
const nextDeliveryNo = computed(() => {
  return numberOfNeededDeliveried.value + numberOfExistsOrder.value + 1;
});
const isDisableCreateOrder = computed(() => {
  return numberOfActiveOrder.value === 12;
});

const onOpenOrderDialog = () => {
  if (isDisableCreateOrder.value) return;
  emits('onOpenOrderDialog', props.orderPackage, numberOfExistsOrder.value + 1, props.index);
}
</script>

<template>
  <div class="bg-white rounded-xl max-w-[300px] border mb-3 text-sm">
    <p class="font-semibold text-white bg-[#EE2736] rounded-t-lg leading-8 pl-3">
      Hợp đồng {{ orderPackage.idPackage }}
    </p>
    <div class="px-3 py-2 text-[13px]">
      <p class="mb-1 text-black">
        <span class="font-semibold">Đã nhận quà: </span>
        {{ orderPackage.product_service_owner.number_deliveries_current || 0 }}/{{ numberOfDelivery }}
      </p>
      <p class="mb-1"><span class="font-semibold">Quá hạn: </span>{{ outOfDateCount }}</p>
      <p class="text-black"><span class="font-semibold">Lần kế tiếp: </span>{{ nextDeliveryDate }} (lần {{ nextDeliveryNo }})</p>
      <p class="mt-3 mb-2 text-black">Khách hàng có nhận quà lần kế tiêp không?</p>
      <div class="flex justify-between">
          <button
            :disabled="isDisableCreateOrder"
            :title="isDisableCreateOrder ? 'Chỉ tạo được tối đa 12 đơn mỗi lần' : undefined"
            class="rounded-full bg-[#4F8D06] text-white font-semibold px-2 py-2 disabled:bg-gray-400 disabled:cursor-not-allowed"
            @click="onOpenOrderDialog"
          >
            Tạo đơn ({{ numberOfCreatableOrder }})
          </button>
        <RemindDialog :index="index" :packageId="orderPackage.idPackage" :productServiceOwnerId="orderPackage.product_service_owner.id" />
      </div>
      <p class="mt-3 mb-2">Khách hàng muốn booking tham quan?</p>
      <BookingDialog :index="index" :packageId="orderPackage.idPackage" :productServiceOwnerId="orderPackage.product_service_owner.id" />
    </div>
  </div>
</template>
