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
    mdiBellRingOutline
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
</script>
<template>
    <LayoutAuthenticated>
        <ModelShipping></ModelShipping>

        <Head title="Bảng quản lý quyền hợp đồng" />
        <SectionMain class="p-3 mt-10">
            <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                <div>
                    <h2 class="font-semibold  flex mr-2">
                        Bảng quản lý quyền hợp đồng
                    </h2>
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
                </div>

                <div class="w-full mt-2 ">
                    <div class="bg-[#5C5C5C] text-white  grid grid-cols-9 divide-x">
                        <div class="text-center py-2 border">STT</div>
                        <div class="text-center py-2 border">Mã HĐ</div>
                        <div class="text-center py-2 border">Loại HĐ</div>
                        <div class="text-center py-2 border">Tên KH</div>
                        <div class="text-center py-2 border">CCCD</div>
                        <div class="text-center py-2 border">Số điện thoại</div>
                        <div class="text-center py-2 border">Lỹ kế nhận quà</div>
                        <div class="text-center py-2 border">Số lần chưa nhận quà</div>
                        <div class="text-center py-2 border">Số lần trễ</div>

                    </div>
                    <div v-for="(orderPackage, index) in orderPackages.data" :key="orderPackage.id" :index="index" :orderPackage="orderPackage"
                    class="grid grid-cols-9 divide-x divide-gray-400 border-gray-400 border-b border-x text-sm bg-white">
                        <div class="text-center border">{{ index + orderPackages.from }}</div>
                        <div class="pl-2 text-[#FF0000] border">{{ orderPackage.idPackage }}</div>
                        <div class="text-center border">{{ orderPackage.product_service?.life_time }} năm</div>
                        <div class="text-center border">{{ orderPackage.customer?.name }}</div>
                        <div class="text-center border">{{ orderPackage.customer?.cic_number }}</div>
                        <div class="text-center border">
                            {{ hasAnyPermission(['super-admin']) ? orderPackage?.customer?.phone_number :
                                                hidePhoneNumber(orderPackage?.customer?.phone_number) }}
                        </div>
                        <div class="text-center border">{{ orderPackage.product_service_owner?.quantity_delivered }}</div>
                        <div class="text-center border">{{ orderPackage.product_service_owner?.product.number_receive_product - orderPackage.product_service_owner?.quantity_delivered }}</div>
                        <div class="text-center border"></div>
                    </div>
                </div>
                <pagination :links="orderPackages.links" />


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
}
</style>
