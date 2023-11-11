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
const props = defineProps({
    product_services: Object,
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    number_tree: null,
    acreage: null,
    free_visit: null,
    amount_products_received: null,
    price: null,
    number_deliveries: null,
    life_time: null,
    description: null,
    unit: null,
});


const save = () => {
    console.log(form);
    if (editMode.value == true) {
        form.post(route("admin.product-service.update", form.id), {
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
        form.post(route("admin.product-service.store"), {
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
        return props.product_services.data
            ? selected.value.length == props.product_services.data
            : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            props.product_services.data.forEach(function (product) {
                array_selected.push(product.id);
            });
        }
        selected.value = array_selected;
    }
});
const searchFilter = () => {
    let query = {
        search: searchVal.value
    };
    router.get(route("admin.product-service.index"), query, {
        preserveState: true
        // only: ["image360s", "errors", 'flash'],
    });
}
const edit = (product) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = product.id;
    form.name = product.name;
    form.number_tree = product.number_tree;
    form.acreage = product.acreage;
    form.free_visit = product.free_visit;
    form.amount_products_received = product.amount_products_received;
    form.price = product.price;
    form.number_deliveries = product.number_deliveries;
    form.life_time = product.life_time;
    form.description = product.description;
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
    router.post(route("admin.product-service.changeStatus"), query, {
        preserveState: false
        // only: ["image360s", "errors", 'flash'],
    });
}

const Delete = (id) => {
    swal
        .fire({
            title: "Are you sure?",
            text: "Delete this tree!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.delete(route("admin.product-service.destroy", id), {
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
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Product Service" />
        <SectionMain>
            <SectionTitleLineWithButton title="Product Service" main></SectionTitleLineWithButton>

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
                        " label="Tạo mới gọi dịch vụ" />
                </div>
            </div>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                classSize="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-2/5 xl:w-5/12 z-50 overflow-auto"
                :title="editMode ? 'Cập nhật gói dịch vụ' : 'Tạo mới gọi dịch vụ'">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div>
                            <InputLabel for="name" value="Tên" />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div class="my-2">
                            <InputLabel for="name" value="Diện tích" />
                            <TextInput id="name" v-model="form.acreage" type="text" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.acreage" />
                        </div>
                        <div class="my-2">
                            <InputLabel for="name" value="Số lượng cây" />
                            <input id="name" v-model="form.number_tree" type="number"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm input__field rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5 w-full"
                                required />
                            <InputError class="mt-2" :message="form.errors.number_tree" />
                        </div>

                        <div class="my-2">
                            <InputLabel for="name" value="Lượt tham quan" />
                            <input id="name" v-model="form.free_visit" type="number"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm input__field rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5 w-full"
                                required />
                            <InputError class="mt-2" :message="form.errors.free_visit" />
                        </div>
                        <div class="my-2">
                            <InputLabel for="name" value="Tổng sản lượng" />
                            <input id="name" v-model="form.amount_products_received" type="number"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm input__field rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5 w-full"
                                required autofocus autocomplete="name" />
                            <InputError class="mt-2" :message="form.errors.amount_products_received" />
                        </div>



                    </div>
                    <div>
                        <div class="">
                            <label class="input w-full" for="recipient-name">

                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Giá
                                </span>
                                <MazInputPrice v-model="form.price" label="Enter your price" currency="VND" locale="vi-VN"
                                    :min="0" @formatted="formattedPrice = $event" />
                            </label>
                            <InputError class="mt-2" :message="form.errors.price" />
                        </div>
                        <div class="my-2">
                            <InputLabel for="number_deliveries" value="Số lượng giao hàng" />
                            <input id="number_deliveries" v-model="form.number_deliveries" type="number"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm input__field rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5 w-full"
                                required autofocus />
                            <InputError class="mt-2" :message="form.errors.number_deliveries" />
                        </div>
                        <div class="my-2">
                            <InputLabel for="life_time" value="Thời gian hoạt động" />
                            <input id="life_time" v-model="form.life_time" type="number"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm input__field rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5 w-full"
                                required autofocus />
                            <InputError class="mt-2" :message="form.errors.life_time" />
                        </div>
                        <div>
                            <InputLabel for="name" value="Đơn vị" />
                            <label class="input w-full" for="recipient-name">
                                <select id="project" v-model="form.unit"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm input__field rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5 w-full">
                                    <option value="day"> Ngày</option>
                                    <option value="month"> Tháng</option>
                                    <option value="year"> Năm</option>

                                </select>

                            </label>
                            <InputError class="mt-2" :message="form.errors.state" />
                        </div>
                        <!-- <div class="mt-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                for="multiple_files">Upload Images</label>
                            <input @input="form.images = $event.target.files" multiple accept="image/*"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                id="multiple_files" type="file">
                        </div> -->
                    </div>
                    <div>
                        <div class="">
                            <InputLabel for="name" value="Mô tả" />
                            <label class="input w-full" for="recipient-name">

                                <quill-editor v-model:content="form.description" contentType="html"></quill-editor>

                            </label>
                            <InputError class="mt-2" :message="form.errors.description" />
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
                                    Tên
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    Số lượng cây
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Diện tích vườn rau
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Lượt Tham quan miễn phí
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Lượng nông sản
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Giá
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Số lần giao hàng
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Thời gian gói hđ
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Đơn vị
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Mô tả
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Đơn vị
                                </th>
                                <th scope="col" class="px-6 py-3">

                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product_service, index) in product_services.data" :key="index"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <th scope="col" class="px-6 py-3 ">
                                    <div class="flex items-center ">
                                        <input id="default-checkbox" type="checkbox" v-model="selected"
                                            :value="product_service.id"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                        {{ index + 1 }}
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    {{ product_service.name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ product_service.number_tree }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ product_service.acreage }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ product_service.free_visit }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ product_service.amount_products_received }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ formatPrice(product_service.price) }}

                                </td>
                                <td class="px-6 py-4">
                                    {{ product_service.life_time }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ product_service.number_deliveries }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ product_service.life_time }}
                                </td>
                                <td class="px-6 py-4" v-html="product_service.description">

                                </td>
                                <td class="px-6 py-4">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" :checked="product_service.status"
                                            @change="changeStatus(product_service, $event)">
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                        </div>
                                    </label>
                                </td>
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
                                                    <div @click="edit(product_service)"
                                                        class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                                        <p class="hover:text-blue-700"> Edit</p>
                                                        <BaseButton :icon="mdiPencil" small class="text-[#2264E5]"
                                                            type="button" data-toggle="modal" data-target="#exampleModal" />
                                                    </div>
                                                    <div @click="Delete(product_service.id)"
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
                    <Pagination :links="product_services.links" />
                </div>
            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
