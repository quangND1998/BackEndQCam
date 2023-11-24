<script setup>
import { computed, ref, inject, watch, toRef } from 'vue'
import LayoutShipper from '@/Layouts/LayoutShipper.vue';
import { Link, useForm, router } from '@inertiajs/vue3';

import SectionMain from '@/Components/SectionMain.vue'
import { Head } from '@inertiajs/vue3'
import { mdiReceiptText, mdiPencilOutline, mdiPlus, mdiTrashCan, mdiTrashCanOutline, mdiPencil, mdiDotsVertical, mdiFilter } from '@mdi/js'
import BaseButtons from '@/Components/BaseButtons.vue';
import BaseButton from '@/Components/BaseButton.vue';
import { useHelper } from '@/composable/useHelper';
import Pagination from "@/Components/Pagination.vue";
import SearchInput from "vue-search-input";
const props = defineProps({
    shipper: Object,
    order_shippers: Object
});
const searchVal = ref("")
const searchCustomer = ref("")
const swal = inject('$swal')
const form = useForm({
    id: null,
    images: null,
    state: null,
});
const searchFilter = () => {
    let query = {
        search: searchVal.value,
        customer: searchCustomer.value
    };
    router.get(route('shippers.detail', props.shipper.id), query, {
        preserveState: true
        // only: ["image360s", "errors", 'flash'],
    });
}
</script>

<template>
    <LayoutShipper :shipper="shipper">

        <Head title="Đơn hàng shipper" />
        <SectionMain>
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 m-2 bg-white" :iconSize="20" />
                        <div class="ml-2">
                            <SearchInput v-model="searchVal" @keyup="searchFilter" placeholder="Tìm kiếm mã đơn hàng"
                                aria-label="Search" size="24" />
                        </div>
                        <div class="ml-2">
                            <SearchInput v-model="searchCustomer" @keyup="searchFilter"
                                placeholder="Tìm kiếm khách hàng(Tên hoặc SĐT)" aria-label="Search" size="24" />
                        </div>
                    </div>
                </div>

            </div>
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg mt-5">
                <table class="w-full text-xs text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3 px-6 text-xs">STT</th>
                            <th scope="col" class="py-3 px-6 text-xs">Mã đơn hàng</th>
                            <th scope="col" class="py-3 px-6 text-xs">Trang Thái</th>
                            <th scope="col" class="py-3 px-6 text-xs">Khách hàng</th>
                            <th scope="col" class="py-3 px-6 text-xs">Số điện thoại khách hàng</th>
                            <th scope="col" class="py-3 px-6 text-xs">Địa chỉ giao hàng</th>
                            <th scope="col" class="py-3 px-6 text-xs">Ảnh giao hàng</th>
                            <th scope="col" class="py-3 px-6 text-xs">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(order, index) in order_shippers.data" :key="index" class="bg-white border-b">
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ index + 1 }}
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ order.order_number }}
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                <span v-if="order.status == 'shipping'"
                                    class="bg-red-500 text-white text-sm font-medium mr-2 px-2.5 py-0.5 rounded">
                                    Đang vận chuyển</span>
                                <span v-if="order.status == 'completed'"
                                    class="bg-lime-500 text-white text-sm font-medium mr-2 px-2.5 py-0.5 rounded">
                                    Giao thành công</span>
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ order.customer.name }}
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ order.customer.phone_number }}
                            </th>

                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ order.customer.address }} {{ order.customer.wards }} {{ order.customer.district }} {{
                                    order.customer.ciity }}
                            </th>

                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                <div class="flex">
                                    <img v-for="(image, index) in order.order_shipper_images" :key="index"
                                        :src="image.original_url" class="w-20 h-20 " />
                                </div>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :links="order_shippers.links" />


        </SectionMain>
    </LayoutShipper>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
