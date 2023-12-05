<script setup>
import { computed, ref, inject, reactive, toRef } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import OrderBar from "@/Pages/Modules/Order/OrderBar.vue";
import ModalDecline from "./ModalDecline.vue";
import ModelRefund from "./ModelRefund.vue";
import ModalShipping from "./ModalShipping.vue";
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
    mdiLandFields
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
import OrderHome from "@/Pages/Test/OrderHome.vue"
import OrderRow from "@/Pages/Modules/Order/OrderRow.vue"

const props = defineProps({
    orders: Object,
    status: String,
    status: String,
    from: String,
    to: String,
    statusGroup: Array,
    shippers: Array
});

const list_order = toRef(props.orders.data)
const filter = reactive({
    customer: null,
    name: null,
    fromDate: null,
    toDate: null,
    search: null,
    payment_status: null,
    payment_method: null,
    type: null,
    per_page: 10

})
const customer = ref()
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

const searchCustomer = () => {
    router.get(route(`admin.orders.${props.status}`),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}

const Fillter = (event) => {
    router.get(route(`admin.orders.${props.status}`),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}

const fillterPaymentMethod = (event) => {
    router.get(route(`admin.orders.${props.status}`),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const search = () => {
    router.get(route(`admin.orders.${props.status}`),
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
    router.get(route(`admin.orders.${props.status}`),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}

const loadOrder = async $state => {
    console.log("loading...");


    router.get(route(`admin.orders.${props.status}`),
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
    // console.log(props.orders)

    // if (props.orders.current_page == props.orders.last_page) $state.complete();
    // else {


    //     // $state.loaded();
    // }
    // filter.per_page += 10;

};

</script>
<template>
    <LayoutAuthenticated>


        <Head title="Quản lý đơn hàng" />
        <SectionMain class="p-3 mt-16">
            <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                <div>
                    <h2 class="font-semibold  flex mr-2">
                        Quản lý đơn hàng
                        <!-- <p class="text-gray-400">( {{ $page.props.auth.total_order }} )</p> -->
                    </h2>
                </div>

                <div>

                    <Link v-if="hasAnyPermission(['create-contract-complete'])" :href="route('admin.orders.create')"
                        class="px-2 py-2 text-sm  bg-btn_green hover:bg-[#318f02] text-white p-2 rounded-lg border mx-1">
                    Tạo đơn hàng
                    </Link>
                </div>
            </div>
            <div>
                <OrderBar :statusGroup="statusGroup"></OrderBar>
                <ModalShipping :shippers="shippers" />
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
                        <div class="flex flex-col mr-4">
                            <div class=" text-gray-500">
                                <label for>Khách hàng</label>
                            </div>
                            <div class="form_search">
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
                                            v-model="filter.customer" @keyup="searchCustomer" laceholder="Search Menus"
                                            data-list=".menu-category"
                                            class="block w-full p-2.5 pl-5 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Tìm khách hàng bằng tên hoặc sđt" required />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="mr-4 flex-col flex">
                            <div class=" text-gray-500">
                                <label for>Phương thức TT</label>
                            </div>
                            <div class="">
                                <select id="countries" v-model="filter.payment_method" @change="fillterPaymentMethod"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                    <option :value="null">Tất cả</option>
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
                            <div class="">
                                <select id="countries"  v-model="filter.payment_status" @change="Fillter()"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">

                                    <option :value="null">Tình trạng</option>
                                    <option :value="1">Đã thanh toán</option>
                                    <option :value="0">Chưa thanh toán</option>
                                </select>
                            </div>
                        </div>

                        <div class="mr-4 flex-col flex">
                            <div class=" text-gray-500">
                                <label for>Loại đơn</label>
                            </div>
                            <div class="">
                                <select id="countries"  v-model="filter.type" @change="Fillter()"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">

                                    <option :value="null">Tất cả</option>
                                    <option value="retail">Đơn lẻ</option>
                                    <option value="gift_delivery">Giao quà</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- <div class="min-[320px]:block sm:block md:block lg:grid lg:gap-4 lg:grid-cols-2 my-4 ">
                    <div class="pr-3">
                        <div class="min-[320px]:block sm:flex">
                            <div class="min-[320px]:w-full sm:w-3/12 mr-3 text-gray-500">
                                <label for>Mã đơn hàng</label>
                            </div>
                            <div class="min-[320px]:w-full form_search sm:w-9/12">
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
                                            laceholder="Search Menus" data-list=".menu-category" v-model="filter.search"
                                            @keyup="search"
                                            class="block w-full p-2 pl-5 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Tìm đơn hàng bằng mã đơn hàng" required />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="min-[320px]:block sm:flex  lg:flex my-3">
                            <div class="min-[320px]:w-full sm:w-3/12 lg:w-3/12 mr-3 text-gray-500">
                                <label for>Ngày tạo đơn</label>
                            </div>
                            <div class="min-[320px]:w-full lg:w-9/12 flex flex-wrap">
                                <div class="flex ">
                                    <div class="relative">

                                        <VueDatePicker v-model="filter.fromDate" time-picker-inline />
                                    </div>
                                    <span class="mx-4 text-gray-500">đến</span>
                                    <div class="relative">
                                        <VueDatePicker v-model="filter.toDate" time-picker-inline />
                                    </div>
                                    <button @click.prevent="changeDate" name="search"
                                        class="block p-2 ml-3 text-xs text-gray-900 border border-gray-300 bg-blue-500 rounded-lg text-white hover:bg-blue-600 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">Search</button>
                                </div>

                            </div>
                        </div>

                        <div class="min-[320px]:block sm:flex my-3">
                            <div class="min-[320px]:w-full sm:w-3/12 mr-3 text-gray-500">
                                <label for>Phương thức TT</label>
                            </div>
                            <div class="min-[320px]:w-full sm:w-9/12">
                                <select id="countries" v-model="filter.type" @change="fillterPaymentMethod"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                    <option :value="null">Tất cả</option>
                                    <option value="retail">Đơn lẻ</option>
                                    <option value="gift_delivery">Giao quà</option>

                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="pl-3">
                        <div class="min-[320px]:block sm:flex sm:my-2 lg:my-0">
                            <div class="min-[320px]:w-full sm:w-3/12 mr-3 text-gray-500">
                                <label for>Khách hàng</label>
                            </div>
                            <div class="min-[320px]:w-full form_search sm:w-9/12">
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
                                            v-model="filter.customer" @keyup="searchCustomer" laceholder="Search Menus"
                                            data-list=".menu-category"
                                            class="block w-full p-2 pl-5 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Tìmkhách hàng bằng tên hoặc sđt" required />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="min-[320px]:block sm:flex my-3">
                            <div class="min-[320px]:w-full sm:w-3/12 mr-3 text-gray-500">
                                <label for>Phương thức TT</label>
                            </div>
                            <div class="min-[320px]:w-full sm:w-9/12">
                                <select id="countries" v-model="filter.payment_method" @change="fillterPaymentMethod"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                    <option :value="null">Tất cả</option>
                                    <option value="cash">Tiền mặt</option>
                                    <option value="banking">Chuyển khoản ngân hàng</option>
                                    <option value="payoo">Payoo</option>
                                </select>
                            </div>
                        </div>
                        <div class="min-[320px]:block sm:flex my-3">
                            <div class="min-[320px]:w-full sm:w-3/12 mr-3 text-gray-500">
                                <label for>Trạng thái TT</label>
                            </div>
                            <div class="min-[320px]:w-full sm:w-9/12">
                                <select id="countries" v-model="filter.payment_status" @change="Fillter()"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                    <option :value="null">Tình trạng</option>
                                    <option :value="1">Đã thanh toán</option>
                                    <option :value="0">Chưa thanh toán</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="py-2 rounded-lg px-0 col-md-12">
                    <div class="panel panel-default">
                        <div class="tableFixHead panel-body relative overflow-x-auto">
                            <div>
                                <div
                                    class="mr-2 px-2 mb-2 text-xs  uppercase bg-gray-200 grid grid-cols-9 grid-flow-col auto-cols-max py-2  text-gray-400">
                                    <div>
                                        <p>Mã đơn hàng</p>
                                    </div>
                                    <div>
                                        <p>Khách hàng</p>
                                    </div>
                                    <div>
                                        <p>SDT</p>
                                    </div>
                                    <div>
                                        <p>Thời gian tạo</p>
                                    </div>
                                    <div>
                                        <p>Trạng thái</p>
                                    </div>
                                    <div>
                                        <p>TT thanh toán</p>
                                    </div>
                                    <div>
                                        <p>Loại đơn</p>
                                    </div>
                                    <div>
                                        <p>Người tạo đơn </p>
                                    </div>
                                    <div>
                                        <p>Hành Động </p>
                                    </div>


                                </div>

                                <div v-for="(order, index) in orders.data" :key="index">
                                    <OrderRow :order="order" :status="status" />

                                </div>



                            </div>
                        </div>
                    </div>
                </div>

                <!-- <Pagination :links="orders.links" /> -->

            </div>
            <infinite-loading @infinite="loadOrder" />

        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
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

</style>
