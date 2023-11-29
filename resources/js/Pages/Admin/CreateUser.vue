<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import BaseButton from "@/Components/BaseButton.vue";
import Dropdown from 'primevue/dropdown';
import {
    mdiEye,
    mdiAccountLockOpen,
    mdiPlus,
    mdiFilter,
    mdiMagnify,
    mdiDotsVertical,
    mdiTrashCanOutline,
    mdiContentSaveMove,
    mdiCodeBlockBrackets,
    mdiPencil,
    mdiLandFields,

} from "@mdi/js";

import Multiselect from '@vueform/multiselect'

const provinces = ref(null);

const props = defineProps({
    roles: Array,
    leader_sales: Array


});

const form = useForm({
    city: null,
    district: null,
    wards: null,
    name: null,
    phone_number: null,
    password: null,
    address: null,
    email: null,
    cic_date: null,
    cic_date_expried: null,
    sex: "male",
    cic_number: null,
    date_of_birth: null,
    roles: null,
    leader_sale_id: null
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


const save = () => {

    form.post(route("users.store"), {
        onError: () => {
            isModalActive.value = true;
            editMode.value = false;
        },
        onSuccess: () => {
            form.reset();

        },
    });
}
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đơn hàng" />
        <SectionMain>
            <div class="container m-auto mt-10 pb-4">
                <div class="min-[320px]:block sm:block md:block lg:grid grid-cols-3 gap-4 mt-10">
                    <div class="col-span-2">

                        <div class="my-3">
                            <h3 class="text-[17px] font-bold">Tạo tải khoản</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="my-3">
                                        <label for="name" class="block mb-2 text-sm  text-gray-900 dark:text-white">Tên
                                            Khách Hàng
                                            *</label>
                                        <input type="text" id="name" v-model="form.name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                        <InputError class="mt-2" :message="form.errors.name" />

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
                                        <InputError class="mt-2" :message="form.errors.sex" />
                                    </div>
                                    <div class="my-3">
                                        <label for="phone" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Số điện thoại *</label>
                                        <input type="text" id="phone" v-model="form.phone_number"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                        <InputError class="mt-2" :message="form.errors.phone_number" />
                                    </div>


                                    <div class="my-3">
                                        <label for="phone" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Email *</label>
                                        <input type="email" id="phone" v-model="form.email"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>

                                        <InputError class="mt-2" :message="form.errors.email" />
                                    </div>
                                    <div class="my-3">
                                        <label for="phone" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Mật khẩu *</label>
                                        <input type="text" id="phone" v-model="form.password"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                        <p class="tex-sm font-bold">Nếu không nhập password: Mật khẩu sẽ là :
                                            "cammattroi"</p>
                                        <InputError class="mt-2" :message="form.errors.password" />
                                    </div>

                                </div>
                                <div class="ml-3">
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Địa chỉ *</label>
                                        <input type="text" id="first_name" v-model="form.address"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="">
                                        <InputError class="mt-2" :message="form.errors.address" />
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
                                        <InputError class="mt-2" :message="form.errors.city" />
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
                                            <InputError class="mt-2" :message="form.errors.district" />
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
                                            <InputError class="mt-2" :message="form.errors.wards" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-3" v-if="hasAnyPermission(['super-admin'])">
                            <h3 class="text-[17px] font-bold">Team *</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <Multiselect v-model="form.leader_sale_id" :appendNewTag="false" :createTag="false"
                                    :searchable="true" label="name" valueProp="id" trackBy="name" :options="leader_sales"
                                    placeholder="Chọn Team" class="form-control"  />
                                <InputError class="mt-2" :message="form.errors.leader_sale_id" />
                            </div>
                        </div>
                        <div class="my-3">
                            <h3 class="text-[17px] font-bold">Thông tin giấy tờ</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="my-3">
                                        <label for="cic_number" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Số CMND/CCCD/Hộ chiếu</label>
                                        <input type="text" id="cic_number" v-model="form.cic_number"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                        <InputError class="mt-2" :message="form.errors.cic_number" />
                                    </div>

                                    <div class="my-3">
                                        <label for="date_of_birth"
                                            class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Ngày sinh</label>
                                        <div class="relative w-full">
                                            <VueDatePicker v-model="form.date_of_birth" :format="format" />
                                        </div>
                                        <InputError class="mt-2" :message="form.errors.date_of_birth" />
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="my-3">
                                        <label for="cic_date" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Ngày cấp</label>
                                        <div class="relative w-full">
                                            <VueDatePicker v-model="form.cic_date" :format="format" />
                                        </div>
                                        <InputError class="mt-2" :message="form.errors.cic_date" />
                                    </div>
                                    <div class="my-3">
                                        <label for="cic_date_expried"
                                            class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Có giá trị đến</label>
                                        <div class="relative w-full">
                                            <VueDatePicker v-model="form.cic_date_expried" :format="format" />
                                        </div>
                                        <InputError class="mt-2" :message="form.errors.cic_date_expried" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label for="phone" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                            Quyền *</label>
                        <Multiselect v-model="form.roles" mode="tags" :appendNewTag="false" :createTag="false"
                            :searchable="true" label="name" valueProp="id" trackBy="name" :options="roles"
                            class="form-control" :classes="{
                                tagsSearch:
                                    'absolute inset-0 border-0  w-full outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                                container:
                                    'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-2xl leading-snug outline-none',
                                tags: 'flex-grow flex-shrink flex flex-wrap items-center mt-1 pl-2 rtl:pl-0 rtl:pr-2',
                                tag: 'bg-red-600 text-white text-xs font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
                            }" />
                        <InputError class="mt-2" :message="form.errors.roles" />


                    </div>
                    <div class="min-[320px]:mx-0 md:mx-5">

                        <div class="my-3">
                            <BaseButton color="info" @click="save()"
                                class="bg-[#F78F43] text-white p-2 w-full text-center justify-center rounded-lg"
                                :icon="mdiContentSaveMove" small label="Tạo" />
                        </div>
                        <div class="my-3">
                            <BaseButton :routeName="'users.index'" color="info"
                                class="bg-green-500 text-white p-2 w-full text-center justify-center"
                                :icon="mdiTrashCanOutline" small label="Hủy" />
                        </div>

                    </div>
                </div>
            </div>



        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>











































