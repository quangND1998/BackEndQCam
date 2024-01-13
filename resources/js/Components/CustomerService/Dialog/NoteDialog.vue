<script setup>
import { ref } from 'vue';

const visible = ref(false);
</script>

<template>
  <div class="relative">
    <div v-if="visible" class="w-96 rounded-lg bg-yellow-200 pb-1 shadow-lg absolute -top-80">
      <div class="flex items-center justify-between rounded-t-lg bg-yellow-500 pr-3 pl-4 py-2">
        <p class="font-semibold">Thông tin khách hàng</p>
        <i class="fa fa-times text-2xl cursor-pointer text-white" aria-hidden="true" @click="visible = false"/>
      </div>
      <textarea
        class="w-96 resize-none bg-yellow-200 border-none text-sm focus:!outline-none focus:!border-none focus:!shadow-none focus:ring-0" rows="10"></textarea>
    </div>
    <button class="w-96 bg-orange-500 rounded-full font-semibold text-white py-2" @click="visible = !visible">
      Ghi chú về khách hàng
    </button>
  </div>
</template>
