<script setup>
  import { inject, computed } from 'vue';

  import Row from '@/Components/CustomerService/RemindTable/Row.vue';

  const { remindData } = inject('REMIND');

  const reminds = computed(() => remindData.data);
  const currentPage = computed(() => remindData.current_page);
  const lastPage = computed(() => remindData.last_page);
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
  <Row v-for="(remind, index) in reminds" :key="remind.id" :index="index" :remind="remind" />
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
      <select
        class="border !border-gray-400 rounded-sm px-2 py-1 !w-20 focus:outline-none focus:!border-gray-400 focus:ring-0">
        <option>5</option>
        <option>10</option>
        <option>20</option>
      </select>
    </div>
    <div class="flex items-center gap-2">
      <p>Trang:</p>
      <div class="flex items-center gap-3">
        <ul class="flex items-center gap-1">
          <li>
              <span aria-current="page" class="page-number active">
                1
              </span>
          </li>
          <li>
              <span class="page-number">
                2
              </span>
          </li>
      </ul>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page-number {
  @apply !px-3 !py-2 rounded-lg duration-150 hover:text-white hover:bg-orange-600;
}
.active {
  @apply bg-orange-600 text-white font-medium;
}
</style>
