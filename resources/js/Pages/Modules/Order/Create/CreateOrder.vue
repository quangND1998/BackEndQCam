<script setup>
import { computed, ref, inject, reactive, toRef, onMounted } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import Dropdown from 'primevue/dropdown';
import BaseIcon from '@/Components/BaseIcon.vue'
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
import NewOrderProduct from '@/Pages/Modules/Order/Create/NewOrderProduct.vue'
import ProductGiff from '@/Pages/Modules/Order/Create/ProductGiff.vue'
import axios from "axios";
import MazSelect from 'maz-ui/components/MazSelect'
import { useCartStore } from "@/stores/cart";
const swal = inject("$swal");

const props = defineProps({
    product_retails: Array,
    cart: Object,
    total_price: Number,
    sub_total: Number,
    customers: Array
});
const store = useCartStore();
const search = ref(null)
// const user = ref(null);
const flash = ref(null);
const provinces = ref(null)
const images = ref([])
const form = useForm({
    user_id: null,
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
    shipping_fee: 0,
    product_service_owner_id: null,
    amount_paid: 0,
    images: []


})
const getProvinces = async () => {
    const response = await fetch('https://raw.githubusercontent.com/kenzouno1/DiaGioiHanhChinhVN/master/data.json');
    const jsonData = await response.json();
    provinces.value = jsonData
};
getProvinces();

const districts = computed(() => {
    if (form.city == null) {
        return [];
    } else {
        return provinces.value.find(pro => {
            return pro.Name == form.city;
        });
    }
})
const user = computed({


    get() {
        if (form.user_id) {
            const user = props.customers.find((customer) => customer.id == form.user_id)
            if (user) {
                foundUser(user)
                return user
            }

        }
        else {
            form.reset()
            return null
        }
    },
    // setter
    set(newValue) {
        return newValue
        // Note: we are using destructuring assignment syntax here.
        console.log(newValue)
    }
})
const wards = computed(() => {
    if (form.city == null && form.district == null) {
        return [];
    } else if (form.city !== null && form.district == null) {
        return [];
    } else {
        if (provinces.value) {
            let array = provinces.value.find(pro => {
                return pro.Name == form.city;
            });
            console.log('wards', array)
            if (array.Districts) {
                return array.Districts.find(district => {
                    return district.Name == form.district;
                });
            }
            return []

        }
        return []
    }
})
const onChangeCity = (event) => {
    form.district = null;
    form.wards = null;
}
const onChangeUser = (event) => {
    form.user_id = event.target.value
}
const onChangeDistrict = (event) => {
    form.wards = null;
}

const foundUser = (data) => {
    form.user_id = data.id
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
        // user.value = null
        flash.value = err.response.data
        form.reset()
    })

}


const saveOrder = () => {
    if (user.value == null) {
        swal.fire({
            title: "Lỗi?",
            text: "Chưa có thông tin khách hàng!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
        }).then((result) => {
            if (result.isConfirmed) {

            }
        });
    }
    else {
        form.post(route('admin.orders.saveOrder', user.value.id), {
            onError: () => {
            },
            onSuccess: () => {
                form.reset()

            }
        });
    }
}

const onChangeType = (event) => {
    console.log(event.target.value);
    form.get(route('admin.cart.fetchCart'), {
        preserveState: true,
        preserveScroll: false
    }, { only: ['cart', 'total_price', 'sub_total'] });
}

const saveGift = () => {
    if (user.value == null) {
        swal.fire({
            title: "Lỗi?",
            text: "Chưa có thông tin khách hàng!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
        }).then((result) => {
            if (result.isConfirmed) {

            }
        });
    }
    else if (form.type == 'gift_delivery' && form.product_service_owner_id == null) {
        swal.fire({
            title: "Lỗi?",
            text: "Chưa chọn dịch vụ sản phẩm để giao quà!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
        }).then((result) => {
            if (result.isConfirmed) {

            }
        });
    }
    else {
        form.post(route('admin.orders.saveOrderGift', user.value.id), {
            onError: () => {
            },
            onSuccess: () => {
                form.reset()

            }
        });
    }
}

