<script setup>
import { computed } from 'vue';

const props = defineProps({
  status: String,
});

const displayStatus = computed(() => {
  switch (props.status) {
    case 'pending':
      return 'Chờ xử lý';
    case 'packing':
      return 'Đóng gói';
    case 'shipping':
      return 'Đang giao hàng';
    case 'completed':
    case 'complete':
    case 1:
      return 'Hoàn thành';
    case 'refund':
      return 'Hoàn trả';
    case 'decline':
    case 'cancel':
    case 0:
      return 'Hủy';
    case 'critical':
      return 'Khẩn cấp';
    case 'urgent':
      return 'Xử lý sớm';
    case 'normal':
      return 'Bình thường';
  }
})
</script>

<template>
  <p
    class="!px-3 !py-1 rounded-full border-2 text-xs !border-emerald-500 font-semibold w-fit capitalize"
    :class="{
      '!text-gray-500 !border-gray-500': status === 'pending',
      '!text-yellow-500 !border-yellow-500':  status === 'packing' || status === 'normal',
      '!text-sky-500 !border-sky-500': status === 'shipping',
      '!text-emerald-500 !border-emerald-500': status === 'completed' || status === 'complete' || status === 1,
      '!text-orange-500 !border-orange-500': status === 'refund' || status === 'urgent',
      '!text-red-500 !border-red-500': status === 'decline' || status === 'cancel' || status === 0 || status === 'critical',
    }"
  >
      {{ displayStatus }}
    </p>
</template>
