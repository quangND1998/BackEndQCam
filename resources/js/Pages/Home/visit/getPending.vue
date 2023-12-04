<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import PillTag from '@/Components/PillTag.vue'

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
// import Multiselect from '@vueform/multiselect'
import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import { initFlowbite } from 'flowbite'
import LayoutBar from '@/Layouts/LayoutBar.vue';

defineProps({
    scheduleVisits: Object,
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
    form.post(route('visit.changeStateToConfirm',visit.id), {
        onFinish: () => {
            form.reset();
        },
    });
}
const cancelState = (visit) => {
    console.log(visit.id);
    form.post(route('visit.cancelState',visit.id), {
        onFinish: () => {
            form.reset();
        },
    });
}
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đặt lịch tham quan" />
        <SectionMain class="p-3 mt-16">
            <SectionTitleLineWithButton class="font-semibold flex mr-2" title="Quản lý đặt lịch tham quan" main></SectionTitleLineWithButton>
            <div>
                <LayoutBar></LayoutBar>
                <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                <div>
                    <h2 class="font-semibold  flex mr-2">
                        Quản lý Lịch tham quan
                        <!-- <p class="text-gray-400">( {{ $page.props.auth.total_order }} )</p> -->
                    </h2>
                </div>

                <div>

                    <Link v-if="hasAnyPermission(['create-schedule'])" :href="route('visit.createShedule')"
                        class="px-2 py-2 text-sm  bg-btn_green hover:bg-[#318f02] text-white p-2 rounded-lg border mx-1">
                    Tạo lịch tham quan
                    </Link>
                </div>
            </div>
                <div class="px-2 flex">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <div class="min-[320px]:w-full sm:w-3/12  md:w-3/12 mr-3 text-gray-500">
                                <label for>Khách hàng</label>
                            </div>
                            <div class="min-[320px]:w-full form_search sm:w-9/12  md:w-9/12">
                                <form v-on:submit.prevent>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-sm text-gray-500 text-gray-400"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="search" id="default-search" name="search" data-toggle="hideseek"
                                            laceholder="Search Menus" data-list=".menu-category"
                                            class="block w-full p-2 pl-5 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="    Tìm lịch bằng tên hoặc sđt khách hàng" required />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <div class="min-[320px]:w-full sm:w-3/12 md:w-3/12 mr-3 text-gray-500">
                                <label for>Đặt lịch ngày</label>
                            </div>
                            <div class="min-[320px]:w-full sm:w-9/12  md:w-9/12">
                                <div date-rangepicker class="flex items-center w-full justify-between">
                                    <div class="relative  ">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 text-gray-400"
                                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input name="start" type="date"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Ngày bắt đầu" />
                                    </div>
                                    <span class="mx-4 text-gray-500">đến</span>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 text-gray-400"
                                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input name="end" type="date"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Ngày kết thúc" />
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="p-2 rounded-lg col-md-12">
                    <div class="panel panel-default">
                        <div class="overflow-x-auto relative  sm:rounded-lg ">
                            <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700  bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6 text-xs">STT</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Khách hàng</th>
                                        <th scope="col" class="py-3 px-6 text-xs">SĐT</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Trạng thái</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Thời gian tạo</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Đặt lịch ngày</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Chi tiết</th>
                                        <th scope="col" class="py-3 px-6 text-xs">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody v-if="scheduleVisits">
                                    <tr v-for="(visit, index) in scheduleVisits.data" :key="index"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ index + 1 }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ visit?.product_owner_service?.customer?.name }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ visit?.product_owner_service?.customer?.phone_number }}
                                        </th>
                                        <th class="py-3 px-6 text-xs">
                                            <PillTag :color="visit.state == 'confirm' ? 'success' : 'danger'"
                                                :label="visit.state" small>
                                            </PillTag>
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ formatTimeDayMonthyear(visit?.created_at) }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ formatTimeDayMonthyear(visit?.date_time) }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                        </th>
                                        <th class="py-1 px-6 text-right flex justify-end my-1">
                                            <button @click="changeState(visit)"
                                                class="inline-block px-6 py-2 bg-gray-200 text-gray-700 font-black text-xs leading-tight  rounded shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out mx-2">
                                                Xác nhận
                                            </button>
                                            <button @click="cancelState(visit)"
                                                class="inline-block flex justify-center items-center p-1 px-2 item-center text-center px-6 py-2.5 text-white bg-red-600 text-gray-700 font-black text-xs leading-tight  rounded shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out mx-2">
                                                <BaseIcon :path="mdiCancel" class="text-white" size="16"></BaseIcon>
                                                Hủy
                                            </button>

                                        </th>
                                    </tr>
                                    <pagination :links="scheduleVisits.links" />
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
<!-- <style src="@vueform/multiselect/themes/default.css">
</style> -->
<style scoped>.collapse {
    visibility: inherit;
}</style>
