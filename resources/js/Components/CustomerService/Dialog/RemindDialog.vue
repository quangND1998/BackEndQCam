<script setup>
  import { reactive, ref, computed, inject, watch } from 'vue';
  import moment from 'moment';

  import useQuery, { CUSTOMER_SERVICE_API_MAKER } from '@/Components/CustomerService/composables/useQuery';
  import DialogLoading from './DialogLoading.vue';

  const props = defineProps({
    index: Number,
    packageId: String,
    productServiceOwnerId: String,
  });

  const { customerId } = inject('ORDER_PACKAGE_PAGE');

  const visible = ref(false);
  const remindForm = reactive({
    remind_at: moment(new Date(new Date().setDate(new Date().getDate() + 1))).format('YYYY-MM-DD HH:mm:ss'),
    note: '',
    product_service_owner_id: props.productServiceOwnerId,
    date: new Date(new Date().setDate(new Date().getDate() + 1))
  });

  const minDate = computed(() => new Date());

  watch(() => remindForm.date, (newVal) => {
    remindForm.remind_at = moment(newVal).format('YYYY-MM-DD HH:mm:ss');
  });

  const resetForm = () => {
    remindForm.remind_at = moment(new Date(new Date().setDate(new Date().getDate() + 1))).format('YYYY-MM-DD HH:mm:ss');
    remindForm.note = '';
    remindForm.date = new Date(new Date().setDate(new Date().getDate() + 1));
    visible.value = false;
  }

  const { isLoading, executeQuery } = useQuery(
    CUSTOMER_SERVICE_API_MAKER.CREATE_REMIND(customerId),
    remindForm,
    resetForm,
    'Tạo lịch hẹn thành công',
  );

  const { displayRemindDialogOrder, addRemindDialog, removeRemindDialog } = inject('REMIND');
  const positionIndex = computed(() => displayRemindDialogOrder.value.findIndex((id) => id === props.packageId))
  const leftPosition = computed(() => `calc(100%/18*4 - 96px + ${positionIndex.value * 384 + positionIndex.value * 16}px)`)
  const onOpenDialog = () => {
    visible.value = true;
    addRemindDialog(props.packageId);
  }
  const onCloseDialog = () => {
    visible.value = false;
    removeRemindDialog(positionIndex.value);
  }
</script>

<template>
  <div
      v-if="visible"
      class="mt-4 w-96 rounded-lg bg-white shadow-lg absolute top-4 z-10"
      :style="{
        left: leftPosition,
      }"
    >
    <div class="flex items-center justify-between rounded-t-lg bg-[#FF0000] pr-3 pl-4 ">
      <p class="font-semibold text-white">Tạo lịch hẹn</p>
      <i class="fa fa-times text-2xl cursor-pointer text-white" aria-hidden="true" @click="onCloseDialog"/>
    </div>
    <div class="px-4 py-3 relative">
      <div class="grid grid-cols-8 bg-[#AEAEAE] text-white font-bold divide-x divide-white text-center text-[13px]">
        <div class="font-semibold">STT</div>
        <div class="col-span-3 font-semibold">Mã HĐ</div>
        <div class="col-span-4 font-semibold">Hẹn gọi lại</div>
      </div>
      <div class="grid grid-cols-8 divide-x divide-[#AEAEAE] border-[#AEAEAE] border-b border-x text-center items-center">
        <div class="!p-1 leading-10 font-semibold">1</div>
        <div class="col-span-3 !p-1 leading-10 font-semibold text-[#FF0000]">{{ packageId }}</div>
        <div class="col-span-4 !p-1">
          <VueDatePicker v-model="remindForm.date" :min-date="minDate" :clearable="false" :enable-time-picker="false" format="dd/MM/yyyy" />
        </div>
      </div>
      <div class="my-3 flex items-start gap-3 flex-col">
        <p class="w-16 text-sm font-semibold required">Lý do</p>
        <textarea :value="remindForm.note" @input="(e) => remindForm.note = e.target.value"
            class="w-full flex-1 resize-none rounded bg-gray-100 focus:border-[#AEAEAE] border-[#AEAEAE] px-2 py-1 text-sm focus:outline-none focus:ring-0" rows="5"></textarea>
      </div>
      <div class="mt-3 flex justify-between items-center">
        <div>
          <input :id="`apply_${packageId}`" type="checkbox" class="focus:outline-none focus:ring-0 rounded" />
          <label :for="`apply_${packageId}`" class="pl-2 mb-0 select-none">Áp dụng chung</label>
        </div>
        <button class="rounded bg-[#FF0000] px-3 py-2 text-sm font-semibold text-white" @click="executeQuery">Tạo lịch</button>
      </div>
      <DialogLoading v-if="isLoading" text="Đang tạo lịch" />
    </div>
  </div>
  <button class="rounded-full bg-[#EE2736] text-white font-semibold px-2 py-2" @click="onOpenDialog">
    Tạo lịch hẹn
  </button>
</template>
