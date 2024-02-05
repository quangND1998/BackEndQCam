<script setup>
import { Head } from '@inertiajs/vue3';
import { provide, ref } from 'vue';
import moment from 'moment';

import useQuery, { CUSTOMER_SERVICE_API_MAKER } from '@/Components/CustomerService/composables/useQuery';
import PlanTableHeader from '@/Components/CustomerService/Table/PlanTableHeader.vue';
import PlanTable from '@/Components/CustomerService/Table/PlanTable.vue';
import PlanTableDescription from '@/Components/CustomerService/Table/PlanTableDescription.vue';
import RemindTable from '@/Components/CustomerService/Table/RemindTable.vue';
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import PhoneCall from '@/Components/CustomerService/PhoneCall.vue';
const props = defineProps({
  orderPackagePlans: Array,
  remindData: Array,
});


const planList = ref(props.orderPackagePlans);
const fromDate = ref(moment().startOf('isoWeek'));
const toDate = ref(moment().endOf('isoWeek'));
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
  executeQuery({ fromDate: fromDate.value.format('YYYY-MM-DD'), toDate: toDate.value.format('YYYY-MM-DD') });
}
const onMoveToPreviousWeek = () => {
  if (isLoading.value) return;
  fromDate.value = moment(fromDate.value).subtract(1, 'week');
  toDate.value = moment(toDate.value).subtract(1, 'week');
  executeQuery({ fromDate: fromDate.value.format('YYYY-MM-DD'), toDate: toDate.value.format('YYYY-MM-DD') });
}
provide('PLAN', {
  isLoading,
  orderPackagePlans: planList,
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
 <LayoutAuthenticated>
  <Head title="Customer Order Packages" />
  <div class="p-10 bg-gray-200 min-h-screen">
    <div class="py-6 px-4 rounded-xl bg-white shadow-lg">
      <div>
        <PlanTableHeader />
        <PlanTable />
        <PlanTableDescription />
      </div>
      <div class="mt-10">
        <p class="font-semibold mb-1 text-black">Danh sách pending của bạn</p>
        <div class="flex items-center justify-between mb-2">
          <div class="flex items-center gap-3">
            <i class="fa fa-arrow-circle-o-left text-3xl cursor-pointer text-black" aria-hidden="true" @click="changePage(-1)"></i>
            <i class="fa fa-arrow-circle-o-right text-3xl cursor-pointer text-black" aria-hidden="true" @click="changePage(1)"></i>
          </div>
          <div class="flex items-center gap-20">
            <div class="flex items-center gap-2">
              <div class="w-6 h-6 bg-[#FF6100] rounded-sm" />
              <p>Đã đến lịch hẹn</p>
            </div>
            <button class="px-4 py-1 rounded-sm bg-black text-white font-semibold">Xuất</button>
          </div>
        </div>
        <RemindTable ref="tableRef" />
      </div>
    </div>
  </div>
  <PhoneCall :isCallable="false" />
</LayoutAuthenticated>
</template>
