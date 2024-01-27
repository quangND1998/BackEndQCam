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

import calWeekOffset from "@/composable/weekOffset";
const { getWeekOffset, getOffset } = calWeekOffset();
const props = defineProps({
    list_cskh: Object,
    offsetWeek: Number,
    orderPackagesNot: Object,
    numberNotCSKH: Number
});
let offset = props.offsetWeek;
const filter = reactive({
    fromDate: getWeekOffset(props)[4],
    toDate: getWeekOffset(props)[5],
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
    fromDate: filter.fromDate,
    toDate: filter.toDate,
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
    router.get(route(`admin.call_distribute.scheduleDetail`),
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

const save = () => {
    // console.log(form);
    form.fromDate = filter.fromDate;
    form.toDate = filter.toDate;
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

        <Head title="Kế hoạch tuần" />
        <SectionMain class="p-3 mt-10">
            <div class="w-full">
                <div>
                    <h2 class="font-semibold  flex mr-2">
                        KẾ HOẠCH TUẦN {{ getWeekOffset(offset)[0] }}
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
                <div v-if="numberNotCSKH > 0" class="w-full">
                    <div class="flex">
                        <p class="w-1/3">Chưa lên kế hoạch</p>
                        <p class="w-2/3 text-[#FF0000] font-bold"> . Có {{ numberNotCSKH }} hợp đồng mới thêm hiện chưa phân công </p>
                    </div>
                    <div class="w-full mt-2 ">
                        <div class="bg-[#5C5C5C] text-white  grid grid-cols-[repeat(14,_minmax(0,_1fr))] divide-x">
                            <div class="text-center py-2 border">STT</div>
                            <div class="text-center py-2 border">Mã HĐ</div>
                            <div class="text-center py-2 border">Loại HĐ</div>
                            <div class="text-center py-2 border">Tên KH</div>
                            <div class="text-center py-2 border">Ngày kích hoạt</div>
                            <div v-for="n in 6" :key="n" class="text-center py-2 ">
                                <div>Thứ {{ n + 1 }}</div>
                                <div>{{ getWeekOffset(offset)[2][n] }}</div>
                            </div>
                            <div class="text-center py-2 border">Thị trường</div>
                            <div class="text-center py-2 border">Tỉnh</div>
                            <div class="text-center py-2 border">Quận/Huyện</div>
                        </div>
                        <div v-for="(packageNot, index) in orderPackagesNot.data" :key="packageNot.id"
                            :index="index"
                            class="grid grid-cols-[repeat(14,_minmax(0,_1fr))] divide-x divide-gray-400 border-gray-400 border-b border-x text-sm bg-white">
                            <div class="text-center border">{{ index + 1 }}</div>
                            <div class="pl-2 text-[#FF0000] border">{{ packageNot.idPackage }}</div>
                            <div class="text-center border">{{ packageNot.product_service?.life_time }}
                                năm</div>
                            <div class="text-center border">{{ packageNot.customer?.name }}</div>
                            <div class="text-center border">{{ packageNot.time_approve }}</div>
                            <div v-for="n in 6" :key="n" class="text-center py-2 ">
                                <div v-for="(dateCall,indexCall) in packageNot.distribute_call" :key="indexCall">
                                    <div v-if="dateCall.date_call == getWeekOffset(offset)[6][n]" class="flex items-center">
                                        <div class="bg-[#3D3C3C] w-4 h-4 mx-2 rounded"></div> G
                                    </div>
                                </div>
                            </div>
                            <div class="text-center border">{{ packageNot.market }}</div>
                            <div class="text-center border">{{ packageNot.customer?.wards }}</div>
                            <div class="text-center border">{{ packageNot.customer?.district }}/{{
                                packageNot.customer?.city
                            }} </div>
                        </div>
                    </div>
                </div>
                <div v-for="(cskh, index) in list_cskh" :key="index">
                    <div class="py-2">Bảng kế hoạch {{ cskh.name }}</div>
                    <div class="w-full mt-2 ">
                        <div class="bg-[#5C5C5C] text-white  grid grid-cols-[repeat(14,_minmax(0,_1fr))] divide-x">
                            <div class="text-center py-2 border">STT</div>
                            <div class="text-center py-2 border">Mã HĐ</div>
                            <div class="text-center py-2 border">Loại HĐ</div>
                            <div class="text-center py-2 border">Tên KH</div>
                            <div class="text-center py-2 border">Ngày kích hoạt</div>
                            <div v-for="n in 6" :key="n" class="text-center py-2 ">
                                <div>Thứ {{ n + 1 }}</div>
                                <div>{{ getWeekOffset(offset)[2][n] }}</div>
                            </div>
                            <div class="text-center py-2 border">Thị trường</div>
                            <div class="text-center py-2 border">Tỉnh</div>
                            <div class="text-center py-2 border">Quận/Huyện</div>
                        </div>
                        <div v-for="(distribute_call, index) in cskh.distribute_call" :key="distribute_call.id"
                            :index="index"
                            class="grid grid-cols-[repeat(14,_minmax(0,_1fr))] divide-x divide-gray-400 border-gray-400 border-b border-x text-sm bg-white">
                            <div class="text-center border">{{ index + 1 }}</div>
                            <div class="pl-2 text-[#FF0000] border">{{ distribute_call?.order_package.idPackage }}</div>
                            <div class="text-center border">{{ distribute_call?.order_package.product_service_owner?.product?.life_time }}
                                năm</div>
                            <div class="text-center border">{{ distribute_call?.order_package.customer?.name }}</div>
                            <div class="text-center border">{{ distribute_call?.order_package.time_approve }}</div>
                            <div v-for="n in 6" :key="n" class="text-center py-2 ">
                                <div v-if="distribute_call.date_call == getWeekOffset(offset)[6][n]"
                                    class="flex items-center">
                                    <div class="w-4 h-4 mx-2 rounded" :class="distribute_call.state == 'pending' ? 'bg-[#3D3C3C]' : distribute_call.state == 'called' ? 'bg-[#4F8D06]' : distribute_call.state == 'dontAnswer' ? 'bg-[#FF0303]' : 'bg-[#1D75FA]' "></div>
                                    {{ cskh?.name }}
                                </div>
                            </div>
                            <div class="text-center border">{{ distribute_call?.order_package.market }}</div>
                            <div class="text-center border">{{ distribute_call?.order_package.customer?.wards }}</div>
                            <div class="text-center border">{{ distribute_call?.order_package.customer?.district }}/{{
                                distribute_call?.order_package.customer?.city
                            }} </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="mt-3">
                <div class="w-full flex">
                    <div class="w-full mt-2 mb-0 container flex justify-end ">
                        <div class="relative right-6  ">
                            <div class="px-4">
                                <div class="flex items-center">
                                    <div class="bg-[#4F8D06] w-4 h-4 mx-2 rounded"></div>
                                    Đã gọi điện
                                </div>
                                <div class="flex items-center ">
                                    <div class="bg-[#3D3C3C] w-4 h-4 mx-2 rounded"></div>
                                    Chưa gọi điện
                                </div>
                                <div class="flex items-center">
                                    <div class="bg-[#FF0303] w-4 h-4 mx-2 rounded"></div>
                                    Không nghe máy
                                </div>
                                <div class="flex items-center ">
                                    <div class="bg-[#1D75FA] w-4 h-4 mx-2 rounded"></div>
                                    Bắt máy, không trả lời
                                </div>
                            </div>
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
