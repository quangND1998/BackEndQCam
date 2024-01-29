<script setup>
import { Head } from '@inertiajs/vue3'
import { provide, ref, watch, computed } from 'vue';

import useCity from '@/Components/CustomerService/composables/useCity';
import OrderTableSection from "@/Components/CustomerService/OrderTableSection.vue";
import ScheduleVisitSection from "@/Components/CustomerService/ScheduleVisitSection.vue";
import NoteDialog from "@/Components/CustomerService/Dialog/NoteDialog.vue";
import ComplaintDialog from "@/Components/CustomerService/Dialog/ComplaintDialog.vue";
import RecentActivityDialog from "@/Components/CustomerService/Dialog/RecentActivityDialog.vue";
import ExtraServiceDialog from '@/Components/CustomerService/Dialog/ExtraServiceDialog.vue';
import OrderDialog from '@/Components/CustomerService/Dialog/OrderDialog.vue';
import ContractCard from '@/Components/CustomerService/ContractCard.vue';
import useQuery, { CUSTOMER_SERVICE_API_MAKER } from '@/Components/CustomerService/composables/useQuery';
import PhoneCall from '@/Components/CustomerService/PhoneCall.vue';
import RecentActivity from '@/Components/CustomerService/Table/RecentActivity.vue';
import useMultipleComponent from '@/Components/CustomerService/composables/useMultipleComponent';
import { v4 as uuid } from 'uuid'

const props = defineProps({
  customerId: String,
  orderPackages: Array,
  declineOrderPackageCount: Number,
  extraServices: Array,
  productRetails: Array,
  customer: Object,
  roles: Array,
});

const idPackageList = computed(() => {
  return orderPackages.value.map((orderPackage) => orderPackage.idPackage);
})

const tableRef = ref();
const tableHeight = computed(() => {
  if (tableRef.value) {
    return tableRef.value.clientHeight;
  }
  return 0;
});

const orderPackages = ref(props.orderPackages);
const extraServices = ref(props.extraServices);
const productRetails = ref(props.productRetails);

const updateScheduleVisits = (orderPackageIndex, scheduleVisit) => {
  orderPackages.value[orderPackageIndex].product_service_owner.visit.push(scheduleVisit);
}
const addExtraService = (extraService) => {
  extraServices.value.push(extraService);
}
const updateExtraServiceState = (index, newState) => {
  extraServices.value[index].is_active = newState;
}

const { executeQuery } = useQuery(
  CUSTOMER_SERVICE_API_MAKER.GET_PRODUCT_RETAIL(),
  undefined,
  (response) => {
    productRetails.value = response.productRetails;
  }
)
const addOrder = (order) => {
  orderPackages.value[orderPackageIndex.value].product_service_owner.orders.push(order);
  orderDialogVisible.value = false;
  targetOrderPackage.value = null;
  deliveryNo.value = 0;
  orderPackageIndex.value = null;
  executeQuery();
}
const updateOrder = (order) => {
  orderPackages.value[orderPackageIndex.value].product_service_owner.orders[orderIndex.value] = order;
  orderDialogVisible.value = false;
  targetOrder.value = null;
  orderIndex.value = null;
  orderPackageIndex.value = null;
  executeQuery();
}

const { data, cities, districts } = useCity();
const [retailOrderDialog, actions] = useMultipleComponent();

const orderDialogVisible = ref(false);
const targetOrderPackage = ref();
const deliveryNo = ref(undefined);
const orderPackageIndex = ref();
const onOpenOrderDialog = (orderPackage, nextDeliveryNo, index) => {
  actions.reset();
  targetOrderPackage.value = orderPackage;
  orderDialogVisible.value = true;
  deliveryNo.value = nextDeliveryNo;
  orderPackageIndex.value = index;
}

const targetOrder = ref();
const orderIndex = ref();
const onOpenEditOrderDialog = (order, ordIndex, ordPIndex) => {
  actions.reset();
  orderDialogVisible.value = true;
  targetOrder.value = order;
  orderIndex.value = ordIndex;
  deliveryNo.value = order.delivery_no;
  orderPackageIndex.value = ordPIndex;
}
const onCloseDialog = () => {
  targetOrderPackage.value = null;
  orderDialogVisible.value = false;
  orderIndex.value = null;
  targetOrder.value = null;
  deliveryNo.value = undefined;
  orderPackageIndex.value = null;
}

const onOpenRetailOrderDialog = () => {
  const id = uuid();
  actions.add(id, { id });
}

