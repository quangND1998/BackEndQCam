<script setup>
import { inject, computed, ref, watch } from 'vue';

import useQuery, { CUSTOMER_SERVICE_API_MAKER } from '@/Components/CustomerService/composables/useQuery';
import { generatePageNumbers } from '@/Components/CustomerService/stuffs/helpers';
import RemindRow from '@/Components/CustomerService/Table/RemindRow.vue';
import SpinnerIcon from '@/Components/CustomerService/SpinnerIcon.vue';

const { remindData } = inject('REMIND');
const reminds = ref(remindData.data);

const { isLoading, executeQuery } = useQuery(
  CUSTOMER_SERVICE_API_MAKER.GET_REMIND(),
  undefined,
  (response) => {
    reminds.value = response.reminds.data
    lastPage.value = response.reminds.last_page;
  }
);
const itemPerPage = ref(5);
watch(itemPerPage, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    currentPage.value = 1;
    executeQuery({ pageNo: 1, itemPerPage: newValue });
  }
});
const currentPage = ref(remindData.current_page || 0);
const lastPage = ref(remindData.last_page || 0);
const pageNumbers = computed(() => generatePageNumbers(currentPage.value, lastPage.value));
const onChangePage = (pageNo) => {
  if (pageNo === currentPage.value) return;
  currentPage.value = pageNo;
  executeQuery({ pageNo, itemPerPage: itemPerPage.value });
}

const changePage = (diffPageNo) => {
  const newPageNo = currentPage.value + diffPageNo;
  if (newPageNo < 1 || newPageNo > lastPage.value) return;
  currentPage.value = newPageNo;
  executeQuery({ pageNo: newPageNo, itemPerPage: itemPerPage.value });
}
defineExpose({ changePage });
</script>

<template>
  <div
    class="grid grid-cols-[repeat(28,_minmax(0,_1fr))] divide-x text-sm bg-gray-400 text-white divide-white items-center  text-center font-semibold leading-10">
    <div>STT</div>
    <div class="col-span-3">Mã HĐ</div>
    <div class="col-span-2">Loại HĐ</div>
    <div class="col-span-4">Tên KH</div>
    <div class="col-span-2">Thứ tuần</div>
    <div class="text-center col-span-12"> Lý do</div>
    <div class="col-span-2">Lịch hẹn gọi lại</div>
    <div class="col-span-2">Chăm sóc</div>
  </div>
  <div v-if="isLoading" class="w-full h-[200px] flex items-center justify-center">
    <SpinnerIcon class="!m-0 !w-14 !h-14 text-orange-600" />
  </div>
  <template v-else>
    <RemindRow v-for="(remind, index) in reminds" :key="remind.id" :index="index" :remind="remind" />
    <template v-if="reminds.length < 5">
      <div v-for="n in (5 - reminds.length)" :key="n"
        class="grid grid-cols-[repeat(28,_minmax(0,_1fr))] divide-x text-sm bg-white border-x border-b !border-gray-400 divide-gray-400 items-center  text-center font-semibold leading-10">
        <div>&#8203;</div>
        <div class="col-span-3">&#8203;</div>
        <div class="col-span-2">&#8203;</div>
        <div class="col-span-4">&#8203;</div>
        <div class="col-span-2">&#8203;</div>
        <div class="text-left pl-2 col-span-12">&#8203;</div>
        <div class="col-span-2">&#8203;</div>
        <div class="col-span-2 grid items-center">&#8203;</div>
      </div>
    </template>
    <div class="flex items-center justify-between mt-3">
      <div class="flex items-center gap-4">
        <p>Hiển thị</p>
        <select v-model="itemPerPage"
          class="border !border-gray-400 rounded-sm px-2 py-1 !w-20 focus:outline-none focus:!border-gray-400 focus:ring-0">
          <option :value="5">5</option>
          <option :value="10">10</option>
          <option :value="20">20</option>
        </select>
      </div>
      <div class="flex items-center gap-2">
        <p>Trang:</p>
        <div class="flex items-center gap-3">
          <ul class="flex items-center gap-1">
            <li v-for="page in pageNumbers" :key="page" class="cursor-pointer" @click="onChangePage(page)">
              <span aria-current="page" class="page-number" :class="{ 'active': page === currentPage }">
                {{ page }}
              </span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </template>
</template>

<style scoped>
.page-number {
  @apply !px-3 !py-2 rounded-lg duration-150 hover:text-white hover:bg-orange-600;
}

.active {
  @apply bg-orange-600 text-white font-medium;
}
</style>
