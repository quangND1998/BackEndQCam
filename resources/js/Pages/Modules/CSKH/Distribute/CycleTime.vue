<script setup>
import { computed, inject, ref } from 'vue';
import moment from 'moment';

import { SCHEDULE_GIFT_DELIVERY_STATE, CYCLE_TIME } from '@/Components/CustomerService/stuffs/constantsGift';
import { arrow, useFloating, autoUpdate } from '@floating-ui/vue';

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
  if (props.data && props.data.booking_type) {
    return moment(props.data.date_time, 'YYYY-MM-DD HH:mm:ss');
  }

  const additionalDate = props.multipler * 12 * CYCLE_TIME
  const nextDate = moment(props.startDate, 'YYYY-MM-DD HH:mm:ss').add(((props.position + 1) * CYCLE_TIME + additionalDate), 'days');

  return nextDate.weekday() === 0 ? nextDate.subtract(1, 'day') : nextDate;
});
const cellStyle = computed(() => {
  if (props.data && props.data?.status === 'complete') return 'bg-[#4F8D06] text-white';
  if (!props.data && props.showEmpty) return 'bg-zinc-700 text-zinc-700 select-none';
  if (props.data && props.data?.status === SCHEDULE_GIFT_DELIVERY_STATE.PROCESSING) return 'bg-[#FF0303] text-white';
  if (props.data && props.data?.status === SCHEDULE_GIFT_DELIVERY_STATE.COMPLETE) return 'bg-[#4F8D06] text-white';
  if (props.data && props.data?.status === SCHEDULE_GIFT_DELIVERY_STATE.CANCEL) return 'bg-red-600 text-white';
//   if (0 < date.value.diff(new Date(), 'days') < 10) return 'bg-[#FF0303] text-white';
  if (props.data && date.value.diff(new Date(), 'days') >= 0) return 'bg-[#FF6100] text-white';
  if (date.value.diff(new Date(), 'days') < 0 && !props.allowEmpty) return 'bg-red-600 text-white';
  if (!props.data) return 'bg-zinc-700 text-white';
});
const displayText = computed(() => {
  if (!props.data && props.showEmpty) return 'Chưa thăm';
  return date.value.format('DD/MM/YYYY');
})

const isFirstOrderDelivery = computed(() => {
  return props.allowPopover && props.position === 0;
})
const distantDate = (dateInput) =>{
    console.log(date.value);
    var date1Input =  moment(dateInput, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD') ;
    var date2Input = date.value.format('YYYY-MM-DD');

    var date1 = new Date(date1Input);
    var date2 = new Date(date2Input);
    console.log(date1);
    console.log(date2);

    var khoangCachNgay = Math.floor((date2 - date1) / (1000 * 60 * 60 * 24));
    console.log(khoangCachNgay)

    var offsetText = '';
    if(date2 > date1){
        offsetText = '+';
    }else if(date2 < date1){
        offsetText = '-';
    }
    return offsetText + khoangCachNgay;
}
</script>

<template>
  <div >
    <p ref="reference" class="text-xs leading-5 cursor-pointer m-1 px-1 py-1 " :class="cellStyle">
        <div> {{ isFirstOrderDelivery
            ? (data && data?.delivery_appointment) ? moment(data.delivery_appointment, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY') : 'dd/mm/yy'
            : displayText  }}
        </div>
        <div>{{ isFirstOrderDelivery
            ?  0 : (data && data?.delivery_appointment) ? distantDate(data.delivery_appointment) : 0  }} </div>
    </p>
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
