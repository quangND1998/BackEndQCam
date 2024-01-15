<script setup>
import { Head } from '@inertiajs/vue3'

import Button from 'primevue/button';

import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import OrderTableSection from "@/Components/CustomerService/OrderTableSection.vue";
import ScheduleVisitSection from "@/Components/CustomerService/ScheduleVisitSection.vue";
import NoteDialog from "@/Components/CustomerService/Dialog/NoteDialog.vue";
import ComplaintDialog from "@/Components/CustomerService/Dialog/ComplaintDialog.vue";
import RecentActivityDialog from "@/Components/CustomerService/Dialog/RecentActivityDialog.vue";
import BookingDialog from "@/Components/CustomerService/Dialog/BookingDialog.vue";

const props = defineProps({
  orderPackages: Object,
});

</script>

<template>
  <LayoutAuthenticated>
  <Head title="Customer Order Packages" />
  <div class="mt-4 mr-4">
    <OrderTableSection :orderPackages="orderPackages" class="mb-3" />
    <ScheduleVisitSection :orderPackages="orderPackages" />
    <div class="grid grid-cols-[repeat(18,_minmax(0,_1fr))] gap-4 mt-3">
      <div class="col-span-4">
        <p class="font-bold mb-3">Giao kế hoạch giao quà cho khách</p>
        <div v-for="orderPackage in orderPackages" :key="orderPackage.id" class="bg-white rounded-lg max-w-[300px] border mb-3 text-sm">
          <p class="font-semibold text-white bg-red-600 rounded-lg leading-8 pl-3">
            Hợp đồng {{ orderPackage.idPackage }}
          </p>
          <div class="px-3 py-2">
            <p class="mb-1"><span class="font-semibold">Đã nhận quà: </span>{{ orderPackage.product_service_owner.number_deliveries_current || 0 }}/12</p>
            <p class="mb-1"><span class="font-semibold">Quá hạn: </span>2</p>
            <p><span class="font-semibold">Lần kế tiếp: </span>22/12/2023 (lần 12)</p>
            <p class="mt-3 mb-2">Khách hàng có nhận quà lần kế tiêp không?</p>
            <div class="flex justify-between">
              <button class="rounded-full bg-emerald-600 text-white font-medium px-2 py-2">Tạo đơn (3)</button>
              <button class="rounded-full bg-red-600 text-white font-medium px-2 py-2">Tạo lịch hẹn</button>
            </div>
            <p class="mt-3 mb-2">Khách hàng muốn booking tham quan?</p>
            <BookingDialog :packageId="orderPackage.idPackage " />
          </div>
        </div>
      </div>
      <div class="flex justify-center" style="grid-column: span 14 / span 14">
        <div>
          <button class="leading-5 rounded-full bg-orange-600 font-semibold text-white px-3 py-1">
            Tạo đơn bán lẻ sản phẩm
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="grid grid-cols-3 gap-8 mt-6">
    <div class="flex justify-center">
      <NoteDialog />
    </div>
    <div class="flex justify-center">
      <ComplaintDialog />
    </div>
    <div class="flex justify-center">
      <RecentActivityDialog />
    </div>
  </div>
</LayoutAuthenticated>
</template>
