<script setup>
import { computed, ref, inject, reactive, toRef, watch } from "vue";

import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import VueDatepickerUi from "vue-datepicker-ui";
import "vue-datepicker-ui/lib/vuedatepickerui.css";
import {
    mdiPhone,
} from "@mdi/js";
import BaseIcon from "@/Components/BaseIcon.vue";
import "vue-search-input/dist/styles.css";
import { emitter } from "@/composable/useEmitter";
import Icon from '@/Components/Icon.vue'
import OrderDecline from '@/Pages/Modules/CSKH/Dialog/OrderDecline.vue';
import DialogLoading from '@/Components/CustomerService/Dialog/DialogLoading.vue';
import Pagination from '@/Pages/Modules/CSKH/Components/Pagination.vue';
const store = useFailProduct();
const products = computed(() => {
    return store.products;
});

const isLoading = computed(() => {
    return store.isLoading;
});

const fetchProducts = () => {
    axios.post(`/visit/extraService/getService`, form, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
        .then(response => {
            console.log(response);
            service_extra.value = response.data
            form.reset();
        })
        .catch(error => {
            console.log(error);
        });
}

fetchProducts();
const filter = reactive({
    fromDate: null,
    toDate: null,
    search: null,
    type: null,
    per_page: 10,
    selectedDate: null,
    state: null,
    page: null
});

const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    state: null,
    selectedDate: [new Date(), new Date(new Date().getTime() + 9 * 24 * 60 * 60 * 1000)],
});




const search = () => {
    filter.page = 1
    store.fetchProducts(filter)
};




watch(() => [filter.per_page], (newVal) => {
    store.fetchProducts(filter)
});

watch(() => [filter.state], (newVal) => {
    store.fetchProducts(filter)
});
watch(() => [filter.type], (newVal) => {
    store.fetchProducts(filter)
});

watch(() => [form.selectedDate], (newVal) => {
    // console.log(newVal[0][0])
    filter.fromDate = newVal[0][0]
    filter.toDate = newVal[0][1]
    store.fetchProducts(filter)
});

const changePage = (page) => {
    filter.page = page;
    store.fetchProducts(filter)
}

const confirm = (id) => {
    swal
        .fire({
            title: "Thông báo?",
            text: "Xác nhận hàng đã nhập kho!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
        })
        .then((result) => {
            if (result.isConfirmed) {
                store.confirm(id)

            } else {
                return;
            }
        });
}

</script>
<template>
    <div>

        <OrderDecline />
        <Head title="Quản lý đơn hàng" />
        <SectionMain class="p-3 mt-16">
            <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                <div>
                    <h2 class="font-semibold flex mr-2">
                        Quản lý hàng hỏng
                        <!-- <p class="text-gray-400">( {{ $page.props.auth.total_order }} )</p> -->
                    </h2>
                </div>
            </div>

            <div class="mt-3">
                <div class="w-full mt-2">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto inline-block min-w-full sm:px-6 lg:px-8 m-0 p-0 h-[60vh] relative">

                            <DialogLoading v-if="isLoading" text="Loading" />
                            <table class="table_stripe min-w-full text-center text-sm font-light overflow-x-auto" v-else>
                                <thead class="font-medium">
                                    <tr>
                                        <th scope="col" class="px-3 py-2 text-left">STT</th>
                                        <th scope="col" class="px-3 py-2 text-left">Mã</th>
                                        <th scope="col" class="px-3 py-2 text-left">Mặt hàng </th>
                                        <th scope="col" class="px-3 py-2 text-left">SL</th>
                                        <th scope="col" class="px-3 py-2 text-left">DVT</th>
                                        <th scope="col" class="px-3 py-2 text-left">Lý do</th>
                                        <th scope="col" class="px-3 py-2 text-left">Ngày hỏng</th>
                                        <th scope="col" class="px-3 py-2 text-left"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(product, index) in products?.data" :key="index">

                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.name }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.code }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.quantity }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.unit }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.time }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.type }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.reason }}
                                        </td>

                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.order_transport_number }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.order_number }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.state == 'pending' ? 'Chờ xác nhận' : "Đã xác nhận" }}
                                        </td>

                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <button v-if="product.state == 'pending'" @click="confirm(product.id)"
                                                class="bg-[#4F8D06] text-white rounded-md px-1 py-2 hover:bg-btn_green ">Xác
                                                nhận</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full flex  justify-between items-center">
                <div class="flex items-center">
                    <span class="mr-2">Hiển thị</span>
                    <select v-model="filter.per_page"
                        class="bg-gray-50 border   text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  mx-auto px-4 py-1   dark:bg-gray-700 dark:border-gray-600 ">
                        <option :value="10">10</option>
                        <option :value="50">50</option>
                        <option :value="100">100</option>
                        <option :value="200">200</option>
                    </select>
                </div>

                <Pagination v-if="products" :data="products" @change-page="changePage" />
            </div>
        </SectionMain>
    </div>
</template>
<style>
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

.title_information {
    background-color: #f3f4f6;
}

.item_information {
    height: 100px;
}

.collapse {
    visibility: inherit;
}

.partiallyPaid {
    background-color: rgb(254 252 232 / var(--tw-bg-opacity));
    border-color: rgb(254 240 138 / var(--tw-border-opacity));
    border-style: solid;
    border-width: 1px;
    color: rgb(202 138 4 / var(--tw-text-opacity));
}

.v-calendar .input-field input {
    height: 40px;
}

.v-calendar .input-field svg.datepicker {
    fill: #65716b;
}
</style>
