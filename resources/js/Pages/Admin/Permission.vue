<script setup>
import { computed, ref, inject } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import {
    mdiEye,
    mdiAccountLockOpen,
    mdiPlus,
    mdiFilter,
    mdiMagnify,
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";



import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
defineProps({
    permissions: Object,
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
});
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);
// const value = ref([]);
// const options = ref([
//     'Batman',
//     'Robin',
//     'Joker',
// ])

const edit = (permission) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = permission.id;
    form.name = permission.name;
};

const save = () => {
    console.log(form);
    if (editMode.value == true) {
        form.put(route("permissions.update", form.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true;
            },
            onSuccess: () => {
                form.reset("name", "id");
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    } else {
        form.post(route("permissions.store"), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = false;
            },
            onSuccess: () => {
                form.reset("name", "id");
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    }

    // form.id = permission.id;
    // form.name = permission.name
};

const Delete = (id) => {
    swal
        .fire({
            title: "Are you sure?",
            text: "Delete this permission!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.delete(route("permissions.delete", id), {
                    onSuccess: () => {
                        swal.fire(
                            "Deleted!",
                            "Your permission has been deleted.",
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

        <Head title="Permissions" />
        <!-- <Multiselect v-model="value" :options="options" mode="tags" :close-on-select="false" :searchable="true"
            :create-option="true" /> -->
        <SectionMain class="p-3 mt-16 ">
            <SectionTitleLineWithButton title="Permissions" main></SectionTitleLineWithButton>

            <!-- Modal -->
            <CardBoxModal v-model="isModalDangerActive" title="Please confirm" button="danger" has-cancel>
                <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
                <p>This is sample modal</p>
            </CardBoxModal>
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 mr-2 my-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" placeholder="Search" aria-label="Search" size="24"/>
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green hover:bg-[#318f02] text-white p-2 hover:bg-[#008000]" :icon="mdiPlus" small @click="
                        isModalActive = true;
                    form.reset();
                    " label="Create Permission" />
                </div>
            </div>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                :title="editMode ? 'Update Permission' : 'Create Permission'">
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" :disabled="editMode" required autofocus
                    autocomplete="name" />
                <InputError class="mt-2" :message="form.errors.name" />
            </CardBoxModal>
            <!-- End Modal -->
            <div class="overflow-x-auto relative mt-0">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700  bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-2 px-6 text-xs">STT</th>
                            <th scope="col" class="py-2 px-6 text-xs">Name</th>
                            <th scope="col" class="py-2 px-6 text-xs">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(permission, index) in permissions.data" :key="index"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="py-2 px-6 text-md text-gray-500 whitespace-nowrap dark:text-white">
                                {{ index + permissions.from }}
                            </th>
                            <th scope="row" class="py-2 px-6 text-md text-gray-500 whitespace-nowrap dark:text-white">
                                {{ permission.name }}
                            </th>
                            <td class="py-2 text-right">
                                <button @click="edit(permission)" type="button" data-toggle="modal"
                                    data-target="#exampleModal"
                                    class="inline-block px-3 py-2 bg-gray-200 text-gray-700 font-black text-xs leading-tight  rounded shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out mx-2">
                                    Edit
                                </button>
                                <button type="button" @click="Delete(permission.id)"
                                    class="inline-block px-3 py-2 bg-red-500 text-white font-black text-xs leading-tight  rounded shadow-md hover:bg-red-600 hover:text-white hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :links="permissions.links" />
        </SectionMain>
    </LayoutAuthenticated>
</template>


