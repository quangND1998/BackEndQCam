<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModalFull from "@/Components/CardBoxModalFull.vue";
import PillTag from '@/Components/PillTag.vue'
import ModelComplain from "./ModelComplain.vue";
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
    mdiLandFields,
    mdiCancel
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
import { initFlowbite } from 'flowbite'
import LayoutBar from '@/Layouts/LayoutBar.vue';
import FilterBar from "./FilterBar.vue";
import { emitter } from '@/composable/useEmitter';
defineProps({
    complains: Object,
    statusGroup: Array,
    roles: Object
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    state: null,
});
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
initFlowbite();
const changeState = (visit) => {
    console.log(visit.id);
    form.post(route('visit.changeStateToConfirm', visit.id), {
        onFinish: () => {
            form.reset();
        },
    });
}
const cancelState = (visit) => {
    console.log(visit.id);
    form.post(route('visit.cancelState', visit.id), {
        onFinish: () => {
            form.reset();
        },
    });
}
const OpenModalHandle = (order) => {
    emitter.emit('OpenModalHandle', order)
}
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý khiếu nại" />
        <SectionMain class="p-3 mt-16">
            <SectionTitleLineWithButton class="font-semibold flex mr-2" title="Quản lý khiếu nại" main>
            </SectionTitleLineWithButton>

            <div>
                <FilterBar :statusGroup="statusGroup"></FilterBar>
                <ModelComplain :roles="roles"></ModelComplain>
                <div class="p-2 rounded-lg col-md-12">
                    <div class="panel panel-default">
                        <div class="overflow-x-auto relative  sm:rounded-lg ">
                            <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700  bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6 text-xs">STT</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Mã HĐ</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Loại HĐ</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Ngày tạo</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Tên KH</th>
                                        <th scope="col" class="py-3 px-6 text-xs">SĐT</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Cấp độ</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Nguồn</th>
                                        <th scope="col" class="py-3 px-6 text-xs">NVTV</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Phân cấp xử lý</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Trạng thái</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Phương án xử lý</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Xử lý</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Mã phiếu</th>
                                    </tr>
                                </thead>
                                <tbody v-if="complains">
                                    <tr v-for="(complain, index) in complains.data" :key="index"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ index + complains.from }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ complain.product_service_owner?.order_package?.idPackage }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ complain.product_service_owner?.product?.life_time }}
                                        </th>

                                        <th class="py-3 px-6 text-xs">
                                            <div>{{ formatDateOnly(complain.created_at) }}</div>
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ complain?.user?.name }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ hasAnyPermission(['super-admin']) ? complain?.user?.phone_number :
                                                hidePhoneNumber(complain?.user?.phone_number) }}
                                        </th>
                                        <th class="py-3 px-6 text-xs">
                                            <div class="px-1 py-1 border rounded-2xl w-20 text-center text-[10px]"
                                                :class="complain.severity == 'urgent' ? 'bg-red' : complain.severity == 'critical' ? 'bg-[#FF6100] text-white' : 'bg-[#cacaca]'">
                                                {{ complain.severity == 'urgent' ? 'Xử lý sớm' : complain.severity ==
                                                    'critical' ? 'Nghiêm trọng' : 'Thông thường' }}</div>
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ complain?.resource }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ complain?.counselor_staff?.name }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                        </th>

                                        <th class="py-3 px-6 text-xs" >
                                            <div class="px-2 w-20 text-center py-1 rounded opacity-35"
                                            :class="complain.status == 'no_process' ? 'text-[#EE2736]' : complain.status == 'pending' ? 'text-[#F29C1F]' : complain.status == 'planning' ? 'text-[#1D75FA]'
                                                : complain.status == 'complete' ? 'text-[#4F8D06]' : 'text-[#FF6100]'">
                                                {{ complain.status == "no_process" ? 'Chưa xử lý' : complain.status == "pending" ? 'đang xử lý' : complain.status == "planning" ? 'Xem phương án'
                                                : complain.status == "complete" ? 'Đã giải quyết' : 'Hủy' }}
                                            </div>
                                        </th>
                                        <th class="py-3 px-6 text-xs">
                                            <div>Chi tiết</div>
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="text-[#1D75FA]" data-toggle="modal" data-target="#exampleModal" @click="OpenModalHandle(complain)">Xử lý</div>
                                        </th>

                                        <th class="py-1 px-6 text-right flex justify-end my-1">
                                            {{ complain.code }}
                                        </th>
                                    </tr>
                                    <pagination :links="complains.links" />
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </SectionMain>
    </LayoutAuthenticated></template>
