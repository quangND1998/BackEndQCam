<script setup>
import { computed, ref, inject } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import { useForm } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head } from "@inertiajs/vue3";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import {
    mdiEye,
    mdiAccountLockOpen,
    mdiPlus,
    mdiFilter,
    mdiMagnify,
    mdiPencil
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";

import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
const props = defineProps({
    contact: Object,
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: props.contact ? props.contact.id : null,
    name: props.contact ? props.contact.name : null,
    email: props.contact ? props.contact.email : null,
    address: props.contact ? props.contact.address : null,
    description: props.contact ? props.contact.description : null,
    hotline: props.contact ? props.contact.hotline : null,
    map: props.contact ? props.contact.map : null,
    facebook: props.contact ? props.contact.facebook : null
});
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);

const edit = () => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = props.contact.id;
    form.description = props.contact.description;
};

const save = () => {
    console.log(form);

    form.post(route("admin.contact.store"), {
        preserveState: true,
        preserveScroll: true
    }, {
        onError: () => {
            isModalActive.value = true;
            editMode.value = false;
        },
        onSuccess: () => {
            form.reset();
            isModalActive.value = false;
            editMode.value = false;
        },
    });


    // form.id = permission.id;
    // form.name = permission.name
};


</script>
<template>
    <LayoutAuthenticated>

        <Head title="Cài đặt thông tin chung " />

        <SectionMain>
            <SectionTitleLineWithButton title="Cài đặt thông tin chung" main></SectionTitleLineWithButton>


            <div>
                <form @submit.prevent="save()" class="p-0 m-0 lg:container mx-auto">
                    <div class="min-[320px]:m-2 sm:m-4 md:m-8 lg:m-8">
                        <div class="min-[320px]:p-0 sm:p-2 md:p-2 lg:p-4">
                            <div class="my-3 flex">
                                <div class="block">
                                    <h2
                                        class="min-[320px]:text-xl min-[320px]:mt-4 sm:text-xl md:text-2xl font-semibold lg:text-3xl flex mr-2">
                                        Cài đặt thông tin chung</h2>
                                    <p class="text-gray-500 text-xs">Chỉnh sửa thông tin về công ty, liên kết, số điện thoại
                                    </p>
                                </div>
                            </div>
                            <div class="bg-white border rounded-md p-3">
                                <h3 class="text-lg font-semibold">Thông tin công ty</h3>
                                <div class="my-2">
                                    <label for="company_name"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên công
                                        ty</label>
                                    <input type="text" v-model="form.name" id="company_name"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Cam Mặt Trời" />
                                    <div class="text-red-500" v-if="form.errors.name">{{ form.errors.name }}</div>
                                </div>
                                <div class="my-2">
                                    <label for="email"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                    <input type="mail" id="email" v-model="form.email"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="cammattroi@gmail.com" />
                                    <div class="text-red-500" v-if="form.errors.email">{{ form.errors.email }}</div>
                                </div>

                                <div class="my-2">
                                    <label for="address"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Địa chỉ</label>
                                    <input type="text" id="address" v-model="form.address"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="" />
                                    <div class="text-red-500" v-if="form.errors.address">{{ form.errors.address }}</div>
                                </div>
                                <!-- <div class="my-2">
                                    <label for="tax_code"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mã số
                                        thuế</label>
                                    <input type="text" id="tax_code" v-model="form.tax_code"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                </div> -->
                            </div>
                            <div class="bg-white border rounded-md my-5 p-3">
                                <h3 class="text-lg font-semibold">Liên hệ</h3>
                                <div class="my-2">
                                    <label for="hotline"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Số
                                        hotline</label>
                                    <input type="text" id="hotline" v-model="form.hotline"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                </div>
                                <div class="my-2">
                                    <label for="zalo_phone"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Zalo</label>
                                    <input type="mail" id="zalo_phone" v-model="form.zalo_phone"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                    <div class="text-red-500" v-if="form.errors.zalo_phone">{{ form.errors.zalo_phone }}
                                    </div>
                                </div>
                                <div class="my-2">
                                    <label for="facebook"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Facebook</label>
                                    <input type="text" id="facebook" v-model="form.facebook"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                    <div class="text-red-500" v-if="form.errors.facebook">{{ form.errors.facebook }}</div>
                                </div>
                                <div class="my-2">
                                    <label for="map"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nhúng bản
                                        đồ</label>
                                    <textarea id="map" rows="4" v-model="form.map"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Nhúng iframe bản đồ"></textarea>
                                    <div class="text-red-500" v-if="form.errors.map">{{ form.errors.map }}</div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="px-3 py-2 bg-gray-800 text-white rounded-md"
                                    @click.prevent="save">Lưu thông tin</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