const onFileChange = (e) => {
    const files = e.target.files;

    if (files.length > 0) {
        for (var i = 0; i < files.length; i++) {
            form.images.push(files[i])
            images.value.push({
                name: files[i].name,
                image: URL.createObjectURL(files[i])
            });
        }
    }
    console.log(form.images)
}

const DeleteImage = (index) => {
    form.images.splice(index, 1);
    images.value.splice(index, 1);
}
const date = ref(new Date());

</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đơn hàng" />

        <SectionMain class="p-3 mt-8">
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
                                            <VueDatePicker v-model="date" time-picker-inline class="bg-gray-50" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-3">
                            <div class="flex  items-center">
                                <h3 class="text-[17px] font-bold mr-[20px]">Thông tin liên hệ</h3>
                                <input type="string" id="first_name" v-model="search" @keyup="onSearchUser()"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/5 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Tìm kiếm SĐT" required>
                            </div>
                            <div class="text-red-500" v-if="flash"> {{ flash }}</div>
                            <div class="min-[320px]:block md:grid grid-cols-2 gap-4 mt-5">
                                <div>
                                    <div class="my-3">
                                        <label for="name" class="block mb-2 text-sm  text-gray-900 dark:text-white">Tên
                                            Khách
                                            Hàng
                                            *</label>
                                        <MazSelect v-model="form.user_id" @change="onChangeUser($event)"
                                            label="Chọn Khách hàng" search option-value-key="id" option-label-key="name"
                                            option-input-value-key="name" :options="customers" />
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
                                        <Dropdown v-model="form.city" :options="provinces" filter optionLabel="Name"
                                            @change="onChangeCity($event)" optionValue="Name" placeholder="Chọn tỉnh thành"
                                            class="w-full md:w-14rem bg-gray-50 border border-gray-300 text-gray-900 text-sm ">
                                            <template #value="slotProps">

                                                <div v-if="slotProps.value" class="flex align-items-center">

                                                    <div>{{ slotProps.value }}</div>
                                                </div>
                                                <span v-else>
                                                    {{ slotProps.placeholder }}
                                                </span>
                                            </template>
                                            <template #option="slotProps">
                                                <div class="flex align-items-center">

                                                    <div>{{ slotProps.option.Name }}</div>
                                                </div>
                                            </template>
                                        </Dropdown>
                                        <InputError class="mt-2" :message="form.errors.city" />
                                    </div>
                                    <div class="my-3 min-[320px]:block md:flex">
                                        <div class="min-[320px]:w-full md:w-1/2 mr-2">
                                            <label for="first_name"
                                                class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                                Quận/huyện *</label>

                                            <Dropdown v-model="form.district" :options="districts.Districts" filter
                                                @change="onChangeDistrict($event)" optionLabel="Name" optionValue="Name"
                                                placeholder="Chọn Quận/huyện"
                                                class="w-full md:w-14rem bg-gray-50 border border-gray-300 text-gray-900 text-sm ">
                                                <template #value="slotProps">

                                                    <div v-if="slotProps.value" class="flex align-items-center">

                                                        <div>{{ slotProps.value }}</div>
                                                    </div>
                                                    <span v-else>
                                                        {{ slotProps.placeholder }}
                                                    </span>
                                                </template>
                                                <template #option="slotProps">
                                                    <div class="flex align-items-center">

                                                        <div>{{ slotProps.option.Name }}</div>
                                                    </div>
                                                </template>
                                            </Dropdown>
                                            <InputError class="mt-2" :message="form.errors.district" />
                                        </div>
                                        <div class="min-[320px]:w-full md:w-1/2 ml-2">
                                            <label for="first_name"
                                                class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                                Phường xã*</label>


                                            <Dropdown v-model="form.wards" :options="wards.Wards" filter optionLabel="Name"
                                                optionValue="Name" placeholder="Chọn Phường xã"
                                                class="w-full md:w-14rem bg-gray-50 border border-gray-300 text-gray-900 text-sm ">
                                                <template #value="slotProps">

                                                    <div v-if="slotProps.value" class="flex align-items-center">

                                                        <div>{{ slotProps.value }}</div>
                                                    </div>
                                                    <span v-else>
                                                        {{ slotProps.placeholder }}
                                                    </span>
                                                </template>
                                                <template #option="slotProps">
                                                    <div class="flex align-items-center">

                                                        <div>{{ slotProps.option.Name }}</div>
                                                    </div>
                                                </template>
                                            </Dropdown>
                                            <InputError class="mt-2" :message="form.errors.wards" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-3">

                            <h3 class="text-base font-semibold">Chứng từ liên quan</h3>
                            <div class="flex mt-2">
                                <div class="mr-2 inline-block" v-for="(img, index) in images " :key="index">
                                    <BaseIcon :path="mdiTrashCanOutline" class="absolute text-red-600 hover:text-red-700  "
                                        @click="DeleteImage(index)" size="16">
                                    </BaseIcon>
                                    <img :src="img.image" class="w-20 h-20 object-cover rounded-lg" alt="">
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
                                <input id="uploadFile" @change="onFileChange" multiple type="file" class="hidden"
                                    accept="image/*">
                            </div>
                            <InputError class="mt-2" :message="form.errors.images" />
                            <div v-for="(error, index) in images" :key="index">

                                <InputError class="mt-2" :message="form.errors[`images.${index}`]" />
                            </div>
                        </div>


                    </div>
                    <div class="min-[320px]:mx-0 md:mx-5">
                        <div class="mb-3">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Loại hình</label>
                            <select id="countries" v-model="form.type" @change="onChangeType"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="retail">Bán lẻ</option>
                                <option value="gift_delivery">Giao quà</option>

                            </select>
                        </div>

                        <div class="mb-3" v-if="form.type == 'gift_delivery' && user">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Theo gói</label>
                            <select id="countries" v-model="form.product_service_owner_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option v-for="(service, index) in user.product_service_owners" :key="index"
                                    :value="service.id">{{
                                        service.product.name }} ({{ service.trees.length > 0 ? service.trees[0].name : null }})
                                </option>


                            </select>
                        </div>
                        <div class="my-3" v-if="form.type == 'retail'">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                VAT(%)</label>
                            <input type="number" id="first_name" min="0" max="100" v-model="form.vat"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="" required>
                        </div>
                        <div class="my-2" v-if="form.type == 'retail'">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Ưu đãi (%)</label>
                            <input type="number" id="first_name" v-model="form.discount_deal"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="" required>
                        </div>
                        <div class="my-2" v-if="form.type == 'retail'">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Vận chuyển</label>
                            <MazInputPrice v-model="form.shipping_fee" label="Enter your price" currency="VND"
                                locale="vi-VN" :min="0" @formatted="formattedPrice = $event" />
                        </div>
                        <!-- <div class="my-2" v-if="form.type == 'retail'">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Số tiền đã thanh toán</label>
                            <MazInputPrice v-model="form.amount_paid" label="Enter your price" currency="VND" locale="vi-VN"
                                :min="0" @formatted="formattedPrice = $event" />
                        </div> -->

                        <div class="my-2" v-if="form.type == 'retail'">
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

            <ProductGiff @saveGift="saveGift" v-if="form.type == 'gift_delivery'" :products="product_retails" :user="user"
                :cart="cart" />

            <NewOrderProduct v-if="form.type == 'retail'" :products="product_retails" :user="user" :cart="cart"
                :total_price="total_price" :vat="form.vat" :discount_deal="form.discount_deal"
                :shipping_fee="form.shipping_fee" :payment_method="form.payment_method" :type="form.type"
                :amount_paid="form.amount_paid" :sub_total="sub_total" @confirm="saveOrder" />

        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>











































