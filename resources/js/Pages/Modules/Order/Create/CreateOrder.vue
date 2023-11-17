<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import {
    mdiEye,
    mdiAccountLockOpen,
    mdiPlus,
    mdiFilter,
    mdiMagnify,
    mdiDotsVertical,
    mdiTrashCanOutline,
    mdiCodeBlockBrackets,
    mdiPencil,
    mdiLandFields,
    mdiContentCopy,
    mdiCreditCardSettingsOutline,
    mdiContentSaveMove
} from "@mdi/js";

import InputError from "@/Components/InputError.vue";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import { initFlowbite } from 'flowbite'
import OrderProduct from '@/Pages/Modules/Order/Create/OrderProduct.vue'
import axios from "axios";


const props = defineProps({
    product_retails: Array,
    cart: Array,
    total_price: Number
});

const search = ref(null)
const user = ref(null);
const flash = ref(null);
const form = useForm({
    name: null,
    phone_number: null,
    sex: null,
    address: null,
    city: null,
    district: null,
    wards: null,
    vat: 0,
    discount_deal: 0,
    type: 'retail',
    payment_method: 'cash',
    shipping_fee: 0

})

const foundUser = (data) => {
    form.name = data.name
    form.phone_number = data.phone_number
    form.sex = data.sex
    form.address = data.address
    form.city = data.city
    form.district = data.district
    form.wards = data.wards
}
const onSearchUser = async () => {
    axios.get(`/admin/orders/searchUser?search=${search.value}`).then(res => {
        if (res.data) {
            user.value = res.data;
            foundUser(res.data)
        }
    }).catch(err => {
        user.value = null
        flash.value = err.response.data
        form.reset()
    })

}
const date = ref(new Date());

