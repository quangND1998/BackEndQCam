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
</script>

<template>
  <p class="font-semibold mb-1 text-black">{{ heading }}</p>
  <div class="flex items-center justify-between mb-2">
    <div class="flex items-center gap-3">
      <i class="fa fa-arrow-circle-o-left text-3xl cursor-pointer text-black" aria-hidden="true" @click="onMoveToPreviousWeek"></i>
      <i class="fa fa-arrow-circle-o-right text-3xl cursor-pointer text-black" aria-hidden="true" @click="onMoveToNextWeek"></i>
    </div>
    <div class="flex gap-3">
      <button class="px-3 py-1 rounded-sm bg-[#E9E9E9] text-black">Tuần {{ Math.ceil(fromDate.date() / 7) }} (T{{ fromDate.month() + 1 }})</button>
      <button class="px-4 py-1 rounded-sm bg-[#1D75FA] text-white font-semibold">Xem</button>
    </div>
  </div>
</template>
