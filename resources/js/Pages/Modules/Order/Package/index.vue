<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModalFull.vue";
import PackageBar from "@/Pages/Modules/Order/Package/PackageBar.vue";
import ModalDecline from "./ModalDecline.vue";
import ModelRefund from "../ModelRefund.vue";
import {
    mdiSquareEditOutline, mdiDeleteOutline, mdiCashMultiple, mdiEyeOutline, mdiCheckCircle, mdiCheckAll,mdiTrashCanOutline
} from '@mdi/js'
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
import OrderHome from "@/Pages/Test/OrderHome.vue"
import OrderRow from "@/Pages/Modules/Order/Package/OrderRow.vue"
import InfiniteLoading from "v3-infinite-loading";
import "v3-infinite-loading/lib/style.css";
import { vFullscreenImg } from 'maz-ui'
import UploadImage from '@/Components/UploadImage.vue'
import UploadImageAuto from '@/Components/UploadImageAuto.vue'
import { emitter } from '@/composable/useEmitter';
const props = defineProps({
    orders: Object,
    status: String,
    status: String,
    from: String,
    to: String,
    statusGroup: Array
});
const filter = reactive({
    customer: null,
    name: null,
    fromDate: null,
    toDate: null,
    search: null

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
        { search: filter.search },
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
    payment_method: 'cash',
    payment_date: new Date(),
    amount_received: null,
    amount_unpaid: null,
    status: null,
    note: null,
    order: null,
    images: [],
});
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
const deleteHistory = (id) => {
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
const openAcceptPayment = (id) => {
    let query = {
        status: "complete"
    };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Duyệt khoản thanh toán này không`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {
                form.post(route("admin.orders.package.setPaymentComplete", [form.order?.id, id]), { preserveState: false }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã thêm hợp đồng với khách hàng.", "success");
                    },
                });

            }
        });
}

const save = () => {
    console.log(form);
    form.post(route("admin.orders.package.historyPayment", form.order?.id), {
        onError: () => {
            isModalActive.value = true;
            editMode.value = false;
        },
        onSuccess: () => {
            form.reset('id', 'payment_method', 'payment_date', 'amount_received', 'note');
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
const orderChangePending = () => {
    let query = {
        status: "pending"
    };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Làm mới gói dịch vụ ${props.order.order_number} !`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {

                router.post(route("admin.orders.package.orderChangeStatus", props.order.id), query,
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
    <LayoutAuthenticated>

        <!-- Modal -->
        <CardBoxModal class="w-full" v-model="isModalActive" buttonLabel="Thêm và cập nhật"

            :hasSave="form?.amount_unpaid > 0 ? true : false" has-cancel @confirm="save"
            :title="`Thanh toán cho ${form.order?.order_number}`">
            <div class="p-6 flex-auto">
                <div v-if="form?.amount_unpaid > 0" class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="amount_received" value="Số tiền" />
                        <MazInputPrice v-model="form.amount_received" currency="VND" locale="vi-VN" :min="0"
                            :max="(form?.order?.grand_total - form?.order?.price_percent)"
                            @formatted="formattedPrice = $event" />
                        <InputError class="mt-2" :message="form.errors.amount_received" />
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <InputLabel for="payment_method" value="Hình thức thanh toán" />
                        <select id="payment" v-model="form.payment_method"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected value="cash">Tiền mặt</option>
                            <option value="banking">Chuyển khoản</option>
                            <option value="payoo">Payoo</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <InputLabel for="owner" value="Ngày thanh toán" />
                        <div date-rangepicker class="flex items-center">
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                </div>
                                <VueDatePicker v-model="form.payment_date" placeholder="date" />
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <InputLabel for="note" value="Ghi chú" />
                        <TextInput id="note" v-model="form.note" type="text" class="mt-1 block w-full"
                            :class="form.errors.note ? 'border-red-500' : ''" autocomplete="name" />
                        <InputError class="mt-2" :message="form.errors.note" />
                    </div>
                    <div class="w-full px-3">
                        <UploadImage :max_files="4" v-model="form.images" :multiple="true" :label="`Chứng từ liên quan`" />
                    </div>
                </div>

                <p class="my-2 mt-5 font-semibold">Lịch sử thanh toán</p>
                <table class="table table-striped w-full text-sm text-left text-gray-500 bg-white rounded-lg">
                    <thead class="text-xs justify-between text-gray-700 uppercase">
                        <tr class="info">
                            <th>#</th>
                            <th>Loại</th>
                            <th>Ngày</th>
                            <th>Số tiền</th>
                            <th>Trạng thái duyệt</th>
                            <th>Proof</th>
                            <th>Người duyệt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="justify-between border-0 bg-white info"
                            v-for="(payment, index) in form?.order?.history_payment" :key="payment.id">
                            <td class="border-0">
                                {{ index + 1 }}
                            </td>
                            <td class="border-0">
                                {{ payment?.payment_method == 'banking' ? 'Chuyển khoản' : payment?.payment_method == "cash"
                                    ? 'Tiền mặt' : 'Payoo' }}
                            </td>
                            <td class="border-0">{{ payment?.payment_date }}</td>
                            <td class="border-0">{{ formatPrice(payment?.amount_received) }}₫</td>
                            <td class="border-0">
                                <p class="btn_label" :class="payment?.status != 'complete' ? 'partiallyPaid' : 'paid'">
                                    {{ payment?.status != 'complete' ? 'Chờ duyệt' : 'Đã duyệt' }}</p>
                            </td>
                            <td class="border-0 flex m-0 p-0">
                                <UploadImageAuto :idPayment="payment?.id" :max_files="1" v-model="form.images" :multiple="false" :old_images="payment?.order_package_payment" class="justify-start" />
                            </td>
                            <td class="border-0">
                                {{ payment.status == 'complete' ? payment.user?.name : null }}
                                <button
                                    v-if="payment.status != 'complete' && hasAnyPermission(['create-contract-complete'])"
                                    class="px-3 py-2 ml-3 text-white border rounded-lg bg-primary"
                                    @click="openAcceptPayment(payment.id)">Duyệt </button>
                                <button v-else-if="payment.status != 'complete'"
                                    class="px-3 py-2 ml-3 border-gray-black text-gray-400 border rounded-lg" disable>Duyệt
                                </button>
                            </td>
                            <td class="border-0">
                                <BaseButton v-if="payment.status != 'complete'" color="gray"
                                    class="border-0 text=gray=300 hover:text-black" :icon="mdiTrashCanOutline" small
                                    @click="deleteHistory(payment.id)" />
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </CardBoxModal>
        <SectionMain class="p-3 mt-16 rounded-xl">
            <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                <div>
                    <h2 class="font-semibold flex mr-2">
                        Quản lý đơn hợp đồng
                    </h2>
                </div>
                <div class="flex">
                    <div>
                        <Link v-if="hasAnyPermission(['add-new-package'])" :href="route('admin.orders.package.create')"
                            class="px-2 py-2 rounded-2xl text-sm text-white  rounded-lg border mx-1 bg-btn_green hover:bg-[#318f02] hover:bg-[#008000]">
                        Thêm đơn hàng hợp đồng
                        </Link>
                    </div>
                </div>
            </div>
            <div>
                <PackageBar :statusGroup="statusGroup"></PackageBar>
                <ModalDecline></ModalDecline>
                <ModelRefund></ModelRefund>
                <div class="w-full flex justify-between">
                    <div class="flex mr-2">
                        <div class="mr-4">
                            <div class="w-full  mr-3 text-gray-500">
                                <label for>Mã đơn hàng</label>
                            </div>
                            <div class="min-[320px]:w-full form_search">
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
                                            @keyup="search"
                                            class="block w-full p-2.5 pl-5 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Nhập mã đơn hàng" required />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="mr-4 w-[320px]">
                            <div class=" text-gray-500">
                                <label for>Ngày tạo đơn</label>
                            </div>
                            <div class="w-full flex flex-wrap">
                                <div class="flex ">
                                    <div class="relative">
                                        <VueDatePicker v-model="filter.fromDate" time-picker-inline />
                                    </div>
                                    <span class="mx-1 text-gray-500">đến</span>
                                    <div class="relative">
                                        <VueDatePicker v-model="filter.toDate" time-picker-inline />
                                    </div>
                                    <button @click.prevent="changeDate" name="search"
                                        class="block p-2 ml-3 text-xs text-gray-900 border border-gray-300 rounded-lg  focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-blue-500 text-white">Search</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="mr-4 flex-col flex">
                            <div class=" text-gray-500">
                                <label for>Phương thức TT</label>
                            </div>
                            <div class="">
                                <select id="countries"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                    <option selected>Tất cả</option>
                                    <option value="US">Tiền mặt</option>
                                    <option value="CA">Chuyển khoản</option>
                                    <option value="CA">Payoo</option>
                                </select>
                            </div>
                        </div>
                        <div class="mr-4 flex-col flex">
                            <div class=" text-gray-500">
                                <label for>Trạng thái TT</label>
                            </div>
                            <div class="">
                                <select id="countries"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                    <option :value="null">Tình trạng</option>
                                    <option :value="1">Đã thanh toán</option>
                                    <option :value="0">Chưa thanh toán</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="w-full mt-2 ">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto inline-block min-w-full py-2 sm:px-6 lg:px-8 p-0">
                            <table class="table_stripe min-w-full text-center text-sm font-light overflow-x-auto">
                                <thead class="font-medium">
                                    <tr>
                                        <th scope="col" class="px-3 py-2 text-left">Mã đơn hàng</th>
                                        <th scope="col" class="px-3 py-2 text-left">Ngày lập phiếu</th>
                                        <th scope="col" class="px-3 py-2 text-left">Mã phiếu</th>
                                        <th scope="col" class="px-3 py-2 text-left">Sản phẩm</th>
                                        <th scope="col" class="px-3 py-2 text-left">Ngày áp dụng (Của gói)</th>
                                        <th scope="col" class="px-3 py-2 text-left">Số tiền</th>
                                        <th scope="col" class="px-3 py-2 text-left">Loại</th>
                                        <th scope="col" class="px-3 py-2 text-left">Khách hàng</th>
                                        <th scope="col" class="px-3 py-2 text-left">Trạng thái</th>
                                        <th scope="col" class="px-3 py-2 text-left">TT duyệt</th>
                                        <th scope="col" class="px-3 py-2 text-left">TT gói</th>
                                        <th v-if="status == 'complete'" scope="col" class="px-3 py-2 text-left">Người duyệt cuối</th>
                                        <th v-if="status == 'complete'" scope="col" class="px-3 py-2 text-left">Tài liệu</th>
                                        <th scope="col" class="px-3 py-2 text-left">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order, index) in orders?.data" :key="index">
                                        <td   class="whitespace-nowrap text-left px-3 py-2 font-medium">
                                            <a class="flex items-center text-blue-600 text-[12px]">
                                                {{ order?.order_number }}
                                            </a>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 ">
                                            <p>{{formatTimeDayMonthyear(order?.created_at) }}</p>
                                            </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 ">{{ order?.idPackage }}</td>
                                        <td class="whitespace-nowrap text-left px-3 py-2">{{ order?.product_service?.name }}
                                        </td>

                                        <td class="whitespace-nowrap text-left px-3 py-2">{{ order?.time_approve }}</td>
                                        <td class="whitespace-nowrap text-left px-3 py-2">{{ formatPrice(order?.grand_total)
                                        }}</td>
                                        <td class="whitespace-nowrap text-left px-3 py-2">
                                            <p>{{ order?.type == "new" || 0 ? "tạo mới" : order?.type }}</p>
                                        </td>

                                        <td class="whitespace-nowrap text-left px-3 py-2">{{ order?.customer?.name }}</td>
                                        <td class="whitespace-nowrap text-left px-3 py-2">
                                            <p class="btn_label "
                                                :class="order?.price_percent < order?.grand_total ? 'partiallyPaid' : order?.price_percent == 0 ? 'unpaid' : 'paid'">
                                                {{ order?.price_percent < order?.grand_total ? 'Thanh toán 1 phần' :
                                                    order?.price_percent == 0 ? 'Chưa thanh toán' : 'Đã thanh toán' }}</p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2">
                                            <p class="btn_label " :class="order.payment_check ? 'paid' : 'partiallyPaid'">
                                                {{ order.payment_check ? 'đã duyệt' : 'chờ duyệt' }}</p>
                                        </td>

                                        <td class="whitespace-nowrap text-left px-3 py-2">
                                            <p
                                                :class="order.product_service_owner?.state == 'active' ? 'text-green' : 'text-red'">
                                                {{ order.product_service_owner?.state }}
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2"  v-if="status == 'decline'">
                                            <p  class="text-[12px] text-left">
                                                {{ order.reason }}
                                            </p>
                                        </td>
                                        <td v-if="status == 'complete'" class="whitespace-nowrap text-left px-3 py-2">
                                            {{ order.package_reviewer?.name }}
                                        </td>
                                        <td v-if="status == 'complete'" class="whitespace-nowrap text-left px-3 py-2">
                                            <!-- tai lieu -->
                                        </td>


                                        <td class="whitespace-nowrap text-left px-3 py-2 action">
                                            <BaseIcon :path="mdiCashMultiple"
                                                class=" text-gray-400 rounded-lg mr-2 hover:text-blue-700"
                                                data-toggle="modal" data-target="#exampleModal" @click="detail(order)"
                                                v-tooltip.top="'Thanh toán'" size="20">
                                            </BaseIcon>
                                            <a :href="route('admin.orders.package.detail', order?.id)" target="_blank"
                                                v-tooltip.top="'Chi tiết gói'">
                                                <BaseIcon :path="mdiEyeOutline"
                                                    class=" text-gray-400 rounded-lg  mr-2 hover:text-blue-700" size="20">
                                                </BaseIcon>
                                            </a>
                                            <a v-if="status == 'pending'" :href="`/admin/orders/package/edit/${order.id}`"
                                                target="_blank">
                                                <BaseIcon :path="mdiSquareEditOutline"
                                                    class=" text-gray-400 rounded-lg mr-2 hover:text-blue-700"
                                                    v-tooltip.top="'Chỉnh sửa'" size="20">
                                                </BaseIcon>
                                            </a>
                                            <BaseIcon :path="mdiDeleteOutline"
                                                class=" text-gray-400 rounded-lg  mr-2 hover:text-red-700"
                                                v-tooltip.top="'Hủy gói'" data-toggle="modal"
                                                data-target="#exampleModalDecline" @click="openDecline(order)"
                                                v-if="status == 'pending' || status == 'complete'" size="20">
                                            </BaseIcon>
                                            <BaseIcon :path="mdiCheckAll"
                                                class=" text-gray-400 rounded-lg  mr-2 hover:text-blue-700"
                                                v-tooltip.top="'Duyệt gói'"
                                                v-if="hasAnyPermission(['create-contract-complete']) && status == 'pending' && (order.payment_check == true && (order.price_percent >= order.grand_total))"
                                                @click="orderChangePacking(order)" size="20">
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
td{
    font-size: 12px;
    color: rgb(107 114 128 / var(--tw-text-opacity));
    font-family:    sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
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
<style src="@vueform/multiselect/themes/default.css"></style>

