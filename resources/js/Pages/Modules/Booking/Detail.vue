<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModalFull.vue";
import {
    mdiSquareEditOutline, mdiDeleteOutline, mdiCashMultiple, mdiEyeOutline, mdiCheckCircle, mdiCheckAll, mdiTrashCanOutline, mdiCheck
    , mdiOpenInNew, mdiRefresh, mdiBellRingOutline, mdiCloseThick, mdiCalendarRange, mdiPrinterOutline
} from '@mdi/js'
import BaseButton from "@/Components/BaseButton.vue";
import Button from "primevue/button";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputNumber from 'primevue/inputnumber';
import TextInput from "@/Components/TextInput.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";

import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import Multiselect from '@vueform/multiselect'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import { initFlowbite } from 'flowbite'
import "v3-infinite-loading/lib/style.css";
import { vFullscreenImg } from 'maz-ui'
import { emitter } from '@/composable/useEmitter';
import VueDatepickerUi from 'vue-datepicker-ui'
import 'vue-datepicker-ui/lib/vuedatepickerui.css';
import ModalDecline from "./ModalDecline.vue";
const props = defineProps({
    booking: Object,
    status: String,
    statusGroup: Array,
    users: Object
})
const filter = reactive({
    status: null,
    fromDate: null,
    toDate: null,
    search: null,
})
const customer = ref()
const searchVal = ref("");
const swal = inject("$swal");
const isModalActive = ref(false);
const isModalHistoryActive = ref(false);
const showAction = ref(false);
const images = ref([]);
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

