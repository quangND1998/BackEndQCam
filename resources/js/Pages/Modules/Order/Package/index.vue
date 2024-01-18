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
    mdiSquareEditOutline, mdiDeleteOutline, mdiCashMultiple, mdiEyeOutline, mdiCheckCircle, mdiCheckAll, mdiTrashCanOutline, mdiCheck
    , mdiOpenInNew, mdiRefresh, mdiBellRingOutline
} from '@mdi/js'
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputNumber from 'primevue/inputnumber';
import TextInput from "@/Components/TextInput.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";

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
const totalOrder = (status) => {
    var findStatus = props.statusGroup.find(e => e.status == status);
    if (findStatus) {
        return findStatus.total;
    } else {
        return 0;
    }
}
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
const loadOrder = async $state => {
    console.log("loading...");
    console.log(filter);
    router.get(route(`admin.orders.package.${props.status}`),
        filter,
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: page => {
                if (props.orders.current_page == props.orders.last_page) $state.complete();
                else {
                    $state.loaded();
                }
                filter.per_page += 10;
            },
            onError: errors => {
                $state.error();
            },
        },

    );

};
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
const openAcceptDocument = (id) => {
    let query = {
        status: "complete"
    };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Duyệt tài liệu thanh toán này không`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {
                form.post(route("admin.orders.package.setPaymentCompleteDocument", [form.order?.id, id]), { preserveState: false }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã thêm hợp đồng với khách hàng.", "success");
                    },
                });

            }
        });
}


const save = () => {
    // console.log(form);
    // form.post(route("admin.orders.package.historyPayment", form.order?.id), {
    //     onError: () => {
    //         isModalActive.value = true;
    //         editMode.value = false;
    //     },
    //     onSuccess: () => {
    //         form.reset('id', 'payment_method', 'payment_date', 'amount_received', 'note');
    //         isModalActive.value = false;
    //         editMode.value = false;
    //     },
    // });
    axios.post(`/admin/orders/package/historyPayment/${form.order?.id}`, form, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            })
            .then(response => {
                console.log(response);
                form.order = response.data
                form.amount_received = form.order.grand_total - form.order.price_percent
                if(form.amount_received == 0){
                    $(`#form_create_${form.order.id}`).hide();
                }
            })
            .catch(error => {
                console.log(error);
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
    <Head title="Quản lý đơn hợp đồng" />
    <LayoutAuthenticated>

        <!-- Modal -->
        <CardBoxModal class="w-full" v-model="isModalActive" buttonLabel="Thêm và cập nhật" has-cancel @confirm="save"
            :title="`Thanh toán cho ${form.order?.idPackage} (${form.order?.order_number})`">
            <div class="p-6 flex-auto">
                <div :id="`form_create_${form.order?.id}`">
                    <div v-if="form?.amount_unpaid > 0" class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            <InputLabel for="amount_received" value="Số tiền" />
                            <MazInputPrice v-model="form.amount_received" currency="VND" locale="vi-VN" :min="0"
                                :max="(form?.order?.grand_total - form?.order?.price_percent)"
                                @formatted="formattedPrice = $event" />

                            <InputError class="mt-2" :message="form.errors.amount_received" />
                        </div>
                        <div class="w-full md:w-1/3 px-3">
                            <InputLabel for="payment_method" value="Hình thức thanh toán" />
                            <select id="payment" v-model="form.payment_method"
                                class="bg-gray-50 border_round border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected value="cash">Tiền mặt</option>
                                <option value="banking">Chuyển khoản</option>
                                <option value="payoo">Payoo</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/3 px-3">
                            <InputLabel for="owner" value="Ngày thanh toán" />
                            <div date-rangepicker class="flex items-center">
                                <div class="relative w-full border_round">
                                    <div
                                        class="absolute border_round inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    </div>
                                    <VueDatePicker class="border_round" v-model="form.payment_date" placeholder="date" />
                                </div>
                            </div>
                        </div>
                        <!-- <div class="w-full px-3">
                            <UploadImage :max_files="4" v-model="form.images" :multiple="true" :label="`Chứng từ liên quan`" />
                        </div> -->
                    </div>
                    <div class="flex justify-end" v-if="form?.amount_unpaid > 0">
                        <BaseButton label="Thêm và cập nhật" color="blue" class="hover:ring-blue-500" @click="save" />
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
                            <!-- <th>Trạng thái duyệt</th> -->
                            <th>Proof</th>
                            <th class="text-center">Duyệt TT</th>
                            <th class="text-center">Duyệt HS</th>
                            <th>Hành động</th>
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
                            <!-- <td class="border-0">
                                <p class="btn_label" :class="payment?.status != 'complete' ? 'partiallyPaid' : 'paid'">
                                    {{ payment?.status != 'complete' ? 'Chờ duyệt' : 'Đã duyệt' }}</p>
                            </td> -->
                            <td class="border-0 flex m-0 p-0">
                                <UploadImageAuto :hasDelete="payment.state_document == 1 ? false : true"
                                    :idPayment="payment?.id" :max_files="100" v-model="form.images" :multiple="true"
                                    :old_images="payment?.order_package_payment" class="justify-start" />
                            </td>
                            <td class="border-0 text-center">
                                <div v-if="payment.status == 'complete'" class="flex wrap items-center flex-col">
                                    <svg viewBox="0 0 24 24" :width="28" :height="28" fill="#00AB55" class="inline-block">
                                        <path :d="mdiCheck" />
                                    </svg>
                                    ({{ payment.user?.name }})
                                </div>

                                <div v-else>
                                    <button
                                        v-if="payment.status != 'complete' && hasAnyPermission(['create-contract-complete'])"
                                        class="px-3 py-2 ml-3 text-white border rounded-lg bg-primary"
                                        @click="openAcceptPayment(payment.id)">Duyệt </button>
                                    <button v-else-if="payment.status != 'complete'"
                                        class="px-3 py-2 ml-3 border-gray-black text-gray-400 border rounded-lg"
                                        disable>Duyệt
                                    </button>
                                </div>

                            </td>
                            <td class="border-0 text-left ">
                                <div v-if="payment.state_document == 1" class="flex wrap flex-col">
                                    <svg viewBox="0 0 24 24" :width="28" :height="28" fill="#00AB55" class="inline-block">
                                        <path :d="mdiCheck" />
                                    </svg>
                                    ({{ payment.user?.name }})
                                </div>
                                <div v-else>
                                    <button
                                        v-if="payment.state_document == 0 && payment?.order_package_payment.length > 0 && hasAnyPermission(['create-contract-complete'])"
                                        class="px-3 py-2 ml-3 text-white border rounded-lg bg-primary"
                                        @click="openAcceptDocument(payment.id)">Duyệt </button>
                                    <button v-else-if="payment.state_document == 1 || payment?.order_package_payment.length == 0"
                                        class="px-3 py-2 ml-3 border-gray-black text-gray-400 border rounded-lg"
                                        disable>Duyệt
                                    </button>
                                </div>
                            </td>

                            <td class="border-0">
                                <BaseButton v-if="payment.status != 'complete'" color="gray"
                                    class="border-0 text=gray=300 hover:text-black" :icon="mdiTrashCanOutline" small
                                    @click="deleteHistory(payment.id)" />
                            </td>
                        </tr>

                    </tbody>
                    <tfoot>
                        <!-- {{ form.order }} -->
                        <tr class="justify-between border-0 bg-gray-100 info">
                            <td class="border-0 font-bold">
                                Tổng
                            </td>
                            <td class="border-0 font-bold">
                                {{ form.order?.grand_total }}
                            </td>
                            <td class="border-0 font-bold text-[#00AB55]">
                                Đã thanh toán
                            </td>
                            <td class="border-0 font-bold text-[#00AB55]">
                                {{ form.order?.price_percent }}
                            </td>
                            <td class="border-0 font-bold text-[#F26322]">
                                Còn lại
                            </td>
                            <td class="border-0 font-bold text-[#F26322]">
                                {{ form.order?.grand_total - form.order?.price_percent }}
                            </td>
                            <td class="border-0 font-bold">
                            </td>
                            <td class="border-0 font-bold">
                            </td>
                            <td class="border-0 font-bold">
                            </td>
                        </tr>
                    </tfoot>
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
                <div class="w-full flex justify-between overflow-auto">
                    <div class="mr-4 flex-col flex">
                        <div class=" text-gray-500">
                            <label for>Loại hình</label>
                        </div>
                        <div class="w-[160px]">
                            <select id="countries" v-model="filter.type" @change="fillterType"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                <option :value="null" selected>Tất cả</option>
                                <option value="new">Lần đầu</option>
                                <option value="upgrade">Gia hạn</option>
                            </select>
                        </div>
                    </div>
                    <div class="mr-4 flex-col flex">
                        <div class=" text-gray-500">
                            <label for>Hồ sơ</label>
                        </div>
                        <div class="w-[160px]">
                            <select id="document" v-model="filter.document_status" @change="fillterType"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                <option :value="null" selected>Tất cả</option>
                                <option value="OK">OK</option>
                                <option value="BS">BS</option>
                                <option value="Check">Check</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex mr-2">

                        <div class="mr-4">
                            <div class="w-full  mr-3 text-gray-500">
                                <label for>Tìm kiếm</label>
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
                                            placeholder="Nhập mã đơn hàng, sđt, tên,..." required />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="mr-4 w-[420px]">
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
                            <div class="w-[160px]">
                                <select id="payment" v-model="filter.payment_method" @change="fillterPaymentMethod"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                    <option :value="null" selected>Tất cả</option>
                                    <option value="cash">Tiền mặt</option>
                                    <option value="banking">Chuyển khoản ngân hàng</option>
                                    <option value="payoo">Payoo</option>
                                </select>
                            </div>
                        </div>
                        <div class="mr-4 flex-col flex">
                            <div class=" text-gray-500">
                                <label for>Trạng thái TT</label>
                            </div>
                            <div class="w-[160px]">
                                <select id="State_payment" v-model="filter.payment_status" @change="Fillter()"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                    <option :value="null">Tình trạng</option>
                                    <option :value="1">Đã thanh toán</option>
                                    <option :value="0">Chưa thanh toán</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="w-full my-3">
                    <div class="w-full flex items-center">
                        <BaseIcon  :path="mdiBellRingOutline " class=" text-[#ff0000] rounded-lg mr-2 " size="20"></BaseIcon>
                        <p class="text-[#ff0000] cursor-pointer" @click="fillterTypeBS">Có {{ totalOrder('BS') }} hợp đồng chờ bổ sung HS thanh toán</p>
                    </div>
                    <div class="w-full flex items-center">
                        <BaseIcon  :path="mdiBellRingOutline " class=" text-[#ff0000] rounded-lg mr-2 " size="20"></BaseIcon>
                        <p class="text-[#ff0000] cursor-pointer" @click="fillterTypeKT">Có {{ totalOrder('Check') }} hợp đồng chờ kiểm duyệt HS thanh toán</p>
                    </div>

                </div>
                <div class="w-full mt-2 ">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto inline-block min-w-full  sm:px-6 lg:px-8 m-0 p-0 h-[60vh]">
                            <table class="table_stripe min-w-full text-center text-sm font-light overflow-x-auto">
                                <thead class="font-medium">
                                    <tr>
                                        <th scope="col" class="px-3 py-2 text-left">#</th>
                                        <th scope="col" class="px-3 py-2 text-left">Mã HĐ</th>
                                        <th scope="col" class="px-3 py-2 text-left">Ngày lập phiếu</th>
                                        <th scope="col" class="px-3 py-2 text-left">Sản phẩm</th>
                                        <th scope="col" class="px-3 py-2 text-left">Ngày áp dụng (Của gói)</th>
                                        <th scope="col" class="px-3 py-2 text-left">Số tiền</th>
                                        <th scope="col" class="px-3 py-2 text-left">TT</th>
                                        <th scope="col" class="px-3 py-2 text-left">CL</th>
                                        <!-- <th scope="col" class="px-3 py-2 text-left">Loại</th> -->
                                        <th scope="col" class="px-3 py-2 text-left">SĐT</th>
                                        <th scope="col" class="px-3 py-2 text-left">Khách hàng</th>
                                        <th scope="col" class="px-3 py-2 text-left">Trạng thái</th>
                                        <th scope="col" class="px-3 py-2 text-left">TT duyệt</th>
                                        <th scope="col" class="px-3 py-2 text-left">Hồ sơ</th>
                                        <th scope="col" class="px-3 py-2 text-left">TT gói</th>
                                        <th scope="col" class="px-3 py-2 text-left">Người tạo</th>
                                        <th scope="col" class="px-3 py-2 text-left">NV Tư vấn (Ref)</th>
                                        <th scope="col" class="px-3 py-2 text-left">Người chốt đơn (To)</th>
                                        <!-- <th scope="col" class="px-3 py-2 text-left">Nguồn khách hàng</th> -->
                                        <th scope="col" class="px-3 py-2 text-left">Lý do</th>
                                        <th scope="col" class="px-3 py-2 text-left">Người duyệt cuối</th>
                                        <th scope="col" class="px-3 py-2 text-left">Tài liệu</th>

                                        <th scope="col" class="px-3 py-2 text-left">Mã đơn hàng</th>
                                        <th scope="col" class="px-3 py-2 text-left">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order, index) in orders?.data" :key="index">
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">{{ index + 1 }}</td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">{{ order?.idPackage
                                        }}</td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 flex items-center text-gray-500">
                                            <p>{{ formatTimeDayMonthyear(order?.created_at) }}
                                            </p>
                                            <BaseIcon v-if="order.type == 'upgrade'" :path="mdiRefresh"
                                                    class=" text-[#F26322] rounded-lg mr-2 "
                                                    v-tooltip.top="'Hợp đồng gia hạn'" size="20"></BaseIcon>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">{{
                                            order?.product_service?.name }}
                                        </td>

                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">{{
                                            order?.time_approve }}</td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">{{
                                            formatPrice(order?.grand_total)
                                        }}</td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">{{
                                            formatPrice(order?.price_percent)
                                        }}</td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">{{
                                            formatPrice(order?.grand_total - order?.price_percent)
                                        }}</td>
                                        <!-- <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <p>{{ order?.type == "new" || 0 ? "tạo mới" : order?.type }}</p>
                                        </td> -->
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ hasAnyPermission(['super-admin']) ? order?.customer?.phone_number :
                                                hidePhoneNumber(order?.customer?.phone_number) }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">{{
                                            order?.customer?.name }}</td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <p class="btn_label "
                                                :class="order?.status == 'decline' ? 'huy' : order?.price_percent == 0 ? 'nhap' : order?.price_percent < order?.grand_total ? 'partiallyPaid' : order?.price_percent == 0 ? 'unpaid' : 'paid'">
                                                {{ order?.status == 'decline' ? 'Đã hủy' :  order?.price_percent == 0 ? 'Nháp' : order?.price_percent < order?.grand_total ? 'Thanh toán 1 phần' :
                                                    order?.price_percent == 0 ? 'Chưa thanh toán' : 'Đã thanh toán' }}</p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <p class="btn_label " :class="(order?.price_percent == order?.grand_total) && order.payment_check ? 'paid' : 'partiallyPaid'">
                                                {{ (order?.price_percent == order?.grand_total ) && order.payment_check ? 'đã duyệt' : (order?.price_percent < order?.grand_total && order.payment_check) ? ' duyệt 1 phần ' :  'chờ duyệt' }}</p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <p class="btn_label " :class="order.document_check && order.document_add ? 'paid' :  order.document_add && order.document_check == false? 'unpaid' : 'partiallyPaid'">
                                                <!-- {{ order.document_add }}, {{ order.document_check }} -->
                                                {{ order.document_check && order.document_add ? 'OK' :  order.document_add && order.document_check == false ? 'Check' : 'BS'}}
                                                </p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <p
                                                :class="order.product_service_owner?.state == 'active' ? 'text-green' : 'text-red'">
                                                {{ order.product_service_owner?.state }}
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order.saler?.name }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order.ref?.name }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order.leader?.name }}
                                        </td>
                                        <!-- <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order.resources?.name }} ({{ order.customer_resources }})
                                        </td> -->
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            <p class="text-[12px] text-left">
                                                {{ order.reason }}
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 text-gray-500">
                                            {{ order.package_reviewer?.name }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2">
                                            <a v-if="order?.history_extend?.contract?.lastcontract?.images.length > 0"
                                                :href="order?.history_extend?.contract?.lastcontract?.images[0].original_url"
                                                target="_blank">
                                                <BaseIcon :path="mdiOpenInNew"
                                                    class=" text-blue-400 rounded-lg mr-2 hover:text-blue-700"
                                                    v-tooltip.top="'Chi tiết hợp đồng'" size="20"></BaseIcon>
                                            </a>
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-2 font-medium">
                                            <a class="flex items-center text-blue-600 text-[12px]">
                                                {{ order?.order_number }}
                                            </a>
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
                                            <Link v-if="order.status == 'pending'"
                                                :href="`/admin/orders/package/edit/${order.id}`">
                                            <BaseIcon :path="mdiSquareEditOutline"
                                                class=" text-gray-400 rounded-lg mr-2 hover:text-blue-700"
                                                v-tooltip.top="'Chỉnh sửa'" size="20">
                                            </BaseIcon>
                                            </Link>
                                            <BaseIcon :path="mdiDeleteOutline"
                                                class=" text-gray-400 rounded-lg  mr-2 hover:text-red-700"
                                                v-tooltip.top="'Hủy gói'" data-toggle="modal"
                                                data-target="#exampleModalDecline" @click="openDecline(order)"
                                                v-if="order.status == 'pending' || order.status == 'complete'" size="20">
                                            </BaseIcon>
                                            <BaseIcon :path="mdiCheckAll"
                                                class=" text-gray-400 rounded-lg  mr-2 hover:text-blue-700"
                                                v-tooltip.top="'Duyệt gói'"
                                                v-if="hasAnyPermission(['create-contract-complete']) && order.status == 'pending' && (order.payment_check == true && (order.price_percent >= order.grand_total))"
                                                @click="orderChangePacking(order)" size="20">
                                            </BaseIcon>
                                        </td>
                                    </tr>
                                    <infinite-loading @infinite="loadOrder" />
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
}</style>


