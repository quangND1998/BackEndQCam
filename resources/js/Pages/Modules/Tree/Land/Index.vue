<script setup>
import { computed, ref, inject } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";
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
defineProps({
    lands: Object,
});
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
// const value = ref([]);
// const options = ref([
//     'Batman',
//     'Robin',
//     'Joker',
// ])

const edit = (land) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = land.id;
    form.name = land.name;
    form.state = land.state;
};

const save = () => {
    console.log(form);
    if (editMode.value == true) {
        form.put(route("admin.land.update", form.id), {
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
        form.post(route("admin.land.store"), {
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
                form.delete(route("admin.land.delete", id), {
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

        <Head title="Lands" />
        <!-- <Multiselect v-model="value" :options="options" mode="tags" :close-on-select="false" :searchable="true"
            :create-option="true" /> -->
        <SectionMain>
            <SectionTitleLineWithButton title="Lands" main></SectionTitleLineWithButton>

            <!-- Modal -->
            <CardBoxModal v-model="isModalDangerActive" title="Please confirm" button="danger" has-cancel>
                <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
                <p>This is sample modal</p>
            </CardBoxModal>
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 m-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" placeholder="Search" aria-label="Search" size="24" />
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 hover:bg-color_green" :icon="mdiPlus" small
                        @click="
                            isModalActive = true;
                        form.reset();
                        " label="Create Land" />
                </div>
            </div>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                :title="editMode ? 'Update Land' : 'Create Land'">
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus
                    autocomplete="name" />
                <InputError class="mt-2" :message="form.errors.name" />

                <InputLabel for="owner" value="Status" />

                <select id="category_project_id" v-model="form.state"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option :value="null">Choose Status</option>
                    <option value="public">Mở bán</option>
                    <option value="private">Chưa mở bán</option>
                </select>
                <InputError class="mt-2" :message="form.errors.state" />
            </CardBoxModal>
            <!-- End Modal -->
            <div class="mt-5">
                <div class="grid sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 2xl:grid-cols-7 gap-4 ">
                    <div v-for="(land, index) in lands.data" :key="index" class="border border-gray-500">
                        <!-- <Link :href="route('admin.project.blocks.getFloors', [project.id, block.id])"> -->

                        <!-- <BaseIcon :path="mdiLandFields" size="160" class="w-full h-40 object-cover" /> -->
                        <!-- </Link> -->
                        <Link :href="route('admin.land.tree.index',  land.id)">
                        <div class="bg-[#D9D9D9]  px-2 py-1 items-center cursor-pointer">
                            <div class="flex justify-between">
                               
                                    <h3 class="text-black text-sm font-medium ">{{ land.name }}</h3>
                               
                                <Dropdown align="right" width="40" @click.prevent>
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <BaseButton class="bg-[#D9D9D9] border-[#D9D9D9]" :icon="mdiDotsVertical" small />
                                    </span>
                                </template>

                                <template #content>
                                    <div class="w-40">
                                        <div @click="edit(land)"
                                            class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                            <p class="hover:text-blue-700"> Edit</p>
                                            <BaseButton :icon="mdiPencil" small class="text-[#2264E5]" type="button"
                                                data-toggle="modal" data-target="#exampleModal" />
                                        </div>
                                        <div @click="Delete(land.id)"
                                            class="flex justify-between items-center px-4  text-sm text-[#D12953] cursor-pointer  font-semibold">
                                            <p class="hover:text-red-700"> Delete</p>
                                            <BaseButton :icon="mdiTrashCanOutline" small class="text-[#D12953]" />
                                        </div>

                                    </div>
                                </template>
                            </Dropdown>
                            </div>
                            <div class=" flex justify-center">
                                <span v-if="land.state == 'public'"
                                class="inline-block whitespace-nowrap rounded-full bg-lime-300 px-[0.65em] py-1 text-center align-baseline text-[0.75em] font-bold leading-none text-success-700">
                                Mở bán
                            </span>
                            <span v-if="land.state == 'private'"
                                class="inline-block whitespace-nowrap rounded-full bg-red-300 px-[0.65em] py-1 text-center align-baseline text-[0.75em] font-bold leading-none text-danger-700">
                                Chưa Mở bán
                            </span>
                            </div>
                            
                           

                        </div>
                    </Link>
                    </div>

                </div>
            </div>
            <pagination :links="lands.links" />
        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
