<script setup>
import { computed, inject, ref } from 'vue';
import moment from 'moment';

import { SCHEDULE_VISIT_STATE, CYCLE_TIME } from '@/Components/CustomerService/stuffs/constants';
import { arrow, useFloating, autoUpdate } from '@floating-ui/vue';

const { onOpenEditOrderDialog } = inject('ORDER')

const props = defineProps({
  data: Object,
  position: Number,
  startDate: String,
  packageIndex: Number,
  allowEmpty: {
    required: false,
    default: false,
  },
  showEmpty: {
    required: false,
    default: false,
  },
  allowPopover: {
    required: false,
    default: false,
  },
  multipler: {
    required: false,
    default: 0,
  }
});

const reference = ref(null);
const floating = ref(null);
const showPopover = ref(false);
const floatingArrow = ref(null);
const timeoutRef = ref();
const { floatingStyles, middlewareData } = useFloating(reference, floating, {
  middleware: [
    arrow({ element: floatingArrow }),
  ],
  whileElementsMounted: autoUpdate,
});

const date = computed(() => {
  // Is schedule visit
  if (props.data && props.data.booking_type) {
    return moment(props.data.date_time, 'YYYY-MM-DD HH:mm:ss');
  }

  const additionalDate = props.multipler * 12 * CYCLE_TIME
  const nextDate = moment(props.startDate, 'YYYY-MM-DD HH:mm:ss').add(((props.position + 1) * CYCLE_TIME + additionalDate), 'days');

  return nextDate.weekday() === 0 ? nextDate.subtract(1, 'day') : nextDate;
});
const cellStyle = computed(() => {
  if (props.data && props.data?.status === 'complete') return 'bg-[#4F8D06] text-white';
  if (!props.data && props.showEmpty) return 'bg-[#3D3C3C] text-[#3D3C3C] select-none';
  if (props.data && props.data?.state === SCHEDULE_VISIT_STATE.COMPLETE) return 'bg-[#4F8D06] text-white';
  if (props.data && props.data?.state === SCHEDULE_VISIT_STATE.CANCEL) return 'bg-[#FF0000] text-white';
  if (props.data && props.data?.state === SCHEDULE_VISIT_STATE.PENDING && date.value.diff(new Date(), 'days') < 0) return 'bg-[#FF0000] text-white';
  if (props.data && date.value.diff(new Date(), 'days') >= 0) return 'bg-[#CAAA00] text-white';
  if (date.value.diff(new Date(), 'days') < 0 && !props.allowEmpty) return 'bg-[#FF0000] text-white';
  if (!props.data) return 'bg-[#3D3C3C] text-white';
});
const displayText = computed(() => {
  if (!props.data && props.showEmpty) return 'Chưa thăm';
  return date.value.format('DD/MM/YYYY');
})

const openPopover = () => {
  clearTimeout(timeoutRef.value);
  timeoutRef.value = setTimeout(() => { showPopover.value = true }, 200);
}
const closePopover = () => {
  clearTimeout(timeoutRef.value);
  showPopover.value = false;
}
const onUpdateOrder = () => {
  if (!props.data) return;
  onOpenEditOrderDialog(props.data, props.position, props.packageIndex);
}
const isFirstOrderDelivery = computed(() => {
  return props.allowPopover && props.position === 0;
})
</script>

<template>
  <div @mouseover="openPopover" @mouseleave="closePopover">
    <p ref="reference" class="text-xs leading-5 cursor-pointer" :class="cellStyle">
      {{ isFirstOrderDelivery
        ? (data && data?.delivery_appointment) ? moment(data.delivery_appointment, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY') : 'dd/mm/yy'
        : displayText  }}
    </p>
    <div v-if="data && data.order_number && allowPopover" v-show="showPopover" ref="floating" :style="floatingStyles"
      class="bg-white rounded-lg border !border-[#AEAEAE] py-3 z-50">
      <div ref="floatingArrow" class="triangle" :style="{
        position: 'absolute',
        left:
          middlewareData.arrow?.x != null
            ? `${middlewareData.arrow.x}px`
            : '',
        top: `calc(${-floatingArrow?.offsetWidth}px + 26px)`,
      }"></div>
      <div></div>
      <div class="mb-2 px-3 flex items-center justify-between">
        <p class="text-left l-3 font-semibold">Lịch sử nhận quà lần {{ position + 1 }}</p>
        <button
          class="rounded-md bg-[#FF6100] text-white relative font-medium px-3 py-2"
          @click="onUpdateOrder"
        >
          Cập nhật đơn
        </button>
      </div>
      <div class="grid grid-cols-12 items-center bg-[#AEAEAE] text-white font-bold leading-6 w-[700px]">
        <div>STT</div>
        <div class="col-span-3">Ngày</div>
        <div class="col-span-8 text-left pl-2">Mô tả</div>
      </div>
      <div class="h-[300px] overflow-y-auto">
        <div v-for="(history, index) in data?.shipping_history || []" :key="history.id"
          class="grid grid-cols-12  divide-x divide-[#AEAEAE] border-[#AEAEAE] border-b border-x text-sm">
          <div>{{ index + 1 }}</div>
          <div class="col-span-3">{{ moment(history.created_at, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY') }}</div>
          <div class="col-span-8 text-left pl-2">{{ history.note }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.triangle {
  position: relative;
  width: 0px;
  height: 0px;
  border-style: solid;
  border-width: 0 16px 6px 16px;
  border-color: transparent transparent #ffffff transparent;
  transform: rotate(0deg);
}
.triangle::after, .triangle::before {
  content: '';
  position: absolute;
  width: 50px;
  height: 10px;
  top: 0;
}
.triangle::after {
  left: 0;
}
.triangle::before {
  right: 0;
}
</style>
