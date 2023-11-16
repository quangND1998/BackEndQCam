<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import OrderBar from "@/Pages/Modules/Order/OrderBar.vue";

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

} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
// import Multiselect from '@vueform/multiselect'
import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import { initFlowbite } from 'flowbite'
import Contract from '@/Pages/Test/Contract.vue'
const provinces = ref(null);

const props = defineProps({
    orders: Object,
    status: String,
    status: String,
    from: String,
    to: String,
    statusGroup: Array
});

const form = useForm({
    city: null,
    district: null,
    wards: null,
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
        if (provinces.value) {
            return provinces.value?.find(pro => {
                return pro.Name == form.city;
            });
        }
        return [];
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
const onChangeDistrict = (event) => {
    // this.form.wards = null;
}
const date = ref(new Date());

const format = (date) => {
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();

    return `${day}/${month}/${year}`;
}
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đơn hàng" />
        <SectionMain>
            <div class="container m-auto mt-10">
                <div class="min-[320px]:block sm:block md:block lg:grid grid-cols-3 gap-4 mt-10">
                    <div class="col-span-2">
                        <div class="flex border-b border-gray-200 pb-4">
                            <div class="w-1/2 px-2">
                                <div class="block">
                                    <img src="assets/images/cammattroi.png" alt="">
                                    <h1>CÔNG TY CỔ PHẦN CAM MẶT TRỜI</h1>

                                    <p class="text-sm text-[#5F5F5F]">Địa chỉ:</p>
                                    <p class="text-sm text-[#5F5F5F]">Farm:</p>
                                    <p class="text-sm text-[#5F5F5F]">Điện thoại:</p>
                                    <p class="text-sm text-[#5F5F5F]">Email:</p>
                                </div>
                            </div>
                            <div class="w-1/2 px-2">
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
                            <h3 class="text-[17px] font-bold">Thông tin liên hệ</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="my-3">
                                        <label for="first_name"
                                            class="block mb-2 text-sm  text-gray-900 dark:text-white">Tên Khách Hàng
                                            *</label>
                                        <select id="countries"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option selected>Choose a country</option>
                                            <option value="US">United States</option>
                                            <option value="CA">Canada</option>
                                            <option value="FR">France</option>
                                            <option value="DE">Germany</option>
                                        </select>
                                    </div>
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Giới tính</label>
                                        <div class="flex">
                                            <div class="flex items-center ">
                                                <input id="default-radio-1" type="radio" value="" name="default-radio"
                                                    class="w-4 h-4  text[#F78F43] bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="default-radio-1"
                                                    class="ms-2 text-sm  text-gray-900 dark:text-gray-300">Nam</label>
                                            </div>
                                            <div class="flex items-center mx-5">
                                                <input checked id="default-radio-2" type="radio" value=""
                                                    name="default-radio"
                                                    class="w-4 h-4 text[#F78F43] bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="default-radio-2"
                                                    class="ms-2 text-sm  text-gray-900 dark:text-gray-300">Nữ</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Số điện thoại *</label>
                                        <input type="text" id="first_name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Địa chỉ *</label>
                                        <input type="text" id="first_name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                    </div>
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Tỉnh/Thành Phố *</label>

                                        <select id="city" v-model="form.city" @change="onChangeCity($event)"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option :value="null">Chọn tỉnh thành</option>
                                            <option v-for="(city, index) in provinces" :value="city.Name" :key="index">{{
                                                city.Name }}</option>
                                        </select>
                                    </div>
                                    <div class="my-3 flex">
                                        <div class="w-1/2 mr-2">
                                            <label for="first_name"
                                                class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                                Quận/huyện *</label>


                                            <select id="city" v-model="form.district" @change="onChangeDistrict($event)"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option :value="null">Chọn quận huyện</option>
                                                <option v-for="(district, index) in districts.Districts"
                                                    :value="district.Name" :key="index">
                                                    {{
                                                        district.Name }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="w-1/2 ml-2">
                                            <label for="first_name"
                                                class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                                Phường xã*</label>

                                            <select v-model="form.wards" id="wards"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option :value="null">Chọn phường xã</option>
                                                <option v-for="(ward, index) in wards.Wards" :value="ward.Name"
                                                    :key="index">{{ ward.Name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-3">
                            <h3 class="text-[17px] font-bold">Thông tin giấy tờ</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Số CMND/CCCD/Hộ chiếu</label>
                                        <input type="text" id="first_name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                    </div>

                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Ngày sinh</label>
                                        <div class="relative w-full">
                                            <VueDatePicker v-model="date" :format="format" />
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Ngày cấp</label>
                                        <div class="relative w-full">
                                            <VueDatePicker v-model="date" :format="format" />
                                        </div>
                                    </div>
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Có giá trị đến</label>
                                        <div class="relative w-full">
                                            <VueDatePicker v-model="date" :format="format" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-3">
                            <h3 class="text-base font-semibold">Chứng từ liên quan</h3>
                            <div class="flex mt-2">
                                <div class="mr-2">
                                    <img src="/assets/images/new4.png" class="w-20 h-20 object-cover rounded-lg" alt="">
                                </div>
                                <div class="mr-2">
                                    <img src="/assets/images/new4.png" class="w-20 h-20 object-cover rounded-lg" alt="">
                                </div>
                                <div class="mr-2">
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
                    <div class="mx-5">
                        <div class="my-3">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                VAT(%)</label>
                            <input type="text" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="" required>
                        </div>
                        <div class="my-2">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Ưu đãi (%)</label>
                            <input type="text" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="" required>
                        </div>
                        <div class="my-2">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Vận chuyển</label>
                            <input type="text" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="" required>
                        </div>
                        <div class="my-2">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Thời gian giữ chỗ (ngày)</label>
                            <input type="number" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="" required>
                        </div>
                        <div class="my-2">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Số tiền thanh toán</label>
                            <input type="number" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="" required>
                        </div>
                        <div class="my-2">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Hình thức thanh toán</label>
                            <select id="countries"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected>Tiền mặt</option>
                                <option value="US">United States</option>
                                <option value="CA">Canada</option>
                                <option value="FR">France</option>
                                <option value="DE">Germany</option>
                            </select>
                        </div>
                        <div class="my-3">
                            <BaseButton color="info"
                                class="bg-[#F78F43] text-white p-2 w-full text-center justify-center rounded-lg"
                                :icon="mdiContentSaveMove" small label="Lưu đơn hàng" />
                        </div>
                        <div class="my-3">
                            <BaseButton color="info" class="bg-green-500 text-white p-2 w-full text-center justify-center"
                                :icon="mdiEye" small label="Xem đơn hàng" />
                        </div>
                        <div class="my-3">
                            <BaseButton color="info" class="bg-blue-400 text-white p-2 w-full text-center justify-center"
                                :icon="mdiCreditCardSettingsOutline" small label="Thanh toán ngay" />
                        </div>
                        <div class="my-3">
                            <BaseButton color="info" class="bg-gray-700 text-white p-2 w-full text-center justify-center"
                                :icon="mdiContentCopy" small label="Sao chép đường dẫn" />
                        </div>
                        <div class="my-3">
                            <BaseButton color="info" class="bg-black text-white p-2 w-full text-center justify-center"
                                :icon="mdiImport" small label="In đơn hàng" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-4/5">
                <div class=" mt-2 w-full">
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
                                        Tổng
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
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-44 p-2.5 ">
                                            <option value="Tiền"></option>
                                            <option value="%">%</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number"
                                            class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 hover:border-gray-500 w-28">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number"
                                            class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 hover:border-gray-500 w-28">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number"
                                            class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 hover:border-gray-500 w-32">
                                    </td>
                                    <td class="px-6 py-4">
                                        <svg @click="deletetable(value.id)" width="15" height="15" viewBox="0 0 15 15"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M1 14L7.50002 7.50003M7.50002 7.50003L14 1M7.50002 7.50003L1 1M7.50002 7.50003L14 14"
                                                stroke="#4E4E4E" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                        classSize="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-2/5 xl:w-8/12 z-50 overflow-auto"
                        :title="editMode ? '' : 'Thêm sản phẩm'">


                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-full">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-4">
                                            <div class="flex items-center">
                                                <input id="checkbox-all-search" type="checkbox"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Hình ảnh
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Giá
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Mô tả
                                        </th>

                                        <th scope="col" class="px-6 py-3">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="w-4 p-4">
                                            <div class="flex items-center">
                                                <input id="checkbox-table-search-1" type="checkbox"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                            </div>
                                        </td>
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            Apple MacBook Pro 17"
                                        </th>
                                        <td class="px-6 py-4">
                                            Silver
                                        </td>
                                        <td class="px-6 py-4">
                                            Laptop
                                        </td>
                                        <td class="px-6 py-4">
                                            Yes
                                        </td>

                                        <td class="flex items-center px-6 py-4">
                                            <a href="#"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                            <a href="#"
                                                class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3">Remove</a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </CardBoxModal>
                    <div class="flex w-full">
                        <div class="w-1/2">
                            <BaseButton color="info" @click="
                                isModalActive = true;" class="bg-btn_green text-white p-2 hover:bg-[#008000]"
                                :icon="mdiPlus" small label="Thêm sản phẩm" />
                        </div>

                        <div class="w-1/2">
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868]">Tổng</p>
                                <p class="text-sm text-[#686868]">1.000.000đ</p>
                            </div>
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868]">VAT(x%)</p>
                                <p class="text-sm text-[#686868]">100.000đ</p>
                            </div>
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868]">Vận chuyển</p>
                                <p class="text-sm text-[#686868]">Miễn phí</p>
                            </div>
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868]">Ưu đãi</p>
                                <p class="text-sm text-[#686868]">100.000đ</p>
                            </div>
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868]">Tổng cộng</p>
                                <p class="text-sm text-[#686868]">1.000.000đ</p>
                            </div>
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868]">Đã thanh toán</p>
                                <p class="text-sm text-[#686868]">1.000.000đ</p>
                            </div>
                            <div class="flex justify-between my-2">
                                <p class="text-sm text-[#686868]">Còn lại</p>
                                <p class="text-sm text-[#686868]">1.000.000đ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>











































