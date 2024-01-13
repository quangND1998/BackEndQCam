<script setup>
import { ref } from 'vue';

const visible = ref(false);
</script>

<template>
  <div class="relative">
    <div v-if="visible" class="mt-4 w-96 rounded-lg bg-white pb-2 shadow-lg absolute -top-[365px]">
      <div class="flex items-center justify-between rounded-t-lg bg-yellow-500 pr-3 pl-4 py-2">
        <p class="font-semibold">Yêu cầu khiếu nại</p>
        <i class="fa fa-times text-2xl cursor-pointer text-white" aria-hidden="true" @click="visible = false"/>
      </div>
      <div class="mx-4 my-3">
        <div class="mb-3 flex items-start gap-3">
          <p class="w-16 text-sm font-semibold">Nội dung</p>
          <textarea class="flex-1 resize-none rounded bg-gray-100 focus:border-gray-400 border-gray-400 px-2 py-1 text-sm focus:outline-none focus:ring-0" rows="5"></textarea>
        </div>
        <div class="flex items-center gap-3">
          <p class="w-16 text-sm font-semibold">Mức độ</p>
          <div class="grid flex-1 grid-cols-2 gap-3">
            <select class="rounded p-2 focus:outline-none focus:ring-0 focus:border-gray-400 border-gray-400 text-sm">
              <option>Bình thường</option>
            </select>
            <select class="rounded p-2 focus:outline-none focus:ring-0 focus:border-gray-400 border-gray-400 text-sm">
              <option>TGĐ</option>
            </select>
          </div>
        </div>
        <div class="mt-3 flex justify-end">
          <button class="rounded bg-red-500 px-3 py-2 text-sm font-semibold text-white">Thêm</button>
        </div>
      </div>
    </div>
    <button class="w-96 bg-orange-500 rounded-full font-semibold text-white py-2" @click="visible = !visible">
      Tạo yêu cầu khiếu nại
    </button>
  </div>
</template>
