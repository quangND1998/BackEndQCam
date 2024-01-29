<script setup>
import { inject, onMounted, ref } from 'vue';

import useQuery, { CUSTOMER_SERVICE_API_MAKER } from '../composables/useQuery';
import Status from '../Status.vue';

const { customerId } = inject('ORDER_PACKAGE_PAGE');

const visible = ref(false);

const { data, executeQuery } = useQuery(
  CUSTOMER_SERVICE_API_MAKER.GET_RECENT_ACTIVITY(customerId),
  undefined,
  (response) => {
    console.log(response);
  }
);

onMounted(() => {
  executeQuery();
});
</script>

<template>
  <div class="relative">
    <div v-if="visible" class="mt-4 w-96 rounded-lg bg-white shadow-lg absolute -top-[500px]">
      <div class="flex items-center justify-between rounded-t-lg bg-[#FF6100] pr-3 pl-4 py-2">
        <p class="font-semibold text-white">Hoạt động mới</p>
        <i class="fa fa-times text-2xl cursor-pointer text-white" aria-hidden="true" @click="visible = false"/>
      </div>
      <div class="px-4 my-3 h-[361px] overflow-y-auto recent-activity">
        <div class="border border-[#AEAEAE] !border-b-0 mb-8">
          <div class="grid grid-cols-6 text-center text-sm text-white font-medium bg-[#AEAEAE] leading-6 divide-x divide-white">
            <div>STT</div>
            <div class="col-span-2">Đơn hàng</div>
            <div>Loại</div>
            <div class="col-span-2">Trạng thái</div>
          </div>
          <div v-for="(order, index) in data.latestOrders  || []" :key="order.id" class="grid grid-cols-6 divide-x divide-[#AEAEAE] text-xs text-center leading-5 border-b border-[#AEAEAE] items-stretch justify-center">
            <div class="grid items-center justify-center">{{ index + 1 }}</div>
            <div class="col-span-2">
              <a :href="`/admin/orders/${order.id}/update`" target="_blank" class="hover:text-[#1D75FA] px-[2px]">
                {{ order.order_number }}
              </a>
            </div>
            <div class="grid items-center justify-center">Quà</div>
            <div class="col-span-2 grid items-center justify-center"><Status :status="order.status" /></div>
          </div>
          <div v-if="!data || data?.latestOrders?.length < 3" class=" text-sm text-center border-b border-[#AEAEAE] h-5"></div>
        </div>

        <div class="border border-[#AEAEAE] !border-b-0 mb-8">
          <div class="grid grid-cols-6 text-center text-sm text-white font-medium bg-[#AEAEAE] leading-6 divide-x divide-white">
            <div>STT</div>
            <div class="col-span-2">Booking</div>
            <div class="col-span-3">Trạng thái</div>
          </div>
          <div v-for="(visit, index) in data?.latestVisits  || []" :key="visit.id" class="grid grid-cols-6 divide-x divide-[#AEAEAE] text-sm text-center leading-5 border-b border-[#AEAEAE] items-stretch">
            <div class="grid items-center justify-center">{{ index + 1 }}</div>
            <div class="col-span-2 grid items-center justify-center">
              <a :href="`#`" target="_blank" class="hover:text-[#1D75FA]">
                Chi tiết
              </a>
            </div>
            <div class="col-span-3 grid items-center justify-center py-1"><Status :status="visit.state" /></div>
          </div>
          <div v-if="!data || data?.latestVisits?.length < 3" class=" text-sm text-center border-b border-[#AEAEAE] h-5"></div>
        </div>

        <div class="border border-[#AEAEAE] !border-b-0">
          <div class="grid grid-cols-6 text-center text-sm text-white font-medium bg-[#AEAEAE] leading-6 divide-x divide-white">
            <div>STT</div>
            <div class="col-span-3">Khiếu nại</div>
            <div class="col-span-2">Trạng thái</div>
          </div>
          <div v-for="(complaint, index) in data?.latestComplaints || []" :key="complaint.id" class="grid grid-cols-6 divide-x divide-[#AEAEAE] text-sm text-center leading-5 border-b border-[#AEAEAE] items-stretch">
            <div class="pt-1">{{ index + 1 }}</div>
            <div class="col-span-3 text-left px-1 py-1">{{ complaint.description }}</div>
            <div class="col-span-2 grid items-center justify-center py-1"><Status :status="complaint.state" /></div>
          </div>
          <div v-if="!data || data?.latestComplaints?.length < 3" class=" text-sm text-center border-b border-[#AEAEAE] h-5"></div>
        </div>
      </div>
    </div>
    <button class="w-96 bg-[#FF6100] rounded-full font-semibold text-white py-2" @click="visible = !visible">
      Hoạt dộng mới nhất
    </button>
  </div>
</template>

<style scoped>
.recent-activity {
  --sb-track-color: #9ca3af;
  --sb-thumb-color: #ea580c;
  --sb-size: 6px;

  scrollbar-color: var(--sb-thumb-color) var(--sb-track-color);
}

.recent-activity::-webkit-scrollbar {
  width: var(--sb-size)
}

.recent-activity::-webkit-scrollbar-track {
  background: var(--sb-track-color);
  border-radius: 1px;
}

.recent-activity::-webkit-scrollbar-thumb {
  background: var(--sb-thumb-color);
  border-radius: 1px;
}

.recent-activity::-webkit-outer-spin-button,
.recent-activity::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
