<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
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
    mdiPackage,
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
import MazInputNumber from 'maz-ui/components/MazInputNumber'
import MazPicker from 'maz-ui/components/MazPicker'
const props = defineProps({
    vouchers: Object
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    code: null,
    discount_amount: null,
    type: null,
    type_product: null,
    unit: "percent",
    is_fixed: null,
    description: null,
    starts_at: null,
    expires_at: null
});


const save = () => {
    console.log(form);
    if (editMode.value == true) {
        form.put(route("admin.voucher.update", form.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true;
            },
            onSuccess: () => {
                form.reset();
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    } else {
        form.post(route("admin.voucher.store"), {
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
    }

    // form.id = permission.id;
    // form.name = permission.name
};


const selected = ref([])
const selectAll = computed({
    get() {
        return props.vouchers.data
            ? selected.value.length == props.vouchers.data
            : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            props.vouchers.data.forEach(function (voucher) {
                array_selected.push(voucher.id);
            });
        }
        selected.value = array_selected;
    }
});
const searchFilter = () => {
    let query = {
        search: searchVal.value
    };
    router.get(route("admin.voucher.index"), query, {
        preserveState: true
        // only: ["image360s", "errors", 'flash'],
    });
}
const edit = (product) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = product.id;
    form.name = product.name;
    form.code = product.code;
    form.discount_amount = product.discount_amount;
    form.type = product.type;
    form.type_product = product.type_product;
    form.is_fixed = product.is_fixed;
    form.description = product.description;
    form.starts_at = product.starts_at;
    form.expires_at = product.expires_at;
    form.unit = product.unit;


};
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
const changeStatus = (data, event) => {
    let query = {
        id: data.id,
        status: event.target.checked
    };
    router.post(route("admin.voucher.changeStatus"), query, {
        preserveState: false
        // only: ["image360s", "errors", 'flash'],
    });
}

const Delete = (id) => {
    swal
        .fire({
            title: "Are you sure?",
            text: "Delete this voucher!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.delete(route("admin.voucher.destroy", id), {
                    onSuccess: () => {
                        swal.fire(
                            "Deleted!",
                            "Your voucher has been deleted.",
                            "success"
                        );
                    },
                });
            }
        });
};
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý Mã giảm giá" />
        <SectionMain>
            <SectionTitleLineWithButton title=" Mã giảm giá" main></SectionTitleLineWithButton>

            <!-- Modal -->
            <CardBoxModal v-model="isModalDangerActive" title="Please confirm" button="danger" has-cancel>
                <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
                <p>This is sample modal</p>
            </CardBoxModal>
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 m-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" @keyup="searchFilter" placeholder="Search" aria-label="Search"
                            size="24" />
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 hover:bg-[#008000]" :icon="mdiPlus" small
                        @click="
                            isModalActive = true;
                        form.reset();
                        " label="Tạo  Mã giảm giá" />
                </div>
            </div>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                classSize="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-2/5 xl:w-5/12 z-50 overflow-auto"
                :title="editMode ? 'Cập nhật Mã giảm giá' : 'Tạo  Mã giảm giá'">
                <div class="grid grid-cols-2 gap-4">
                    <div>

                        <div class="my-2">
                            <InputLabel for="name" value="Mã giảm giá" />
                            <TextInput id="name" v-model="form.code" type="text" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.code" />
                        </div>
                        <div>
                            <InputLabel for="name" value="Tên" />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="my-2">
                            <InputLabel for="name" value="Ngày bắt đầu" />
                            <MazPicker v-model="form.starts_at" format="YYYY-MM-DD HH:mm" label="Select date and time"
                                color="secondary" time />

                            <InputError class="mt-2" :message="form.errors.starts_at" />
                        </div>

                        <div class="my-2">
                            <InputLabel for="name" value="Ngày kết thúc" />
                            <MazPicker v-model="form.expires_at" format="YYYY-MM-DD HH:mm" label="Select date and time"
                                color="secondary" time />

                            <InputError class="mt-2" :message="form.errors.expires_at" />
                        </div>

                        <div class="flex items-center my-2 flex-warp">
                            <InputLabel for="name" value="Trang thái" />
                            <div class="ml-2">
                                <input id="link-checkbox" type="checkbox" v-model="form.is_fixed"
                                    :checked="form.is_fixed == 1 ? true : false"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="link-checkbox"
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    <a v-if="form.is_fixed == 1" class="text-blue-600 dark:text-blue-500 hover:underline">
                                        Kích
                                        hoạt</a> <a v-else class="text-blue-600 dark:text-blue-500 hover:underline">Không
                                        Kích
                                        hoạt</a>.</label>
                            </div>


                        </div>
                        <div class="my-2">
                            <InputLabel for="name" value="Giảm giá cho Loại sản phẩm" />
                            <label class="input w-full" for="recipient-name">
                                <select id="project" v-model="form.type_product"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm input__field rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5 w-full">
                                    <option :value="null">Chọn Loại sản phẩm</option>
                                    <option value="service">Sản phẩm dịch vụ</option>
                                    <option value="retail">Sản phẩm lẻ</option>
                                </select>
                            </label>
                            <InputError class="mt-2" :message="form.errors.type" />
                        </div>


                    </div>
                    <div>
                        <div class="my-2">
                            <InputLabel for="name" value="Đơn vị" />
                            <label class="input w-full" for="recipient-name">
                                <select id="project" v-model="form.unit"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm input__field rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5 w-full">
                                    <option value="percent"> %</option>
                                    <option value="price">Tiền</option>
                                </select>
                            </label>
                            <InputError class="mt-2" :message="form.errors.unit" />
                        </div>

                        <div class="my-2">
                            <InputLabel for="name" value="Loại" />
                            <label class="input w-full" for="recipient-name">
                                <select id="project" v-model="form.type"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm input__field rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5 w-full">
                                    <option value="0">Sale</option>
                                    <option value="1">Voucher</option>
                                    <option value="2">Giảm giá</option>
                                </select>
                            </label>
                            <InputError class="mt-2" :message="form.errors.type" />
                        </div>


                        <div class="my-2">
                            <InputLabel for="name" value="Mô tả" />
                            <label class="input w-full" for="recipient-name">

                                <quill-editor v-model:content="form.description" contentType="html"></quill-editor>

                            </label>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>


                        <div class="my-2">
                            <label class="input w-full" for="recipient-name">

                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Giảm giá
                                </span>
                                <MazInputPrice v-if="form.unit == 'price'" v-model="form.discount_amount"
                                    label="Enter your price" currency="VND" locale="vi-VN" :min="0"
                                    @formatted="formattedPrice = $event" />
                                <MazInputNumber v-else v-model="form.discount_amount" label="Enter number" :min="0"
                                    :max="100" :step="1" size="md" color="secondary" />
                                <span v-if="form.unit == 'price'">VND</span>
                                <span v-else>%</span>
                            </label>
                            <InputError class="mt-2" :message="form.errors.discount_amount" />
                        </div>

                    </div>




                </div>





            </CardBoxModal>
            <!-- End Modal -->
            <div class="mt-5">
                <div class="relative mt-5 ">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 flex items-center">
                                    <input type="checkbox" v-model="selectAll"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                    #
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Code
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tên
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    Loại
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Giá trị giảm giá
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Đơn vị giảm giá
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Trang thái
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Ngày bắt đầu
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Ngày kết thúc
                                </th>
                                <th scope="col" class="px-6 py-3">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(voucher, index) in vouchers.data" :key="index"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox" v-model="selected" :value="voucher.id"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        {{ index + 1 }}
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    {{ voucher.code }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ voucher.name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ voucher.type }}
                                </td>
                                <td class="px-6 py-4">
                                    <p v-if="voucher.type_product == 'retail'">
                                        Sản phẩm lẻ
                                    </p>

                                    <p v-if="voucher.type_product == 'service'">
                                        'Sản phẩm dịch vụ'
                                    </p>


                                </td>
                                <td class="px-6 py-4">
                                    {{ voucher.discount_amount }}
                                </td>
                                <td class="px-6 py-4">
                                    <p v-if="voucher.unit == 'percent'">%</p>

                                    <p v-else> VND</p>
                                </td>
                                <td class="px-6 py-4">

                                    <span v-if="voucher.is_fixed == 0"
                                        class="bg-red-500 text-white text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Chưa
                                        kích hoạt</span>
                                    <span v-else
                                        class="bg-lime-500 text-white text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-lime-600 dark:text-green-300">Kích
                                        hoạt</span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ voucher.starts_at }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ voucher.expires_at }}
                                </td>
                                <td class="px-6 py-4" v-html="voucher.description">
                                </td>
                                <!-- <td class="px-6 py-4">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" :checked="product_service.status"
                                            @change="changeStatus(product_service, $event)">
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                        </div>
                                    </label>
                                </td> -->
                                <td class="px-6 py-4 ">
                                    <div class="flex ">

                                        <Dropdown align="right" width="40" class="ml-5">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <BaseButton class="bg-[#D9D9D9] border-[#D9D9D9]"
                                                        :icon="mdiDotsVertical" small />
                                                </span>
                                            </template>

                                            <template #content>
                                                <div class="w-40">
                                                    <div @click="edit(voucher)"
                                                        class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                                        <p class="hover:text-blue-700"> Edit</p>
                                                        <BaseButton :icon="mdiPencil" small class="text-[#2264E5]"
                                                            type="button" data-toggle="modal" data-target="#exampleModal" />
                                                    </div>

                                                    <Link :href="route('admin.voucher.products', voucher.id)"
                                                        v-if="voucher.type_product == 'retail'"
                                                        class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                                    <p class="hover:text-blue-700"> Products</p>
                                                    <BaseButton :icon="mdiPackage" small class="text-[#2264E5]"
                                                        type="button" data-toggle="modal" />
                                                    </Link>

                                                    <Link :href="route('admin.voucher.product-service.index', voucher.id)"
                                                        v-if="voucher.type_product == 'service'"
                                                        class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                                    <p class="hover:text-blue-700"> Product Service</p>
                                                    <BaseButton :icon="mdiPackage" small class="text-[#2264E5]"
                                                        type="button" data-toggle="modal" />
                                                    </Link>
                                                    <div @click="Delete(voucher.id)"
                                                        class="flex justify-between items-center px-4  text-sm text-[#D12953] cursor-pointer  font-semibold">
                                                        <p class="hover:text-red-700"> Delete</p>
                                                        <BaseButton :icon="mdiTrashCanOutline" small
                                                            class="text-[#D12953]" />
                                                    </div>

                                                </div>
                                            </template>
                                        </Dropdown>
                                    </div>

                                </td>
                            </tr>


                        </tbody>
                    </table>
                    <Pagination :links="vouchers.links" />
                </div>
            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
