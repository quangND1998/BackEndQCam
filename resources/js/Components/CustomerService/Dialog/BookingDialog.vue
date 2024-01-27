<script setup>
  import { ref, reactive, inject, computed, watch } from 'vue';
  import moment from 'moment';
  import cloneDeep from 'lodash/cloneDeep';

  import DialogLoading from './DialogLoading.vue';
  import useQuery, { CUSTOMER_SERVICE_API_MAKER } from '@/Components/CustomerService/composables/useQuery';

  const { customerId, extraServices, updateScheduleVisits } = inject('ORDER_PACKAGE_PAGE');

  const props = defineProps({
    index: Number,
    packageId: String,
    productServiceOwnerId: String,
  });

  const initData = {
    product_service_owner_id: props.productServiceOwnerId,
    date_time: new Date(new Date().setDate(new Date().getDate() + 1)),
    number_adult: 1,
    number_children: 0,
    services: [],
    description: '',
    date: new Date(new Date().setDate(new Date().getDate() + 1)),
  }

  const visible = ref(false);
  const scheduleVisitId = ref();
  const bookingForm = reactive(cloneDeep(initData));
  const minDate = computed(() => new Date());
  const activeService = computed(() => extraServices.value.filter((service) => service.is_active));

  watch(() => bookingForm.date, (newVal) => {
    bookingForm.date_time = moment(newVal).format('YYYY-MM-DD HH:mm:ss');
  });

  const { isLoading, executeQuery } = useQuery(
    CUSTOMER_SERVICE_API_MAKER.CREATE_VISIT(customerId),
    bookingForm,
    (data) => {
      Object.assign(bookingForm, cloneDeep(initData))
      visible.value = false;
      updateScheduleVisits(props.index, data.scheduleVisit);
    },
    'Book lịch thành công',
  );

  const { displayBookingDialogOrder, addBookingDialog, removeBookingDialog } = inject('BOOKING');
  const positionIndex = computed(() => displayBookingDialogOrder.value.findIndex((id) => id === props.packageId))
  const leftPosition = computed(() => `calc(100%/18*4 - 96px + ${positionIndex.value * 700 + positionIndex.value * 16}px)`)
  const onOpenDialog = () => {
    visible.value = true;
    addBookingDialog(props.packageId);
  }
  const onCloseDialog = () => {
    visible.value = false;
    removeBookingDialog(positionIndex.value);
  }
</script>

<template>
    <div
      v-if="visible"
      class="absolute top-10 w-[700px] rounded-xl bg-white shadow-lg z-10"
      :style="{
        left: leftPosition
      }"
    >
      <div class="flex items-center justify-between rounded-t-lg bg-yellow-500 pr-3 pl-4 py-2">
        <p class="font-semibold">Booking theo HD {{ packageId }}</p>
        <i class="fa fa-times text-2xl cursor-pointer text-white" aria-hidden="true" @click="onCloseDialog"/>
      </div>
      <div class="px-4 py-2 relative">
        <div class="flex items-center mb-3">
          <p class="w-28">Ngày</p>
          <div class="w-56">
            <VueDatePicker v-model="bookingForm.date" :min-date="minDate" time-picker-inline format="HH:mm dd/MM/yyyy" />
          </div>
        </div>
        <div class="flex items-center mb-3">
          <p class="w-28">Người lớn</p>
          <input v-model="bookingForm.number_adult" type="number" min="1" class="w-28 h-8 rounded-sm border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0" />
        </div>
        <div class="flex items-center mb-3">
          <p class="w-28">Trẻ em</p>
          <input v-model="bookingForm.number_children" type="number" min="0" class="w-28 h-8 rounded-sm border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0" />
        </div>
        <div class="flex items-center mb-3">
          <p class="w-28">Dịch vụ</p>
          <div class="flex gap-5 !flex-wrap">
            <div v-for="service in activeService" :key="service.id" class="flex items-center">
              <input v-model="bookingForm.services" :id="`service_${service.id}`" :value="service.id" type="checkbox" class="focus:outline-none focus:ring-0 rounded" />
              <label :for="`service_${service.id}`" class="m-0 select-none pl-2">{{ service.name }}</label>
            </div>
          </div>
        </div>
        <div class="flex mb-4">
          <p class="w-28">Ghi chú</p>
          <textarea v-model="bookingForm.description" class="flex-1 resize-none rounded focus:border-gray-400 border-gray-400 px-2 py-1 text-sm focus:outline-none focus:ring-0" rows="5"></textarea>
        </div>
        <div class="flex justify-end">
          <button class="rounded-md bg-sky-600 text-white font-medium px-3 py-2 mb-2" @click="executeQuery">
            {{ scheduleVisitId ? 'Cập nhật' : 'Book' }}
          </button>
        </div>
        <DialogLoading v-if="isLoading" text="Booking" />
      </div>
    </div>
  <button class="rounded-full bg-sky-600 text-white font-medium px-3 py-2 mb-2" @click="onOpenDialog">Booking</button>
</template>
