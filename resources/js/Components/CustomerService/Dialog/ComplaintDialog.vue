<script setup>
import { inject, reactive, ref, watch } from 'vue';

import useQuery, { CUSTOMER_SERVICE_API_MAKER } from '@/Components/CustomerService/composables/useQuery';
import DialogLoading from './DialogLoading.vue';
import SpinnerIcon from '@/Components/CustomerService/SpinnerIcon.vue';

const { roles } = inject('COMPLAINT');
const { customerId } = inject('ORDER_PACKAGE_PAGE');
const { idPackageList } = inject('ORDER');

const visible = ref(false);
const complaintForm = reactive({
  description: '',
  severity: 'normal',
  role_id: undefined,
  package_id: undefined
});

const { isLoading, executeQuery } = useQuery(
  CUSTOMER_SERVICE_API_MAKER.CREATE_COMPLAINT(customerId),
  complaintForm,
  () => {
    visible.value = false;
    complaintForm.description = '';
    complaintForm.severity = 'normal';
    complaintForm.role_id = undefined;
    complaintForm.package_id = undefined;
  },
  'Tạo khiếu nại thành công'
);

const onCreateComplaint = () => {
  if (!complaintForm.description || !complaintForm.role_id || !complaintForm.package_id) return;
  executeQuery();
}

watch(visible, (newValue) => {
  if (!newValue) {
    complaintForm.description = '';
    complaintForm.severity = 'normal';
    complaintForm.role_id = undefined;
    complaintForm.package_id = undefined;
  }
})

</script>

<template>
  <div class="relative">
    <div v-if="visible" class="w-96 rounded-lg bg-white shadow-lg absolute -top-[386px]">
      <div class="flex items-center justify-between rounded-t-lg bg-[#B7AD75] pr-3 pl-4 py-2">
        <p class="font-semibold text-black">Yêu cầu khiếu nại</p>
        <i class="fa fa-times text-2xl cursor-pointer text-white" aria-hidden="true" @click="visible = false"/>
      </div>
      <div class="px-4 py-3 relative">
        <div class="mb-3 flex items-center gap-2">
          <p class="w-20 text-sm font-semibold required">Mã HĐ</p>
          <select v-model="complaintForm.package_id" class="rounded flex-1 p-2 focus:outline-none focus:ring-0 focus:border-[#AEAEAE] border-[#AEAEAE] text-sm">
            <option disabled selected :value="undefined"> -- Chọn hợp đồng -- </option>
            <option v-for="idPackage in idPackageList" :value="idPackage" :key="idPackage">
              {{ idPackage }}
            </option>
          </select>
        </div>
        <div class="mb-3 flex items-start gap-2">
          <p class="w-20 text-sm font-semibold required">Nội dung</p>
          <textarea v-model="complaintForm.description" class="flex-1 resize-none rounded bg-white focus:border-gray-400 border-gray-400 px-2 py-1 text-sm focus:outline-none focus:ring-0" rows="5"></textarea>
        </div>
        <div class="flex items-center gap-2">
          <p class="w-20 text-sm font-semibold required">Mức độ</p>
          <div class="grid flex-1 grid-cols-2 gap-3">
            <select v-model="complaintForm.severity" class="rounded p-2 focus:outline-none focus:ring-0 focus:border-[#AEAEAE] border-[#AEAEAE] text-sm">
              <option value="normal">Bình thường</option>
              <option value="urgent">Xử lý sớm</option>
              <option value="critical">Nghiêm trọng</option>
            </select>
            <select v-model="complaintForm.role_id" class="rounded p-2 focus:outline-none focus:ring-0 focus:border-[#AEAEAE] border-[#AEAEAE] text-sm">
              <option disabled selected :value="undefined"> -- Phòng ban -- </option>
              <option v-for="role in roles" :value="role.id" :key="role.id">
                {{ role.name.replaceAll('-', ' ') }}
              </option>
            </select>
          </div>
        </div>
        <div class="mt-3 flex justify-end">
          <button
            class="relative rounded bg-[#FF0000] px-3 py-2 text-sm font-semibold text-white"
            :class="{
              'cursor-wait': isLoading
            }"
            @click="onCreateComplaint"
          >
          <span v-if="isLoading" class="absolute w-full h-full left-0 top-0 flex items-center justify-center bg-gray-400/40">
            <SpinnerIcon class="!m-0" />
          </span>
            Thêm
          </button>
        </div>
        <DialogLoading v-if="isLoading" text="Đang thêm khiếu nại" />
      </div>
    </div>
    <button class="w-96 bg-[#FF6100] rounded-full font-semibold text-white py-2" @click="visible = !visible">
      Tạo yêu cầu khiếu nại
    </button>
  </div>
</template>
