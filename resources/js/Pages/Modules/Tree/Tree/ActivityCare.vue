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
import "vue-search-input/dist/styles.css";
import TreeModal from "./TreeModal.vue";
import { useTreeStore } from '@/stores/tree.js'
import { emitter } from '@/composable/useEmitter';
import BaseButtons from '@/Components/BaseButtons.vue';
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
const store = useTreeStore()
const props = defineProps({
    activity: Object
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null
});
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);
const edit = (activity) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = activity.id;
    form.name = activity.name;
};

const crumbs = ref([

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
                form.delete(route("admin.activity.tree.destroy", id), {
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
    router.get(route(`admin.activity.tree.index`, props.activity.id),
        { search: searchVal.value },
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const save = () => {
    console.log(form);
    if (editMode.value == true) {
        form.post(route("admin.activity-care.update", form.id), {
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
        form.post(route("admin.activity-care.store"), {
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
</script>
<template>
    <LayoutAuthenticated>
        <SectionMain class="p-3">
            <SectionTitleLineWithButton title="Hoạt động chăm sóc" main></SectionTitleLineWithButton>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                classSize="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-3/5 xl:w-1/2 z-50 overflow-auto"
                :title="editMode ? 'Chỉnh sửa' : 'Tạo mới'">
                <div class="w-full">
                    <div>
                        <div>
                            <InputLabel for="name" value="Tên" />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                    </div>
                </div>
            </CardBoxModal>
            <div class="flex justify-end">
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green hover:bg-[#318f02] text-white p-2 hover:bg-[#008000]" :icon="mdiPlus" small
                        @click="isModalActive = true; form.reset();" label="Thêm hành động" />
                </div>
            </div>

            <div class="overflow-x-auto relative  sm:rounded-lg mt-2">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700  bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6 text-xs">STT</th>
                            <th scope="col" class="py-3 px-6 text-xs">Tên</th>
                            <th scope="col" class="py-3 px-6 text-xs">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(acti, index) in activity.data" :key="index"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ index  + activity.from  }}
                            </th>
                            <th scope="row" class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                acti.name }}</th>

                            <td class="py-1 px-6 text-right">
                                <BaseButtons type="justify-start lg:justify-end" no-wrap>
                                    <BaseButton color="contrast" :icon="mdiPencilOutline" small @click="edit(acti)"
                                        type="button" data-toggle="modal" data-target="#exampleModal" />
                                    <BaseButton color="danger" :icon="mdiTrashCan" small @click="Delete(acti.id)" />
                                </BaseButtons>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :links="activity.links" />
        </SectionMain>
    </LayoutAuthenticated>
</template>

