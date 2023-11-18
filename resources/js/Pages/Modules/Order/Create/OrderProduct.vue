<script setup>
import { computed, ref, inject, reactive,toRef, toRefs, onMounted } from "vue";

import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";
import CardBoxModal from "@/Components/CardBoxModal.vue";


import {
    mdiEye,
    mdiAccountLockOpen,
    mdiPlus,
    mdiContentSaveMove,
    mdiCreditCardSettingsOutline,
    mdiContentCopy,
    mdiImport,
    mdiTrashCanOutline

} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

// import Multiselect from '@vueform/multiselect'
import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import MazInputNumber from 'maz-ui/components/MazInputNumber'
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    state: null,
    number: null,
    price: null,
    count: null
});
const product = computed(()=>{
    return props.products.find((e)=> e.id == product_selectd.value )
});

const quantity= ref(1)
const product_selectd= toRef(props.products.length>0? props.products[0].id :null)
const cart_selected = ref([])
const props = defineProps({
    products: Array,
    user: Object,
    cart: Object,
    total_price: Number,
    vat:Number,
    discount_deal:Number,
    shipping_fee:Number,
    payment_method:String,
    type:String,
    sub_total:Number
});
const  cart = toRef(props.cart);
const  total_price = toRef(props.total_price);
const  sub_total = toRef(props.sub_total);
onMounted(()=>{
   cart.value.forEach(element => {
      this.form.skus.push({
        id: element.id,
        price: element.price,
        stock: element.stock,
        name: element.name,
        barcode: element.barcode
      });
    });
    
})
const selectAllCart = computed({
    get() {
        return props.cart
            ? cart_selected.value.length == props.cart
            : false;
    },
    set(value) {
        var selected = [];

        if (value) {
            for (const [key, value] of Object.entries(props.cart)) {
                selected.push(value.id);
            }
        }
        cart_selected.value = selected;
        
    }
});
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);


const numberValue = ref([])
const date = ref(new Date());
const changeProduct =(event)=>{

    product_selectd.value = event.target.value
}
const addToCart=()=>{
    if(product.value ==null || props.user ==null){
        swal.fire({
                title: "Lỗi?",
                text: "Chưa có thông tin khách hàng!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                }).then((result) => {
            if (result.isConfirmed) {
                  return 
                }
            });
    }
    else{
        axios.post('/admin/orders/addToCart', {product:product.value , quantity:quantity.value}).then(res=>{
            console.log( res.data.cart)
            cart.value = res.data.cart
            total_price.value = res.data.total_price
            sub_total.value = res.data.sub_total
        }).catch(err=>{

        })
    }
 
}
const updateCart=(product, value)=>{
      // quantity_cart:,
      this.$refs[`quantity${product.id}`][0].value =
        parseInt(this.$refs[`quantity${product.id}`][0].value) +
        parseInt(value);
    //   let query = {
    //     quantity_cart: this.$refs[`quantity${product.id}`][0].value,
    //     product_id: product.id
    //   };
    //   // this.$store.dispatch("stores/updateCart", query);
    //   if (
    //     this.$refs[`quantity${product.id}`][0].value == 0 ||
    //     this.$refs[`quantity${product.id}`][0].value < 0 ||
    //     query.quantity_cart == 0
    //   ) {
    //     this.$swal("Error, Some values are missing.", {
    //       icon: "error"
    //     });
    //   } else {
    //     axios
    //       .post("/admin/cart/updateCart", query)
    //       .then(response => {
    //         // console.log(response)
    //         this.total_price = response.data.total_price;
    //         // console.log(response.data.item);

    //         this.cart[product.id] = response.data.item;
    //       })
    //       .catch(error => {});
    //   }
    }
const    updateCartInput =(product)=> {
    
      let query = {
        quantity_cart: this.$refs[`quantity${product.id}`][0].value,
        product_id: product.id
      };
      // this.$store.dispatch("stores/updateCart", query);
    //   console.log(query);
    //   if (
    //     parseInt(this.$refs[`quantity${product.id}`][0].value) == 0 ||
    //     this.$refs[`quantity${product.id}`][0].value < 0
    //   ) {
    //     this.$swal("Error, Số lượng không được nhỏ hơn hoặc bằng 0 .", {
    //       icon: "error"
    //     });
    //   } else {
    //     axios
    //       .post("/admin/cart/updateCart", query)
    //       .then(response => {
    //         // console.log(response)
    //         this.total_price = response.data.total_price;
    //         // console.log(response.data.item);

    //         this.cart[product.id] = response.data.item;
    //       })
    //       .catch(error => {});
    //   }
    }
