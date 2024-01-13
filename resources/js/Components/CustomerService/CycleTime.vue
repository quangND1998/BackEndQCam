<script setup>
import { computed } from 'vue';
import moment from 'moment';

const props = defineProps({
  order: Object,
  position: Number,
  startDate: String,
});

const date = computed(() => {
  return moment(props.startDate, 'YYYY-MM-DD HH:mm:ss').add((props.position + 1) * 25, 'days').format('DD/MM/YY');
});
const cellStyle = computed(() => {
  if (!props.order) return 'bg-zinc-700 text-white';
});
</script>

<template>
  <p class="text-xs leading-5 cursor-pointer" :class="cellStyle">{{ date }}</p>
</template>
