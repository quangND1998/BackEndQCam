<script setup>
import { Head } from '@inertiajs/vue3';
import { provide, ref } from 'vue';
import moment from 'moment';

import useQuery, { CUSTOMER_SERVICE_API_MAKER } from '@/Components/CustomerService/composables/useQuery';
import PlanTableHeader from '@/Components/CustomerService/Table/PlanTableHeader.vue';
import PlanTable from '@/Components/CustomerService/Table/PlanTable.vue';
import PlanTableDescription from '@/Components/CustomerService/Table/PlanTableDescription.vue';
import RemindTable from '@/Components/CustomerService/Table/RemindTable.vue';

const props = defineProps({
  plans: Array,
  orderPackagePlans: Array,
});

const fromDate = ref(moment().startOf('week'));
const toDate = ref(moment().endOf('week'));
const { isLoading, executeQuery } = useQuery(
  CUSTOMER_SERVICE_API_MAKER.GET_PLAN(),
  undefined,
  (response) => {
    planList.value = response.orderPackagePlans
  }
);
const onMoveToNextWeek = () => {
  if (isLoading.value) return;
  fromDate.value = moment(fromDate.value).add(1, 'week');
  toDate.value = moment(toDate.value).add(1, 'week');
  executeQuery({ fromDate: fromDate.value, toDate: toDate.value });
}
const onMoveToPreviousWeek = () => {
  if (isLoading.value) return;
  fromDate.value = moment(fromDate.value).subtract(1, 'week');
  toDate.value = moment(toDate.value).subtract(1, 'week');
  executeQuery({ fromDate: fromDate.value.format('YYYY-MM-DD'), toDate: toDate.value.format('YYYY-MM-DD') });
}
provide('PLAN', {
  isLoading,
  orderPackagePlans: props.orderPackagePlans,
  fromDate,
  toDate,
  onMoveToNextWeek,
  onMoveToPreviousWeek
});


provide('REMIND', {
  remindData: props.remindData,
});
const tableRef = ref();
const changePage = (diffPageNo) => {
  tableRef.value.changePage(diffPageNo);
}
</script>

<template>
  <Head title="Customer Order Packages" />
  <div class="p-10 bg-gray-200 min-h-screen">
    <div class="py-6 px-4 rounded-xl bg-white shadow-lg">
      <div>
        <PlanTableHeader />
        <PlanTable />
        <PlanTableDescription />
      </div>
      <div class="mt-10">
        <p class="font-semibold mb-1">Danh sách pending của bạn</p>
        <div class="flex items-center justify-between mb-2">
          <div class="flex items-center gap-3">
            <i class="fa fa-arrow-circle-o-left text-3xl cursor-pointer" aria-hidden="true" @click="changePage(-1)"></i>
            <i class="fa fa-arrow-circle-o-right text-3xl cursor-pointer" aria-hidden="true" @click="changePage(1)"></i>
          </div>
          <div class="flex items-center gap-20">
            <div class="flex items-center gap-2">
              <div class="w-6 h-6 bg-orange-600 rounded-sm" />
              <p>Đã đến lịch hẹn</p>
            </div>
            <button class="px-4 py-1 rounded-sm bg-zinc-900 text-white font-semibold">Xuất</button>
          </div>
        </div>
        <RemindTable ref="tableRef" />
      </div>
    </div>
  </div>
</template>