</script>
<template>
 

    <div class="min-[320x]:w-full grid grid-cols-3 gap-4">
       
        <div class=" col-span-2 mt-2 w-full">
          
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-5 mt-4">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                #
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Số lượng
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Giá
                            </th>

                            <th scope="col" class="px-6 py-3">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b ">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                <span></span>
                            </th>
                            <td class="px-6 py-4 ">
                                <select id="countries" 
                                    @change="changeProduct($event)" v-model="product_selectd"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-44 p-2.5 ">
                                    <option v-for="(product, index) in products" :key="index" :value="product.id">{{
                                        product.name }}</option>

                                </select>
                            </td>
                            <td class="px-6 py-4">
                              
                                    <MazInputNumber
                                        v-model="quantity"
                                        label="Enter number"
                                        :min="1"
                                        :max="10000"
                                        :step="1"
                                        size="md"
                                        color="secondary"
                                    />
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" readonly  :value="product?.price"
                                    class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 hover:border-gray-500 w-28">
                            </td>
                            <!-- <td class="px-6 py-4">
                                <input type="number"
                                    class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 hover:border-gray-500 w-32">
                            </td> -->
                            <td class="px-6 py-4">

                                <BaseButton color="info" @click="addToCart()" class="bg-btn_green text-white p-2 hover:bg-[#008000]"
                                    :icon="mdiPlus" small label="Thêm sản phẩm" />

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="bg-white rounded-lg p-3">
                <div class="flex justify-between">
                    <div>
                        <!-- <font-awesome-icon :icon="['fas', 'cart-shopping']" class="mt-1" /> -->
                        <span class="text-xl font-semibold ml-2">Giỏ hàng</span>
                    </div>
                    <button v-if="cart" class="flex text-red-600 mx-3 text-sm">
                        <BaseIcon :path="mdiTrashCanOutline" class="text-red-600 hover:text-red-700 mx-1 "></BaseIcon>
                        Hủy đơn hàng
                    </button>
                </div>
                <hr />
                <div class="flex my-3">
                    <input v-if="cart_selected" id="default-checkbox" type="checkbox" v-model="selectAllCart"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                    <div>
                        <button v-if="cart_selected.length > 0" class="flex text-red-600 mx-3 text-sm">
                           <BaseIcon :path="mdiTrashCanOutline" class="text-red-600 hover:text-red-700"></BaseIcon>Xóa
                            <span>({{ cart_selected.length }})</span> sản phẩm đã chọn
                        </button>
                    </div>
                </div>

                <hr />
                <div v-for="(item, index) in cart" :key="index" class="flex my-3">
                    <div class="flex">
                        <input id="default-checkbox" type="checkbox" v-model="cart_selected" :value="item.id"
                            class="icon_checkbox w-4 h-4 mt-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />

                    </div>
                    <img v-if="item.images && item.images.length > 0 && item.images[0].image" :src="item.images[0].image"
                        class="w-16 h-16 object-cover ml-3" alt="">
                    <div class="flex  justify-between items-center w-full">
                        <div class=" ml-4">
                            <h3>{{ item.name }}</h3>
                            <p class="text-red-700 text-base">{{ formatPrice(item.price) }} </p>
                            <div class="buttons_added">
                                <!-- <MazInputNumber v-model="numberValue[item.id]" label="Enter number" :min="5" :max="10000" :step="1"
                                    size="xs" color="secondary" /> -->
                                     <div class="buttons_added">
                        <input
                          class="minus is-form"
                          type="button"
                          value="-"
                          @click="updateCart(item, -1)"
                        />
                        <input
                          aria-label="quantity"
                          class="input-qty_create"
                          :ref="`quantity${item.id}`"
                        
                          :value="item.quantity"
                          max="100"
                          min="0"
                          name
                          type="number"
                        />
                        <input
                          class="plus is-form"
                          type="button"
                          value="+"
                          @click="updateCart(item, 1)"
                        />
                        
                      </div>

                            </div>
                        </div>
                        <BaseIcon :path="mdiTrashCanOutline" class="text-red-600 hover:text-red-700"></BaseIcon>

                    </div>

                </div>

                <hr />
                <div class="mt-5 pt-5">
                    <hr />
                    <div class="flex justify-between my-2">
                        <p>Tạm tính:</p>
                        <h3 class="text-xl text-red-700 font-semibold"></h3>
                    </div>

                    <div class="text-center">
                        <h3 class="text-gray-500 text-sm">Lưu ý: Số tiền trên chưa bao gồm phí ship</h3>
                    </div>
                </div>
            </div>

            <!-- <div class="min-[320px]:block md:flex w-full">
                <div class="min-[320px]:w-full md:w-1/2">
                    <BaseButton color="info" @click="
                        isModalActive = true;" class="bg-btn_green text-white p-2 hover:bg-[#008000]" :icon="mdiPlus"
                        small label="Thêm sản phẩm" />
                </div>

                <div class="min-[320px]:w-full md:w-1/2 min-[320px]:mt-3 md:mt-0">
                    <div class="flex justify-between my-2">
                        <p class="text-sm text-[#686868] font-bold">Tổng</p>
                        <p class="text-sm text-[#686868] font-bold">{{ formatPrice(total_price) }}đ</p>
                    </div>
                    <div class="flex justify-between my-2">
                        <p class="text-sm text-[#686868] font-bold">VAT(x%)</p>
                        <p class="text-sm text-[#686868] font-bold">100.000đ</p>
                    </div>
                    <div class="flex justify-between my-2">
                        <p class="text-sm text-[#686868] font-bold">Vận chuyển</p>
                        <p class="text-sm text-[#686868] font-bold">Miễn phí</p>
                    </div>
                    <div class="flex justify-between my-2">
                        <p class="text-sm text-[#686868] font-bold">Ưu đãi</p>
                        <p class="text-sm text-[#686868] font-bold">100.000đ</p>
                    </div>
                    <div class="flex justify-between my-2">
                        <p class="text-sm text-[#686868] font-bold">Tổng cộng</p>
                        <p class="text-sm text-[#686868]">1.000.000đ</p>
                    </div>
                    <div class="flex justify-between my-2">
                        <p class="text-sm text-[#686868]">Đã thanh toán</p>
                        <p class="text-sm text-[#686868] font-bold">1.000.000đ</p>
                    </div>
                    <div class="flex justify-between my-2">
                        <p class="text-sm text-[#686868] font-bold">Còn lại</p>
                        <p class="text-sm text-[#686868] font-bold">1.000.000đ</p>
                    </div>
                </div>
            </div> -->
        </div>
        <div class="mx-5">
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868] font-bold">Tổng</p>
                <p class="text-sm text-[#686868] font-bold">{{ formatPrice(total_price) }}đ</p>
            </div>
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868] font-bold">VAT(x%)</p>
                <p class="text-sm text-[#686868] font-bold">{{vat}}đ</p>
            </div>
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868] font-bold">Vận chuyển</p>
                <p class="text-sm text-[#686868] font-bold" v-if="shipping_fee ==0">Miễn phí</p>
                  <p class="text-sm text-[#686868] font-bold" v-else>{{formatPrice(shipping_fee)}}</p>
            </div>
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868] font-bold">Ưu đãi</p>
                <p class="text-sm text-[#686868] font-bold">{{discount_deal}}đ</p>
            </div>
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868] font-bold">Tổng cộng</p>
                <p class="text-sm text-[#686868]">1.000.000đ</p>
            </div>
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868]">Đã thanh toán</p>
                <p class="text-sm text-[#686868] font-bold">1.000.000đ</p>
            </div>
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868] font-bold">Còn lại</p>
                <p class="text-sm text-[#686868] font-bold">1.000.000đ</p>
            </div>
            <div class="my-3">
                <BaseButton color="info"
                    class="bg-orange-500 hover:bg-orange-600 text-white p-2 w-full text-center justify-center rounded-lg"
                    :icon="mdiContentSaveMove" small label="Lưu đơn hàng" />
            </div>
            <!-- <div class="my-3">
                <BaseButton color="info"
                    class="bg-lime-600 hover:bg-lime-700 text-white p-2 w-full text-center justify-center" :icon="mdiEye"
                    small label="Xem đơn hàng" />
            </div>
            <div class="my-3">
                <BaseButton color="info"
                    class="bg-blue-900 hover:bg-blue-900 text-white p-2 w-full text-center justify-center"
                    :icon="mdiCreditCardSettingsOutline" small label="Thanh toán ngay" />
            </div>
            <div class="my-3">
                <BaseButton color="info"
                    class="bg-gray-700 hover:bg-gray-800 text-white p-2 w-full text-center justify-center"
                    :icon="mdiContentCopy" small label="Sao chép đường dẫn" />
            </div>
            <div class="my-3">
                <BaseButton color="info" class="bg-black text-white p-2 w-full text-center justify-center" :icon="mdiImport"
                    small label="In đơn hàng" />
            </div> -->
        </div>
    </div>


