<script setup>
import { computed, ref, inject, reactive } from "vue";
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
    mdiPackage,
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
import MazInputNumber from 'maz-ui/components/MazInputNumber'
import MazPicker from 'maz-ui/components/MazPicker'
import InputNumber from 'primevue/inputnumber';
const props = defineProps({
    commissions: Object
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    // name: null,
    spend: null,
    commission: null,
    type: 'sale',
    greater: 0
});


const save = () => {
    console.log(form);
    if (editMode.value == true) {
        form.post(route("commission.update", form.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true;
            },
            onSuccess: () => {
                form.reset();
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    } else {
        form.post(route("commission.store"), {
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
    }

    // form.id = permission.id;
    // form.name = permission.name
};


const selected = ref([])
const selectAll = computed({
    get() {
        return props.commissions.data
            ? selected.value.length == props.commissions.data
            : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            props.commissions.data.forEach(function (commission) {
                array_selected.push(commission.id);
            });
        }
        selected.value = array_selected;
    }
});
const searchFilter = () => {
    let query = {
        search: searchVal.value
    };
    router.get(route("commission.index"), query, {
        preserveState: true
        // only: ["image360s", "errors", 'flash'],
    });
}
const edit = (commission) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = commission.id;
    // form.name = commission.name;
    form.spend = commission.spend;
    form.commission = commission.commission;
    form.type = commission.type,
        form.greater = commission.greater


};
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);

const changeStatus = (data, event) => {
    let query = {

        status: event.target.checked
    };
    router.post(route("commission.changeStatus", data.id), query, {
        preserveState: false
        // only: ["image360s", "errors", 'flash'],
    });
}


</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý Chính sách hoa hồng" />
        <SectionMain class="p-3 mt-8">
            <SectionTitleLineWithButton title=" Mã giảm giá" main></SectionTitleLineWithButton>
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 my-2 mr-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" @keyup="searchFilter" placeholder="Search" aria-label="Search"
                            size="24" />
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green hover:bg-[#318f02] text-white p-2 hover:bg-[#008000]"
                        :icon="mdiPlus" small @click="
                            isModalActive = true;

                        " label="Tạo Chính sách Hoa hồng" />
                </div>
            </div>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                classSize="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-2/5 xl:w-5/12 z-50 overflow-auto"
                :title="editMode ? 'Cập nhật Chính sách Hoa hồng' : 'Tạo  Chính sách Hoa hồng'">
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <div class="my-2">
                            <InputLabel for="name" value="Danh cho" />
                            <select id="countries" v-model="form.type"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="sale">Sale</option>
                                <option value="ctv">Cộng tác viên</option>
                                <option value="sale_manager">Quản lý sale</option>
                                <option value="telesale">Telesale</option>
                            </select>
                        </div>
                        <div class="my-2">

                            <InputLabel for="name" value="Doanh thu" />

                            <InputNumber v-model="form.spend" :min="0"
                                inputClass="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                            <InputError class="mt-2" :message="form.errors.spend" />
                        </div>
                    </div>
                    <div>

                        <!-- <div class="my-2">
    <InputLabel for="name" value="Tên chính sách" />
    <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required />
    <InputError class="mt-2" :message="form.errors.name" />
</div> -->

                        <div class="my-2">
                            <InputLabel for="name" value="Lớn hơn hay nhỏ hơn cột doanh thu" />
                            <select id="countries" v-model="form.greater"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option :value="0">Nhỏ hơn</option>
                                <option :value="1">Lớn hơn</option>

                            </select>
                        </div>
                        <div class="my-2">
                            <InputLabel for="name" value="Hoa hồng %" />

                            <InputNumber v-model="form.commission" :minFractionDigits="1" :min="0" :max="100"
                                inputClass="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                            <InputError class="mt-2" :message="form.errors.commission" />
                        </div>
                    </div>
                </div>
            </CardBoxModal>
            <!-- End Modal -->
            <div class="mt-5">
                <div class="relative mt-5 ">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 flex items-center">
                                    <input type="checkbox" v-model="selectAll"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                    #
                                </th>
                                <!-- <th scope="col" class="px-6 py-3">
                                    name
                                </th> -->
                                <th scope="col" class="px-6 py-3">
                                    Mục tiêu (VNĐ)
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    Giá trị hoa hồng
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    Dành cho
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Trạng thái
                                </th>
                                <th scope="col" class="px-6 py-3">
                                  Điều kiện
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    Hành động
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(commission, index) in commissions.data" :key="index"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox" v-model="selected"
                                            :value="commission.id"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        {{ index + 1 }}
                                    </div>
                                </th>
                                <!-- <td class="px-6 py-4">
                                    {{ commission.name }}
                                </td> -->

                                <td class="px-6 py-4">
                                    {{ formatPrice(commission.spend) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ formatPrice(commission.commission) }} %
                                </td>

                                <td class="px-6 py-4">
                                    {{ commission.type }}
                                </td>

                                <td class="px-2 py-4">

                                    <span v-if="commission.status == 0"
                                        class="bg-red-500 text-white text-sm font-medium  px-1 py-1 rounded dark:bg-red-900 dark:text-red-300">Chưa
                                        kích hoạt</span>
                                    <span v-else
                                        class="bg-lime-500 text-white text-sm font-medium  px-1 py-1 rounded dark:bg-lime-600 dark:text-green-300">Kích
                                        hoạt</span>

                                </td>
                                <td class="px-2 py-4">
                                    <p class="btn_label" :class="commission.greater ==0 ? 'partiallyPaid' : 'paid'">
                                    {{ commission.greater == 0 ? 'Nhỏ hơn doanh thu' : 'Lớn hơn doanh thu' }}</p>
                                   
                                </td>

                                <td class="px-6 py-4 ">
                                    <div class="flex ">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer" :checked="commission.status"
                                                @change="changeStatus(commission, $event)">
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                            </div>
                                        </label>
                                        <Dropdown align="right" width="40" class="ml-5">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <BaseButton class="bg-[#D9D9D9] border-[#D9D9D9]"
                                                        :icon="mdiDotsVertical" small />
                                                </span>
                                            </template>

                                            <template #content>
                                                <div class="w-40">
                                                    <div @click="edit(commission)"
                                                        class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                                        <p class="hover:text-blue-700"> Edit</p>
                                                        <BaseButton :icon="mdiPencil" small class="text-[#2264E5]"
                                                            type="button" data-toggle="modal" data-target="#exampleModal" />
                                                    </div>
                                                    <!-- <div @click="Delete(commission.id)"
                                                        class="flex justify-between items-center px-4  text-sm text-[#D12953] cursor-pointer  font-semibold">
                                                        <p class="hover:text-red-700"> Delete</p>
                                                        <BaseButton :icon="mdiTrashCanOutline" small
                                                            class="text-[#D12953]" />
                                                    </div> -->
                                                </div>
                                            </template>
                                        </Dropdown>
                                    </div>

                                </td>
                            </tr>


                        </tbody>
                    </table>
                    <Pagination :links="commissions.links" />
                </div>
            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>