provide('COMPLAINT', {
  roles: props.roles,
})

provide('ORDER', {
  onOpenEditOrderDialog,
  idPackageList
});

provide('ORDER_PACKAGE_PAGE', {
  productRetails: productRetails,
  customerId: props.customerId,
  extraServices,
  updateScheduleVisits,
  addExtraService,
  updateExtraServiceState,
  addOrder,
  updateOrder,
  data,
  cities,
  districts,
  customer: props.customer,
});

watch(orderDialogVisible, (newValue) => {
  if (newValue) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = 'auto';
  }
});

const showRecentActivityDialog = ref(false);

const displayRemindDialogOrder = ref([])
const addRemindDialog = (packageId) => {
  if (displayRemindDialogOrder.value.includes(packageId)) return;
  displayRemindDialogOrder.value.push(packageId);
}
const removeRemindDialog = (index) => {
  displayRemindDialogOrder.value.splice(index, 1);
}
provide('REMIND', {
  displayRemindDialogOrder,
  addRemindDialog,
  removeRemindDialog,
})

const displayBookingDialogOrder = ref([])
const addBookingDialog = (packageId) => {
  if (displayBookingDialogOrder.value.includes(packageId)) return;
  displayBookingDialogOrder.value.push(packageId);
}
const removeBookingDialog = (index) => {
  displayBookingDialogOrder.value.splice(index, 1);
}
provide('BOOKING', {
  displayBookingDialogOrder,
  addBookingDialog,
  removeBookingDialog,
});

</script>

<template>
    <Head title="Customer Order Packages" />
    <div class="pt-3 pb-24 px-10 bg-slate-100 relative min-h-screen">
      <a class="mb-3 font-bold inline-block cursor-pointer hover:!text-sky-600">
        <i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Quay lại
      </a>
      <div ref="tableRef">
        <OrderTableSection :orderPackages="orderPackages" :declineOrderPackageCount="declineOrderPackageCount" class="mb-3" />
        <ScheduleVisitSection :orderPackages="orderPackages" />
      </div>
      <OrderDialog v-if="orderDialogVisible"
        :orderPackage="targetOrderPackage"
        :deliveryNo="deliveryNo"
        :order="targetOrder"
        @onCloseDialog="onCloseDialog" />
      <div class="grid grid-cols-[repeat(18,_minmax(0,_1fr))] gap-4 mt-3 relative">
        <div class="col-span-4">
          <p class="font-bold mb-3">Giao kế hoạch giao quà cho khách</p>
          <ContractCard v-for="(orderPackage, index) in orderPackages"
            :key="orderPackage.id"
            :index="index"
            :orderPackage="orderPackage"
            @onOpenOrderDialog="onOpenOrderDialog" />
        </div>
        <div class="flex items-center flex-col" style="grid-column: span 14 / span 14">
          <div class="grid gap-4 grid-cols-2">
            <div>
              <button
                class="leading-5 rounded-full bg-[#FF6100] font-semibold text-white px-3 py-1"
                @click="onOpenRetailOrderDialog"
              >
                Tạo đơn bán lẻ sản phẩm
              </button>
            </div>
            <ExtraServiceDialog />
          </div>
          <RecentActivity v-if="showRecentActivityDialog" class="mt-4" />
          <div class="relative w-[calc(100vw/18*14+4*13px)] left-[calc(-100vw/18+68px)] mt-2 z-50">
            <OrderDialog
              v-for="([key], index) in retailOrderDialog"
              :key="key"
              :orderPackage="null"
              :deliveryNo="undefined"
              :order="null"
              :marginTop="index === 0 ? 0 : 16"
              @onCloseDialog="() => { actions.remove(key) }"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-3 gap-8 py-6 fixed bottom-0 bg-white border border-t-[#AEAEAE] w-full right-0">
      <div class="flex justify-center">
        <NoteDialog />
      </div>
      <div class="flex justify-center">
        <ComplaintDialog />
      </div>
      <div class="flex justify-center">
        <RecentActivityDialog />
        <!-- <button
          class="w-96 bg-orange-500 rounded-full font-semibold text-white py-2"
          @click="showRecentActivityDialog = !showRecentActivityDialog"
        >
          Hoạt dộng mới nhất
        </button> -->
      </div>
    </div>
    <PhoneCall :tableHeight="tableHeight" />
</template>

<style>
.required::before {
  content: '*';
  color: theme('colors.red.600');
  margin-right: 2px;
}
#pwBackground {
  visibility: hidden !important;
}
</style>
