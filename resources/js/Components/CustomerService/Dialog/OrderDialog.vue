<script setup>
import { computed, inject, reactive, ref, watch } from 'vue';

import FilterableDropdown from '@/Components/CustomerService/FilterableDropdown.vue';

const { productRetails, data, cities, districts, wards } = inject('ORDER_PACKAGE_PAGE');

const orderForm = reactive({
  products: {},
});

// Product picker
const selectedProducts = ref({});
const productRows = ref([1]);
const handleAddRow = () => {
  console.log(selectedProducts.value);
  productRows.value.push(productRows.value[productRows.value.length - 1] + 1);
  console.log(selectedProducts.value);
}
const handleRemoveRow = (rowNumber) => {
  productRows.value = productRows.value.filter((number) => number !== rowNumber);
  delete selectedProducts.value[`product_${rowNumber}`];
}
const handleChangeProduct = (productId, rowNumber) => {
  const product = productRetails.find((product) => product.id == productId);
  if (product) {
    selectedProducts.value = {
      ...selectedProducts.value,
      [`product_${rowNumber}`]: {
        name: product.name,
        unit: product.unit,
        quantity: 1,
      }
    };
  }
}
const handleUpdateQuantity = (quantity, rowNumber) => {
  selectedProducts.value[`product_${rowNumber}`].quantity = quantity;
}

// Address picker
const addressType = ref('default');
const address = reactive({
  city: null,
  district: null,
  ward: null,
});
watch(() => address.city, () => {
  address.district = null;
  address.ward = null;
});
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
const filterFunction = (value) => value.replace(/Tỉnh |Thành phố |Quận |Huyện |Thị xã |Phường |Xã |Thị trấn /g, '')
</script>

<template>
  <div class="fixed w-screen h-screen bg-black/40 top-0 left-0 z-50 overflow-hidden flex items-center justify-center">
    <div class="bg-white shadow-lg w-[calc(100vw_-_318px)] rounded-lg">
      <div class="grid grid-cols-3 p-3 gap-10">
        <div>
          <div class="flex items-center justify-between border-b border-gray-400 pb-2 mb-3">
            <p class="font-semibold text-sm">Lên đơn cho hợp đồng</p>
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
                    <option v-for="product in productRetails" :key="product.id" :value="product.id">{{ product.name }}</option>
                  </select>
                  </div>
              </div>
              <div class="leading-[42px]">{{ selectedProducts[`product_${rowNumber}`] ? selectedProducts[`product_${rowNumber}`].unit : '&#8203;' }}</div>
              <div :class="`${ selectedProducts[`product_${rowNumber}`] ? 'p-1' : 'leading-[42px]'}`">
                {{ selectedProducts[`product_${rowNumber}`] ? '' : '&#8203;' }}
                <input
                  v-if="selectedProducts[`product_${rowNumber}`]"
                  @input="(e) => handleUpdateQuantity(e.target.value, rowNumber)"
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
                :onChange="() => {}"
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
                :onChange="() => {}"
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
                :onChange="() => {}"
                :normalizeValue="filterFunction"
                placeholder="Xã/Phường"
                optionLabel="Name"
                optionValue="Name" />
            </div>
          </div>
          <div class="!mt-[18px]">
            <p class="text-sm font-semibold mb-1">Địa chỉ chi tiết</p>
            <textarea :disabled="true" class="w-full resize-none rounded bg-white disabled:!bg-gray-100 focus:border-gray-400 border-gray-400 px-2 py-1 text-sm focus:outline-none focus:ring-0" rows="5"></textarea>
          </div>
        </div>

        <div>
          <div class="flex items-center gap-4 pb-1 mb-3 border-b border-gray-400">
            <p class="text-sm font-semibold mb-1 w-20">SĐT phụ</p>
            <input type="text" placeholder="Số điện thoại" class="w-full h-8 rounded-sm border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-0" />
          </div>
          <div class="mt-3">
            <p class="text-sm font-semibold mb-1">Ghi chú đơn hàng (giờ nhận...)</p>
            <textarea class="w-full resize-none rounded bg-white disabled:!bg-gray-100 focus:border-gray-400 border-gray-400 px-2 py-1 text-sm focus:outline-none focus:ring-0" rows="9"></textarea>
          </div>
        </div>
      </div>
      <div class="mx-3 border-b border-gray-400"></div>
      <div class="flex justify-end items-center pr-3 py-6">
        <button class="rounded-md bg-orange-600 text-white font-medium px-3 py-2 mb-2">
          Tạo đơn
        </button>
      </div>
    </div>
  </div>
</template>

