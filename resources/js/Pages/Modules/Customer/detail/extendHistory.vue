<script setup>
import { computed, ref, inject, watch, toRef } from 'vue'
import LayoutProfileDetail from '@/Layouts/LayoutProfileDetail.vue';
import { Link, useForm } from "@inertiajs/vue3";
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
import moment from 'moment';
import { useHelper } from '@/composable/useHelper';

const props = defineProps({
    product_owner: Object,
    customer: Object
});
const { multipleSelect } = useHelper();
const swal = inject('$swal')
const form = useForm({
    id: null,
    images: null,
    state: null,
});
const totalTree = toRef(props.trees);
const isModalActive = ref(false)
const editMode = ref(false)
const edit = (history_extend) => {
    console.log(history_extend);
    isModalActive.value = true
    editMode.value = true
    form.id = history_extend.id;
    form.state = history_extend.state
    console.log(form.id);
}
const crumbs = ref([

    {
        route: "customer.detail.info",
        parma: props.product_owner?.customer?.id,
        name: props.product_owner?.customer?.name
    },
    {
        route: "customer.detail.info",
        parma: props.product_owner?.customer?.id,
        name: "Amenities"
    }
])
const form_reset = () => {
    console.log("reset");
};
const save = () => {
    console.log(form.id);
        form.post(route("product_owner.history_extend", form.id), {
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


};
</script>

<template>
    <LayoutProfileDetail :customer="customer" :crumbs="crumbs">

        <Head title="Hợp đồng" />
        <SectionMain>

            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                :title="editMode ? 'Chỉnh sửa' : 'Tạo mới'">
                <div class="p-6 flex-auto">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="owner" value="Trạng thái" />
                            <select id="category_project_id" v-model="form.state" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option  value="active">Đang hoạt động</option>
                                <option value="expired">Dừng hoạt động</option>
                                <option value="stop">Đã hủy</option>
                            </select>
                        </div>
                    </div>
                </div>
            </CardBoxModal>
            <div class="p-2 flex-auto sm:w-full"> <Link :href="`/customer/${customer?.id}/products`" class="text-blue"> Gói dịch vụ {{product_owner?.product?.name}}</Link> > Lịch sử gia hạn</div>
            <div class="p-2 flex-auto sm:w-full">
                <!-- <div class="flex justify-between">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 hover:bg-bg_green_active" :icon="mdiPlus"
                        small @click="
                            isModalActive = true;
                        editMode = false;
                        form.reset();
                        form_reset();
                        " label="Tải scan hợp đồng" />
                </div> -->
                <div class=" relative shadow-md sm:rounded-lg mt-1 min-h-[400px]">
                    <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6 text-xs">STT</th>
                                <th scope="col" class="py-3 px-6 text-xs">Gói sp</th>
                                <th scope="col" class="py-3 px-6 text-xs">Giá</th>
                                <th scope="col" class="py-3 px-6 text-xs">Từ</th>
                                <th scope="col" class="py-3 px-6 text-xs">Đến</th>
                                <th scope="col" class="py-3 px-6 text-xs">State</th>
                                <th scope="col" class="py-3 px-6 text-xs">
                                    <span class="sr-only">Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody v-if="product_owner?.history_extend">
                            <tr v-for="(history_extend, index) in product_owner?.history_extend" :key="index"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ index + 1 }}
                                </th>

                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ history_extend?.product_name }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ history_extend?.price }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ history_extend?.date_from }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ history_extend?.date_to }}

                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ history_extend?.state }}

                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <Link :href="route('product_owner.contract.index', history_extend.id)"
                                                class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                            Thông tin hợp đồng</Link>
                                </th>
                                <td class="px-6 py-4 ">
                                    <div class="flex ">
                                        <div
                                            class=" justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">

                                        </div>
                                        <Dropdown align="right" width="40" class="ml-5">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <BaseButton class="bg-[#D9D9D9] border-[#D9D9D9]"
                                                        :icon="mdiDotsVertical" small />
                                                </span>
                                            </template>

                                            <template #content>
                                                <div class="w-40">
                                                    <div @click="edit(history_extend)"
                                                        class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                                        <p class="hover:text-blue-700"> Edit</p>
                                                        <BaseButton :icon="mdiPencil" small class="text-[#2264E5]"
                                                            type="button" data-toggle="modal" data-target="#exampleModal" />
                                                    </div>
                                                    <div @click="Delete(history_extend.id)"
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

                        </tbody>
                    </table>
                </div>
            </div>

    </SectionMain>
</LayoutProfileDetail></template>
<style scoped src="@vueform/multiselect/themes/default.css"></style>
