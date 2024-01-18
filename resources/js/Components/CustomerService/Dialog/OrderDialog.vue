<script setup>
import { computed, inject, reactive, ref, toRaw, watch } from 'vue';

import FilterableDropdown from '@/Components/CustomerService/FilterableDropdown.vue';
import useQuery, { CUSTOMER_SERVICE_API_MAKER } from '../composables/useQuery';
import SpinnerIcon from '@/Components/CustomerService/SpinnerIcon.vue';

const { productRetails, data, cities, districts, customer, customerId, updateOrder } = inject('ORDER_PACKAGE_PAGE');

const props = defineProps({
  orderPackage: Object,
  deliveryNo: Number,
});

const emit = defineEmits(['onCloseDialog']);

// Product picker
const selectedProducts = ref({});
const showProductError = ref(false);
const productRows = ref([1]);
const handleAddRow = () => {
  productRows.value.push(productRows.value[productRows.value.length - 1] + 1);
}
const handleRemoveRow = (rowNumber) => {
  productRows.value = productRows.value.filter((number) => number !== rowNumber);
  delete selectedProducts.value[`product_${rowNumber}`];
}
const handleChangeProduct = (productId, rowNumber) => {
  const product = productRetails.find((product) => product.id == productId);
  if (product) {
    showProductError.value = false;
    selectedProducts.value = {
      ...selectedProducts.value,
      [`product_${rowNumber}`]: {
        id: product.id,
        name: product.name,
        unit: product.unit,
        quantity: 1,
        available: product.available_quantity,
      }
    };
  }
}
const handleUpdateQuantity = (quantity, rowNumber) => {
  selectedProducts.value[`product_${rowNumber}`].quantity = parseInt(quantity, 10) || 1;
}

// Address picker
const addressType = ref('default');
const showAddressError = ref(false);
const address = reactive({
  city: customer.city,
  district: customer.district,
  ward: customer.wards,
  detail: customer.address
});
const disabledAdressEdit = computed(() => addressType.value === 'default');
const districtsOfCity = computed(() => {
  if (address.city && cities.value[encodeURIComponent(address.city).slice(-12)]) {
    return cities.value[encodeURIComponent(address.city).slice(-12)].Districts || [];
  }
  return [];
});
const wardsOfDistrict = computed(() => {
  if (address.district && districts.value[encodeURIComponent(address.district).slice(-12)]) {
    return districts.value[encodeURIComponent(address.district).slice(-12)].Wards || [];
  }
  return [];
});
const filterFunction = (value) => value.replace(/Tỉnh |Thành phố |Quận |Huyện |Thị xã |Phường |Xã |Thị trấn /g, '');
watch(() => address.city, () => {
  address.district = null;
  address.ward = null;
});
watch(addressType, (newType) => {
  if (newType === 'default') {
    address.city = customer.city;
    address.district = customer.district;
    address.ward = customer.wards;
    address.detail = customer.address;
  }
});

// Other
const subPhoneNumber = ref('');
const note = ref('');

const { isLoading, executeQuery } = useQuery(
  CUSTOMER_SERVICE_API_MAKER.CREATE_ORDER(customerId),
  undefined,
  (data) => {
    updateOrder(data.order);
  },
  'Tạo đơn hàng thành công'
);
const onCreateOrder = () => {
  if (isLoading.value) return;
  const order = {
    products: Object.values(selectedProducts.value).map((product) => ({
      id: product.id,
      quantity: product.quantity,
    })),
    address: toRaw(address),
    note: note.value,
    subPhoneNumber: subPhoneNumber.value,
    productServiceOwnerId: props.orderPackage.product_service_owner.id,
    deliveryNo: props.deliveryNo
  }

  const haveError = false;
  if (order.products.length === 0) {
    showProductError.value = true
    haveError = true;
  }
  if (!order.address.detail) {
    showAddressError.value = true;
    haveError = true;
  }

  if (haveError) return;

  executeQuery(undefined, order);
}
</script>

