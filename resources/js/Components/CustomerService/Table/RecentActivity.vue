<script setup>
import { inject, onMounted } from 'vue';
import moment from 'moment';

import useQuery, { CUSTOMER_SERVICE_API_MAKER } from '@/Components/CustomerService/composables/useQuery';
import Status from '@/Components/CustomerService/Status.vue';
import SpinnerIcon from '@/Components/CustomerService/SpinnerIcon.vue';

const { customerId } = inject('ORDER_PACKAGE_PAGE');

const { data, isLoading, executeQuery } = useQuery(
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
  <div class="w-full mb-10">

    <!-- ORDER -->
    <div class="!mr-[200px]">
      <div class="grid grid-cols-12 bg-gray-400 text-white font-bold divide-x divide-white text-center">
        <div>STT</div>
        <div class="col-span-2">Mã HĐ</div>
        <div class="col-span-2">Mã đơn hàng</div>
        <div class="col-span-2">Trạng thái</div>
        <div class="col-span-3">Sản phẩm</div>
        <div class="col-span-2">Ngày tạo</div>
      </div>
      <div v-if="isLoading" class="bg-white text-center border-x border-b border-gray-400 flex items-center justify-center h-28">
        <SpinnerIcon class="!m-0 !w-10 !h-10" />
      </div>
      <tempate v-else>
        <div v-for="(order, index) in data?.latestOrders || []" :key="order.id" class="items-stretch text-sm grid grid-cols-12 bg-white divide-x divide-gray-400 text-center leading-8 border-x border-b border-gray-400">
          <div class="h-full grid items-center">{{ index + 1 }}</div>
          <div class="col-span-2 flex items-center justify-center">{{ order?.product_service?.order_package?.idPackage }}</div>
          <div class="col-span-2 flex items-center justify-center">{{  order.order_number }}</div>
          <div class="col-span-2 h-full grid items-center justify-center"><Status :status="order.status" class="my-1" /></div>
          <div class="col-span-3 text-left pl-2">
            <p v-for="item in order.order_items" :key="item.id" class="!leading-6">
              {{ item.product.name }}: {{ item.quantity }}
            </p>
          </div>
          <div class="col-span-2 h-full grid items-center">{{ moment(order.created_at, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY') }}</div>
        </div>
        <div v-if="data?.latestOrders?.length === 0" class="bg-white text-center border-x border-b border-gray-400 flex items-center justify-center h-28">
          <p class="text-gray-400">Không có đơn hàng nào</p>
        </div>
      </tempate>
    </div>

    <!-- VISIT -->
    <div class="!mr-[200px] mt-10">
      <div class="grid grid-cols-12 bg-gray-400 text-white font-bold divide-x divide-white text-center">
        <div>STT</div>
        <div class="col-span-2">Mã HĐ</div>
        <div class="col-span-2">Khách thăm quan</div>
        <div class="col-span-2">Dịch vụ</div>
        <div class="col-span-3">Ghi chú</div>
        <div class="col-span-2">Ngày tạo</div>
      </div>
      <div v-if="isLoading" class="bg-white text-center border-x border-b border-gray-400 flex items-center justify-center h-28">
        <SpinnerIcon class="!m-0 !w-10 !h-10" />
      </div>
      <tempate v-else>
        <div v-for="(visit, index) in data?.latestVisits || []" :key="visit.id" class="text-sm grid grid-cols-12 bg-white divide-x divide-gray-400 text-center leading-8 border-x border-b border-gray-400">
          <div class="flex items-center justify-center">{{ index + 1 }}</div>
          <div class="col-span-2 flex items-center justify-center">{{ visit?.product_owner_service?.order_package?.idPackage }}</div>
          <div class="col-span-2 text-left pl-2 flex flex-col justify-center">
            <p class="!leading-6">{{ visit.number_adult }} người lớn</p>
            <p class="!leading-6">{{ visit.number_children }} trẻ con</p>
          </div>
          <div class="col-span-2 flex items-center justify-center"><Status :status="visit.state" class="my-1" /></div>
          <div class="col-span-3 text-left pl-2 flex flex-col justify-center">
            <p class="!leading-6 text-xs font-medium">Ngày: {{ moment(visit.date_time, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY') }}</p>
            <p class="!leading-6">{{  visit.description  }}</p>
          </div>
          <div class="col-span-2 flex items-center justify-center">{{ moment(visit.created_at, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY') }}</div>
        </div>
        <div v-if="data?.latestVisits?.length === 0" class="bg-white text-center border-x border-b border-gray-400 flex items-center justify-center h-28">
          <p class="text-gray-400">Không có booking nào</p>
        </div>
      </tempate>
    </div>

    <!-- COMPLAINT -->
    <div class="!mr-[200px] mt-10">
      <div class="grid grid-cols-12 bg-gray-400 text-white font-bold divide-x divide-white text-center">
        <div>STT</div>
        <div class="col-span-2">Mức độ</div>
        <div class="col-span-2">Phòng ban</div>
        <div class="col-span-2">Trạng thái</div>
        <div class="col-span-3">Nội dung</div>
        <div class="col-span-2">Ngày tạo</div>
      </div>
      <div v-if="isLoading" class="bg-white text-center border-x border-b border-gray-400 flex items-center justify-center h-28">
        <SpinnerIcon class="!m-0 !w-10 !h-10" />
      </div>
      <tempate v-else>
        <div v-for="(complaint, index) in data?.latestComplaints || []"  :key="complaint.id" class="grid grid-cols-12 bg-white divide-x divide-gray-400 text-center leading-8 border-x border-b border-gray-400 text-sm">
          <div class="flex items-center justify-center">{{ index + 1 }}</div>
          <div class="col-span-2 flex items-center justify-center">
            <Status :status="complaint.severity" />
          </div>
          <div class="col-span-2 flex items-center justify-center capitalize">{{ complaint?.role?.name.replaceAll('-', ' ') }}</div>
          <div class="col-span-2 flex items-center justify-center">
            <Status :status="complaint.state" class="my-1" />
          </div>
          <div class="col-span-3 text-left pl-2">{{ complaint.description }}</div>
          <div class="col-span-2 flex items-center justify-center">{{ moment(complaint.created_at, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY') }}</div>
        </div>
        <div v-if="data?.latestComplaints?.length === 0" class="bg-white text-center border-x border-b border-gray-400 flex items-center justify-center h-28">
          <p class="text-gray-400">Không có khiếu nại nào</p>
        </div>
      </tempate>
    </div>
  </div>
</template>
