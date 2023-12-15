<script setup>
import { computed, ref, inject } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/PaginationDefault.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBoxModal from "@/Components/CardBoxModal.vue";
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
const store = useTreeStore()
const props = defineProps({
    land: Object,
    trees: Object
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
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);
const createTree = (data) => {
    store.changeisModalTree(true)
    store.changeEditMode(false)
    emitter.emit('createTree')
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
</script>
<template>
    <LayoutAuthenticated>

        <Breadcrumb :crumbs="crumbs" />
        <SectionMain class="p-3">
            <SectionTitleLineWithButton title="Cây cam " main></SectionTitleLineWithButton>


            <CardBoxModal v-model="isModalDangerActive" title="Please confirm" button="danger" has-cancel>
                <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
                <p>This is sample modal</p>
            </CardBoxModal>
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 my mr-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" @keyup="searchTree" placeholder="Search" aria-label="Search"
                            size="24" />
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green hover:bg-[#318f02] text-white p-2 hover:bg-[#008000]" :icon="mdiPlus" small
                        @click="createTree()" label="Create Tree" />
                </div>
            </div>
            <TreeModal :land="land" />
            <div class="overflow-x-auto relative  sm:rounded-lg mt-2">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700  bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
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
                            <!-- <th scope="col" class="py-3 px-6 text-xs">Bộ sưu tập</th> -->
                            <th scope="col" class="py-3 px-6 text-xs">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(tree, index) in trees.data" :key="index"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                index + 1 }}</th>
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
                                    <span v-if="tree.state == 'public'"
                                        class="text-xs font-mono paid btn_key text-center">
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

