<script setup>
import { computed, inject } from 'vue';

const { customer } = inject('ORDER_PACKAGE_PAGE');

const props = defineProps({
  tableHeight: Number
});

const topPosition = computed(() => {
  return `calc(${props.tableHeight + 56}px) `;
});
const hidePhoneNumber = computed(() => {
  if (customer.phone_number) {
    return customer.phone_number.slice(0, -4).padEnd(customer.phone_number.length, '*');
  }
  return '';
})
</script>

<template>
  <div title="Gọi cho khách hàng" class="fixed right-0 p-2 pr-5 bg-white border !border-gray-400 rounded-l-full flex items-center gap-3 cursor-pointer" :style="`top: ${topPosition}`">
    <div class="w-10 h-10 flex items-center justify-center bg-emerald-500 rounded-full">
      <i class="fa fa-phone text-white text-2xl" aria-hidden="true"></i>
    </div>
    <div>
      <p class="font-medium">{{ customer.name }}</p>
      <p class="font-medium">{{ hidePhoneNumber }}</p>
    </div>
  </div>
</template>
