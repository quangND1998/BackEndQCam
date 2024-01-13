<script setup>
import { computed, ref, inject } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/PaginationDefault.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import CardBoxModalFull from "@/Components/CardBoxModalFull.vue";
import {
    mdiPlus,
    mdiFilter, mdiPencilOutline, mdiTrashCan
} from '@mdi/js'
import BaseButton from "@/Components/BaseButton.vue";

import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import TreeModal from "./TreeModal.vue";
import { useTreeStore } from '@/stores/tree.js'
import { emitter } from '@/composable/useEmitter';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import BaseButtons from '@/Components/BaseButtons.vue';
import InputLabel from "@/Components/InputLabel.vue";
import Multiselect from '@vueform/multiselect'
import InputError from "@/Components/InputError.vue";
const store = useTreeStore()
const props = defineProps({
    land: Object,
    trees: Object,
    activityCare: Object,
    treesAll: Object
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    state: null,
    address: null,
    price: null,
    status: null,
    description: null,
    user_manual: null,
    terms_policy: null,
    images: null
});
const formActivity = useForm({
    id: null,
    tree: null,
    date: null,
    activities: null,
    selectedActivity: [],
});
const formActivityLand = useForm({
    id: null,
    land: props.land.id,
    trees: null,
    date: null,
    selectedActivity: [],
});
const selectedActivity = ref([])
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);
const isModalActivityActive = ref(false);
const editActivityMode = ref(false);

const isModalActivityLandActive = ref(false);
const editActivityLandMode = ref(false);
const isSelectTree = ref(false);
const createTree = (data) => {
    store.changeisModalTree(true)
    store.changeEditMode(false)
    emitter.emit('createTree')
}
const Careland = (data) => {
    isModalActivityLandActive.value = true;
    formActivityLand.trees = [];
    isSelectTree.value = false;
}
const CarelandSelected = (data) => {
    isModalActivityLandActive.value = true;
    formActivityLand.trees = selected.value
    isSelectTree.value = true;
}
const edit = (land) => {
    store.changeisModalTree(true);
    store.changeEditMode(true);
    store.setTree(land)
    emitter.emit('editTree', land)
};

