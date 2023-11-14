<script setup>
import { computed, ref, inject, watch, toRef } from 'vue'
import LayoutProfileDetail from '@/Layouts/LayoutProfileDetail.vue';
import SectionMain from '@/Components/SectionMain.vue'
import { Head } from '@inertiajs/vue3'
import CardBoxModal from '@/Components/CardBoxModal.vue'
import { mdiReceiptText, mdiPencilOutline, mdiPlus, mdiTrashCan, mdiTrashCanOutline, mdiPencil, mdiDotsVertical } from '@mdi/js'
import BaseButton from '@/Components/BaseButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue';
import PillTag from '@/Components/PillTag.vue'
import Multiselect from "@vueform/multiselect";
import Dropdown from '@/Components/Dropdown.vue';
import { Link, useForm } from "@inertiajs/vue3";
import moment from 'moment';
import { useHelper } from '@/composable/useHelper';
const props = defineProps({
    customer: Object,
    products: Array,
    trees: Array
});
const { multipleSelect } = useHelper();
const swal = inject('$swal')
const form = useForm({
    id: null,
    product_service: props.products.length > 0 ? props.products[0].id : null,
    tree: null,
    time_approve: null,
    state: null,
});
const totalTree = toRef(props.trees);
const isModalActive = ref(false)
const editMode = ref(false)
const isModalExtendActive = ref(false);
const edit = (product_owner) => {
    isModalActive.value = true
    editMode.value = true
    form.id = product_owner.id;
    form.product_service = product_owner.product.id;
    form.state = product_owner.state
    form.tree = multipleSelect(product_owner.trees)
    form.time_approve = product_owner.time_approve
    totalTree.value = totalTree.value.concat(product_owner.trees)
    console.log("product_service", product_owner.product.id)
}
const extend = (product_owner) => {
    isModalExtendActive.value = true;
    form.product_service = product_owner.product;
}
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
const form_reset = () => {
    console.log("reset");
    totalTree.value = props.trees;
};
const save = () => {

    console.log(form);
    if (editMode.value == true) {
        form.put(route("customer.detail.products.update", [props.customer.id, form.product_service]), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true;
            },
            onSuccess: () => {
                form_reset();
                form.reset();
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    } else {
        form
            .transform((data) => ({
                ...data,
                remember: data.remember ? 'on' : '',
            }))
            .post(route("customer.detail.products.store", [props.customer.id, form.product_service]), {
                onError: () => {
                    isModalActive.value = true;
                    editMode.value = false;
                },
                onSuccess: () => {
                    form_reset();
                    form.reset();
                    isModalActive.value = false;
                    editMode.value = false;
                },
            });
    }

};
const upgrade = () => {
    form.post(route("product_owner.extend", [props.customer.id, form.product_service]), {
            onError: () => {
                isModalExtendActive.value = true;
                editMode.value = true;
            },
            onSuccess: () => {
                form_reset();
                form.reset();
                isModalExtendActive.value = false;
                editMode.value = false;
            },
        });
}
const limit_tree = computed(() => {
    console.log('limit_tree', form.product_service)
    let product_service = props.products.find(e => e.id == form.product_service);
    return product_service
}
);
</script>

<template>
    <LayoutProfileDetail :customer="customer" :crumbs="crumbs">

        <Head title="Product" />
        <SectionMain>

            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                :title="editMode ? 'Chỉnh sửa' : 'Tạo mới'">
                <div class="p-6 flex-auto">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="owner" value="Gói dịch vụ" />
                            <select id="category_project_id" v-model="form.product_service" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option v-for="product in products" :key="product.id" :value="product.id">{{
                                    product.name }}</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="owner" value="Cây" />
                            <Multiselect v-model="form.tree" mode="tags" :appendNewTag="false" :createTag="false"
                                :limit="limit_tree?.number_tree" :searchable="true" label="name" valueProp="id"
                                trackBy="name" :options="totalTree" class="form-control" :classes="{
                                    tagsSearch: 'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                                    container: 'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-2xl leading-snug outline-none',
                                    tags: 'flex-grow flex-shrink flex flex-wrap items-center mt-1 pl-2 rtl:pl-0 rtl:pr-2',
                                    tag: 'bg-red-600 text-white text-xs font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
                                }" />
                        </div>


                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="owner" value="Thời gian bắt đầu áp dụng" />
                            <div date-rangepicker class="flex items-center">
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    </div>
                                    <input v-model="form.time_approve" type="date"
                                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5  placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Ngày bắt đầu" />
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="owner" value="Gói dịch vụ" />
                            <select id="category_project_id" v-model="form.state" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="active">Đang hoạt động</option>
                                <option value="expired">Dừng hoạt động</option>
                                <option value="stop">Đã hủy</option>
                            </select>
                        </div>
                    </div>
                </div>
            </CardBoxModal>

            <CardBoxModal v-model="isModalExtendActive" buttonLabel="Save" has-cancel @confirm="upgrade"
                title=" Gia hạn ">
                <div class="p-6 flex-auto">
                        <div class="w-full  px-3">
                            <InputLabel class="py-5" for="owner" :value="form.product_service.name " />

                            <InputLabel for="owner" value="Thời gian bắt đầu áp dụng" />

                            <div date-rangepicker class="flex items-center">
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    </div>
                                    <input v-model="form.time_approve" type="date"
                                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5  placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Ngày bắt đầu" />
                                </div>
                            </div>
                    </div>
                </div>
            </CardBoxModal>
            <div class="p-6 flex-auto sm:w-full">
                <div class="flex justify-between">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 hover:bg-bg_green_active" :icon="mdiPlus"
                        small @click="
                            isModalActive = true;
                        editMode = false;
                        form.reset();
                        form_reset();
                        " label="Thêm gói sản phẩm" />
                </div>
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg mt-5">
                    <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6 text-xs">STT</th>
                                <th scope="col" class="py-3 px-6 text-xs">Gói</th>
                                <th scope="col" class="py-3 px-6 text-xs">Giá (vnđ)</th>
                                <th scope="col" class="py-3 px-6 text-xs">Thời gian</th>
                                <th scope="col" class="py-3 px-6 text-xs">Trạng thái</th>
                                <th scope="col" class="py-3 px-6 text-xs">Số cây</th>
                                <th scope="col" class="py-3 px-6 text-xs">Lượt thăm miễn phí</th>
                                <th scope="col" class="py-3 px-6 text-xs">Nông sản nhận</th>
                                <th scope="col" class="py-3 px-6 text-xs">Số lần giao hàng</th>
                                <th scope="col" class="py-3 px-6 text-xs">
                                    <span class="sr-only">Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody v-if="customer">
                            <tr v-for="(product_owner, index) in customer?.product_service_owners" :key="index"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ index + 1 }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ product_owner?.product?.name }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ formatPrice(product_owner?.product?.price) }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Từ {{ moment(String(product_owner.time_approve)).format('MM/DD/YYYY') }} đến
                                    {{ moment(String(product_owner.time_end)).format('MM/DD/YYYY') }}
                                </th>
                                <th class="py-3 px-6 text-xs">
                                    <PillTag :color="product_owner.state == 'active' ? 'success' : 'danger'"
                                        :label="product_owner.state" small>
                                    </PillTag>
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ product_owner?.product?.number_tree }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ product_owner?.product?.free_visit }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <Link href="/">
                                    </Link>
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    1/{{ product_owner?.product?.number_deliveries }}
                                </th>
                                <td class="py-4 px-6 text-right flex justify-end my-3" @click="extend(product_owner)" >
                                    <BaseButton color="info" class="bg-[#F78F43] border-[0px] mx-2 text-white p-2 hover:bg-bg_green_active"
                                    type="button" data-toggle="modal" data-target="#exampleModal"
                                        label="gia hạn ngay" />
                                </td>
                                <td class="px-6 py-4 ">
                                    <div class="flex ">
                                        <Link :href="route('product_owner.contract.index', product_owner.id)" class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">Thông tin hợp đồng </Link>

                                        <Dropdown align="right" width="40" class="ml-5">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <BaseButton class="bg-[#D9D9D9] border-[#D9D9D9]"
                                                        :icon="mdiDotsVertical" small />
                                                </span>
                                            </template>

                                            <template #content>
                                                <div class="w-40">
                                                    <div
                                                        class=" justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                                        <a href="" class="hover:text-blue-700">Lịch sử gia hạn</a>
                                                    </div>
                                                    <div
                                                        class=" justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">

                                                    </div>
                                                    <div @click="edit(product_owner)"
                                                        class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                                        <p class="hover:text-blue-700"> Edit</p>
                                                        <BaseButton :icon="mdiPencil" small class="text-[#2264E5]"
                                                            type="button" data-toggle="modal" data-target="#exampleModal" />
                                                    </div>
                                                    <div @click="Delete(product_owner.id)"
                                                        class="flex justify-between items-center px-4  text-sm text-[#D12953] cursor-pointer  font-semibold">
                                                        <p class="hover:text-red-700"> Delete</p>
                                                        <BaseButton :icon="mdiTrashCanOutline" small
                                                            class="text-[#D12953]" />
                                                    </div>

                                                </div>
                                            </template>
                                        </Dropdown>
                                    </div>

                                </td>
                            </tr>
                            <pagination :links="customer.product_service_owners.links" />
                        </tbody>
                    </table>
                </div>
            </div>

        </SectionMain>
    </LayoutProfileDetail>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
