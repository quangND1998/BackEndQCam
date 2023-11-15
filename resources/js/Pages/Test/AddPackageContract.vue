<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import OrderBar from "@/Pages/Modules/Order/OrderBar.vue";

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
// import Multiselect from '@vueform/multiselect'
import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import { initFlowbite } from 'flowbite'
import Contract from '@/Pages/Test/Contract.vue'


const props = defineProps({
    orders: Object,
    status: String,
    status: String,
    from: String,
    to: String,
    statusGroup: Array
});


</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đơn hàng" />
        <SectionMain>
            <Contract />
            <div class="w-4/5">
                <div class="flex justify-between bg-[#F0F0F0] px-5 py-2">
                    <p class="text-[#686868]">Gói sản phẩm</p>
                    <p class="text-[#686868]">Tổng</p>
                </div>
                <div class="flex justify-between  px-5 py-2 my-2">
                    <select id="countries"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/5 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Gói nhận nuôi 1 năm</option>
                        <option value="US">United States</option>
                        <option value="CA">Canada</option>
                        <option value="FR">France</option>
                        <option value="DE">Germany</option>
                    </select>
                    <p class="text-[#686868] font-semibold">1.000.000đ</p>
                </div>
                <div class="   mx-5 py-2 mt-2 pb-5  border-b border-gray-200">
                    <p class="text-[#686868] mb-2">Áp dụng từ ngày</p>
                    <div class="relative w-1/5">
                            <VueDatePicker v-model="date" time-picker-inline />
                     </div>
                    
                </div>
                <div class="flex mt-3 mx-5">
                    <div class="w-2/5">
                        <h1 class="text-[17px] font-bold my-2">Quyền lợi nhận nuôi cây 1 năm</h1>
                        <p class="text-[#686868]">Khách hàng sẽ được </p>
                    </div>
                    <div class="w-3/5">
                        <div class="flex justify-between my-2">
                            <p class="font-bold text-lg text-[#686868]">Tổng</p>
                            <p class="font-bold text-lg text-[#686868]">1.000.000đ</p>
                        </div>
                        <div class="flex justify-between my-2">
                            <p class="font-bold text-lg text-[#686868]">VAT(x%)</p>
                            <p class="font-bold text-lg text-[#686868]">1.000.000đ</p>
                        </div>
                        <div class="flex justify-between my-2">
                            <p class="font-bold text-lg text-[#686868]">Vận chuyển</p>
                            <p class="font-bold text-lg text-[#686868]">1.000.000đ</p>
                        </div>
                        <div class="flex justify-between my-2">
                            <p class="font-bold text-lg text-[#686868]">Ưu đãi</p>
                            <p class="font-bold text-lg text-[#686868]">1.000.000đ</p>
                        </div>
                        <div class="flex justify-between my-2">
                            <p class="font-bold text-lg text-[#686868]">Tổng cộng</p>
                            <p class="font-bold text-lg text-[#686868]">1.000.000đ</p>
                        </div>
                        <div class="flex justify-between my-2">
                            <p class="font-bold text-lg text-[#686868]">Tổng cộng</p>
                            <p class="font-bold text-lg text-[#686868]">1.000.000đ</p>
                        </div>
                        <div class="flex justify-between my-2">
                            <p class="font-bold text-lg text-[#686868]">Còn lại</p>
                            <p class="font-bold text-lg text-[#686868]">1.000.000đ</p>
                        </div>
                    </div>
                </div>
            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