</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped >
.list_icon_crud {
  box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;

  right: -40px;
  top: 20px;
  z-index: 111;
  display: inline-grid;
}

.btn_crud {
  font-size: 20px;
}

.icon_giam {
  width: 32px;
  height: 32px;
}

/*  */
.buttons_added {
  opacity: 1;
  display: inline-block;
  display: -ms-inline-flexbox;
  display: inline-flex;
  white-space: nowrap;
  vertical-align: top;
}

.is-form {
  overflow: hidden;
  position: relative;
  background-color: #f9f9f9;
  height: 2.2rem;
  width: 1.9rem;
  padding: 0;
  text-shadow: 1px 1px 1px #fff;
  border: 1px solid #ddd;
}

.is-form:focus,
.input-text:focus {
  outline: none;
}

.is-form.minus {
  border-radius: 4px 0 0 4px;
}

.is-form.plus {
  border-radius: 0 4px 4px 0;
}

.input-qty {
  background-color: #fff;
  height: 2.2rem;
  text-align: center;
  font-size: 1rem;
  display: inline-block;
  vertical-align: top;
  margin: 0;
  border-top: 1px solid #ddd;
  border-bottom: 1px solid #ddd;
  border-left: 0;
  border-right: 0;
  padding: 0;
  width: 35px;
}

.input-qty::-webkit-outer-spin-button,
.input-qty::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>