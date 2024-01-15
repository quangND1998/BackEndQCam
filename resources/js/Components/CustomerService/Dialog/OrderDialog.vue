<script setup>
import { inject, reactive, ref } from 'vue';

import FilterableDropdown from '@/Components/CustomerService/FilterableDropdown.vue';

const { productRetails, cities } = inject('ORDER_PACKAGE_PAGE');

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
const city = ref();
</script>

<template>
  <div class="bg-white shadow-lg w-[calc((100vw_-_288px)/18*14.2)] fixed right-6 !top-[54px]">
    <div class="grid grid-cols-3 p-3 gap-6">
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
          <p class="font-semibold text-sm">Địa chỉ nhận hàng</p>
          <div class="flex justify-end items-center">
            <input v-model="addressType" id="default" value="default" type="radio" class="focus:outline-none focus:ring-0" />
            <label for="default" class="pl-2 m-0 select-none">Hiện tại</label>
          </div>
          <div class="flex justify-end items-center">
            <input v-model="addressType" id="change" value="change" type="radio" class="focus:outline-none focus:ring-0" />
            <label for="change" class="pl-2 m-0 select-none">Thay đổi</label>
          </div>
        </div>
        <div>
          <FilterableDropdown
            v-model="city"
            :options="cities"
            :onChange="() => {}"
            placeholder="Chọn tỉnh thành"
            optionLabel="Name"
            optionValue="Name" />
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
</template>
