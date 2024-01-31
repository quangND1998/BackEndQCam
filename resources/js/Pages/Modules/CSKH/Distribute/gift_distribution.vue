<script setup>
import { computed, ref, inject, reactive, toRef } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/PaginationDefault.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import OrderBar from "@/Pages/Modules/Order/OrderBar.vue";
import VueDatepickerUi from 'vue-datepicker-ui'
import 'vue-datepicker-ui/lib/vuedatepickerui.css';
import ModelShipping from '../ModelShipping.vue'
// import ModalDecline from "./ModalDecline.vue";
// import ModelRefund from "./ModelRefund.vue";
// import ModalShipping from "./ModalShipping.vue";
import OrderTable from './OrderTable.vue';

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
    mdiSquareEditOutline,
    mdiArrowLeftBoldCircleOutline,
    mdiLayersTripleOutline,
    mdiPhone,
    mdiBellRingOutline,
    mdiCalendarRange
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
import OrderHome from "@/Pages/Test/OrderHome.vue"
import OrderRow from "@/Pages/Modules/Order/OrderRow.vue"
import { emitter } from '@/composable/useEmitter';
const props = defineProps({
    orderPackages: Object
});

const list_order = toRef(props.orderPackages.data)
const filter = reactive({
    customer: null,
    name: null,
    fromDate: null,
    toDate: null,
    search: null,
    payment_status: null,
    payment_method: null,
    type: null,
    per_page: 10,
    selectedDate: null


})
const customer = ref()
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    state: null,
    selectedDate: [
        new Date(),
        new Date(new Date().getTime() + 9 * 24 * 60 * 60 * 1000)]
    ,
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

