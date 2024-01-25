<script setup>
import { Head } from '@inertiajs/vue3';
import moment from 'moment';
import { computed, provide, ref } from 'vue';

import Table from '@/Components/CustomerService/RemindTable/Table.vue';

const props = defineProps({
  remindData: Object,
});

provide('REMIND', {
  remindData: props.remindData,
});

const date = computed(() => moment());
const heading = computed(() => {
  const headingSegments = [
    `KẾ HOẠCH TUẦN ${date.value.week()} NĂM ${date.value.year()}`,
    `(Tuần thứ ${Math.ceil(date.value.date() / 7)}/tháng ${date.value.month() + 1})`,
    `TỪ NGÀY ${moment().weekday(0).format('DD/MM/YYYY')} ĐẾN NGÀY ${moment().weekday(7).format('DD/MM/YYYY')})`
  ];

  return headingSegments.join('  ');
})
const numberOfFreeContract = ref(1);
const formatNumerOfFreeContract = computed(() => numberOfFreeContract.value < 10 ? `0${numberOfFreeContract.value}` : numberOfFreeContract.value);

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
        <p class="font-semibold mb-1">{{ heading }}</p>
        <div class="flex items-center justify-between mb-2">
          <div class="flex items-center gap-3">
            <i class="fa fa-arrow-circle-o-left text-3xl cursor-pointer" aria-hidden="true"></i>
            <i class="fa fa-arrow-circle-o-right text-3xl cursor-pointer" aria-hidden="true"></i>
          </div>
          <ul class="list-disc text-red-600 font-medium">
            <li>Có <span class="font-bold">{{ formatNumerOfFreeContract }}</span> hợp đồng mới thêm hiện chưa được phân công
            </li>
          </ul>
          <div class="flex gap-3">
            <button class="px-3 py-1 rounded-sm bg-gray-300">Tuần {{ date.week() }} (T{{ date.month() + 1 }})</button>
            <button class="px-4 py-1 rounded-sm bg-sky-600 text-white font-semibold">Xem</button>
          </div>
        </div>
        <div
          class="grid grid-cols-[repeat(28,_minmax(0,_1fr))] divide-x text-sm bg-gray-400 text-white divide-white items-stretch font-semibold">
          <div class="grid items-center justify-center">STT</div>
          <div class="col-span-3 grid items-center justify-center">Mã HĐ</div>
          <div class="col-span-2 grid items-center justify-center">Loại HĐ</div>
          <div class="col-span-4 grid items-center justify-center">Tên KH</div>
          <div class="col-span-2 grid items-center justify-center">Ngày kích hoạt</div>
          <div v-for="n in 7" class="col-span-2 text-center py-1">
            <p>{{ n === 7 ? 'Chủ nhật' : `Thứ ${n + 1}` }}</p>
            <p class="font-normal">{{ moment().weekday(n).format('DD/MM') }}</p>
          </div>
          <div class="col-span-2 grid items-center justify-center">Chăm sóc</div>
        </div>
        <div
          class="grid grid-cols-[repeat(28,_minmax(0,_1fr))] divide-x text-sm bg-white border-x border-b !border-gray-400 divide-gray-400 text-center items-center">
          <div class="leading-10">1</div>
          <div class="col-span-3 leading-10">22022024</div>
          <div class="col-span-2 leading-10">1 năm</div>
          <div class="col-span-4 leading-10">Đào Quang Huy</div>
          <div class="col-span-2 leading-10">19/01/2024</div>
          <div v-for="n in 7" :key="n" class="col-span-2 text-center leading-10">&#8203;</div>
          <div class="col-span-2 grid items-center justify-center h-10">
            <a class="flex items-center cursor-pointer">
              <i class="fa fa-phone text-2xl text-emerald-600" aria-hidden="true"></i>
            </a>
          </div>
        </div>
        <div>
          <div v-for="n in 4" :key="n"
            class="grid grid-cols-[repeat(28,_minmax(0,_1fr))] divide-x text-sm bg-white border-x last:border-b !border-gray-400 divide-gray-400 items-center text-center leading-10">
            <div>&#8203;</div>
            <div class="col-span-3">&#8203;</div>
            <div class="col-span-2">&#8203;</div>
            <div class="col-span-4">&#8203;</div>
            <div class="col-span-2">&#8203;</div>
            <div v-for="n in 7" :key="n" class="col-span-2 text-center">&#8203;</div>
            <div class="col-span-2">&#8203;</div>
          </div>
        </div>
        <div class="flex items-center gap-8 mt-2">
          <div class="flex items-center gap-2 text-sm">
            <div class="w-4 h-4 rounded-sm bg-gray-400" />
            <p>Chưa gọi điện</p>
          </div>
          <div class="flex items-center gap-2 text-sm">
            <div class="w-4 h-4 rounded-sm bg-red-600" />
            <p>Không nghe máy</p>
          </div>
          <div class="flex items-center gap-2 text-sm">
            <div class="w-4 h-4 rounded-sm bg-sky-600" />
            <p>Bắt máy, không trả lời</p>
          </div>
          <div class="flex items-center gap-2 text-sm">
            <div class="w-4 h-4 rounded-sm bg-emerald-600" />
            <p>Đã gọi điện</p>
          </div>
        </div>
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
        <Table ref="tableRef" />
      </div>
    </div>
  </div>
</template>
