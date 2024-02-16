<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import PillTag from "@/Components/PillTag.vue";

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
  mdiCancel,
  mdiSquareEditOutline,
  mdiCheckCircle
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";

import Dropdown from "@/Components/Dropdown.vue";
import BaseIcon from "@/Components/BaseIcon.vue";
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from "maz-ui/components/MazInputPrice";
import { initFlowbite } from "flowbite";
import LayoutBar from "@/Layouts/LayoutBar.vue";

defineProps({
  scheduleVisits: Object,
  statusGroup: Array
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
  id: null,
  name: null,
  state: null,
});
const filter = reactive({
    from: null,
    to: null,
    search: null,

})
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);

const state = reactive({
  content: "<p>2333</p>",
  _content: "",
  editorOption: {
    placeholder: "core",
    modules: {},
  },
  disabled: false,
});
initFlowbite();
const changeState = (visit) => {
  console.log(visit.id);
  form.post(route("visit.changeStateToConfirm", visit.id), {
    onFinish: () => {
      form.reset();
    },
  });
};
const cancelState = (visit) => {
  console.log(visit.id);
  form.post(route("visit.cancelState", visit.id), {
    onFinish: () => {
      form.reset();
    },
  });
};
const search = () => {
    router.get(route('visit.all'),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đặt lịch tham quan" />
        <SectionMain class="p-3 mt-16">
            <SectionTitleLineWithButton class="font-semibold flex mr-2" title="Quản lý đặt lịch tham quan" main></SectionTitleLineWithButton>
            <div>
                
                <div class="px-2 flex items-center">
                        <div class=" px-3 mb-6 md:mb-0">
                            
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
                                            v-model="filter.search" @keyup="search"
                                            laceholder="Search Menus" data-list=".menu-category"
                                            class="block w-full p-2 pl-5 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="  Tìm HĐ, SĐT" required />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class=" px-3 mb-6 md:mb-0">
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
                        <div>
                            <Link v-if="hasAnyPermission(['create-schedule'])" :href="route('visit.createShedule')"
                                class="px-3 py-2 text-sm  bg-[#27AE60] hover:bg-[#318f02] text-white p-2 rounded-lg border mx-1">
                            Thêm DV
                            </Link>
                        </div>
                        <div>
                            <Link v-if="hasAnyPermission(['create-schedule'])" :href="route('visit.createShedule')"
                                class="px-3 py-2 text-sm  bg-[#27AE60] hover:bg-[#318f02] text-white p-2 rounded-lg border mx-1">
                            Thêm Booking
                            </Link>
                        </div>
                        <div>
                            <Button class="px-3 py-2 bg-[#1D75FA] rounded-lg mx-1 text-white">Xuất</Button>
                        </div>
                </div>
                <LayoutBar :statusGroup="statusGroup"></LayoutBar>
                <div class="p-2 rounded-lg col-md-12">
                    <div class="panel panel-default">
                        <div class="overflow-x-auto relative  sm:rounded-lg ">
                            <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700  bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6 text-xs">STT</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Mã HĐ</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Tên KH</th>
                                        <th scope="col" class="py-3 px-6 text-xs">SĐT</th>
                                        <th scope="col" class="py-3 px-6 text-xs">T. Phần</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Dịch vụ</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Ngày tạo</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Ngày thăm</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Trạng thái</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Note</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Hạnh động</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Log</th> 
                                        <th scope="col" class="py-3 px-6 text-xs">Code</th>
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
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white text-red-500">
                                            {{ visit.product_owner_service?.order_package?.idPackage }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ visit?.product_owner_service?.customer?.name }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ hasAnyPermission(['super-admin']) ? visit?.product_owner_service?.customer?.phone_number :
                                                hidePhoneNumber(visit?.product_owner_service?.customer?.phone_number) }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white flex flex-col">
                                           <span>NL: {{ visit.number_adult }} </span> 
                                           <span>TE: {{ visit.number_children }}</span>
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                           <span v-for="sevice in visit.extra_services" :key="sevice.id" class="flex flex-col">
                                            {{ sevice.name }}
                                            </span>
                                           
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ formatTimeDayMonthyear(visit?.created_at) }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ formatTimeDayMonthyear(visit?.date_time) }}
                                        </th>
                                        <th class="py-3 px-6 text-xs">
                                            <!-- <PillTag :color="visit.state == 'confirm' ? 'success' : 'danger'"
                                                :label="visit.state" small>
                                            </PillTag> -->
                                            <span> {{ visit.state == "pending" ? 'Đặt lịch' : visit.state == "complete" ? 'Đã checkin' : 'Hủy' }} </span>
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ visit.description }}
                                        </th>
                                        <th class="py-1 px-6 text-right flex items-center justify-end my-1">
                                            <Link v-if="visit.state=='pending'" :href="route('visit.edit', visit.id)"
                                                >
                                                <BaseIcon :path="mdiSquareEditOutline"
                                                    class=" text-[#FF6100] rounded-lg mr-2 hover:text-blue-700"
                                                    v-tooltip.top="'Chỉnh sửa'" size="20">
                                                </BaseIcon>
                                            </Link>
                                            <BaseIcon :path="mdiCheckCircle" @click="changeState(visit)"
                                                    class=" text-[#4F8D06] rounded-lg mr-2 hover:text-blue-700"
                                                    v-tooltip.top="'Xác nhận'" size="20">
                                            </BaseIcon>
                                            <button @click="cancelState(visit)"
                                                class="">
                                                <BaseIcon :path="mdiCancel" class="text-[#FF0000]" size="20"></BaseIcon>
                                            </button>

                                        </th>
                                        <th></th>
                                        <th>

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
