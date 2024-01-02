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

import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import MazInputNumber from 'maz-ui/components/MazInputNumber'
import MazPicker from 'maz-ui/components/MazPicker'
import InputNumber from 'primevue/inputnumber';
import Multiselect from '@vueform/multiselect'
const props = defineProps({
    commissions: Object,
    userType: Object,
    commissionType: Array,
});
const searchVal = ref("");
const swal = inject("$swal");

const form = useForm({
    description: null,
    participants: null,
});
const form2 = useForm({
    id: null,
    // name: null,
    spend_from: null,
    spend_to: null,
    commission: [],
    type: 'sale',
    greater: 0,
    level_revenue: 30,
});
const saveType = () => {
    console.log(form);
    form.post(route("commission.type"), {
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
};

const save = (type,userType) => {
    console.log(form);
    if (editMode.value == true) {
        form.post(route("commission.update", form.id), {
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
        form.post(route("commission.store"), {
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
        return props.commissions.data
            ? selected.value.length == props.commissions.data
            : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            props.commissions.data.forEach(function (commission) {
                array_selected.push(commission.id);
            });
        }
        selected.value = array_selected;
    }
});
const searchFilter = () => {
    let query = {
        search: searchVal.value
    };
    router.get(route("commission.index"), query, {
        preserveState: true
        // only: ["image360s", "errors", 'flash'],
    });
}
const edit = (commission) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = commission.id;
    // form.name = commission.name;
    form.spend_from = commission.spend_from;
    form.spend_to = commission.spend_to;
    form.commission = commission.commission;
    form.type = commission.type
    form.level_revenue = commission.level_revenue
    form.discount_form_sale = commission.discount_form_sale
    form.discount_form_manager_sale = commission.discount_form_manager_sale
};
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);

const changeStatus = (data, event) => {
    let query = {

        status: event.target.checked
    };
    router.post(route("commission.changeStatus", data.id), query, {
        preserveState: false
        // only: ["image360s", "errors", 'flash'],
    });
}


</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý Chính sách hoa hồng" />
        <SectionMain class="p-3 mt-8">
            <SectionTitleLineWithButton title="Bảng chiết khấu hoa hồng" main></SectionTitleLineWithButton>
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 my-2 mr-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" @keyup="searchFilter" placeholder="Search" aria-label="Search"
                            size="24" />
                    </div>
                </div>
                <div class="right">
                    <!-- <BaseButton color="info" class="bg-btn_green hover:bg-[#318f02] text-white p-2 hover:bg-[#008000]"
                        :icon="mdiPlus" small @click="
                            isModalActive = true;

                        " label="Tạo Chính sách Hoa hồng" /> -->
                    <BaseButton color="info" class="bg-btn_green hover:bg-[#318f02] text-white p-2 hover:bg-[#008000]"
                        :icon="mdiPlus" small @click="
                            isModalActive = true;

                        " label="Loại chính sách" />
                </div>
            </div>
            <div class="my-1">
                <div
                    class="min-[320px]:grid min-[320px]:justify-between sm:justify-start md:justify-start lg:justify-start sm:flex md:flex lg:flex">
                    <Link :href="route('commission.index')"
                        class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white "
                        :class="$page.url.includes('index') ? 'bg-white  text-blue-500' : 'text-gray-500'">
                    All
                    </Link>
                    <Link :href="route('commission.leader')"
                        class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white "
                        :class="$page.url.includes('leader') ? 'bg-white  text-blue-500' : 'text-gray-500'">
                    Leader Sale
                    </Link>
                    <Link :href="route('commission.sale')" class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white
                        " :class="$page.url.includes('sale') ? 'bg-white  text-blue-500' : 'text-gray-500'">
                    Sale
                    </Link>
                    <Link :href="route('commission.ctv')"
                        class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white "
                        :class="$page.url.includes('ctv') ? 'bg-white  text-blue-500' : 'text-gray-500'">
                    CTV
                    </Link>
                    <Link :href="route('commission.telesale')"
                        class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white "
                        :class="$page.url.includes('tele') ? 'bg-white  text-blue-500' : 'text-gray-500'">
                    Telesale
                    </Link>
                </div>
            </div>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="saveType"
                classSize="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-2/5 xl:w-5/12 z-50 overflow-auto"
                :title="editMode ? 'Cập nhật Chính sách Hoa hồng' : 'Tạo  Chính sách Hoa hồng'">
                <div class="flex w-full flex-wrap items-center">
                    <div class="my-2 w-1/2">
                        <InputLabel for="name" value="Loại hoa hồng" />
                        <TextInput type="text" v-model="form.description" required
                            class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </TextInput>
                    </div>
                    <div class="my-2 w-full">
                        <InputLabel for="name" value="Danh cho" />
                        <Multiselect v-model="form.participants" mode="tags" :appendNewTag="false" :createTag="false"
                            :searchable="true" label="name" valueProp="id" trackBy="name" :options="userType"
                            class="form-control" style="height:fit-content" :classes="{
                                tagsSearch: 'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                                container: 'relative mx-auto w-full flex items-center py-2 px-3 justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-2xl leading-snug outline-none',
                                tags: 'flex-grow flex-shrink flex flex-wrap items-center mt-1 pl-2 rtl:pl-0 rtl:pr-2',
                                tag: 'bg-red-600 text-white text-xs font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
                            }" />
                        <InputError class="mt-2" :message="form.errors.type" />
                    </div>

                </div>
            </CardBoxModal>
            <!-- End Modal -->
            <div class="mt-2">
                <div class="grid lg:grid-cols-3 md:grip-cols-2 sm:grip-cols-1 mt-3 gap-4">
                    <div v-for="(type, index) in commissionType" :key="index"
                        class=" h-[1000px] rounded-3xl  bg-gray-100 p-3">
                        <p class="font-bold my-2">{{ type.description }}</p>
                        <div v-for="(parameter, index2) in type.participants" :key="index2" class="w-full ">
                            <p class="font-bold my-2">{{ parameter.name }}</p>
                            <div class="flex w-full">
                                <div class="w-1/5 ">
                                    Mức áp dụng
                                </div>
                                <div class="w-4/5 ml-2 pl-2">
                                    Mốc doanh thu áp dụng(TRIỆU ĐỒNG)
                                </div>
                            </div>
                            <div class="flex w-full">
                                <div class="flex items-center w-1/5  ">
                                    <input type="number" v-model="form2.commission[index].commission" :min="0" :max="100" step="0.01"
                                        class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                    <InputLabel for="name" value=" %" class="ml-1" />
                                </div>
                                <div class="flex w-4/5 ml-4 items-center ">

                                        <InputLabel for="name" value="Từ"  />
                                        <InputNumber v-model="form2.commission[index].spend_from" :min="0" class="p-3 w-[160px]"
                                            inputClass=" bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />


                                        <InputLabel for="name" value="đến <" class="w-[40px] "/>
                                        <InputNumber v-model="form2.commission[index].spend_to" :min="0" class="p-3 w-[160px]"
                                            inputClass="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                </div>
                            </div>
                            <div class="flex justify-end mx-3">
                                <button type="submit" @confirm="save" color="info" class="bg-[#27AE60] hover:bg-[#318f02] rounded-xl text-white py-2.5 px-3 right  "  >Thêm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
