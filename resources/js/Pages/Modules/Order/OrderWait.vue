<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import OrderBar from "@/Pages/Modules/Order/OrderBar.vue";
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
const props = defineProps({
    orders: Object,
    status: String,
    status: String,
    from: String,
    to: String,
    statusGroup: Array
});
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
    this.$inertia.get(
        this.route(`orders.${props.status}`),
        { customer: this.customer },
        {
            preserveState: true,
            preserveScroll: true
        }
    );
},
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đơn hàng" />
        <SectionMain>
            <SectionTitleLineWithButton title="Quản lý đơn hàng" main></SectionTitleLineWithButton>

            <div>
                <OrderBar :statusGroup="statusGroup"></OrderBar>

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
                                            laceholder="Search Menus" data-list=".menu-category"
                                            class="block w-full p-2 pl-5 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Tìm đơn hàng bằng mã đơn hàng" required />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="min-[320px]:block sm:flex lg:flex my-3">
                            <div class="min-[320px]:w-full w-1/5 mr-3 text-gray-500">
                                <label for>Ngày tạo đơn</label>
                            </div>
                            <div class="min-[320px]:w-full w-4/5">
                                <div date-rangepicker class="flex items-center">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 text-gray-400"
                                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input name="start" type="date"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Ngày bắt đầu" />
                                    </div>
                                    <span class="mx-4 text-gray-500">đến</span>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 text-gray-400"
                                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input name="end" type="date"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Ngày kết thúc" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="min-[320px]:block sm:flex sm:my-2">
                            <div class="min-[320px]:w-full sm:w-3/12 mr-3 text-gray-500">
                                <label for>SKU</label>
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
                                            laceholder="Search Menus" data-list=".menu-category"
                                            class="block w-full p-2 pl-5 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Tìm sản phẩm bằng SKU" required />
                                    </div>
                                </form>
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
                                            v-model="customer" @keyup="searchCustomer" laceholder="Search Menus"
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
                                <div class="grid grid-cols-5 gap-4 text-xs  uppercase bg-gray-600  px-3 py-4 text-gray-400">
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
                                </div>

                                <div class=" cursor-pointer" id="accordion-open" data-accordion="open">
                                    <div id="accordion-open-heading-1" data-accordion-target="#accordion-open-body-1"
                                        aria-expanded="true" aria-controls="accordion-open-body-1"
                                        class="accordion-toggle grid grid-cols-5 gap-4 text-sm px-3 py-3 text-gray-400">
                                        <div>
                                            <a class="flex items-center">
                                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0 mr-2"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 10 6">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                                                </svg>DH - 1345</a>
                                        </div>
                                        <div>
                                            <p>Hoàng Thị Huyền</p>
                                        </div>
                                        <div>
                                            <p>098-844-2947</p>
                                        </div>
                                        <div>
                                            <p>15:05 25/03/2023</p>
                                        </div>
                                        <div>
                                            <p>Chờ</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 bg-gray-300 p-3 border rounded-lg hidden "
                                        id="accordion-open-body-1" aria-labelledby="accordion-open-heading-1">
                                        <div class="my-3 rounded-lg border">
                                            <div class="title_information p-2">
                                                <h3>Thông tin khách hàng</h3>
                                            </div>
                                            <div class="grid grid-cols-4 gap-4  bg-white py-3 px-3">
                                                <div class="block">
                                                    <p class="text-gray-500">Địa chỉ</p>
                                                    <div class="item_information p-2 bg-gray-100 rounded-lg">
                                                        <p class="text-gray-600">
                                                            Phố nhổn, quận Bắc Từ Liêm, Thành phố Hà Nội
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="block">
                                                    <p class="text-gray-500">Số nhà/ Địa chỉ cụ thể</p>
                                                    <div class="item_information p-2 bg-gray-100 rounded-lg">
                                                        <p class="text-gray-600">Số 120</p>
                                                    </div>
                                                </div>
                                                <div class="block">
                                                    <p class="text-gray-500">Ghi chú</p>
                                                    <div class="item_information p-2 bg-gray-100 rounded-lg">
                                                        <p class="text-gray-600">Giao trong tuần</p>
                                                    </div>
                                                </div>
                                                <div class="block">
                                                    <p class="text-gray-500">Hình thức thanh toán</p>
                                                    <div class="item_information p-2 bg-gray-100 rounded-lg">
                                                        <p class="text-gray-600">COD</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <table
                                            class="table table-striped w-full text-sm text-left text-gray-500 text-gray-400 rounded-lg">
                                            <thead class="text-xs text-gray-700 uppercase bg-gray-50  text-gray-400">
                                                <tr class="info">
                                                    <th>Sản phẩm</th>
                                                    <th>SL</th>
                                                    <th>Giá trước chiết khấu</th>
                                                    <th>Chiết khấu</th>
                                                    <th>Giá sau chiết khấu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    class=" bg-white  bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        <!-- <img src="/img/xop.png" alt="" /> -->
                                                        <div class="ml-3">
                                                            <h4>
                                                                Hộp carton 3 lớp sóng E | 20x30x15cm | mặt nâu
                                                            </h4>
                                                            <p class="text-gray-500">SKU: CT3LSA203015MN</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">50</td>
                                                    <td class="px-6 py-4">250,000 đ</td>
                                                    <td class="px-6 py-4">-10%</td>
                                                    <td class="px-6 py-4">225.000 đ7</td>
                                                </tr>
                                                <tr d
                                                    class=" bg-white  bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        <!-- <img src="/img/xop.png" alt="" /> -->
                                                        <div class="ml-3">
                                                            <h4>
                                                                Hộp carton 3 lớp sóng E | 20x30x15cm | mặt nâu
                                                            </h4>
                                                            <p class="text-gray-500">SKU: CT3LSA203015MN</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">50</td>
                                                    <td class="px-6 py-4">250,000 đ</td>
                                                    <td class="px-6 py-4">-10%</td>
                                                    <td class="px-6 py-4">225.000 đ7</td>
                                                </tr>
                                                <tr
                                                    class=" bg-white  bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        <img src="/img/xop.png" alt="" />
                                                        <div class="ml-3">
                                                            <h4>
                                                                Hộp carton 3 lớp sóng E | 20x30x15cm | mặt nâu
                                                            </h4>
                                                            <p class="text-gray-500">SKU: CT3LSA203015MN</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">50</td>
                                                    <td class="px-6 py-4">250,000 đ</td>
                                                    <td class="px-6 py-4">-10%</td>
                                                    <td class="px-6 py-4">225.000 đ7</td>
                                                </tr>
                                                <tr
                                                    class=" bg-white  bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        <img src="/img/xop.png" alt="" />
                                                        <div class="ml-3">
                                                            <h4>
                                                                Hộp carton 3 lớp sóng E | 20x30x15cm | mặt nâu
                                                            </h4>
                                                            <p class="text-gray-500">SKU: CT3LSA203015MN</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">50</td>
                                                    <td class="px-6 py-4">250,000 đ</td>
                                                    <td class="px-6 py-4">-10%</td>
                                                    <td class="px-6 py-4">225.000 đ7</td>
                                                </tr>
                                                <tr
                                                    class=" bg-white bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        Công tiền hàng
                                                    </td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4">450.000 đ</td>
                                                </tr>
                                                <tr
                                                    class="a bg-white bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        Chiết khấu
                                                    </td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4">
                                                        <input type="text" class="w-3/4 rounded-lg border border-gray-300"
                                                            placeholder="0%" />
                                                    </td>
                                                </tr>
                                                <tr
                                                    class=" bg-white bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        Phí ship
                                                    </td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4">
                                                        <input type="text" class="w-3/4 rounded-lg border border-gray-300"
                                                            placeholder="30,000 đ" />
                                                    </td>
                                                </tr>
                                                <tr
                                                    class=" bg-white bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        Khách phải trả
                                                    </td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4">450,000 đ</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="flex justify-between ">
                                            <button class="border rounded-lg bg-gray-100 px-3 py-2">
                                                Hủy đơn
                                            </button>
                                            <div class="flex">

                                                <select id="countries"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4.5 py-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                                    <option selected>Chưa thanh toán</option>

                                                    <option value="CA">Đã thanh toán</option>
                                                </select>
                                                <button class="px-3 py-2 ml-3  border rounded-lg bg-primary">
                                                    Đóng gói hàng
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div id="accordion-open-heading-2" data-accordion-target="#accordion-open-body-2"
                                        aria-expanded="false" aria-controls="accordion-open-body-2"
                                        class="accordion-toggle grid grid-cols-5 gap-4 text-sm px-3 py-3 text-gray-400">
                                        <div>
                                            <a class="flex items-center">
                                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0 mr-2"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 10 6">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                                                </svg>DH - 1345</a>
                                        </div>
                                        <div>
                                            <p>Hoàng Thị Huyền</p>
                                        </div>
                                        <div>
                                            <p>098-844-2947</p>
                                        </div>
                                        <div>
                                            <p>15:05 25/03/2023</p>
                                        </div>
                                        <div>
                                            <p>Chờ</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 bg-gray-300 p-3 border rounded-lg hidden "
                                        id="accordion-open-body-2" aria-labelledby="accordion-open-heading-2">
                                        <div class="my-3 rounded-lg border">
                                            <div class="title_information p-2">
                                                <h3>Thông tin khách hàng</h3>
                                            </div>
                                            <div class="grid grid-cols-4 gap-4  bg-white py-3 px-3">
                                                <div class="block">
                                                    <p class="text-gray-500">Địa chỉ</p>
                                                    <div class="item_information p-2 bg-gray-100 rounded-lg">
                                                        <p class="text-gray-600">
                                                            Phố nhổn, quận Bắc Từ Liêm, Thành phố Hà Nội
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="block">
                                                    <p class="text-gray-500">Số nhà/ Địa chỉ cụ thể</p>
                                                    <div class="item_information p-2 bg-gray-100 rounded-lg">
                                                        <p class="text-gray-600">Số 120</p>
                                                    </div>
                                                </div>
                                                <div class="block">
                                                    <p class="text-gray-500">Ghi chú</p>
                                                    <div class="item_information p-2 bg-gray-100 rounded-lg">
                                                        <p class="text-gray-600">Giao trong tuần</p>
                                                    </div>
                                                </div>
                                                <div class="block">
                                                    <p class="text-gray-500">Hình thức thanh toán</p>
                                                    <div class="item_information p-2 bg-gray-100 rounded-lg">
                                                        <p class="text-gray-600">COD</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <table
                                            class="table table-striped w-full text-sm text-left text-gray-500 text-gray-400 rounded-lg">
                                            <thead class="text-xs text-gray-700 uppercase bg-gray-50  text-gray-400">
                                                <tr class="info">
                                                    <th>Sản phẩm</th>
                                                    <th>SL</th>
                                                    <th>Giá trước chiết khấu</th>
                                                    <th>Chiết khấu</th>
                                                    <th>Giá sau chiết khấu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    class=" bg-white  bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        <img src="/img/xop.png" alt="" />
                                                        <div class="ml-3">
                                                            <h4>
                                                                Hộp carton 3 lớp sóng E | 20x30x15cm | mặt nâu
                                                            </h4>
                                                            <p class="text-gray-500">SKU: CT3LSA203015MN</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">50</td>
                                                    <td class="px-6 py-4">250,000 đ</td>
                                                    <td class="px-6 py-4">-10%</td>
                                                    <td class="px-6 py-4">225.000 đ7</td>
                                                </tr>
                                                <tr d
                                                    class=" bg-white  bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        <img src="/img/xop.png" alt="" />
                                                        <div class="ml-3">
                                                            <h4>
                                                                Hộp carton 3 lớp sóng E | 20x30x15cm | mặt nâu
                                                            </h4>
                                                            <p class="text-gray-500">SKU: CT3LSA203015MN</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">50</td>
                                                    <td class="px-6 py-4">250,000 đ</td>
                                                    <td class="px-6 py-4">-10%</td>
                                                    <td class="px-6 py-4">225.000 đ7</td>
                                                </tr>
                                                <tr
                                                    class=" bg-white  bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        <img src="/img/xop.png" alt="" />
                                                        <div class="ml-3">
                                                            <h4>
                                                                Hộp carton 3 lớp sóng E | 20x30x15cm | mặt nâu
                                                            </h4>
                                                            <p class="text-gray-500">SKU: CT3LSA203015MN</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">50</td>
                                                    <td class="px-6 py-4">250,000 đ</td>
                                                    <td class="px-6 py-4">-10%</td>
                                                    <td class="px-6 py-4">225.000 đ7</td>
                                                </tr>
                                                <tr
                                                    class=" bg-white  bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        <img src="/img/xop.png" alt="" />
                                                        <div class="ml-3">
                                                            <h4>
                                                                Hộp carton 3 lớp sóng E | 20x30x15cm | mặt nâu
                                                            </h4>
                                                            <p class="text-gray-500">SKU: CT3LSA203015MN</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">50</td>
                                                    <td class="px-6 py-4">250,000 đ</td>
                                                    <td class="px-6 py-4">-10%</td>
                                                    <td class="px-6 py-4">225.000 đ7</td>
                                                </tr>
                                                <tr
                                                    class=" bg-white bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        Công tiền hàng
                                                    </td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4">450.000 đ</td>
                                                </tr>
                                                <tr
                                                    class="a bg-white bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        Chiết khấu
                                                    </td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4">
                                                        <input type="text" class="w-3/4 rounded-lg border border-gray-300"
                                                            placeholder="0%" />
                                                    </td>
                                                </tr>
                                                <tr
                                                    class=" bg-white bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        Phí ship
                                                    </td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4">
                                                        <input type="text" class="w-3/4 rounded-lg border border-gray-300"
                                                            placeholder="30,000 đ" />
                                                    </td>
                                                </tr>
                                                <tr
                                                    class=" bg-white bg-gray-800 border-gray-700 hover:bg-gray-50 hover:bg-gray-600">
                                                    <td class="px-6 flex py-4 font-medium text-gray-900 whitespace-nowrap ">
                                                        Khách phải trả
                                                    </td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4"></td>
                                                    <td class="px-6 py-4">450,000 đ</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="flex justify-between ">
                                            <button class="border rounded-lg bg-gray-100 px-3 py-2">
                                                Hủy đơn
                                            </button>
                                            <div class="flex">

                                                <select id="countries"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4.5 py-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                                    <option selected>Chưa thanh toán</option>

                                                    <option value="CA">Đã thanh toán</option>
                                                </select>
                                                <button class="px-3 py-2 ml-3  border rounded-lg bg-primary">
                                                    Đóng gói hàng
                                                </button>
                                            </div>
                                        </div>
                                    </div>
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