const search = () => {
    router.get(route(`admin.gift_distribute.index`),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}

const contents = ref([
    { id: 1, text: 'Content 1' },
    { id: 2, text: 'Content 2' },
    { id: 3, text: 'Content 3' },
]);

const selected = ref([])
const selectAll = computed({
    get() {
        return props.orderPackages.data
            ? selected.value.length == props.orderPackages.data
            : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            props.orderPackages.data.forEach(function (order) {
                array_selected.push(order.id);
            });
        }
        selected.value = array_selected;

    }
});
const totalOrder = (status) => {
    var findStatus = props.statusGroup.find(e => e.status == status);
    if (findStatus) {
        return findStatus.total;
    } else {
        return 0;
    }
}
const cellStyle = computed((order) => {
    if (order?.status === 'complete') return 'bg-emerald-600 text-white';
    if (!props.data && props.showEmpty) return 'bg-zinc-700 text-zinc-700 select-none';
    if (order?.state === SCHEDULE_VISIT_STATE.COMPLETE) return 'bg-emerald-600 text-white';
    if (order?.state === SCHEDULE_VISIT_STATE.CANCEL) return 'bg-red-600 text-white';
    if (order?.state === SCHEDULE_VISIT_STATE.PENDING && date.value.diff(new Date(), 'days') < 0) return 'bg-red-600 text-white';
    if (props.data && date.value.diff(new Date(), 'days') >= 0) return 'bg-yellow-600 text-white';
    if (date.value.diff(new Date(), 'days') < 0 && !props.allowEmpty) return 'bg-red-600 text-white';
    if (!props.data) return 'bg-zinc-700 text-white';
});
</script>
<template>
    <LayoutAuthenticated>
        <ModelShipping></ModelShipping>

        <Head title="Bảng phân bố quà theo hợp đồng" />
        <SectionMain class="p-1 mt-10">
            <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                <div>
                    <h2 class="font-semibold  flex mr-2">
                        Bảng phân bố quà theo hợp đồng
                    </h2>
                </div>
            </div>
            <div class="w-full my-3 flex">
                <div class="w-1/3">
                    <div class="w-full flex items-center py-2">
                        <BaseIcon :path="mdiBellRingOutline" class=" text-[#ff0000] rounded-lg mr-2 " size="20"></BaseIcon>
                        <p class="text-[#ff0000] cursor-pointer">Hợp đồng chưa nhận quà lần 2 (10)</p>
                    </div>
                    <div class="w-full flex items-center mb-2">
                        <BaseIcon :path="mdiBellRingOutline" class=" text-[#ff0000] rounded-lg mr-2 " size="20"></BaseIcon>
                        <p class="text-[#ff0000] cursor-pointer">Hơp đồng quá hạn 15 ngày chưa nhận quà (5)</p>
                    </div>
                </div>
                <div class="w-2/3">
                    <div class="w-full flex items-center">
                        <p class="text-[#000000] py-2">Ngày nhận quà = ngày kích hoạt + 25 ngày. trùng ngày chủ nhật chuyển
                            trước 1 ngày</p>
                    </div>
                    <div class="w-full flex items-center">
                        <p class="text-[#000000]">Lúc lên đơn sẽ chọn ngày giao dự kiến, tính từ ngày giao dự kiến + 5 ngày
                            mà đơn hàng chưa giao thành công => chưa nhận </p>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <div class="w-full flex justify-between">
                    <div class="flex mr-2">
                        <div class="flex">
                            <button class="w-[100px] py-1 rounded bg-[#1D75FA] text-white mr-2">MB</button>
                            <button class="w-[100px] py-1 rounded bg-[#E9E9E9]">MN</button>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="mr-4">
                            <div class="mx-2 min-[320px]:w-full form_search">
                                <form v-on:submit.prevent>
                                    <div class="relative">

                                        <input type="search" id="default-search" name="search" data-toggle="hideseek"
                                            data-list=".menu-category" v-model="filter.search" @keyup="search"
                                            class="block w-[200px] p-2.5  text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Tìm mã hợp đồng, tên KH   " required />
                                        <div
                                            class="absolute h-full  inset-y-0 right-0 flex items-center  pointer-events-none bg-[#FF6100] items-center p-1">
                                            <svg aria-hidden="true" class="w-6 h-6 text-sm text-gray-500 text-gray-400"
                                                fill="none" stroke="#ffffff" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <p class="font-bold px-3"> Tổng: 59</p>
                        <p class="font-bold px-3"> Chưa giao lần 2: 14</p>
                        <p class="font-bold px-3"> Quá hạn 15 ngày: 5</p>
                        <p class="font-bold px-3"> Dự kiến 10 ngày tới: 30</p>
                    </div>
                    <button class="w-[100px] py-1 rounded bg-[#E9E9E9]">Xuất</button>

                </div>

                <div class="w-full mt-2 ">
                    <div class="bg-[#5C5C5C] text-white  grid grid-cols-[repeat(18,_minmax(0,_1fr))] divide-x">
                        <div class="text-center py-2 border">STT</div>
                        <div class="text-center py-2 border">Mã HĐ</div>
                        <div class="text-center py-2 border">Loại HĐ</div>
                        <div class="text-center py-2 border">Tên KH</div>
                        <div class="text-center py-2 border">Ngày kích hoạt</div>
                        <div v-for="n in 12" :key="n" class="text-center py-2 border-0">Lần {{ n }}</div>
                        <div class="text-center py-2 border-0"></div>
                    </div>
                    <!-- <div v-for="(orderPackage, index) in orderPackages.data" :key="orderPackage.id" :index="index"
                        :orderPackage="orderPackage"
                        class="grid grid-cols-[repeat(18,_minmax(0,_1fr))] divide-x divide-gray-400 border-gray-400 border-b border-x text-sm bg-white">
                        <div class="text-center border">{{ index + orderPackages.from }}</div>
                        <div class=" text-[#FF0000] font-bold px-1 text-[13px] border">{{ orderPackage.idPackage }}</div>
                        <div class="text-center border">{{ orderPackage.product_service?.life_time }}</div>
                        <div class="text-center border">{{ orderPackage.customer?.name }}</div>
                        <div class="text-center border">{{ orderPackage.time_approve }}</div>
                        <div v-for="(date, indexD) in orderPackage?.distribute_date" :key="indexD">
                            <div v-if="indexD < 13" class="text-center py-2 border-0">
                                <div class="bg-[#3D3C3C] text-[12px] m-1 px-1 py-1  text-white flex flex-col text-center">
                                    <div>{{formatDateOnly(date.date_recevie)}}</div>
                                    <div>0</div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <OrderTable v-for="(orderPackage, index) in orderPackages.data" :key="orderPackage.id" :index="index" :orderPackage="orderPackage" :orderPackages="orderPackages" />
                </div>
                <div class="w-full flex items-center justify-between  my-3">
                    <pagination :links="orderPackages.links" />

                </div>
                <div class="w-full flex items-center justify-center mx-auto ">
                    <div class="flex ">
                        <BaseIcon :path="mdiCalendarRange" class=" text-gray-400 rounded-lg  mr-2 hover:text-red-700"
                            size="20"></BaseIcon>
                        <label>{{ currentDate() }}</label>
                    </div>
                    <div class="flex px-2 items-center mx-2">
                        <div class="flex items-center">
                            <div class="bg-[#4F8D06] w-4 h-4 mx-2 rounded"></div>
                            Thành công
                        </div>
                        <div class="flex items-center mx-2">
                            <div class="bg-[#FF0303] w-4 h-4 mx-2 rounded"></div>
                            Chưa nhận
                        </div>
                        <div class="flex items-center">
                            <div class="bg-[#FFD600] w-4 h-4 mx-2 rounded"></div>
                            Dự kiến trước 10 ngày
                        </div>
                        <div class="flex items-center mx-2">
                            <div class="bg-[#FF6100] w-4 h-4 mx-2 rounded"></div>
                            Đã lên đơn
                        </div>
                    </div>

                </div>
            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>
<style >
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
    background-color: rgb(254 252 232/var(--tw-bg-opacity));
    border-color: rgb(254 240 138/var(--tw-border-opacity));
    border-style: solid;
    border-width: 1px;
    color: rgb(202 138 4/var(--tw-text-opacity));
}

.v-calendar .input-field input {
    height: 40px;
}

.v-calendar .input-field svg.datepicker {
    fill: #65716b;
}</style>
