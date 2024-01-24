<script setup>
import { computed } from 'vue';
import moment from 'moment';

const props = defineProps({
  index: Number,
  remind: Object,
})

const date = computed(() => moment(remind.remind_at, 'YYYY-MM-DD'));
const weekday = computed(() => {
    const wd = data.value.weekday();
    return wd === 7 ? 'Chủ nhật' : `Thứ ${wd + 1}`;
});
</script>

<template>
  <div
    class="grid grid-cols-[repeat(28,_minmax(0,_1fr))] divide-x text-sm bg-white border-x border-b !border-gray-400 divide-gray-400 items-stretch text-center font-semibold leading-10">
    <div class="pt-1">{{ index + 1 }}</div>
    <div class="col-span-3 pt-1">{{ remind.product_service_owner.order_package.idPackage }}</div>
    <div class="col-span-2 pt-1">{{ remind.product_service_owner.order_package.product_service.life_time }} năm</div>
    <div class="col-span-4 pt-1">{{ remind.product_service_owner.customer.name }}</div>
    <div class="col-span-2 pt-1">
        {{ weekday }}/ Tuần {{ date.week() }}
    </div>
    <div class="text-left pl-2 col-span-12">{{ remind.note }}</div>
    <div class="col-span-2 pt-1">{{ date.format('DD/MM/YYYY') }}</div>
    <div class="col-span-2 grid items-center pt-1"><i class="fa fa-phone text-2xl text-emerald-600" aria-hidden="true"></i>
    </div>
  </div>
</template>