<template>
  <div class="fixed w-screen h-screen bg-black/40 top-0 left-0 z-50 overflow-hidden flex items-center justify-center">
    <div class="bg-white shadow-lg w-[calc(100vw_-_318px)] rounded-xl">
      <div class="flex items-center justify-between rounded-t-lg bg-orange-500 pr-3 pl-4 py-2">
        <p class="font-semibold text-white">Lên đơn cho hợp đồng {{ orderPackage?.idPackage }}</p>
        <i class="fa fa-times text-2xl cursor-pointer text-white" aria-hidden="true" @click="emit('onCloseDialog')"/>
      </div>
      <div class="grid grid-cols-3 p-3 pt-4 gap-10">
        <div>
          <div class="flex items-center justify-between border-b border-gray-400 pb-2 mb-3">
            <p class="font-semibold text-sm">Lần: {{ deliveryNo }}</p>
            <button class="rounded-full bg-sky-600 text-white font-medium px-2 py-1 text-sm" @click="handleAddRow">
              Thêm
            </button>
          </div>
          <div class="border-x border-gray-400">
            <div class="grid grid-cols-7 bg-gray-400 divide-x divide-white leading-6 text-sm font-medium text-white text-center border-x border-gray-400">
              <div>STT</div>
              <div class="col-span-3">Loại quà</div>
              <div>DVT</div>
              <div>SL</div>
              <div></div>
            </div>
            <div v-for="(rowNumber, index) in productRows" :key="rowNumber" class="grid grid-cols-7 divide-x divide-gray-400 border-b border-gray-400 text-sm text-center items-center">
              <div>{{ index + 1 }}</div>
              <div class="col-span-3">
                <div class="p-1">
                  <select @input="(e) => handleChangeProduct(e.target.value, rowNumber)" class="w-full rounded pl-1 focus:outline-none focus:ring-0 focus:border-gray-400 border-gray-400 text-xs">
                    <option disabled selected value> -- Chọn sản phẩm -- </option>
                    <option v-for="product in productRetails" :key="product.id" :value="product.id">{{ product.name }} ({{ product.available_quantity }})</option>
                  </select>
                  </div>
              </div>
              <div class="leading-[42px]">{{ selectedProducts[`product_${rowNumber}`] ? selectedProducts[`product_${rowNumber}`].unit : '&#8203;' }}</div>
              <div :class="`${ selectedProducts[`product_${rowNumber}`] ? 'p-1' : 'leading-[42px]'}`">
                {{ selectedProducts[`product_${rowNumber}`] ? '' : '&#8203;' }}
                <input
                  v-if="selectedProducts[`product_${rowNumber}`]"
                  @input="(e) => handleUpdateQuantity(e.target.value, rowNumber)"
                  min="1"
                  :max="selectedProducts[`product_${rowNumber}`] ? selectedProducts[`product_${rowNumber}`].available : 1"
                  :value="selectedProducts[`product_${rowNumber}`] ? selectedProducts[`product_${rowNumber}`].quantity : 1"
                  type="number"
                  class="pl-1 py-1 pr-1 rounded w-full border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0" />
              </div>
              <div class="leading-[42px]">
                {{ selectedProducts[`product_${rowNumber}`] ? '' : '&#8203;' }}
                <i class="fa fa-trash-o text-xl cursor-pointer"
                  :class="{ 'hide': rowNumber === 1 }"
                  aria-hidden="true"
                  @click="handleRemoveRow(rowNumber)" />
              </div>
            </div>
          </div>
          <p v-if="showProductError" class="mt-2 text-red-500 font-semibold">Hãy chọn sản phẩm</p>
        </div>

        <div>
          <div class="grid grid-cols-3 gap-4 mb-3 pb-2 border-b border-gray-400">
            <p class="font-semibold text-sm leading-7">Địa chỉ nhận hàng</p>
            <div class="flex justify-end items-center">
              <input v-model="addressType" id="default" value="default" type="radio" class="focus:outline-none focus:ring-0" />
              <label for="default" class="pl-2 m-0 select-none">Hiện tại</label>
            </div>
            <div class="flex justify-end items-center">
              <input v-model="addressType" id="change" value="change" type="radio" class="focus:outline-none focus:ring-0" />
              <label for="change" class="pl-2 m-0 select-none">Thay đổi</label>
            </div>
          </div>
          <div class="grid grid-cols-3 gap-2">
            <div>
              <p class="text-sm font-semibold mb-1">Tỉnh/TP</p>
              <FilterableDropdown
                v-model="address.city"
                :options="data"
                :disabled="disabledAdressEdit"
                :normalizeValue="filterFunction"
                placeholder="Tỉnh/TP"
                optionLabel="Name"
                optionValue="Name" />
            </div>
            <div>
              <p class="text-sm font-semibold mb-1">Quận/Huyện</p>
              <FilterableDropdown
                v-model="address.district"
                :options="districtsOfCity"
                :disabled="disabledAdressEdit"
                :normalizeValue="filterFunction"
                placeholder="Quận/Huyện"
                optionLabel="Name"
                optionValue="Name" />
            </div>
            <div>
              <p class="text-sm font-semibold mb-1">Xã/Phường</p>
              <FilterableDropdown
                v-model="address.ward"
                :options="wardsOfDistrict"
                :disabled="disabledAdressEdit"
                :normalizeValue="filterFunction"
                placeholder="Xã/Phường"
                optionLabel="Name"
                optionValue="Name" />
            </div>
          </div>
          <div class="!mt-[18px]">
            <p class="text-sm font-semibold mb-1">
              Địa chỉ chi tiết
              <span v-if="showAddressError && address.detail === ''" class="text-red-600 pl-2">
                Hãy nhập địa chỉ chi tiết
              </span>
            </p>
            <textarea v-model="address.detail" :disabled="disabledAdressEdit"
              class="w-full resize-none rounded bg-white disabled:!bg-gray-200 disabled:text-gray-600 focus:border-gray-400 border-gray-400 px-2 py-1 text-sm focus:outline-none focus:ring-0" rows="5"></textarea>
          </div>
        </div>

        <div>
          <div class="flex items-center gap-4 pb-1 mb-3 border-b border-gray-400">
            <p class="text-sm font-semibold mb-1 w-20">SĐT phụ</p>
            <input v-model="subPhoneNumber" type="text" placeholder="Số điện thoại" class="w-full h-8 rounded-sm border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0" />
          </div>
          <div class="mt-3">
            <p class="text-sm font-semibold mb-1">Ghi chú đơn hàng (giờ nhận...)</p>
            <textarea v-model="note" class="w-full resize-none rounded bg-white disabled:!bg-gray-100 focus:border-gray-400 border-gray-400 px-2 py-1 text-sm focus:outline-none focus:ring-0" rows="9"></textarea>
          </div>
        </div>
      </div>
      <div class="mx-3 border-b border-gray-400"></div>
      <div class="flex justify-end items-center pr-3 py-6">
        <button class="rounded-md bg-orange-600 text-white relative font-medium px-3 py-2"
          :class="{
            '!cursor-wait': isLoading
          }"
          @click="onCreateOrder"
        >
          <span v-if="isLoading" class="absolute w-full h-full left-0 top-0 flex items-center justify-center bg-gray-400/40">
            <SpinnerIcon class="!m-0" />
          </span>
          Tạo đơn
        </button>
      </div>
    </div>
  </div>
</template>

