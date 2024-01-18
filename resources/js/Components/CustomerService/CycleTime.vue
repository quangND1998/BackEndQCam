<script setup>
import { computed } from 'vue';
import moment from 'moment';

import { SCHEDULE_VISIT_STATE, CYCLE_TIME } from '@/Components/CustomerService/stuffs/constants';

const props = defineProps({
  data: Object,
  position: Number,
  startDate: String,
  allowEmpty: {
    required: false,
    default: false,
  },
  showEmpty: {
    required: false,
    default: false,
  },
});

const date = computed(() => {
  if (props.data) {
    return moment(props.data.date_time, 'YYYY-MM-DD HH:mm:ss');
  }
  const subtractDate = moment(props.startDate, 'YYYY-MM-DD HH:mm:ss').weekday() === 7 ? 1 : 0;
  return moment(props.startDate, 'YYYY-MM-DD HH:mm:ss').add((props.position + 1) * CYCLE_TIME - subtractDate, 'days');
});
const cellStyle = computed(() => {
  if (!props.data && props.showEmpty) return 'bg-zinc-700 text-zinc-700 select-none';
  if (props.data && props.data.state === SCHEDULE_VISIT_STATE.COMPLETE) return 'bg-emerald-600 text-white';
  if (props.data && props.data.state === SCHEDULE_VISIT_STATE.CANCEL) return 'bg-red-600 text-white';
  if (props.data && date.value.diff(new Date(), 'seconds') > 0) return 'bg-yellow-600 text-white';
  if (date.value.diff(new Date(), 'seconds') < 0 && !props.allowEmpty) return 'bg-red-600 text-white';
  if (!props.data) return 'bg-zinc-700 text-white';
});
const displayText = computed(() => {
  if (!props.data && props.showEmpty) return 'Chưa thăm';
  return date.value.format('DD/MM/YYYY');
})
</script>

<template>
  <p class="text-xs leading-5 cursor-pointer" :class="cellStyle">{{ displayText }}</p>
</template>
