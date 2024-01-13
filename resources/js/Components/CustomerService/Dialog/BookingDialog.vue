<script setup>
  import { ref } from 'vue';
  import InputNumber from 'primevue/inputnumber';

  const props = defineProps({
    packageId: String,
  });

  const visible = ref(false);
</script>

<template>
  <div class="relative">
    <div v-if="visible" class="mt-4 w-[700px] rounded-lg bg-white pb-2 shadow-lg absolute -top-[292px] left-[260px] z-10">
      <div class="flex items-center justify-between rounded-t-lg bg-yellow-500 pr-3 pl-4 py-2">
        <p class="font-semibold">Booking theo HD {{ packageId }}</p>
        <div class="w-48">
          <VueDatePicker time-picker-inline />
        </div>
      </div>
      <div class="px-4 py-2">
        <div class="flex items-center mb-3">
          <p class="w-28">Người lớn</p>
          <input type="number" min="1" value="1" class="w-28 h-8 rounded-sm border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0" />
        </div>
        <div class="flex items-center mb-3">
          <p class="w-28">Trẻ em</p>
          <input type="number" min="1" value="1" class="w-28 h-8 rounded-sm border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0" />
        </div>
        <div class="flex items-center mb-3">
          <p class="w-28">Dịch vụ</p>
          <div class="flex gap-10">
            <div class="flex items-center">
              <input type="checkbox" class="focus:outline-none focus:ring-0" />
              <label class="m-0 ml-2">Thuê xe</label>
            </div>
            <div class="flex items-center">
              <input type="checkbox" class="focus:outline-none focus:ring-0" />
              <label class="m-0 ml-2">Ăn uống</label>
            </div>
            <div class="flex items-center">
              <input type="checkbox" class="focus:outline-none focus:ring-0" />
              <label class="m-0 ml-2">DV1</label>
            </div>
            <div class="flex items-center">
              <input type="checkbox" class="focus:outline-none focus:ring-0" />
              <label class="m-0 ml-2">DV2</label>
            </div>
          </div>
        </div>
        <div class="flex mb-4">
          <p class="w-28">Ghi chú</p>
          <textarea class="flex-1 resize-none rounded bg-gray-100 focus:border-gray-400 border-gray-400 px-2 py-1 text-sm focus:outline-none focus:ring-0" rows="5"></textarea>
        </div>
        <div class="flex justify-end">
          <button class="rounded-md bg-sky-600 text-white font-medium px-3 py-2 mb-2" @click="visible = !visible">Book</button>
        </div>
      </div>
    </div>
    <button class="rounded-full bg-sky-600 text-white font-medium px-3 py-2 mb-2" @click="visible = !visible">Booking</button>
  </div>
</template>
