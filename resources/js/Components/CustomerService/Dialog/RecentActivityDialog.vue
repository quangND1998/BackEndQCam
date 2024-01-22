<script setup>
import { ref } from 'vue';

const visible = ref(false);
</script>

<template>
  <div class="relative">
    <div v-if="visible" class="mt-4 w-96 rounded-lg bg-white shadow-lg absolute -top-[490px]">
      <div class="flex items-center justify-between rounded-t-lg bg-orange-500 pr-3 pl-4 py-2">
        <p class="font-semibold text-white">Hoạt động mới</p>
        <i class="fa fa-times text-2xl cursor-pointer text-white" aria-hidden="true" @click="visible = false"/>
      </div>
      <div class="px-4 my-3 h-[361px] overflow-y-auto">
        <div class="border border-gray-400 !border-b-0 mb-8">
          <div class="grid grid-cols-6 text-center text-sm text-white font-medium bg-gray-400 leading-6 divide-x divide-white">
            <div>STT</div>
            <div class="col-span-2">Đơn hàng</div>
            <div>Loại</div>
            <div class="col-span-2">Trạng thái</div>
          </div>
          <div class="grid grid-cols-6 divide-x divide-gray-400 text-sm text-center leading-5 border-b border-gray-400">
            <div>1</div>
            <div class="col-span-2"></div>
            <div></div>
            <div class="col-span-2"></div>
          </div>
          <div class="grid grid-cols-6 divide-x divide-gray-400 text-sm text-center leading-5 border-b border-gray-400">
            <div>2</div>
            <div class="col-span-2"></div>
            <div></div>
            <div class="col-span-2"></div>
          </div>
          <div class=" text-sm text-center border-b border-gray-400 h-5"></div>
        </div>
        <div class="border border-gray-400 !border-b-0 mb-8">
          <div class="grid grid-cols-6 text-center text-sm text-white font-medium bg-gray-400 leading-6 divide-x divide-white">
            <div>STT</div>
            <div class="col-span-2">Booking</div>
            <div class="col-span-3">Trạng thái</div>
          </div>
          <div class="grid grid-cols-6 divide-x divide-gray-400 text-sm text-center leading-5 border-b border-gray-400">
            <div>1</div>
            <div class="col-span-2"></div>
            <div class="col-span-3"></div>
          </div>
          <div class="grid grid-cols-6 divide-x divide-gray-400 text-sm text-center leading-5 border-b border-gray-400">
            <div>2</div>
            <div class="col-span-2"></div>
            <div class="col-span-3"></div>
          </div>
          <div class=" text-sm text-center border-b border-gray-400 h-5"></div>
        </div>
        <div class="border border-gray-400 !border-b-0">
          <div class="grid grid-cols-6 text-center text-sm text-white font-medium bg-gray-400 leading-6 divide-x divide-white">
            <div>STT</div>
            <div class="col-span-2">Khiếu nại</div>
            <div class="col-span-3">Trạng thái</div>
          </div>
          <div class="grid grid-cols-6 divide-x divide-gray-400 text-sm text-center leading-5 border-b border-gray-400">
            <div>1</div>
            <div class="col-span-2"></div>
            <div class="col-span-3"></div>
          </div>
          <div class="grid grid-cols-6 divide-x divide-gray-400 text-sm text-center leading-5 border-b border-gray-400">
            <div>2</div>
            <div class="col-span-2"></div>
            <div class="col-span-3"></div>
          </div>
          <div class=" text-sm text-center border-b border-gray-400 h-5"></div>
        </div>
      </div>
    </div>
    <button class="w-96 bg-orange-500 rounded-full font-semibold text-white py-2" @click="visible = !visible">
      Hoạt dộng mới nhất
    </button>
  </div>
</template>
