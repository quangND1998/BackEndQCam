<script setup>
import { computed, ref, inject, watch, toRef } from 'vue'
import LayoutShipper from '@/Layouts/LayoutShipper.vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import CardBoxModal from "@/Components/CardBoxModal.vue";
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
    orders: Object,
    order_shippers: Object
});
const searchVal = ref("")
const searchCustomer = ref("")
const swal = inject('$swal')
const search = ref();
const order_list= toRef(props.orders)
const form = useForm({
    id: null,
    images: null,
    state: null,
});

const selected = ref([])
const selectAll = computed({
    get() {
        return order_list.value.data
            ? selected.value.length == order_list.value.data
            : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            order_list.value.data.forEach(function (order) {
                array_selected.push(order.id);
            });
        }
        selected.value = array_selected;
    }
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
const isModalActive = ref(false);
const editMode = ref(false);

const saveOrderShipper=()=>{
    let query = {
        orders: selected.value
    };

    swal
        .fire({
            title: "Bạn có muốn?",
            text: "Phân các đơn hàng này cho shipper!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Save",
        })
        .then((result) => {
            if (result.isConfirmed) {

                router.post(route("shippers.saveOrderShipper", props.shipper.id), query,
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

const searchOrder = () => {
    let params = {
        search: search.value
    }
    axios.get(`/admin/shippers/${props.shipper.id}/detail`, { params }).then(response => {
        order_list.value = response.data

    })
}
const changepPage = (url) => {
    axios.get(url).then(response => {
        order_list.value = response.data

    })
}
const Delete=(id)=>{
    swal
        .fire({
            title: "Bạn có muốn?",
            text: "Không phân đơn hàng cho shipper!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.delete(route("shippers.cancelOrderShipper", id), {
                    onSuccess: () => {
                        swal.fire("Deleted!", "Your role has been deleted.", "success");
                    },
                });
            }
        });
}
</script>

<template>
    <LayoutShipper :shipper="shipper">

        <Head title="Đơn hàng shipper" />
        <SectionMain class="p-3 mt-8">
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
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green hover:bg-[#318f02] text-white p-2 " :icon="mdiPlus" small
                        @click="
                            isModalActive = true;
                        form.reset();
                        " label="Thêm đơn ship" />
                </div>


            </div>

            <!-- <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                :title="editMode ? 'Cập nhật đơn ship' : 'Thêm đơn ship '">





                <Multiselect v-model="form.orders" mode="tags" :appendNewTag="false" :createTag="false" :searchable="true"
                    label="order_number" valueProp="id" trackBy="order_number" :options="orders" class="form-control"
                    style="height:fit-content" :classes="{
                        tagsSearch: 'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                        container: 'relative mx-auto w-full flex items-center py-2 px-3 justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-2xl leading-snug outline-none',
                        tags: 'flex-grow flex-shrink flex flex-wrap items-center mt-1 pl-2 rtl:pl-0 rtl:pr-2',
                        tag: 'bg-blue-600 text-white text-xs font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
                    }">
                    <template v-slot:tag="{ option, handleTagRemove, disabled }">
                        <div class="multiselect-tag is-user" :class="{
                            'is-disabled': disabled
                        }">
                        <img >
                            {{ option.order_number }}
                            <span v-if="!disabled" class="multiselect-tag-remove" @click="handleTagRemove(option, $event)">
                                <span class="multiselect-tag-remove-icon"></span>
                            </span>
                        </div>
                    </template>
                </Multiselect>
                <InputError class="mt-2" :message="form.errors.orders" />
            </CardBoxModal> -->


            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="saveOrderShipper"
                classSize="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-3/5 xl:w-8/12 z-50 overflow-auto"
                :title="editMode ? 'Sửa Sản Phẩm' : 'Thêm sản phẩm'">
                <div class="w-3/12">

                    <form>
                        <label for="default-search"
                            class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                        <div class="relative">

                            <input type="search" id="default-search" v-model="search" @keyup="searchOrder"
                                class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
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

                            <tr v-for="(order, index) in order_list.data" :key="index"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox" v-model="selected" :value="order.id"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        {{ index++ }}
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    {{ order.order_number }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ formatPrice(order.last_price) }}
                                </td>

                            </tr>

                        </tbody>
                    </table>
                    <div v-if="order_list.links.length > 3" class="mt-8 ml-4">
                        <div class="flex flex-wrap -mb-1">

                            <div v-for="(link, k) in order_list.links" :key="k">

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

                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                <button type=" button" @click="Delete(order.id)"
                                    class="inline-block px-6 py-2 bg-red-500 text-white font-black text-xs leading-tight  rounded shadow-md hover:bg-red-600 hover:text-white hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out">
                                    Delete
                                </button>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :links="order_shippers.links" />


        </SectionMain>
    </LayoutShipper>
</template>
