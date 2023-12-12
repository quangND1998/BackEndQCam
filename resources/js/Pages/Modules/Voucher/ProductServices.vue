<script setup>
import { computed, ref, inject, reactive, toRef } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
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
    mdiLandFields
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'

const props = defineProps({
    voucher: Object,
    products: Object
});
const searchVal = ref("");
const swal = inject("$swal");
const product_services = toRef(props.products)
const form = useForm({
    id: null,
    name: null,
    state: null,
});
const search = ref();
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);

const state = reactive({
    content: '<p>2333</p>',
    _content: '',
    editorOption: {
        placeholder: 'core',
        modules: {

        },

    },
    disabled: false
})
const selected = ref([])
const selectAll = computed({
    get() {
        return product_services.value.data
            ? selected.value.length == product_services.value.data
            : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            product_services.value.data.forEach(function (product) {
                array_selected.push(product.id);
            });
        }
        selected.value = array_selected;
    }
});


const selected_productOwnder = ref([])
const selectAllOwner = computed({
    get() {
        return props.voucher.product_service_vouchers.data
            ? selected.value.length == props.voucher.product_service_vouchers.data
            : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            props.voucher.product_service_vouchers.forEach(function (product_service_voucher) {
                array_selected.push(product_service_voucher.id);
            });
        }
        selected_productOwnder.value = array_selected;
    }
});
const save = () => {

}
const searchProduct = () => {
    let params = {
        search: search.value
    }
    axios.get(`/admin/vouchers/${props.voucher.id}/product-service`, { params }).then(response => {
        product_services.value = response.data

    })
}
const changepPage = (url) => {
    axios.get(url).then(response => {
        product_services.value = response.data

    })
}

const AddProductVouchers = () => {
    let query = {
        ids: selected.value
    };

    swal
        .fire({
            title: "Bạn có muốn?",
            text: "Thêm các sản phẩm trên vào trường trình mã giảm giá!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Save",
        })
        .then((result) => {
            if (result.isConfirmed) {

                router.post(route("admin.voucher.product-service.saveItems", props.voucher.id), query,
                    {
                        preserveState: true,
                        preserveScroll: true
                    }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã thêm các sản phẩm vào mã giảm giá.", "success");
                    },
                });
            }
        });
}

const RemoveMutipleProduct = () => {
    let query = {
        ids: selected_productOwnder.value
    };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: "Xóa sản phẩm trên vào trường trình mã giảm giá!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
        })
        .then((result) => {
            if (result.isConfirmed) {

                router.delete(route("admin.voucher.product-service.deleteItems", query),
                    {
                        preserveState: true,
                        preserveScroll: true
                    }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã thêm các sản phẩm vào mã giảm giá.", "success");
                    },
                });
            }
        });
}
const RemoveProductVoucher = (id) => {
    swal
        .fire({
            title: "Bạn có muốn?",
            text: "Xóa sản phẩm trên vào trường trình mã giảm giá!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Save",
        })
        .then((result) => {
            if (result.isConfirmed) {

                form.delete(route("admin.voucher.product-service.deleteProductServiceVoucher", id),
                    {
                        preserveState: true,
                        preserveScroll: true
                    }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã xóa sản phẩm.", "success");

                    },
                });
            }
        });
}
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Product " />
        <SectionMain>
            <SectionTitleLineWithButton title="Product " main></SectionTitleLineWithButton>

            <!-- Modal -->
            <CardBoxModal v-model="isModalDangerActive" title="Please confirm" button="danger" has-cancel>
                <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
                <p>This is sample modal</p>
            </CardBoxModal>
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 m-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" placeholder="Search" aria-label="Search" size="24" />
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 hover:bg-[#008000]" :icon="mdiPlus" small
                        @click="
                            isModalActive = true;
                        form.reset();
                        " label="Thêm sản phẩm dịch vụ" />
                </div>
            </div>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="AddProductVouchers"
                classSize="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-3/5 xl:w-8/12 z-50 overflow-auto"
                :title="editMode ? 'Sửa Sản Phẩm' : 'Thêm sản phẩm dịch vụ'">
                <div class="w-3/12">

                    <form>
                        <label for="default-search"
                            class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="default-search" v-model="search" @keyup="searchProduct"
                                class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search" required>

                        </div>
                    </form>
                </div>
                <div v-if="selected.length > 1">
                    <p class="text-end text-blue-700">Thêm ({{ selected.length }}) sản phẩm đã chọn vào chương trình </p>
                </div>
                <div class="relative mt-5 ">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 flex items-center">
                                    <input type="checkbox" v-model="selectAll"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                    #
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tên
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    Giá
                                </th>




                            </tr>
                        </thead>
                        <tbody>

                            <tr v-for="(product, index) in product_services.data" :key="index"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox" v-model="selected" :value="product.id"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        {{ index++ }}
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    {{ product.name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ formatPrice(product.price) }}
                                </td>

                            </tr>

                        </tbody>
                    </table>
                    <div v-if="product_services.links.length > 3" class="mt-8 ml-4">
                        <div class="flex flex-wrap -mb-1">

                            <div v-for="(link, k) in product_services.links" :key="k">

                                <div v-if="link.url === null" class=" mr-1 mb-1 px-3 py-1 text-xs leading-4 border
                                    rounded hover:bg-white hover:cursor-pointer focus:border-indigo-500
                                    focus:text-indigo-500" v-html="link.label" />
                                <button v-else @click="changepPage(link.url)"
                                    class="mr-1 mb-1 px-3 py-1 text-xs leading-4 border rounded hover:bg-white hover:cursor-pointer focus:border-indigo-500 focus:text-indigo-500"
                                    :class="{ 'bg-blue-700 text-white': link.active }" v-html="link.label" />
                            </div>
                        </div>
                    </div>
                </div>

            </CardBoxModal>
            <!-- End Modal -->
            <div class="mt-5">
                <div class="flex justify-end items-center" v-if="selected_productOwnder.length > 0"
                    @click="RemoveMutipleProduct()">
                    <BaseButton :icon="mdiTrashCanOutline" small class="text-[#D12953]" style="color:red" />
                    <p class="text-red-600">Đã chọn ({{ selected_productOwnder.length }}) sản phẩm</p>
                </div>
                <div class="relative mt-5 ">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 flex items-center">
                                    <input type="checkbox" v-model="selectAllOwner"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                    #
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tên
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    Giá
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Chiết Khấu
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Giá sale
                                </th>

                                <th scope="col" class="px-6 py-3">

                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product_service_voucher, index) in voucher.product_service_vouchers" :key="index"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox" v-model="selected_productOwnder"
                                            :value="product_service_voucher.id"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        {{ index + 1 }}
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    {{ product_service_voucher.product_service.name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ formatPrice(product_service_voucher.product_service.price) }} VND
                                </td>
                                <td class="px-6 py-4">
                                    {{ product_service_voucher.unit == 'percent' ? product_service_voucher.discount :
                                        formatPrice(product_service_voucher.discount) }}
                                    <span v-if="product_service_voucher.unit == 'percent'">%</span>
                                    <span v-else>VND</span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ formatPrice(product_service_voucher.price_sale) }} VND
                                </td>
                                <td class="px-6 py-4">
                                    <BaseButton :icon="mdiTrashCanOutline"
                                        @click="RemoveProductVoucher(product_service_voucher.id)" small
                                        class="text-[#D12953] " />
                                </td>

                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>

