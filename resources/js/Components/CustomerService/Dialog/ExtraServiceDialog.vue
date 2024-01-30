<script setup>
import { ref, inject, reactive } from 'vue';

import useQuery, { CUSTOMER_SERVICE_API_MAKER } from '@/Components/CustomerService/composables/useQuery';
import SpinnerIcon from '@/Components/CustomerService/SpinnerIcon.vue';

const { extraServices, addExtraService, updateExtraServiceState } = inject('ORDER_PACKAGE_PAGE');

const visible = ref(false);
const extraServiceForm = reactive({
  name: '',
});

const onSuccess = (data) => {
  extraServiceForm.name = '';
  addExtraService(data.visitExtraService);
}
const { isLoading, executeQuery } = useQuery(
  CUSTOMER_SERVICE_API_MAKER.CREATE_EXTRA_SERVICE(),
  extraServiceForm,
  onSuccess
);
const handleAddExtraService = () => {
  if (!extraServiceForm.name) return;
  executeQuery();
}

const { executeQuery: update } = useQuery(CUSTOMER_SERVICE_API_MAKER.UPDATE_EXTRA_SERVICE());
const handelUpdateService = (id, index, newValue) => {
  update({ extraServiceId: id }, newValue);
  updateExtraServiceState(index, newValue.is_active);
}
</script>

<template>
  <div>
    <div
      v-if="visible"
      class="mt-4 w-[700px] rounded-lg bg-white shadow-lg fixed left-[calc((100vw_-_540px)/2)] top-[calc((100vh-700px)/2)] h-[700px] z-10"
    >
      <div class="flex items-center justify-between rounded-t-lg bg-[#FF6100] pr-3 pl-4 py-2">
        <p class="font-semibold text-white">Thêm dịch vụ booking</p>
        <i class="fa fa-times text-2xl cursor-pointer text-white" aria-hidden="true" @click="visible = false"/>
      </div>
      <div class="px-4 py-3 relative h-[652px] overflow-y-auto">
        <div class="flex gap-4 mb-10">
          <input
            :disabled="isLoading"
            v-model="extraServiceForm.name"
            class="h-[34px] rounded-sm border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0 flex-1 disabled:bg-gray-300 disabled:cursor-not-allowed disabled:text-gray-400"
            placeholder="Tên dịch vụ" />
          <button
            :disabled="!extraServiceForm.name"
            class="rounded-md bg-[#FF6100] text-white font-medium px-3 py-2 relative"
            :class="{
              'cursor-not-allowed !bg-gray-400 !text-gray-200': !extraServiceForm.name,
            }"
            @click="handleAddExtraService"
          >
            <div
              v-if="isLoading"
              class="absolute top-0 left-0 flex items-center justify-center w-full h-full bg-gray-400/40 cursor-wait"
            >
              <SpinnerIcon class="!m-0" />
            </div>
            Thêm dịch vụ
          </button>
        </div>
        <div class="border-x border-gray-400">
          <div class="grid grid-cols-6 bg-gray-400 divide-x divide-white text-center leading-8">
            <div>STT</div>
            <div class="col-span-4 text-left pl-2">Tên dịch vụ</div>
            <div>Hành động</div>
          </div>
          <div
            v-for="(service, index) in extraServices" :key="service.id"
            class="grid grid-cols-6 divide-x divide-gray-400 text-center leading-8 border-b border-gray-400"
          >
            <div>{{ index + 1 }}</div>
            <div class="col-span-4 text-left pl-2">{{ service.name }}</div>
            <div class="leading-8 flex items-center justify-center">
              <label class="relative inline-flex items-center cursor-pointer m-0">
                <input
                  type="checkbox"
                  :checked="service.is_active"
                  class="sr-only peer"
                  @input="(e) => {
                    handelUpdateService(service.id, index, {
                      name: service.name,
                      is_active: !service.is_active,
                    });
                    e.tartget.checked = !e.tartget.checked;
                  }
                  "
                />
                <div class="w-11 h-6 bg-gray-400 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#FF6100]"></div>
              </label>
            </div>
          </div>
        </div>
      </div>

    </div>
    <button
      class="leading-5 rounded-full bg-[#FF6100] font-semibold text-white px-3 py-1"
      @click="visible = !visible"
    >
      Thêm dịch vụ booking
    </button>
  </div>
</template>