</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đơn hàng" />
        <SectionMain>
            <div class="lg:container m-auto mt-10">
                <div class="min-[320px]:block sm:block md:block lg:grid grid-cols-3 gap-4 mt-10">
                    <div class="col-span-2">
                        <div class="min-[320px]:block md:flex border-b border-gray-200 pb-4">
                            <div class="min-[320px]:w-full md:w-1/2 px-2">
                                <div class="block">
                                    <img src="assets/images/cammattroi.png" alt="">
                                    <h1>CÔNG TY CỔ PHẦN CAM MẶT TRỜI</h1>

                                    <p class="text-sm text-[#5F5F5F] my-1">Địa chỉ:</p>
                                    <p class="text-sm text-[#5F5F5F] my-1">Farm:</p>
                                    <p class="text-sm text-[#5F5F5F] my-1">Điện thoại:</p>
                                    <p class="text-sm text-[#5F5F5F] my-1">Email:</p>
                                </div>
                            </div>
                            <div class="min-[320px]:w-full min-[320px]:mt-3 min-[320px]:px-0 md:w-1/2 md:mt-0 md:px-2">
                                <div class="w-full">
                                    <div class="flex items-center w-full">
                                        <p class="text-sm text-[#5F5F5F] w-28 ">Số phiếu #</p>
                                        <input type="text" id="first_name"
                                            class="  bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="12345" required>
                                    </div>
                                    <div class="flex items-center w-full my-4">
                                        <p class="text-sm text-[#5F5F5F] w-28 ">Ngày</p>
                                        <div class="relative w-full">
                                            <VueDatePicker v-model="date" time-picker-inline />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-3">
                            <div class="flex justify-between items-center">
                                <h3 class="text-[17px] font-bold">Thông tin liên hệ</h3>
                                <input type="string" id="first_name" v-model="search" @keyup="onSearchUser()"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/5 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Search SĐT" required>
                            </div>
                            <div class="text-red-500" v-if="flash"> {{ flash }}</div>
                            <div class="min-[320px]:block md:grid grid-cols-2 gap-4 mt-5">
                                <div>
                                    <div class="my-3">
                                        <label for="name" class="block mb-2 text-sm  text-gray-900 dark:text-white">Tên
                                            Khách
                                            Hàng
                                            *</label>
                                        <input type="text" id="name" v-model="form.name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                    </div>
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Giới tính</label>
                                        <div class="flex">
                                            <div class="flex items-center ">
                                                <input id="default-radio-1" type="radio" value="male" name="default-radio"
                                                    v-model="form.sex"
                                                    class="w-4 h-4  text[#F78F43] bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="default-radio-1"
                                                    class="ms-2 text-sm  text-gray-900 dark:text-gray-300">Nam</label>
                                            </div>
                                            <div class="flex items-center mx-5">
                                                <input checked id="default-radio-2" type="radio" value="female"
                                                    v-model="form.sex" name="default-radio"
                                                    class="w-4 h-4 text[#F78F43] bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="default-radio-2"
                                                    class="ms-2 text-sm  text-gray-900 dark:text-gray-300">Nữ</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Số điện thoại *</label>
                                        <input type="text" id="first_name" v-model="form.phone_number"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                    </div>
                                </div>
                                <div class="min-[320px]:ml-0 md:ml-3">
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Địa chỉ *</label>
                                        <input type="text" id="first_name" v-model="form.address"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                    </div>
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Tỉnh/Thành Phố *</label>
                                        <input type="text" id="first_name" v-model="form.city"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                    </div>
                                    <div class="my-3 min-[320px]:block md:flex">
                                        <div class="min-[320px]:w-full md:w-1/2 mr-2">
                                            <label for="first_name"
                                                class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                                Quận/huyện *</label>
                                            <input type="text" id="first_name" v-model="form.district"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="" required>
                                        </div>
                                        <div class="min-[320px]:w-full md:w-1/2 ml-2">
                                            <label for="first_name"
                                                class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                                Phường xã*</label>
                                            <input type="text" id="first_name" v-model="form.wards"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="my-3">
                            <h3 class="text-base font-semibold">Chứng từ liên quan</h3>
                            <div class="flex mt-2">
                                <div class="mr-2 inline-block">
                                    <img src="/assets/images/new4.png" class="w-20 h-20 object-cover rounded-lg" alt="">
                                </div>
                                <div class="mr-2 inline-block">
                                    <img src="/assets/images/new4.png" class="w-20 h-20 object-cover rounded-lg" alt="">
                                </div>
                                <div class="mr-2 inline-block">
                                    <img src="/assets/images/new4.png" class="w-20 h-20 object-cover rounded-lg" alt="">
                                </div>
                                <label for="uploadFile"
                                    class="mr-2 cursor-pointer border-dashed items-center border-gray-500 mx-1 justify-center flex border rounded-lg w-20 h-20">
                                    <svg width="10" height="11" viewBox="0 0 10 11" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3.88228 10.1406V0.311079H6.11239V10.1406H3.88228ZM0.0825639 6.34091V4.1108H9.91211V6.34091H0.0825639Z"
                                            fill="#D9D9D9" />
                                    </svg>
                                </label>
                                <input id="uploadFile" type="file" class="hidden">
                            </div>
                        </div>


                    </div>
                    <div class="min-[320px]:mx-0 md:mx-5">
                        <div class="mb-3">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Loại hình</label>
                            <select id="countries" v-model="form.type"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="retail" selected>Tiền mặt</option>


                            </select>
                        </div>
                        <div class="my-3">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                VAT(%)</label>
                            <input type="number" id="first_name" min="0" max="100" v-model="form.vat"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="" required>
                        </div>
                        <div class="my-2">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Ưu đãi (%)</label>
                            <input type="number" id="first_name" v-model="form.discount_deal"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="" required>
                        </div>
                        <div class="my-2">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Vận chuyển</label>
                            <MazInputPrice v-model="form.shipping_fee" label="Enter your price" currency="VND"
                                locale="vi-VN" :min="0" @formatted="formattedPrice = $event" />
                        </div>


                        <div class="my-2">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Hình thức thanh toán</label>
                            <select id="countries" v-model="form.payment_method"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="cash">Tiền mặt</option>
                                <option value="banking">Chuyển khoản</option>
                                <option value="payoo">Payoo</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
            <OrderProduct 
            :products="product_retails" 
            :user="user" :cart="cart" 
            :total_price="total_price"  
            :vat="form.vat" 
            :discount_deal="form.discount_deal" 
            :shipping_fee="form.shipping_fee" 
            :payment_method="form.payment_method" 
            :type="form.type" />

        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>











































