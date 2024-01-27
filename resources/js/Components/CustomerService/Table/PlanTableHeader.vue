<script setup>
  import { computed, ref, inject } from 'vue';

  const { onMoveToNextWeek, onMoveToPreviousWeek, fromDate, toDate } = inject('PLAN');

  const heading = computed(() => {
    const headingSegments = [
      `KẾ HOẠCH TUẦN ${fromDate.value.week()} NĂM ${fromDate.value.year()}`,
      `(Tuần thứ ${Math.ceil(fromDate.value.date() / 7)}/tháng ${fromDate.value.month() + 1})`,
      `TỪ NGÀY ${fromDate.value.format('DD/MM/YYYY')} ĐẾN NGÀY ${toDate.value.format('DD/MM/YYYY')})`
    ];

    return headingSegments.join('  ');
  })
  const numberOfFreeContract = ref(1);
  const formatNumerOfFreeContract = computed(() => numberOfFreeContract.value < 10 ? `0${numberOfFreeContract.value}` : numberOfFreeContract.value);
</script>

<template>
  <p class="font-semibold mb-1">{{ heading }}</p>
  <div class="flex items-center justify-between mb-2">
    <div class="flex items-center gap-3">
      <i class="fa fa-arrow-circle-o-left text-3xl cursor-pointer" aria-hidden="true" @click="onMoveToPreviousWeek"></i>
      <i class="fa fa-arrow-circle-o-right text-3xl cursor-pointer" aria-hidden="true" @click="onMoveToNextWeek"></i>
    </div>
    <ul class="list-disc text-red-600 font-medium">
      <li>Có <span class="font-bold">{{ formatNumerOfFreeContract }}</span> hợp đồng mới thêm hiện chưa được phân công
      </li>
    </ul>
    <div class="flex gap-3">
      <button class="px-3 py-1 rounded-sm bg-gray-300">Tuần {{ fromDate.value.week() }} (T{{ fromDate.value.month() + 1 }})</button>
      <button class="px-4 py-1 rounded-sm bg-sky-600 text-white font-semibold">Xem</button>
    </div>
  </div>
</template>
