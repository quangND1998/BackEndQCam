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
const props = defineProps({
    booking: Object,
    status: String,
    status: String,
    from: String,
    to: String,
    statusGroup: Array,
    users: Object
});
const filter = reactive({
    name: null,
    date: null,
    toDate: null,
    search: null,
    payment_status: null,
    payment_method: null,
    type: null,
    per_page: 10,
    status: props.status,
    document_status: null

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
const searchCustomer = () => {
    router.get(route(`admin.orders.package.${props.status}`),
        { customer: filter.customer },
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}

const search = () => {
    router.get(route(`admin.orders.package.${props.status}`),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const fillterPaymentMethod = (event) => {
    router.get(route(`admin.orders.package.${props.status}`),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const Fillter = (event) => {
    router.get(route(`admin.orders.package.${props.status}`),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const fillterType = (event) => {
    router.get(route(`admin.orders.package.${props.status}`),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const fillterTypeBS = (event) => {
    filter.document_status = 'BS';
    router.get(route(`admin.orders.package.${props.status}`),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const fillterTypeKT = (event) => {
    filter.document_status = 'Check';
    router.get(route(`admin.orders.package.${props.status}`),
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



const changeDate = () => {
    let query = {
        from: filter.fromDate,
        to: filter.toDate
    };
    router.get(route(`admin.orders.package.${props.status}`), query, {
        preserveScroll: true
    });
}

const showButton = () => {
    showAction.value = true
}
const hideButton = () => {
    showAction.value = false
}
const form = useForm({
    id: null,
    number_generate: null,
});
const formRef = useForm({
    id: null,
    index: null,
    ref_id: [],
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
const ChangeRef = (code,index) => {
    // axios.post(`/admin/booking/${code?.id}/changeRef`, form, {
    //     headers: {
    //         'Content-Type': 'multipart/form-data',
    //     },
    // })
    //     .then(response => {
    //         console.log(response);
    //         // form.order = response.data
    //         // form.amount_received = form.order.grand_total - form.order.price_percent
    //         // if (form.amount_received == 0) {
    //         //     $(`#form_create_${form.order.id}`).hide();
    //         // }
    //     })
    //     .catch(error => {
    //         console.log(error);
    //     });
    formRef.index = index;

    formRef.post(route("admin.booking.changeRef",code.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = false;
            },
            onSuccess: () => {
                form.reset("name", "id");
                isModalActive.value = false;
                editMode.value = false;
            },
        });
}
const detail = (order) => {
    isModalActive.value = true;
    isModalHistoryActive.value = false;
    form.order = order
    form.amount_unpaid = order.grand_total - order.price_percent
    form.amount_received = order.grand_total - order.price_percent
};
const edit = (history) => {
    isModalActive.value = false;
    isModalHistoryActive.value = true;
};
const Delete = (id) => {
    swal
        .fire({
            title: "Are you sure?",
            text: "Delete this History Payment!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.post(route("admin.orders.package.deleteHistoryPayment", [form.order?.id, id]), { preserveState: false }, {
                    onSuccess: () => {
                        swal.fire(
                            "Deleted!",
                            "Your tree has been deleted.",
                            "success"
                        );
                    },
                });
            }
        });
};


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
const orderChangePacking = (order) => {
    let query = {
        status: "complete"
    };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Duyệt gói, thiết lập hợp đồng`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {
                router.post(route("admin.orders.package.orderComplete", order.id), query,
                    {
                        preserveState: true,
                        preserveScroll: true
                    }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã thêm hợp đồng với khách hàng.", "success");
                    },
                });

            }
        });
}
const orderChangePending = (order) => {
    let query = {
        status: "pending"
    };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Làm mới gói dịch vụ ${order.order_number} !`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {

                router.post(route("admin.orders.package.orderChangeStatus", order.id), query,
                    {
                        preserveState: true,
                        preserveScroll: true
                    }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã làm mới hợp đồng, chuyển sang trạng thái chờ duyệt.", "success");
                    },
                });
            }
        });
}
const openDecline = (order) => {
    emitter.emit('OpenModalDecline', order)
}
const deleteOrder = (order) => {
    emitter.emit('OpenModalDelete', order)
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
        <SectionMain class="p-3 mt-16 rounded-xl">
            <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                <div>
                    <h2 class="font-semibold flex mr-2">
                        Quản lý mã booking: {{ booking.ballot_number }}
                    </h2>
                </div>
                <!-- <div class="flex">
                    <div class=" px-2">
                        <InputLabel for="number_generate" :value="`Nhập SL mã (${booking.ballot_code})`" />
                        <div class="flex">
                            <TextInput id="number_generate" v-model="form.number_generate" type="text" class="mr-2 block w-full" required autofocus
                            autocomplete="number_generate" />
                            <InputError class="mt-2" :message="form.errors.number_generate" />
                            <Button class="px-4 py-1.5 rounded bg-[#4F8D06] text-white">Tạo</Button>
                        </div>

                    </div>
                </div> -->
            </div>
            <div class="">
                <div class="w-full  flex items-center  justify-between ">
                    <div class="flex ">
                        <BaseIcon :path="mdiCalendarRange" class=" text-gray-400 rounded-lg  mr-2 hover:text-red-700"
                            size="20"></BaseIcon>
                        <label>Toàn thời gian</label>
                    </div>
                    <div class="flex">
                        <label class="px-3">Tổng: {{ booking.history_count }} mã booking</label>
                        <label class="px-3 text-[#FF6100]">Chưa phát: {{ booking.history_count }}</label>
                        <label class="px-3 text-[#1D75FA]">Đang phát: {{ booking.history_count }} </label>
                        <label class="px-3 text-[#4F8D06]">Thu hồi: {{ booking.history_count }} </label>
                        <label class="px-3 text-[#FF0303]">Hủy: {{ booking.history_count }} </label>
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
                <div class="w-full flex items-center justify-between">
                    <div class="flex">
                        <div class="mr-4 flex-col flex">
                            <Button class="px-3 py-1.5 rounded bg-[#1D75FA] text-white"> Phát toàn bộ</Button>
                        </div>
                        <div class="mr-4 flex-col flex">
                            <Button class="px-3 py-1.5 rounded bg-[#FF6100] text-white"> Thu toàn bộ</Button>
                        </div>
                    </div>

                    <div class="flex mr-2">

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
                                    <Button class="mx-2 px-3 rounded py-1.5 bg-[#EAEAEA] text-black">Chưa phát</Button>
                                    <Button @click="save" class="px-4 py-1.5 rounded bg-[#4F8D06] text-white">Lọc</Button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flex">

                        <BaseIcon :path="mdiPrinterOutline" class=" text-gray-400 rounded-lg  mr-2 hover:text-red-700"
                            size="60"></BaseIcon>
                        <p>In</p>
                    </div>

                </div>
                <div class="w-full mt-2 ">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto inline-block min-w-full  sm:px-6 lg:px-8 m-0 p-0 h-[60vh]">
                            <table class=" min-w-full text-center text-sm font-light overflow-x-auto">
                                <thead class="font-medium">
                                    <tr>
                                        <th scope="col" class="px-3 py-2 text-left">STT</th>
                                        <th scope="col" class="px-3 py-2 text-left">Mã booking</th>
                                        <th scope="col" class="px-3 py-2 text-left">Ref nhận</th>
                                        <th scope="col" class="px-3 py-2 text-left">Date giao</th>
                                        <th scope="col" class="px-3 py-2 text-left">Date thu về</th>
                                        <th scope="col" class="px-3 py-2 text-left">Tình trạng</th>
                                        <th scope="col" class="px-3 py-2 text-left">Lý do</th>
                                        <th scope="col" class="px-3 py-2 text-left">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(code, index) in booking.history" :key="index">
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">{{ index + 1 }}</td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">{{ code?.ballot_code
                                        }}</td>
                                        <td>
                                            <Multiselect @select="ChangeRef(code,index)"  v-model="formRef.ref_id[index]"
                                                :searchable="true" label="name" valueProp="id" trackBy="name"
                                                placeholder="None" :options="users" :classes="{
                                                    tagsSearch: 'absolute text-left fit-content bg-gray-50 inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                                                    container: 'relative border_round mx-auto w-full bg-gray-50  items-center  box-border cursor-pointer border border-gray-300 rounded bg-gray-50 text-sm  '
                                                }">
                                                <template v-slot:singlelabel="{ value }">
                                                    <div class="multiselect-single-label">
                                                        {{ value.name }}
                                                    </div>
                                                </template>

                                                <template v-slot:option="{ option }">
                                                    {{ option.name }} (Team: {{ option.team?.name }})
                                                </template>
                                            </Multiselect>
                                            <!-- <Button @click="ChangeRef(code,index)">Check</Button> -->
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 flex items-center text-gray-500">
                                            <p>{{ code.dateStart }}
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 flex items-center text-gray-500">
                                            <p>{{ formatTimeDayMonthyear(code?.dateEnd) }}
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">{{
                                            code?.status }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">{{
                                            code?.reason }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 action">
                                            <Button class=" text-gray-400 rounded-lg mr-2 hover:text-blue-700">
                                                Phát
                                            </Button>
                                            <Button class=" text-gray-400 rounded-lg mr-2 hover:text-blue-700">
                                                Thu
                                            </Button>
                                            <BaseIcon :path="mdiCloseThick"
                                                class=" text-gray-400 rounded-lg  mr-2 hover:text-red-700"
                                                v-tooltip.top="'xóa'" @click="Delete(code)" size="20">
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

.table_stripe tr th:last-child,
.table_stripe td:last-child {
    position: sticky;
    width: 100px;
    right: 0;
    z-index: 10;
    background: #fff;
}

.table_stripe tr th:last-child {
    z-index: 11;
}

.table_stripe tr th {
    position: sticky;
    top: 0;
    z-index: 9;
    background: #fff;
}
</style>


