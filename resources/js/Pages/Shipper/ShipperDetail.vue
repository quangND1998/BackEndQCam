<script setup>
import { computed, ref, inject, watch, toRef } from 'vue'
import LayoutShipper from '@/Layouts/LayoutShipper.vue';
import { Link, useForm } from '@inertiajs/vue3';
import SectionMain from '@/Components/SectionMain.vue'
import { Head } from '@inertiajs/vue3'
import CardBoxModal from '@/Components/CardBoxModal.vue'
import { mdiReceiptText, mdiPencilOutline, mdiPlus, mdiTrashCan, mdiTrashCanOutline, mdiPencil, mdiDotsVertical } from '@mdi/js'
import BaseButtons from '@/Components/BaseButtons.vue';
import PillTag from '@/Components/PillTag.vue'
import Multiselect from "@vueform/multiselect";
import Dropdown from '@/Components/Dropdown.vue';
import moment from 'moment';
import { useHelper } from '@/composable/useHelper';
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    shipper: Object,
    order_shippers: Object
});

const swal = inject('$swal')
const form = useForm({
    id: null,
    images: null,
    state: null,
});

</script>

<template>
    <LayoutShipper :shipper="shipper" >

        <Head title="Đơn hàng shipper" />
        <SectionMain>

            <div class="overflow-x-auto relative shadow-md sm:rounded-lg mt-5">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6 text-xs">STT</th>
                            <th scope="col" class="py-3 px-6 text-xs">Mã đơn hàng</th>
                            <th scope="col" class="py-3 px-6 text-xs">Trang Thái</th>
                            <th scope="col" class="py-3 px-6 text-xs">Khách hàng</th>
                            <th scope="col" class="py-3 px-6 text-xs">Số điện thoại khách hàng</th>
                            <th scope="col" class="py-3 px-6 text-xs">Địa chỉ giao hàng</th>
                            <th scope="col" class="py-3 px-6 text-xs">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(order, index) in order_shippers.data" :key="index"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ index + 1 }}
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.order_number }}
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.status }}
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.customer.name }}
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.customer.phone_number }}
                            </th>

                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.customer.address }} {{ order.customer.wards }} {{ order.customer.district }} {{
                                    order.customer.ciity }}
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
