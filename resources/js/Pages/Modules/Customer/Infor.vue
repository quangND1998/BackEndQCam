<script setup>
import { computed, ref, inject, watch, toRef } from 'vue'
import LayoutProfileDetail from '@/Layouts/LayoutProfileDetail.vue';
import { useForm } from '@inertiajs/vue3';
import SectionMain from '@/Components/SectionMain.vue'
import { Head } from '@inertiajs/vue3'
import CardBoxModal from '@/Components/CardBoxModal.vue'
import { mdiReceiptText, mdiPencilOutline, mdiPlus, mdiTrashCan } from '@mdi/js'
import BaseButton from '@/Components/BaseButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue';
const props = defineProps({
    customer: Object,
    info: Object
});
const swal = inject('$swal')
const form = useForm({
    id: props.customer.id,
    name: props.info.name,
    cic_number: props.info.cic_number,
    phone_number: props.info.phone_number,
    email: props.info.email,
    sex: null,
    address: props.info.address,
    date_of_birth: props.info.date_of_birth,
    city: props.info.city,
    district: props.info.district,
    wards: props.info.wards,
    cic_date: props.info.cic_date,
    cic_date_expried: props.info.cic_date_expried,

});


const crumbs = ref([

    {
        route: "customer.detail.info",
        parma: props.customer.id,
        name: props.customer.name
    },
    {
        route: "customer.detail.info",
        parma: props.customer.id,
        name: "Amenities"
    }
])
const save = () => {
    form.post(route("customer.approInfo", props.customer.id), {
        onError: () => {

        },
        onSuccess: () => {

        },
    });
};
</script>

<template>
    <LayoutProfileDetail :customer="customer" :crumbs="crumbs">

        <Head title="Amenities" />
        <SectionMain>
            <div class="p-6 flex-auto lg:w-2/3 sm:w-full">
                <div class="flex flex-wrap  -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="name" value="Name" />
                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" disabled
                            :class="form.errors.name ? 'border-red-500' : ''" required autofocus autocomplete="name" />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="email" value="Email" />
                        <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" disabled
                            :class="form.errors.email ? 'border-red-500' : ''" autocomplete="name" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="phone" value="Số điện thoại" />
                        <TextInput id="phone" v-model="form.phone_number" type="text" class="mt-1 block w-full" disabled
                            :class="form.errors.phone_number ? 'border-red-500' : ''" />
                        <InputError class="mt-2" :message="form.errors.phone_number" />
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="cic_number" value="Chứng minh thư" />

                        <TextInput id="cic_number" v-model="form.cic_number" type="text" class="mt-1 block w-full" disabled
                            :class="form.errors.cic_number ? 'border-red-500' : ''" />
                        <InputError class="mt-2" :message="form.errors.cic_number" />
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="sex" value="Giới tính" />
                        <div class="flex">
                            <div class="flex items-center ">
                                <input id="default-radio-1" type="radio" value="male" name="default-radio"
                                    v-model="form.sex"
                                    class="w-4 h-4  text[#F78F43] bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="default-radio-1"
                                    class="ms-2 text-sm  text-gray-900 dark:text-gray-300">Nam</label>
                            </div>
                            <div class="flex items-center mx-5">
                                <input checked id="default-radio-2" type="radio" value="female" v-model="form.sex"
                                    name="default-radio"
                                    class="w-4 h-4 text[#F78F43] bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="default-radio-2"
                                    class="ms-2 text-sm  text-gray-900 dark:text-gray-300">Nữ</label>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="address" value="Địa chỉ" />

                        <TextInput id="address" v-model="form.address" type="text" class="mt-1 block w-full" disabled
                            :class="form.errors.address ? 'border-red-500' : ''" />
                        <InputError class="mt-2" :message="form.errors.address" />
                    </div>   
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="city" value="Tỉnh/Thành phố" />
                        <TextInput id="city" v-model="form.city" type="text" class="mt-1 block w-full" disabled
                            :class="form.errors.city ? 'border-red-500' : ''" />
                        <InputError class="mt-2" :message="form.errors.city" />
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="district" value="Quận/Huyện" />

                        <TextInput id="district" v-model="form.district" type="text" class="mt-1 block w-full" disabled
                            :class="form.errors.district ? 'border-red-500' : ''" />
                        <InputError class="mt-2" :message="form.errors.district" />
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="wards" value="Phường/Xã" />
                        <TextInput id="wards" v-model="form.wards" type="text" class="mt-1 block w-full" disabled
                            :class="form.errors.wards ? 'border-red-500' : ''" />
                        <InputError class="mt-2" :message="form.errors.wards" />
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="date_of_birth" value="Ngày sinh" />
                        <TextInput id="date_of_birth" v-model="form.date_of_birth" type="text" class="mt-1 block w-full" disabled
                            :class="form.errors.date_of_birth ? 'border-red-500' : ''" />
                        <InputError class="mt-2" :message="form.errors.date_of_birth" />
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="cic_date" value="Ngày Cấp chứng minh thư" />
                        <TextInput id="cic_date" v-model="form.cic_date" type="text" class="mt-1 block w-full" disabled
                            :class="form.errors.cic_date ? 'border-red-500' : ''" />
                        <InputError class="mt-2" :message="form.errors.cic_date" />
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="cic_date_expried" value="Ngày hết hạn" />

                        <TextInput id="cic_date_expried" v-model="form.cic_date_expried" type="text" class="mt-1 block w-full" disabled
                            :class="form.errors.cic_date_expried ? 'border-red-500' : ''" />
                        <InputError class="mt-2" :message="form.errors.cic_date_expried" />
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 hover:bg-color_green" :icon="mdiPlus" small
                        @click="save()" label="Xét duyệt" />
                </div>
            </div>

        </SectionMain>
    </LayoutProfileDetail>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
