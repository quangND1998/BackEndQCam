<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import PackageBar from "@/Pages/Modules/Order/Package/PackageBar.vue";
import ModalDecline from "./ModalDecline.vue";
import ModelRefund from "./../ModelRefund.vue";
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
import OrderRow from "@/Pages/Modules/Order/Package/OrderRow.vue"

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
        { customer: filter.customer },
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}

const search = () => {
    router.get(route(`admin.orders.${props.status}`),
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
    router.get(route(`admin.orders.${props.status}`), query, {
        preserveScroll: true
    });
}

</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đơn hàng" />
        <SectionMain>
            <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                <div>
                    <h2 class="min-[320px]:text-xl sm:text-2xl font-semibold lg:text-3xl flex mr-2">
                        Quản lý đơn hàng
                        <p class="text-gray-400">( {{ $page.props.auth.total_order }} )</p>
                    </h2>
                </div>
                <div class="flex">
                    <div>
                        <Link :href="route('admin.orders.package.create')"
                            class="px-2 py-2 text-sm text-white bg-primary rounded-lg border mx-1">
                        <font-awesome-icon :icon="['fas', 'plus']" />Thêm đơn hàng hợp đồng
                        </Link>
                    </div>
                </div>
            </div>
            <div>
                <PackageBar :statusGroup="statusGroup"></PackageBar>
                <ModalDecline></ModalDecline>
                <ModelRefund></ModelRefund>
                <div class="min-[320px]:block sm:block md:block lg:grid lg:gap-4 lg:grid-cols-2 my-4">
                    <div>
                        <div class="min-[320px]:block sm:flex">
                            <div class="min-[320px]:w-full sm:w-1/5 mr-3 text-gray-500">
                                <label for>Mã đơn hàng</label>
                            </div>
                            <div class="min-[320px]:w-full form_search sm:w-4/5">
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
                        <div class="min-[320px]:block sm:flex lg:flex my-3">
                            <div class="min-[320px]:w-full lg:w-1/5 mr-3 text-gray-500">
                                <label for>Ngày tạo đơn</label>
                            </div>
                            <div class="min-[320px]:w-full lg: w-4/5 flex flex-wrap">
                                <div class="flex ">
                                    <div class="relative">

                                        <VueDatePicker v-model="filter.fromDate" time-picker-inline />
                                    </div>
                                    <span class="mx-4 text-gray-500">đến</span>
                                    <div class="relative">
                                        <VueDatePicker v-model="filter.toDate" time-picker-inline />
                                    </div>
                                </div>
                                <button @click.prevent="changeDate" name="search"
                                    class="block p-2 ml-3 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">Search</button>
                            </div>
                        </div>

                    </div>
                    <div>
                        <div class="min-[320px]:block sm:flex sm:my-2">
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
                                <select id="countries"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                    <option selected>Tất cả</option>
                                    <option value="US">Còn hàng</option>
                                    <option value="CA">Hết hàng</option>
                                </select>
                            </div>
                        </div>
                        <div class="min-[320px]:block sm:flex my-3">
                            <div class="min-[320px]:w-full sm:w-3/12 mr-3 text-gray-500">
                                <label for>Trạng thái TT</label>
                            </div>
                            <div class="min-[320px]:w-full sm:w-9/12">
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
                <div class="p-2 rounded-lg col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body relative overflow-x-auto shadow-md sm:rounded-lg">
                            <div>
                                <div class="grid grid-cols-5 gap-4 text-xs  uppercase bg-gray-600  px-3 py-4 text-gray-400 grid grid-cols-6 gap-4 text-sm px-3 py-3 text-gray-400">
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
                                        <p></p>
                                    </div>
                                </div>

                                <div v-for="(order, index) in orders" :key="index">
                                    <OrderRow :order="order" :status="status" />

                                </div>



                            </div>
                        </div>
                    </div>
                </div>



            </div>


        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
