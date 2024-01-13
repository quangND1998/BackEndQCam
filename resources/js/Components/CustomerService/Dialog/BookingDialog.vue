<script setup>
  import { ref, reactive, inject, computed } from 'vue';
  import axios from 'axios';
  import moment from 'moment';

  import DialogLoading from './DialogLoading.vue';

  const { customerId } = inject('ORDER_PACAGE_PAGE');

  const props = defineProps({
    packageId: String,
    productServiceOwnerId: String,
  });

  const isLoading = ref(false);
  const visible = ref(false);
  const scheduleVisitId = ref();
  const bookingForm = reactive({
    date_time: new Date(new Date().setDate(new Date().getDate() + 1)),
    number_adult: 1,
    number_children: 1,
    services: [],
    description: '',
  });
  const minDate = computed(() => new Date());
  const formatedDate = computed(() => {
    return moment(bookingForm.date_time).format('YYYY-MM-DD HH:mm:ss');
  });

  const handleBook = async () => {
    try {
      isLoading.value = true;
      // if (scheduleVisitId.value) {
      //   await axios.put(`/customer-service/customer/${customerId}/schedule-visit/${scheduleVisitId.value}`, {
      //     ...bookingForm,
      //     date_time: formatedDate,
      //   });
      // } else {
      //   await axios.post(`/customer-service/customer/${customerId}/schedule-visit`, {
      //     ...bookingForm,
      //     date_time: formatedDate,
      //   });
      // }
    } catch (error) {

    } finally {
      isLoading.value = false;
    }
  }
</script>

<template>
  <div class="relative">
    <div v-if="visible" class="mt-4 w-[700px] rounded-lg bg-white shadow-lg absolute -top-[272px] left-[300px] z-10">
      <div class="flex items-center justify-between rounded-t-lg bg-yellow-500 pr-3 pl-4 py-2">
        <p class="font-semibold">Booking theo HD {{ packageId }}</p>
        <i class="fa fa-times text-2xl cursor-pointer text-white" aria-hidden="true" @click="visible = false"/>
      </div>
      <div class="px-4 py-2 relative">
        <div class="flex items-center mb-3">
          <p class="w-28">Ngày</p>
          <div class="w-56">
            <VueDatePicker v-model="bookingForm.date_time" :min-date="minDate" time-picker-inline format="HH:mm dd/MM/yyyy" />
          </div>
        </div>
        <div class="flex items-center mb-3">
          <p class="w-28">Người lớn</p>
          <input v-model="bookingForm.number_adult" type="number" min="1" value="1" class="w-28 h-8 rounded-sm border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0" />
        </div>
        <div class="flex items-center mb-3">
          <p class="w-28">Trẻ em</p>
          <input v-model="bookingForm.number_children" type="number" min="1" value="1" class="w-28 h-8 rounded-sm border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0" />
        </div>
        <div class="flex items-center mb-3">
          <p class="w-28">Dịch vụ</p>
          <div class="flex gap-10">
            <div class="flex items-center gap-2">
              <input v-model="bookingForm.services" id="tx" value="Thuê xe" type="checkbox" class="focus:outline-none focus:ring-0" />
              <label for="tx" class="m-0">Thuê xe</label>
            </div>
            <div class="flex items-center gap-2">
              <input v-model="bookingForm.services" id="au" value="Ăn uống" type="checkbox" class="focus:outline-none focus:ring-0" />
              <label for="au" class="m-0">Ăn uống</label>
            </div>
            <div class="flex items-center gap-2">
              <input v-model="bookingForm.services" id="dv1" value="DV1" type="checkbox" class="focus:outline-none focus:ring-0" />
              <label for="dv1" class="m-0">DV1</label>
            </div>
            <div class="flex items-center gap-2">
              <input v-model="bookingForm.services" id="dv2" value="DV2" type="checkbox" class="focus:outline-none focus:ring-0" />
              <label for="dv2" class="m-0">DV2</label>
            </div>
          </div>
        </div>
        <div class="flex mb-4">
          <p class="w-28">Ghi chú</p>
          <textarea v-model="bookingForm.description" class="flex-1 resize-none rounded bg-gray-100 focus:border-gray-400 border-gray-400 px-2 py-1 text-sm focus:outline-none focus:ring-0" rows="5"></textarea>
        </div>
        <div class="flex justify-end">
          <button class="rounded-md bg-sky-600 text-white font-medium px-3 py-2 mb-2" @click="handleBook">
            {{ scheduleVisitId ? 'Cập nhật' : 'Book' }}
          </button>
        </div>
        <DialogLoading v-if="isLoading" text="Booking" />
      </div>
    </div>
    <button class="rounded-full bg-sky-600 text-white font-medium px-3 py-2 mb-2" @click="visible = !visible">Booking</button>
  </div>
</template>