const Fillter = (event) => {
    console.log(filter);
    router.get(route(`admin.booking.detail`,props.booking.id),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const FillterStatus = (status) => {
    filter.status = status;
    router.get(route(`admin.booking.detail`,props.booking.id),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}

const changeDate = () => {
    let query = {
        from: filter.fromDate,
        to: filter.toDate
    };
    router.get(route(`admin.orders.package.${props.status}`), query, {
        preserveScroll: true
    });
}
const form = useForm({
    id: null,
    number_generate: null,
});
const formRef = useForm({
    id: null,
    index: null,
    ref_id: [],
    status: null,
});

const started = () => {
    console.log("khởi tạo ref");
    props.booking.history.forEach(element => {
        let ref_id = element.ref_id == null ? 'None' : element.ref_id;
        const ref = {
            id: element.id,
            ref_id: ref_id
        };
        // console.log(ref);
        formRef.ref_id.push(ref_id);
    });
    // console.log(formRef.ref_id);
}
started();
const ChangeRef = (code, index, status) => {
    formRef.index = index;
    formRef.status = status;
    axios.post(`/admin/booking/${code?.id}/changeRef`, formRef, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
        .then(response => {
            console.log(response);
            props.booking.history[index] = response.data;
        })
        .catch(error => {
            console.log(error);
        });
}
const detail = (order) => {
    isModalActive.value = true;
    isModalHistoryActive.value = false;
    form.order = order
    form.amount_unpaid = order.grand_total - order.price_percent
    form.amount_received = order.grand_total - order.price_percent
};
const totalBooking = (status) => {
    var findStatus = props.statusGroup.find(e => e.status == status);
    if (findStatus) {
        return findStatus.total;
    } else {
        return 0;
    }
}
const openDecline = (code) => {
    emitter.emit('OpenModalDecline', code)
}

const save = () => {
    // console.log(form);
    form.post(route("admin.booking.generate", props.booking?.id), {
        onError: () => {
            isModalActive.value = true;
            editMode.value = false;
        },
        onSuccess: () => {
            isModalActive.value = false;
            editMode.value = false;
        },
    });
};
const changeStatus = (code, index, status) => {

    formRef.index = index;
    formRef.status = status;
    console.log(formRef.ref_id[index]);
    if (status !="Đã hủy" && ( formRef.index == null || formRef.ref_id[index] == "None")) {
        swal.fire({
            title: "Lỗi?",
            text: "Chưa có thông tin Ref nhận!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
        }).then((result) => {
            if (result.isConfirmed) {

            }
        });
    } else {
        formRef.post(route("admin.booking.changeStatus", code?.id), { preserveState: false }, {
            onSuccess: (response) => {
                console.log(response);
                formRef.index = null;
                formRef.status = null;
            },
        });
    }
}
const changeStatusAll = (status) => {
    formRef.status = status;
    formRef.post(route("admin.booking.changeStatusAll", props.booking?.id), { preserveState: false }, {
        onSuccess: (response) => {
            console.log(response);
            formRef.index = null;
            formRef.status = null;
        },
    });
}
</script>
<template class="body_fix">
    <Head :title="`Quản lý mã booking: ${booking.ballot_number}`" />
    <LayoutAuthenticated>
        <!-- Modal -->
        <CardBoxModal class="w-full" v-model="isModalActive" buttonLabel="Thêm và cập nhật" has-cancel @confirm="save"
            title="Thanh toán cho">
            <div class="p-6 flex-auto">

            </div>
        </CardBoxModal>
        <ModalDecline></ModalDecline>
        <SectionMain class="p-3 mt-16 rounded-xl">
            <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                <div>
                    <h2 class="font-semibold flex mr-2">
                        Quản lý mã booking: {{ booking.ballot_number }}
                    </h2>
                </div>
            </div>
            <div class="">
                <div class="w-full  flex items-center  justify-between ">
                    <div class="flex ">
                        <BaseIcon :path="mdiCalendarRange" class=" text-gray-400 rounded-lg  mr-2 hover:text-red-700"
                            size="20"></BaseIcon>
                        <label>Toàn thời gian</label>
                    </div>
                    <div class="flex">
                        <label @click="FillterStatus('all')" class="px-3 cursor-pointer">Tổng: {{ booking.history_count }} mã booking</label>
                        <label @click="FillterStatus('Chưa phát')" class="px-3 text-[#FF6100] cursor-pointer">Chưa phát: {{ totalBooking("Chưa phát") }}</label>
                        <label @click="FillterStatus('Đang phát')" class="px-3 text-[#1D75FA] cursor-pointer">Đang phát: {{ totalBooking("Đang phát") }} </label>
                        <label @click="FillterStatus('Đã thu hồi')" class="px-3 text-[#4F8D06] cursor-pointer">Thu hồi: {{ totalBooking("Đã thu hồi") }} </label>
                        <label @click="FillterStatus('Đã hủy')" class="px-3 text-[#FF0303] cursor-pointer">Hủy: {{ totalBooking("Đã hủy") }} </label>
                    </div>
                    <div>
                        <div class=" px-2">
                            <InputLabel for="number_generate" :value="`Nhập SL mã (${booking.ballot_code})`" />
                            <div class="flex">
                                <TextInput id="number_generate" v-model="form.number_generate" type="text"
                                    class="mr-2 py-1 block w-full" required autofocus autocomplete="number_generate" />
                                <InputError class="mt-2" :message="form.errors.number_generate" />
                                <Button @click="save" class="px-4 py-1.5 rounded bg-[#4F8D06] text-white">Tạo</Button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="w-full mt-2 flex items-center justify-between">
                    <div class="flex">
                        <div class="mr-4 flex-col flex">
                            <Button @click="changeStatusAll('phát')" class="px-4 py-1.5 rounded bg-[#1D75FA] text-white"> Phát toàn bộ</Button>
                        </div>
                        <div class="mr-4 flex-col flex">
                            <Button @click="changeStatusAll('thu')" class="px-4 py-1.5 rounded bg-[#FF6100] text-white"> Thu toàn bộ</Button>
                        </div>
                    </div>

                    <div class="flex items-center mr-4">
                        <div class=" form_search">
                                <form v-on:submit.prevent>
                                    <div class="relative">
                                        <div class="absolute inset-y-1 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-sm text-gray-500 text-gray-400"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="search" id="default-search" name="search" data-toggle="hideseek"
                                            laceholder="Search Menus" data-list=".menu-category" v-model="filter.search"
                                            @keyup="Fillter()"
                                            class="block w-full p-2 pl-5 text-xs text-gray-900 border border-gray-300 rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Nhập mã booking" required />
                                    </div>
                                </form>
                        </div>
                        <div class="mr-4 ">
                            <div class="w-full flex flex-wrap">
                                <div class="flex items-center">
                                    <span class="mx-1 text-gray-500">Từ </span>
                                    <div class="relative w-[200px]">
                                        <VueDatePicker class="py-1" v-model="filter.fromDate" time-picker-inline>
                                        </VueDatePicker>
                                    </div>
                                    <span class="mx-1 text-gray-500">đến </span>
                                    <div class="relative w-[200px]">
                                        <VueDatePicker v-model="filter.toDate" time-picker-inline />
                                    </div>
                                    <select id="payment" v-model="filter.status"
                                        class="w-[120px] bg-gray-50 border_round border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block mx-2">
                                        <option selected value="Đang phát">Đang phát</option>
                                        <option value="Chưa phát">Chưa phát</option>
                                        <option value="Đã thu hồi">Đã thu hồi</option>
                                        <option value="Đã hủy">Đã hủy</option>
                                    </select>
                                    <!-- <Button class="mx-2 px-3 rounded py-1.5 bg-[#EAEAEA] text-black">Chưa phát</Button> -->
                                    <Button @click="Fillter()" class="px-4 py-2 rounded bg-[#4F8D06] text-white">Lọc</Button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <Link :href="route('admin.booking.print',props.booking.id)" class="flex">
                            <BaseIcon :path="mdiPrinterOutline" class=" text-gray-400 rounded-lg  mr-2 hover:text-red-700"
                                size="60"></BaseIcon>
                            <p>In</p>
                        </Link>

                    </div>

                </div>
                <div class="w-full mt-2 ">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto inline-block min-w-full  sm:px-6 lg:px-8 m-0 p-0 h-[60vh]">
                            <table class="text-center mx-auto text-sm font-light overflow-x-auto">
                                <thead class="font-medium">
                                    <tr>
                                        <th class="px-3 py-2 text-left">STT</th>
                                        <th class="px-3 py-2 text-left">Mã booking</th>
                                        <th class="px-4 py-2 text-left">Ref nhận</th>
                                        <th class="px-3 py-2 text-left">Date giao</th>
                                        <th class="px-3 py-2 text-left">Date thu về</th>
                                        <th class="px-3 py-2 text-left">Tình trạng</th>
                                        <th class="px-3 py-2 text-left">Lý do</th>
                                        <th class="px-3 py-2 text-left">Hành động</th>

                                    </tr>
                                </thead>
                                <tbody class="text-left">
                                    <tr v-for="(code, index) in booking.history" :key="index">
                                        <td class=" text-left px-3 py-2 text-gray-500">{{ index + 1 }}</td>
                                        <td class=" text-left px-3 py-2 font-bold " :class="code.status == 'Chưa phát' ? 'text-[#FF6100]' : code.status == 'Đang phát' ? 'text-[#1D75FA]' :
                                            code.status == 'Đã thu hồi' ? 'text-[#4F8D06]' : 'text-[#FF0000]'">{{
                                            code?.ballot_code
                                        }}</td>
                                        <td class="w-[300px] px-4">
                                            <Multiselect @select="ChangeRef(code, index,'select')" @clear="ChangeRef(code, index,'huy')"
                                                 v-model="formRef.ref_id[index]"
                                                :searchable="true" label="name" valueProp="id" trackBy="name"
                                                placeholder="None" :options="users" :classes="{
                                                    placeholder: 'text-[12px] relative text-left w-full px-3',
                                                    tagsSearch: 'absolute text-left fit-content bg-gray-50 inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-[10px] font-sans box-border w-full',
                                                    container: 'relative w-full  items-center  box-border cursor-pointer border  rounded  text-[12px]  '
                                                }">
                                                <template v-slot:singlelabel="{ value }">
                                                    <div class="multiselect-single-label">
                                                        {{ value.name }}
                                                    </div>
                                                </template>

                                                <template v-slot:option="{ option }">
                                                    {{ option.name }} ({{ option?.phone_number }})
                                                </template>
                                            </Multiselect>
                                            <!-- <Button @click="ChangeRef(code,index)">Check</Button> -->
                                        </td>
                                        <td class=" text-left px-3 py-2  text-gray-500  ">
                                            <div class="flex items-center justify-between">
                                                {{ code.dateStart != null ? formatTimeDayMonthyear(code?.dateStart) :
                                                    "dd/mm/yy" }}
                                                <BaseIcon :path="mdiCalendarRange"
                                                    class=" text-gray-400 rounded-lg  mr-2 hover:text-red-700" size="20">
                                                </BaseIcon>
                                            </div>
                                        </td>
                                        <td class=" text-left px-3 py-2  text-gray-500  ">
                                            <div class="flex items-center justify-between">
                                                {{ code.dateEnd != null ? formatTimeDayMonthyear(code?.dateEnd) : "dd/mm/yy"
                                                }}
                                                <BaseIcon :path="mdiCalendarRange"
                                                    class=" text-gray-400 rounded-lg  mr-2 hover:text-red-700" size="20">
                                                </BaseIcon>
                                            </div>

                                        </td>
                                        <td class=" text-left px-3 py-2 " :class="code.status == 'Chưa phát' ? 'text-[#FF6100]' : code.status == 'Đang phát' ? 'text-[#1D75FA]' :
                                            code.status == 'Đã thu hồi' ? 'text-[#4F8D06]' : 'text-[#FF0000]'">{{
        code?.status }}
                                        </td>
                                        <td class=" text-left px-3 py-2 text-gray-500">{{
                                            code?.reason }}
                                        </td>
                                        <td class=" text-left px-3 py-2 action flex items-center">
                                            <Button class=" rounded-lg mr-2 " :disabled="code.status == 'Đang phát' ? true : false" :class="code.status == 'Đang phát' ? 'text-gray':  'text-[#FF6100]' "
                                                @click="changeStatus(code, index, 'Đang phát')">
                                                Phát
                                            </Button>
                                            <Button class=" text-[#FF6100] rounded-lg mr-2 " :disabled="code.status == 'Đã thu hồi' ? true : false" :class="code.status == 'Đã thu hồi' ? 'text-gray':  'text-[#FF6100]' "
                                                @click="changeStatus(code, index, 'Đã thu hồi')">
                                                Thu
                                            </Button>
                                            <BaseIcon :path="mdiCloseThick" class=" text-[#FF0000] rounded-lg  mr-2 "
                                                v-tooltip.top="'hủy'"
                                                data-toggle="modal"
                                                data-target="#exampleModalDecline" @click="openDecline(code)"
                                                size="20">
                                            </BaseIcon>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>


        </SectionMain>
    </LayoutAuthenticated>
</template>
<style scoped>
.body_fix {
    overflow: hidden;

}

td {
    font-size: 12px;
    /* color: rgb(107 114 128 / var(--tw-text-opacity)); */
    font-family: sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
}
</style>


