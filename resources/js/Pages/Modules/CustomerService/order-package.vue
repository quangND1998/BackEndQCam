<script setup>
import { Head } from '@inertiajs/vue3'
import { provide, ref, computed } from 'vue';

import useCity from '@/Components/CustomerService/composables/useCity';
import OrderTableSection from "@/Components/CustomerService/OrderTableSection.vue";
import ScheduleVisitSection from "@/Components/CustomerService/ScheduleVisitSection.vue";
import NoteDialog from "@/Components/CustomerService/Dialog/NoteDialog.vue";
import ComplaintDialog from "@/Components/CustomerService/Dialog/ComplaintDialog.vue";
import RecentActivityDialog from "@/Components/CustomerService/Dialog/RecentActivityDialog.vue";
import ExtraServiceDialog from '@/Components/CustomerService/Dialog/ExtraServiceDialog.vue';
import OrderDialog from '@/Components/CustomerService/Dialog/OrderDialog.vue';
import ContractCard from '@/Components/CustomerService/ContractCard.vue';

const props = defineProps({
  customerId: String,
  orderPackages: Array,
  declineOrderPackageCount: Number,
  extraServices: Array,
  productRetails: Array,
  customer: Object,
});

const tableRef = ref();
const orderPackages = ref(props.orderPackages);
const extraServices = ref(props.extraServices);

const updateScheduleVisits = (orderPackageIndex, scheduleVisit) => {
  orderPackages.value[orderPackageIndex].product_service_owner.visit.push(scheduleVisit);
}
const addExtraService = (extraService) => {
  extraServices.push(extraService);
}
const updateExtraServiceState = (index, newState) => {
  extraServices.value[index].is_active = newState;
}

const { data, cities, districts, wards } = useCity();

const orderDialogVisible = ref(false);
const targetOrderPackage = ref();
const onOpenOrderDialog = (orderPackage) => {
  targetOrderPackage.value = orderPackage;
  orderDialogVisible.value = true;
}
const onCloseDialog = () => {
  targetOrderPackage.value = null;
  orderDialogVisible.value = false;
}

provide('ORDER_PACKAGE_PAGE', {
  productRetails: props.productRetails,
  customerId: props.customerId,
  extraServices,
  updateScheduleVisits,
  addExtraService,
  updateExtraServiceState,
  data,
  cities,
  districts,
  customer: props.customer,
});
</script>

<template>
    <Head title="Customer Order Packages" />
    <div class="pt-10 pb-20 px-10">
      <div ref="tableRef">
        <OrderTableSection :orderPackages="orderPackages" :declineOrderPackageCount="declineOrderPackageCount" class="mb-3" />
        <ScheduleVisitSection :orderPackages="orderPackages" />
      </div>
      <OrderDialog v-if="orderDialogVisible" :orderPackage="targetOrderPackage" @onCloseDialog="onCloseDialog" />
      <div class="grid grid-cols-[repeat(18,_minmax(0,_1fr))] gap-4 mt-3">
        <div class="col-span-4">
          <p class="font-bold mb-3">Giao kế hoạch giao quà cho khách</p>
          <ContractCard v-for="(orderPackage, index) in orderPackages"
            :key="orderPackage.id"
            :index="index"
            :orderPackage="orderPackage"
            @onOpenOrderDialog="onOpenOrderDialog" />
        </div>
        <div class="flex justify-center" style="grid-column: span 14 / span 14">
          <div class="grid gap-4 grid-cols-2">
            <div>
              <button class="leading-5 rounded-full bg-orange-600 font-semibold text-white px-3 py-1">
                Tạo đơn bán lẻ sản phẩm
              </button>
            </div>
            <ExtraServiceDialog />
          </div>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-3 gap-8 py-6 fixed bottom-0 bg-white border border-t-gray-500 w-full right-0">
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
</template>
