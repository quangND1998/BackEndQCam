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
    mdiArrowLeftCircleOutline,
    mdiArrowRightCircleOutline
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
import  calWeekOffset  from "@/composable/weekOffset";
const { getWeekOffset,MonthOffset } = calWeekOffset();
const props = defineProps({
    reminds: Object,
    offsetWeek: Number
});
let offset = props.offsetWeek;
const filter = reactive({
    fromDate:  getWeekOffset(props)[4],
    toDate:  getWeekOffset(props)[5],
    per_page: 10,
})

console.log(filter.fromDate);
console.log(filter.toDate);
console.log(offset);

const customer = ref()
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    state: null,
    cskh_selected: [],
    fromDate:  filter.fromDate,
    toDate:  filter.toDate,
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
    router.get(route(`pending.index`),
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
const save = () => {
    // console.log(form);
    form.fromDate =  filter.fromDate;
    form.toDate =  filter.toDate;
    form.post(route("admin.call_distribute.deviceSchedule"), filter, {
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
};

const cacularOffSet = (index) => {
    offset += index;
    filter.fromDate = getWeekOffset(offset)[4];
    filter.toDate = getWeekOffset(offset)[5];
    // console.log(filter.fromDate);
    console.log(offset);
    search();
}
</script>
<template>
    <LayoutAuthenticated>
        <ModelShipping></ModelShipping>

        <Head title="Danh sách pending" />
        <SectionMain class="p-3 mt-10">
            <div class="w-full">
                <div>
                    <h2 class="font-semibold  flex mr-2">
                        Pending tháng {{ MonthOffset(offset) }}
                    </h2>
                    <div class="flex w-full justify-between">
                        <div class="flex ">
                            <BaseIcon :path="mdiArrowLeftCircleOutline" class="  rounded-lg  mr-1 hover:text-red-700"
                                size="30" @click="cacularOffSet(-1)"></BaseIcon>
                            <BaseIcon :path="mdiArrowRightCircleOutline" class=" rounded-lg  mr-2 hover:text-red-700"
                                size="30" @click="cacularOffSet(1)"></BaseIcon>

                        </div>
                        <div class="flex right-0">
                            <button class="w-[120px] py-1 rounded bg-[#E9E9E9] mr-2">{{ getWeekOffset(offset)[1]
                            }}</button>
                            <button class="w-[80px] py-1 rounded bg-[#1D75FA] text-white ">Xem</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full mt-2 h-[60vh] ">
                    <div class="bg-[#5C5C5C] text-white  grid grid-cols-[repeat(7,_minmax(0,_1fr))] divide-x">
                        <div class="text-center py-2 border">STT</div>
                        <div class="text-center py-2 border">Mã HĐ</div>
                        <div class="text-center py-2 border">Loại HĐ</div>
                        <div class="text-center py-2 border">Tên KH</div>
                        <div class="text-center py-2 border">Thứ/ tuần</div>
                        <div class="text-center py-2 border">NV Chăm sóc</div>
                        <div class="text-center py-2 border">Tình trạng</div>

                    </div>
                    <div v-for="(remind, index) in reminds.data" :key="remind.id" :index="index"
                        class="grid grid-cols-[repeat(7,_minmax(0,_1fr))] divide-x divide-gray-400 border-gray-400 border-b border-x text-sm bg-white">
                        <div class="text-center border">{{ index + reminds.from }}</div>
                        <div class="pl-2 text-[#FF0000] border">{{ remind.product_service_owner?.order_package.idPackage }}</div>
                        <div class="text-center border">{{ remind.product_service_owner?.order_package?.product_service.life_time }} năm</div>
                        <div class="text-center border">{{ remind.product_service_owner?.customer?.name }}</div>
                        <div class="text-center border">{{ remind.remind_at }}</div>
                        <div class="text-center border">{{ remind.csr?.name }}</div>
                        <div class="text-center border">{{ remind.csr?.pending }}</div>
                    </div>
                </div>
                <pagination :links="reminds.links" />
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
