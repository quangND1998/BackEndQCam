<script setup>
  import { reactive, ref, computed } from 'vue';
  import DialogLoading from './DialogLoading.vue';

  const props = defineProps({
    packageId: String,
  })

  const isLoading = ref(false);
  const visible = ref(false);
  const remindForm = reactive({
    date: new Date(new Date().setDate(new Date().getDate() + 1)),
    reason: '',
  })

  const minDate = computed(() => new Date());
</script>

<template>
  <div class="relative">
    <div v-if="visible" class="mt-4 w-96 rounded-lg bg-white shadow-lg absolute -top-[196px] left-[127px] z-10">
      <div class="flex items-center justify-between rounded-t-lg bg-red-600 pr-3 pl-4 py-2">
        <p class="font-semibold text-white">Tạo lịch hẹn</p>
        <i class="fa fa-times text-2xl cursor-pointer text-white" aria-hidden="true" @click="visible = false"/>
      </div>
      <div class="px-4 py-3 relative">
        <div class="grid grid-cols-8  bg-gray-400 text-white font-bold devide-x text-center">
          <div>STT</div>
          <div class="col-span-3">Mã HĐ</div>
          <div class="col-span-4">Hẹn gọi lại</div>
        </div>
        <div class="grid grid-cols-8 divide-x divide-gray-400 border-gray-400 border-b border-x text-sm text-center items-center">
          <div>1</div>
          <div class="col-span-3">{{ packageId }}</div>
          <div class="col-span-4 p-1">
            <VueDatePicker v-model="remindForm.date" :min-date="minDate" :clearable="false" :enable-time-picker="false" format="dd/MM/yyyy" />
          </div>
        </div>
        <div class="my-3 flex items-start gap-3 flex-col">
          <p class="w-16 text-sm font-semibold">Lý do</p>
          <textarea v-model="remindForm.reason" class="w-full flex-1 resize-none rounded bg-gray-100 focus:border-gray-400 border-gray-400 px-2 py-1 text-sm focus:outline-none focus:ring-0" rows="5"></textarea>
        </div>
        <div class="mt-3 flex justify-end">
          <button class="rounded bg-red-600 px-3 py-2 text-sm font-semibold text-white">Tạo lịch</button>
        </div>
        <DialogLoading v-if="isLoading" text="Đang tạo lịch" />
      </div>
    </div>
    <button class="rounded-full bg-red-600 text-white font-medium px-2 py-2" @click="visible = !visible">
      Tạo lịch hẹn
    </button>
  </div>
</template>