const crumbs = ref([
    {
        route: "admin.land.index",
        parma: null,
        name: props.land.name
    },
    {
        route: "admin.land.tree.index",
        parma: props.land.id,
        name: "Quản lý cây"
    },

])
const historyCare = (tree) => {
    console.log(tree);
    formActivity.date = null,
    formActivity.selectedActivity = [],
    isModalActivityActive.value = true;
    isModalActive.value = false;
    formActivity.tree = tree
}
const save = () => {
    // console.log(formActivity);
    formActivity.post(route("admin.historyCare.store", formActivity.tree?.id), {
        onError: () => {
            isModalActivityActive.value = true;
            editActivityMode.value = false;
        },
        onSuccess: () => {
            formActivity.reset();
            isModalActivityActive.value = false;
            editActivityMode.value = false;
            formActivity.date = null;
            formActivity.selectedActivity = [];
        },
    });
    // axios.post(`/admin/historyCare/${formActivity.tree?.id}/store`, formActivity)
    // .then(response => {
    //     // console.log(response);
    //     form.tree = response.data;
    //     formActivity.reset();
    //     isModalActivityActive.value = false;
    //     editActivityMode.value = false;
    //     formActivity.date = null;
    //     formActivity.selectedActivity = [];
    // })
    // .catch(error => {
    //     // console.log(error);
    //     console.error("There was an error!", error.message);
    // });
};
const saveCarryLand = () => {
    formActivityLand.post(route("admin.historyCare.storeLand"), {
        onError: () => {
            isModalActivityActive.value = false;
            editActivityMode.value = false;
        },
        onSuccess: () => {
            formActivityLand.reset();
            isModalActivityLandActive.value = false;
            formActivityLand.date = null;
            formActivityLand.selectedActivity = [];
            formActivityLand.trees = [];
        },
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
                form.delete(route("admin.land.tree.destroy", id), {
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

const searchTree = () => {
    router.get(route(`admin.land.tree.index`, props.land.id),
        { search: searchVal.value },
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const selected = ref([])
const selectAll = computed({
    get() {
        return props.trees.data
            ? selected.value.length == props.trees.data
            : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            props.trees.data.forEach(function (tree) {
                array_selected.push(tree.id);
            });
        }
        selected.value = array_selected;

    }
});

const selectActivity = (id) => {

    console.log(id);
    console.log(formActivity.selectedActivity);
    //in here you can check what ever condition  before append to array.
    if (formActivity.selectedActivity.includes(id)) {
        // formActivity.selectedActivity=_.without(formActivity.selectedActivity,id)
    } else {
        formActivity.selectedActivity.push(id)
    }
}
const selectActivityland = (id) => {

console.log(id);
console.log(formActivityLand.selectedActivity);
//in here you can check what ever condition  before append to array.
if (formActivityLand.selectedActivity.includes(id)) {
    // formActivity.selectedActivity=_.without(formActivity.selectedActivity,id)
} else {
    formActivityLand.selectedActivity.push(id)
}
}
</script>
<template>
    <LayoutAuthenticated>

        <Breadcrumb :crumbs="crumbs" />
        <SectionMain class="p-3">
            <SectionTitleLineWithButton title="Cây cam " main></SectionTitleLineWithButton>
            <!-- Modal -->
            <CardBoxModalFull class="w-full overflow-hidden" v-model="isModalActivityActive" buttonLabel="Xác nhận" has-cancel
                @confirm="save"
                :title="`Lịch sử chăm sóc cây ${formActivity?.tree?.name} (${formActivity?.tree?.address})`">
                <div class="p-6 flex-auto">
                    <div :id="`form_create_${formActivity?.id}`">
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/3 px-3">
                                <InputLabel for="owner" value="Chọn ngày" />
                                <div date-rangepicker class="flex items-center">
                                    <div class="relative border_round">
                                        <div
                                            class="absolute border_round inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        </div>
                                        <VueDatePicker class="border_round" v-model="formActivity.date" placeholder="date" />
                                    </div>
                                </div>
                            </div>
                            <div class="w-full md:w-1/3 px-3">
                                <InputLabel for="activity" value="Hoạt động" />
                                <div class="w-full left-0 ml-0">
                                    <div class="" v-for="(activity, index) in activityCare" :key="index">
                                        <input class="mr-2" type="checkbox" id="checkbox" :value="activity.id"
                                            @click="() => { selectActivity(activity.id) }" />
                                        <label for="checkbox">{{ activity.name }}</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="flex justify-end">
                            <Button label="Xác nhận" color="blue"
                                class="border py-2 px-4 rounded-2xl bg-[#4F8D06] text-white" @click="save">Xác nhận</Button>
                        </div>
                    </div>
                    <p class="my-2 mt-5 font-semibold">Lịch sử chăm sóc</p>
                    <table class="table table-striped w-full text-sm text-left text-gray-500 bg-white rounded-lg">
                        <thead class="text-xs justify-between text-gray-700 uppercase">
                            <tr class="info">
                                <th>Loại</th>
                                <th>Ngày</th>
                                <th>Hoạt động</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="justify-between border-0 bg-white info"
                                v-for="(history_care, index2) in formActivity?.tree?.history_care" :key="index2">
                                <td class="border-0">
                                    {{ index2 + 1 }}
                                </td>
                                <td class="border-0">{{ history_care?.date }}</td>
                                <td class="border-0 flex">
                                    <p v-for="(activity,index3) in history_care.activity_care" :key="index3"> {{ activity.name }}, </p>
                                </td>

                                <td class="border-0">
                                    <BaseButton color="gray" class="border-0 text=gray=300 hover:text-black"
                                        :icon="mdiTrashCanOutline" small @click="deleteHistory(history_care.id)" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardBoxModalFull>
            <CardBoxModalFull class="w-full overflow-hidden" v-model="isModalActivityLandActive" buttonLabel="Xác nhận"
                @confirm="saveCarryLand"
                :title="`Thêm hoạt động chăm sóc toàn lô ${land?.name}`">
                <div class="p-6 flex-auto">
                    <div :id="`form_create_${form?.id}`">
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full  px-3" :class="isSelectTree ? 'md:w-1/4' : 'md:w-1/2'">
                                <InputLabel for="owner" value="Chọn ngày" />
                                <div date-rangepicker class="flex items-center">
                                    <div class="relative border_round">
                                        <div
                                            class="absolute border_round inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        </div>
                                        <VueDatePicker class="border_round" v-model="formActivityLand.date" placeholder="date" />
                                        <InputError class="mt-2" :message="formActivityLand.errors.date" />
                                    </div>
                                </div>
                            </div>
                            <div class="w-full px-3" :class="isSelectTree ? 'md:w-1/4' : 'md:w-1/2'">
                                <InputLabel for="activity" value="Hoạt động" />
                                <div class="w-full left-0 ml-0">
                                    <div class="" v-for="(activity, index) in activityCare" :key="index">
                                        <input class="mr-2" type="checkbox" id="checkbox" :value="activity.id"
                                            @click="() => { selectActivityland(activity.id) }" />
                                        <label for="checkbox">{{ activity.name }}</label>
                                    </div>
                                </div>
                                <InputError class="mt-2" :message="formActivityLand.errors.selectedActivity" />
                            </div>
                            <div v-show="isSelectTree" class="w-full md:w-2/4">
                                <InputLabel for="activity" value="Cây đã chọn" />
                                <Multiselect v-model="formActivityLand.trees" :searchable="true" mode="tags"  label="name" valueProp="id" trackBy="name" placeholder="None"  :options="treesAll"
                                :classes="{
                                tagsSearch: 'absolute  inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                                container: 'relative border_round mx-auto w-full bg-gray-50  items-center  box-border cursor-pointer border border-gray-300 rounded bg-gray-50 text-sm  '
                                }" >
                                    <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                        <div
                                        class="multiselect-tag is-user"
                                        :class="{
                                            'is-disabled': disabled
                                        }"
                                        >
                                        {{ option.name }} ({{ option.address }})
                                        <span
                                            v-if="!disabled"
                                            class="multiselect-tag-remove"
                                            @click="handleTagRemove(option, $event)"
                                        >
                                            <span class="multiselect-tag-remove-icon"></span>
                                        </span>
                                        </div>
                                    </template>

                                    <template v-slot:option="{ option }">
                                        {{ option.name }}  ({{ option.address }})
                                    </template>
                                </Multiselect>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <Button label="Xác nhận" color="blue"
                                class="border py-2 px-4 rounded-2xl bg-[#4F8D06] text-white" @click="saveCarryLand">Xác nhận</Button>
                        </div>
                    </div>
                </div>
            </CardBoxModalFull>
            <div class="flex justify-between">
                <div class="right">
                    <Button
                        class="bg-[#DBE9FF] rounded border-2 border-[#1D75FA]  text-black p-2  mr-3 hover:bg-[#1D75FA] hover:text-white"
                        small @click="CarelandSelected()" label="Chăm sóc">Chăm sóc</Button>
                    <Button
                        class="bg-[#95F7BE] rounded border-2 border-[#27AE60]  text-black p-2  mr-3 hover:bg-[#27AE60] hover:text-white"
                        small @click="Careland();" label="Chăm sóc">Chăm sóc toàn lô</Button>
                </div>
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 my mr-2 bg-white" :iconSize="20" />
                        <input v-model="searchVal" @keyup="searchTree" placeholder="Search" aria-label="Search" size="24" />
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green hover:bg-[#318f02] text-white p-2 hover:bg-[#008000]"
                        :icon="mdiPlus" small @click="createTree()" label="Tạo mới cây" />
                </div>
            </div>
            <TreeModal :land="land" />
            <div class="overflow-x-auto relative  sm:rounded-lg mt-2">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700  bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 flex items-center">
                                <input type="checkbox" v-model="selectAll"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                #
                            </th>
                            <th scope="col" class="py-3 px-6 text-xs">STT</th>
                            <th scope="col" class="py-3 px-6 text-xs">Tên</th>
                            <th scope="col" class="py-3 px-6 text-xs">Vị trí</th>
                            <th scope="col" class="py-3 px-6 text-xs">Ảnh đại diện</th>
                            <th scope="col" class="py-3 px-6 text-xs">Giá gốc</th>
                            <th scope="col" class="py-3 px-6 text-xs">Giá bán</th>
                            <th scope="col" class="py-3 px-6 text-xs">Giá chuyển nhượng</th>
                            <th scope="col" class="py-3 px-6 text-xs">Trang thái</th>
                            <th scope="col" class="py-3 px-6 text-xs">Trạng thái</th>
                            <th scope="col" class="py-3 px-6 text-xs">Tình Trạng cây</th>
                            <th scope="col" class="py-3 px-6 text-xs">Hành động</th>
                            <th scope="col" class="py-3 px-6 text-xs">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(tree, index) in trees.data" :key="index"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="col" class="px-6 py-3 ">
                                <div class="flex items-center ">
                                    <input id="default-checkbox" type="checkbox" v-model="selected" :value="tree.id"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                </div>
                            </th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ index + trees.from }}
                            </th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                tree.name }}</th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                tree.address }}</th>
                            <th scope="row"
                                class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white flex flex-wrap">
                                <img :src="tree.thumb_image?.[0]?.original_url" class="w-12 h-12 " />
                            </th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <strong>{{
                                    formatPrice(tree.price_origin) }}</strong>
                            </th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <strong>{{
                                    formatPrice(tree.price) }}</strong>
                            </th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <strong>{{
                                    formatPrice(tree.transfer_value) }}</strong>
                            </th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class=" flex justify-center">
                                    <span v-if="tree.state == 'public'" class="text-xs font-mono paid btn_key text-center">
                                        Mở bán
                                    </span>
                                    <span v-if="tree.state == 'private'"
                                        class="inline-block whitespace-nowrap rounded-full border bg-red-200 border-rose-600 text-red px-[0.65em] py-1 text-center align-baseline text-[1em] font-bold leading-none text-danger-700">
                                        Chưa Mở bán
                                    </span>
                                </div>
                            </th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <strong>{{ tree.pay_status }}</strong>
                            </th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <strong>{{ tree.status }}</strong>
                            </th>

                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <strong @click="historyCare(tree)">Chăm sóc</strong>
                            </th>
                            <!-- <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                link</th> -->
                            <td class="py-1 px-6 text-right">
                                <BaseButtons type="justify-start lg:justify-end" no-wrap>
                                    <BaseButton color="contrast" :icon="mdiPencilOutline" small @click="edit(tree)"
                                        type="button" data-toggle="modal" data-target="#exampleModal" />
                                    <BaseButton color="danger" :icon="mdiTrashCan" small @click="Delete(tree.id)" />
                                </BaseButtons>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :links="trees.links" />
        </SectionMain>
    </LayoutAuthenticated>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
<style>
  .multiselect-tag.is-user {
    padding: 5px 8px;
    border-radius: 22px;
    background: #35495e;
    margin: 3px 3px 8px;
  }

  .multiselect-tag.is-user img {
    width: 18px;
    border-radius: 50%;
    height: 18px;
    margin-right: 8px;
    border: 2px solid #ffffffbf;
  }

  .multiselect-tag.is-user i:before {
    color: #ffffff;
    border-radius: 50%;;
  }

  .user-image {
    margin: 0 6px 0 0;
    border-radius: 50%;
    height: 22px;
  }
</style>
