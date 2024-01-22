<script setup>
import { computed, inject, onMounted, reactive, ref, toRaw, watch } from 'vue';

import FilterableDropdown from '@/Components/CustomerService/FilterableDropdown.vue';
import useQuery, { CUSTOMER_SERVICE_API_MAKER } from '../composables/useQuery';
import SpinnerIcon from '@/Components/CustomerService/SpinnerIcon.vue';

const { productRetails, data, cities, districts, customer, customerId, addOrder, updateOrder } = inject('ORDER_PACKAGE_PAGE');

const props = defineProps({
  orderPackage: Object,
  deliveryNo: Number,
  order: Object,
});

const emit = defineEmits(['onCloseDialog']);

const isRetailOrder = computed(() => props.deliveryNo === undefined);

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
  const product = productRetails.value.find((product) => product.id == productId);
  if (product) {
    showProductError.value = false;
    selectedProducts.value = {
      ...selectedProducts.value,
      [`product_${rowNumber}`]: {
        id: product.id,
        name: product.name,
        unit: product.unit,
        price: product.price,
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
const addressType = ref(isRetailOrder.value ? 'customer' : 'default');
const showAddressError = ref(false);
const address = reactive({
  city: !isRetailOrder.value ? props.order?.city || customer.city : '',
  district: !isRetailOrder.value ? props.order?.district || customer.district : '',
  ward: !isRetailOrder.value ? props.order?.wards ||customer.wards : '',
  detail: !isRetailOrder.value ? props.order?.address ||customer.address : ''
});
const disabledAdressEdit = computed(() => addressType.value === 'default');
const districtsOfCity = computed(() => {
  if (address.city && cities.value[encodeURIComponent(address.city)]) {
    return cities.value[encodeURIComponent(address.city)].Districts || [];
  }
  return [];
});
const wardsOfDistrict = computed(() => {
  if (address.district && districts.value[encodeURIComponent(address.district)]) {
    return districts.value[encodeURIComponent(address.district)].Wards || [];
  }
  return [];
});
const filterFunction = (value) => value.replace(/Tỉnh |Thành phố |Quận |Huyện |Thị xã |Phường |Xã |Thị trấn /g, '');
watch(() => address.city, () => {
  address.district = null;
  address.ward = null;
});
watch(() => address.district, () => {
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

const { isLoading, executeQuery: executeCreate } = useQuery(
  CUSTOMER_SERVICE_API_MAKER.CREATE_ORDER(customerId),
  undefined,
  (data) => {
    addOrder(data.order);
  },
  'Tạo đơn hàng thành công'
);
const { isLoading: isUpdating, executeQuery: executeUpdate } = useQuery(
  CUSTOMER_SERVICE_API_MAKER.UPDATE_ORDER(customerId),
  undefined,
  (data) => {
    updateOrder(data.order);
  },
  'Cập nhật đơn hàng thành công'
);
const onCreateOrder = () => {
  if (isLoading.value || isUpdating.value || isDisable.value || isCreateRetailOrder.value) return;


  const order = {
    products: Object.values(selectedProducts.value).map((product) => ({
      id: product.id,
      quantity: product.quantity,
    })),
    address: toRaw(address),
    note: note.value,
    subPhoneNumber: subPhoneNumber.value,
    deliveryNo: props.deliveryNo
  }

  const haveError = false;
  if (order.products.length === 0) {
    showProductError.value = true
    haveError = true;
  }
  if (!order.address.detail || !order.address.city || !order.address.district || !order.address.ward) {
    showAddressError.value = true;
    haveError = true;
  }

  if (haveError) return;

  if (isRetailOrder.value) onCreateRetailOrder();
  if (!props.order) {
    executeCreate(undefined, { ...order, productServiceOwnerId: props.orderPackage.product_service_owner.id });
  } else {
    executeUpdate({ orderId: props.order.id }, {...order, productServiceOwnerId: props.order.product_service_owner_id })
  }
}

const isDisable = computed(() => !!(props.order && props.order.shipper_id));

const formatMoney = (number) => {
  return number ? number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") : 0
};

const retailOrderError = reactive({
  other_code: false,
  phone_number: false,
})
const priceForm = reactive({
  vat: 0,
  discount_deal: 0,
  shipping_fee: 0,
  payment_method: 0,
  order_code: '',
});
watch(() => priceForm.order_code, () => {
  retailOrderError.other_code = false;
});
watch(() => subPhoneNumber.value, () => {
  retailOrderError.phone_number = false;
});
const productPrice = computed(() => {
  return Object.values(selectedProducts.value).reduce((total, product) => total + product.price * product.quantity, 0) || 0;
});
const changePaymentMethod = (method) => {
  priceForm.payment_method = method;
}
const { isLoading: isCreateRetailOrder, executeQuery: createRetailOrder } = useQuery(
  CUSTOMER_SERVICE_API_MAKER.CREATE_RETAIL_ORDER(),
  undefined,
  () => {
    emit('onCloseDialog');
  },
  'Tạo đơn bán lẻ thành công'
);
const onCreateRetailOrder = () => {
  const order = {
    products: Object.values(selectedProducts.value).map((product) => ({
      id: product.id,
      quantity: product.quantity,
      price: product.price,
    })),
    address: toRaw(address),
    note: note.value,
    subPhoneNumber: subPhoneNumber.value,
    otherPayment: toRaw(priceForm),
  }

  const haveError = false;
  if (isRetailOrder.value) {
    if (!priceForm.order_code) {
      retailOrderError.other_code = true;
      haveError = true;
    }
    if (!subPhoneNumber.value) {
      retailOrderError.phone_number = true;
      haveError = true;
    }
  }

  if (haveError) return;

  createRetailOrder(undefined, order);
}

onMounted(() => {
  if (!props.order) return;
  address.city = props.order.city;
  address.district = props.order.district;
  address.ward = props.order.wards;
  address.detail = props.order.address;
  subPhoneNumber.value = props.order.phone_number;
  note.value = props.order.notes;
  productRows.value = [];
  props.order.order_items.forEach((item, index) => {
    productRows.value.push(index + 1);
    selectedProducts.value[`product_${index + 1}`] = {
      id: item.product.id,
      name: item.product.name,
      unit: item.product.unit,
      quantity: item.quantity,
      available: item.product.available_quantity + item.quantity,
    }
  });
})
</script>

<template>
  <div class="fixed w-screen h-screen bg-black/40 top-0 left-0 z-50 overflow-hidden flex items-center justify-center">
    <div class="bg-white shadow-lg w-[calc(100vw_-_150px)] rounded-xl">
      <div class="flex items-center justify-between rounded-t-lg bg-orange-500 pr-3 pl-4 py-2">
        <p v-if="!isRetailOrder" class="font-semibold text-white">Lên đơn cho hợp đồng {{ orderPackage?.idPackage }}</p>
        <p v-else class="font-semibold text-white">Lên đơn bán lẻ</p>
        <i class="fa fa-times text-2xl cursor-pointer text-white" aria-hidden="true" @click="emit('onCloseDialog')"/>
      </div>
      <div class="grid grid-cols-12 p-3 pt-4 gap-10">
        <div class="col-span-6">
          <div class="flex items-center justify-between border-b border-gray-400 pb-2 mb-3">
            <p v-if="!isRetailOrder" class="font-semibold text-sm">Lần: {{ deliveryNo }}</p>
            <div v-else class="flex items-center">
              <p class="font-semibold text-sm mr-2 required">Nhập mã phiếu</p>
              <input v-model="priceForm.order_code" type="text" class="px-2 w-36 h-8 rounded-sm border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0"
                :class="{
                  '!border-red-600': retailOrderError.other_code && priceForm.order_code === ''
                }"
              />
            </div>
              <button
                :disabled="isDisable"
                class="disabled:!bg-gray-400 rounded-full bg-sky-600 text-white font-medium px-2 py-1 text-sm"
                :class="{
                  'cursor-not-allowed': isDisable,
                }"
                @click="handleAddRow"
              >
                Thêm
              </button>
          </div>
          <div class="border-x border-gray-400 ">
            <div class="grid grid-cols-12 bg-gray-400 divide-x divide-white leading-6 text-sm font-medium text-white text-center border-x border-gray-400">
              <div class="col-span-1">STT</div>
              <div
                class="col-span-6"
                :class="{
                  '!col-span-4': isRetailOrder
                }"
              >
                Loại quà
              </div>
              <div
                class="col-span-2"
                :class="{
                  '!col-span-1': isRetailOrder
                }"
              >
                DVT
              </div>
              <div
                class="col-span-2"
                :class="{
                  '!col-span-1': isRetailOrder
                }"
              >
                SL
              </div>
              <div v-if="isRetailOrder" class="col-span-2">Gía</div>
              <div v-if="isRetailOrder" class="col-span-2">Tổng</div>
              <div class="col-span-1"></div>
            </div>
            <div class="product-detail max-h-[calc(100vh-600px)] overflow-y-auto">
              <div v-for="(rowNumber, index) in productRows" :key="rowNumber" class="relative grid grid-cols-12 divide-x divide-gray-400 border-b border-gray-400 text-sm text-center items-center">
                <div v-if="isDisable" class="absolute w-full h-full left-0 top-0 bg-gray-400/30 cursor-not-allowed"/>
                <div class="col-span-1">{{ index + 1 }}</div>
                <div
                  class="col-span-6"
                  :class="{
                    '!col-span-4': isRetailOrder
                  }"
                >
                  <div class="p-1">
                    <select :value="selectedProducts[`product_${rowNumber}`] ? selectedProducts[`product_${rowNumber}`].id : undefined" @input="(e) => handleChangeProduct(e.target.value, rowNumber)" class="w-full rounded pl-1 focus:outline-none focus:ring-0 focus:border-gray-400 border-gray-400 text-xs">
                      <option disabled selected value> -- Chọn sản phẩm -- </option>
                      <option v-for="product in productRetails" :key="product.id" :value="product.id">{{ product.name }} ({{ product.available_quantity }})</option>
                    </select>
                    </div>
                </div>
                <div class="col-span-2 leading-[42px]"
                  :class="{
                    '!col-span-1': isRetailOrder
                  }"
                >
                  {{ selectedProducts[`product_${rowNumber}`] ? selectedProducts[`product_${rowNumber}`].unit : '&#8203;' }}
                </div>
                <div
                  class="col-span-2 p-1"
                  :class="{
                    '!col-span-1': isRetailOrder,
                    'leading-[42px] !p-0': !selectedProducts[`product_${rowNumber}`],
                  }"
                >
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
                <div v-if="isRetailOrder" class="col-span-2 leading-[42px]">
                  {{ selectedProducts[`product_${rowNumber}`] ? formatMoney(selectedProducts[`product_${rowNumber}`].price) : 0 }}
                </div>
                <div v-if="isRetailOrder" class="col-span-2 leading-[42px]">
                  {{
                    selectedProducts[`product_${rowNumber}`]
                      ? formatMoney(selectedProducts[`product_${rowNumber}`].price * selectedProducts[`product_${rowNumber}`].quantity)
                      : 0
                  }}
                </div>
                <div class="leading-[42px] col-span-1">
                  {{  productRows.length === 1 ? '&#8203;' : '' }}
                  <i class="fa fa-trash-o text-xl cursor-pointer"
                    :class="{ 'hide': productRows.length === 1 }"
                    aria-hidden="true"
                    @click="handleRemoveRow(rowNumber)" />
                </div>
              </div>
            </div>
          </div>
          <div v-if="isRetailOrder" class="grid grid-cols-12 mt-3">
            <div class="col-span-6">
              <p class="font-semibold !leading-8 text-sm mb-1">Phương thức thanh toán</p>
              <div class="text-sm font-medium">
                <button
                  class="w-24 leading-6 py-1 border !border-gray-600 rounded-l-lg"
                  :class="{
                    '!bg-orange-600 !text-white !border-orange-600': priceForm.payment_method === 0,
                    '!border-r-orange-600': priceForm.payment_method === 1,
                  }"
                  @click="changePaymentMethod(0)"
                >
                  Tiền mặt
                </button>
                <button
                  class="w-24 leading-6 py-1 border !border-gray-600 !border-x-0"
                  :class="{
                    '!bg-orange-600 !text-white !border-orange-600': priceForm.payment_method === 1
                  }"
                  @click="changePaymentMethod(1)"
                >
                  Banking
                </button>
                <button
                  class="w-24 leading-6 py-1 border !border-gray-600 rounded-r-lg"
                  :class="{
                    '!bg-orange-600 !text-white !border-orange-600': priceForm.payment_method === 2,
                    '!border-l-orange-600': priceForm.payment_method === 1,
                  }"
                  @click="changePaymentMethod(2)"
                >
                  Payoo
                </button>
              </div>
            </div>
            <div class="flex justify-end flex-col col-span-6">
              <div class="grid grid-cols-6 leading-8">
                <div class="col-span-3 font-semibold !leading-8 text-sm flex items-center justify-between">
                  <span>VAT (%)</span>
                  <input v-model="priceForm.vat" type="number" min="0" max="100" class="px-1 w-18 h-7 rounded-sm border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0 text-sm font-normal" />
                </div>
                <div class="col-span-2 text-right">
                  {{ formatMoney(productPrice * (priceForm.vat / 100)) }}
                </div>
                <div />
              </div>
              <div class="grid grid-cols-6 leading-8">
                <div class="col-span-3 font-semibold !leading-8 text-sm flex items-center justify-between">
                  <span>Vận chuyển</span>
                  <input v-model="priceForm.shipping_fee" type="number" min="0" max="100" class="px-1 w-18 h-7 rounded-sm border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0 text-sm font-normal" />
                </div>
                <div class="col-span-2 text-right">
                  {{  formatMoney(priceForm.shipping_fee) }}
                </div>
                <div />
              </div>
              <div class="grid grid-cols-6 leading-8">
                <div class="col-span-3 font-semibold !leading-8 text-sm flex items-center justify-between">
                  <span>Ưu đãi (%)</span>
                  <input v-model="priceForm.discount_deal" type="number" min="0" max="100" class="px-1 w-18 h-7 rounded-sm border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0 text-sm font-normal" />
                </div>
                <div class="col-span-2 text-right">
                  {{ formatMoney(productPrice * (priceForm.discount_deal / 100)) }}
                </div>
                <div />
              </div>
              <div class="grid grid-cols-6 leading-8">
                <div class="col-span-3 font-semibold !leading-8 text-sm">Tổng cộng</div>
                <div class="col-span-2 text-right">
                  {{ formatMoney(productPrice + productPrice * (priceForm.vat / 100) - productPrice * (priceForm.discount_deal / 100) + priceForm.shipping_fee)  }}
                </div>
                <div />
              </div>
            </div>
          </div>

          <p v-if="showProductError" class="mt-2 text-red-500 font-semibold">Hãy chọn sản phẩm</p>
        </div>

        <div class="col-span-3">
          <div class="grid grid-cols-3 gap-4 mb-3 pb-2 border-b border-gray-400">
            <p
              class="font-semibold text-sm leading-7"
              :class="{
                'col-span-3': isRetailOrder
              }"
            >
              Địa chỉ nhận hàng
            </p>
            <div v-if="!isRetailOrder" class="flex justify-end items-center">
              <input :disabled="isDisable" v-model="addressType" id="default" value="default" type="radio" class="disabled:cursor-not-allowed focus:outline-none focus:ring-0 disabled:bg-gray-400 disabled:hover:bg-gray-400" />
              <label for="default" class="pl-2 m-0 select-none">Hiện tại</label>
            </div>
            <div v-if="!isRetailOrder" class="flex justify-end items-center">
              <input :disabled="isDisable" v-model="addressType" id="change" value="change" type="radio" class="disabled:cursor-not-allowed focus:outline-none focus:ring-0 disabled:bg-gray-400 disabled:hover:bg-gray-400" />
              <label for="change" class="pl-2 m-0 select-none">Thay đổi</label>
            </div>
          </div>
          <div class="grid grid-cols-3 gap-2">
            <div>
              <p class="text-sm font-semibold mb-1 required">Tỉnh/TP</p>
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
              <p class="text-sm font-semibold mb-1 required">Quận/Huyện</p>
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
              <p class="text-sm font-semibold mb-1 required">Xã/Phường</p>
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
            <p class="text-sm font-semibold mb-1 required">
              Địa chỉ chi tiết
              <span v-if="showAddressError && address.detail === ''" class="text-red-600 pl-2">
                Hãy nhập địa chỉ chi tiết
              </span>
            </p>
            <textarea v-model="address.detail" :disabled="disabledAdressEdit || isDisable"
              class="w-full resize-none rounded bg-white disabled:!bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-600 focus:border-gray-400 border-gray-400 px-2 py-1 text-sm focus:outline-none focus:ring-0" rows="5"></textarea>
          </div>
        </div>

        <div class="col-span-3">
          <div class="flex items-center gap-4 pb-1 mb-3 border-b border-gray-400">
            <p
              class="text-sm font-semibold mb-1 w-20"
              :class="{
                'required': isRetailOrder
              }"
            >
              SĐT {{ isRetailOrder ? '' : 'phụ' }}
            </p>
            <input :disabled="isDisable" v-model="subPhoneNumber" type="text" placeholder="Số điện thoại" class="disabled:cursor-not-allowed w-full h-8 rounded-sm border-gray-400 disabled:!bg-gray-200 disabled:text-gray-600 focus:border-gray-400 focus:outline-none focus:ring-0"
              :class="{
                '!border-red-600': retailOrderError.other_code && priceForm.order_code === '' && isRetailOrder
              }"
            />
          </div>
          <div class="mt-3">
            <p class="text-sm font-semibold mb-1">Ghi chú đơn hàng (giờ nhận...)</p>
            <textarea :disabled="isDisable" v-model="note" class="disabled:cursor-not-allowed w-full resize-none rounded bg-white disabled:!bg-gray-200 disabled:text-gray-600 focus:border-gray-400 border-gray-400 px-2 py-1 text-sm focus:outline-none focus:ring-0" rows="9"></textarea>
          </div>
        </div>
      </div>
      <div class="mx-3 border-b border-gray-400"></div>
      <div class="flex justify-end items-center pr-3 py-6">
        <button class="rounded-md bg-orange-600 text-white relative font-medium px-3 py-2 disabled:bg-gray-400 disabled:cursor-not-allowed"
          :disabled="isDisable"
          :class="{
            '!cursor-wait': isLoading || isUpdating || isCreateRetailOrder
          }"
          @click="onCreateOrder"
        >
          <span v-if="isLoading || isUpdating || isCreateRetailOrder" class="absolute w-full h-full left-0 top-0 flex items-center justify-center bg-gray-400/40">
            <SpinnerIcon class="!m-0" />
          </span>
          {{ order ? 'Cập nhật' : 'Tạo đơn' }}
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>

.product-detail {
  --sb-track-color: #9ca3af;
  --sb-thumb-color: #ea580c;
  --sb-size: 6px;

  scrollbar-color: var(--sb-thumb-color) var(--sb-track-color);
}

.product-detail::-webkit-scrollbar {
  width: var(--sb-size)
}

.product-detail::-webkit-scrollbar-track {
  background: var(--sb-track-color);
  border-radius: 1px;
}

.product-detail::-webkit-scrollbar-thumb {
  background: var(--sb-thumb-color);
  border-radius: 1px;

  }
</style>
