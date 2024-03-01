<script setup>
import { computed, ref, inject, reactive, toRef, watch } from "vue";

import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import VueDatepickerUi from "vue-datepicker-ui";
import "vue-datepicker-ui/lib/vuedatepickerui.css";
import {
    mdiPhone,mdiCloseThick
} from "@mdi/js";
import BaseIcon from "@/Components/BaseIcon.vue";
import "vue-search-input/dist/styles.css";
import { emitter } from "@/composable/useEmitter";
import Icon from '@/Components/Icon.vue'
import Multiselect from '@vueform/multiselect'
import OrderDecline from '@/Pages/Modules/CSKH/Dialog/OrderDecline.vue';
import DialogLoading from '@/Components/CustomerService/Dialog/DialogLoading.vue';
import Pagination from '@/Pages/Modules/CSKH/Components/Pagination.vue';
import CardBoxModal from "@/Components/CardBoxModalFull.vue";
import InputLabel from "@/Components/InputLabel.vue";
import BaseButton from '@/Components/BaseButton.vue'
const props = defineProps({
    product_retails: Object
});
const isModalActive = ref(false);
const products = ref(null);
const isLoading = ref(false);
const fetchProducts = (params) => {
    isLoading.value = true
    axios.get(`/admin/product_fail/getProduct`,  { params }, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
        .then(response => {
            console.log(response);
            products.value = response.data
            isLoading.value = false
        })
        .catch(error => {
            console.log(error);
            isLoading.value = false
        });
}

fetchProducts();
const swal = inject("$swal");
const form = useForm({
    product_retail_id: null,
    name: null,
    code: null,
    quality: null,
    reason: null
});

const filter = reactive({
    page: null
});
const changePage = (page) => {
    filter.page = page;
    console.log("filter",filter.page);
    fetchProducts(filter);
}


const deleteProduct = (id) => {
    swal
        .fire({
            title: "Are you sure?",
            text: "Xóa hàng hỏng!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                axios.post(`/admin/product_fail/destroyProduct/${id}`, form, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                    })
                    .then(response => {
                        fetchProducts();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        });
}
const onChangeProduct = (value,select) => {
    console.log(value);
    findProduct();
}
const findProduct = () => {
    console.log(form.product_retail_id)
    if (form.product_retail_id) {
        const product_retail = props.product_retails.find((product) => product.id == form.product_retail_id)
        if (product_retail) {
            form.code = product_retail.code;
            form.name = product_retail.name;
            form.unit = product_retail.unit;
        }
    }
}
const save = () => {
    axios.post(`/admin/product_fail/store`, form, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
        .then(response => {
            isModalActive.value = false;
            fetchProducts();
            form.reset();
        })
        .catch(error => {
            console.log(error);
        });
}
</script>
<template>
    <div>

        <OrderDecline />

        <Head title="Quản lý đơn hàng" />
        <div class="mt-16">
            <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                <div>
                    <h2 class="font-semibold flex mr-2">
                        Quản lý hàng hỏng
                        <!-- <p class="text-gray-400">( {{ $page.props.auth.total_order }} )</p> -->
                    </h2>
                </div>
                <div class="right">
                    <Button color="info" class="bg-[#4F8D06] text-white py-2 px-3 rounded "
                        @click="isModalActive = true; form.reset();">Thêm hàng hỏng</Button>
                </div>
            </div>
            <!-- Modal -->
            <CardBoxModal class="w-full" v-model="isModalActive" buttonLabel="Thêm"  has-save @confirm="save"
                title="Thêm hàng hỏng">
                <div class="p-6 flex-auto">
                    <div class="flex justify-between ">
                        <div class="w-[300px]">
                            <InputLabel for="ballot_code" value="Mặt hàng" />
                            <Multiselect v-model="form.product_retail_id" :searchable="true" label="name" valueProp="id"
                             @select="onChangeProduct"
                                trackBy="name" placeholder="Chọn mặt hàng" :options="product_retails" :classes="{
                                    tagsSearch: 'absolute bg-[#E9E9E9]  inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans  w-full',
                                    container: 'relative  mx-auto w-full bg-gray-50  items-center  box-border cursor-pointer border border-gray-300 rounded bg-[#E9E9E9] text-sm  '
                                }">
                                <template v-slot:singlelabel="{ value }">
                                    <div class="multiselect-single-label">
                                        {{ value.name }} ({{ value.code }})
                                    </div>
                                </template>

                                <template v-slot:option="{ option }">
                                    {{ option.name }} ({{ option.code }})
                                </template>
                            </Multiselect>
                        </div>
                        <div class=" px-2">
                            <InputLabel for="ballot_code" value="Mã hàng" />
                            <input type="text" class="border rounded " v-model="form.code">
                        </div>
                        <div class=" px-2">
                            <InputLabel for="ballot_code" value="Số lượng thực tế" />
                            <input type="number" class="border rounded " :min="0" v-model="form.quality">
                        </div>
                        <div class=" px-2">
                            <InputLabel for="ballot_code" value="Lý do" />
                            <textarea v-model="form.reason" class="flex-1 resize-none rounded w-[200px] border-[#dee2e6] px-2 py-1 text-sm " rows="5"></textarea>
                        </div>

                    </div>
                </div>
            </CardBoxModal>
            <DialogLoading v-if="isLoading" text="Loading" />
            <div v-else class="mt-3">
                <div class="w-full mt-2">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto inline-block min-w-full sm:px-6 lg:px-8 m-0 p-0 h-[40vh] relative">

                            <table class="table_stripe min-w-full text-center text-sm font-light overflow-x-auto">
                                <thead class="font-medium bg-[#AEAEAE]">
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
                                    <tr class="border" v-for="(product, index) in products?.data" :key="index">

                                        <td class="whitespace-nowrap text-center px-3 py-2 text-gray-500">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.code }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.name }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.quality }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.unit }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.reason }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ product.created_at }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <BaseButton color="#FF0000"
                                                class=" text-[#FF0000] hover:text-black border-0" :icon="mdiCloseThick" small
                                                @click="deleteProduct(product.id)" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>


                        </div>
                    </div>
                    <Pagination v-if="products" :data="products" @change-page="changePage"/>
                </div>
            </div>

        </div>
    </div>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
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


.table_stripe tr th {
    position: sticky;
    top: 0;
    z-index: 9;
    background: #AEAEAE;
}
</style>
