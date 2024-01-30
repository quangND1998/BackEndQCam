<script setup>
import { computed } from 'vue';
import moment from 'moment';

const props = defineProps({
  index: Number,
  remind: Object,
})

const date = computed(() => moment(props.remind.remind_at, 'YYYY-MM-DD'));
const weekday = computed(() => {
    const wd = date.value.weekday();
    return wd === 7 ? 'Chủ nhật' : `Thứ ${wd + 1}`;
});
const isToday = computed(() => date.value.isSame(moment(), 'day'));
const customerId = computed(() => props.remind.product_service_owner.customer.id);
</script>

<template>
  <div
    class="grid grid-cols-[repeat(28,_minmax(0,_1fr))] divide-x text-sm bg-white border-x border-b !border-[#AEAEAE] divide-[#AEAEAE] items-stretch text-center font-semibold leading-10 text-black">
    <div class="pt-1 font-semibold">{{ index + 1 }}</div>
    <div class="col-span-3 pt-1 font-semibold text-[#FF0000]">{{ remind.product_service_owner.order_package.idPackage }}</div>
    <div class="col-span-2 pt-1">{{ remind.product_service_owner.order_package.product_service.life_time }} năm</div>
    <div class="col-span-4 pt-1">{{ remind.product_service_owner.customer.name }}</div>
    <div class="col-span-2 pt-1">
        {{ weekday }}/ Tuần {{ date.week() }}
    </div>
    <div class="text-left pl-2 col-span-12">{{ remind.note }}</div>
    <div class="col-span-2 pt-1 flex items-center justify-center gap-2">
      <div v-if="isToday" class="w-4 h-4 rounded-sm bg-[#FF6100]" />
      {{ date.format('DD/MM/YYYY') }}
    </div>
    <div class="col-span-2 grid items-center pt-1 justify-center">
      <a class="w-fit h-fit leading-none" :href="`/customer-service/customer/${customerId}/order-packages`">
        <i class="fa fa-phone text-2xl text-[#4F8D06] cursor-pointer" aria-hidden="true" />
      </a>
    </div>
  </div>
</template>
