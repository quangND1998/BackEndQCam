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

import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import Multiselect from '@vueform/multiselect'
defineProps({
    bookings: Object,
    users: Object,
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    ballot_number: null,
    ballot_code: null,
    user_id: null,
    status: 'active',
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

const edit = (booking) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = booking.id;
    form.ballot_number = booking.ballot_number;
    form.ballot_code = booking.ballot_code;
    form.status = booking.status;
    form.user_id = booking.user_id;
};

const save = () => {
    console.log(form);
    if (editMode.value == true) {
        form.put(route("admin.booking.update", form.id), {
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
        form.post(route("admin.booking.store"), {
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
            title: "Bạn có chắc chắn?",
            text: "Muốn xóa đầu phiếu!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.delete(route("admin.booking.destroy", id), {
                    onSuccess: () => {
                        swal.fire(
                            "Deleted!",
                            "Đã xóa đầu phiếu.",
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

        <SectionMain class="pl-0 mt-5 p-3" >
            <SectionTitleLineWithButton title="Quản lý booking" main></SectionTitleLineWithButton>
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 my-2 mr-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" placeholder="Search" aria-label="Search" size="24" />
                    </div>
                </div>
                <div class="right">
                    <Button color="info" class="bg-[#FF6100] text-white px-4 py-2.5 rounded-xl "
                        @click="
                            isModalActive = true;
                        form.reset();
                        " label="Thêm mã" >Thêm mã</Button>

                </div>
            </div>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                :title="editMode ? 'Sửa phiếu' : 'Tạo phiếu'">
                <div class="w-full flex">
                    <div class="w-1/3 px-2">
                        <InputLabel for="ballot_number" value="Đầu phiếu" />
                        <TextInput id="ballot_number" v-model="form.ballot_number" type="text" class="mt-1 block w-full" required autofocus
                            autocomplete="ballot_number" />
                        <InputError class="mt-2" :message="form.errors.ballot_number" />
                    </div>
                    <div class="w-1/3 px-2">
                        <InputLabel for="ballot_code" value="Mã phiếu" />
                        <TextInput id="ballot_code" v-model="form.ballot_code" type="text" class="mt-1 block w-full" required autofocus
                            autocomplete="ballot_code" />
                        <InputError class="mt-2" :message="form.errors.ballot_code" />
                    </div>
                    <div class="w-1/3 px-2">
                        <InputLabel for="ballot_code" value="Đơn vị nhận mã" />
                        <Multiselect v-model="form.user_id" :searchable="true" label="name" valueProp="id" trackBy="name" placeholder="None"  :options="users" :classes="{
                            tagsSearch: 'absolute  inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                            container: 'relative border_round mx-auto w-full bg-gray-50  items-center  box-border cursor-pointer border border-gray-300 rounded bg-gray-50 text-sm  '
                            }" >
                                <template v-slot:singlelabel="{ value }">
                                    <div class="multiselect-single-label">
                                        {{ value.name }}
                                    </div>
                                </template>

                                <template v-slot:option="{ option }">
                                    {{ option.name }}
                                </template>
                        </Multiselect>
                    </div>
                </div>
                <div class="px-2">
                    <InputLabel for="owner" value="Trạng thái" />

                    <select id="category_project_id" v-model="form.status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="active">active</option>
                        <option value="deactive">deactive</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.status" />
                </div>

            </CardBoxModal>
            <!-- End Modal -->
            <div class="mt-2">
                <div class="grid sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 2xl:grid-cols-7 gap-4 ">
                    <div v-for="(booking, index) in bookings.data" :key="index" class="py-2">
                        <!-- </Link> -->
                        <Link :href="route('admin.booking.detail',  booking.id)">
                            <div class="bg-[#ffffff] border px-2 py-2 items-center  rounded-xl">
                                <div class="w-full flex  items-center justify-between ">
                                <div class="w-full flex flex-col items-center ">
                                    <h3 class="text-black text-sm font-medium ">{{ booking.ballot_number }} </h3>
                                        <p :class="booking.status == 'active' ? 'text-[#4F8D06]' : 'text-black' ">
                                            Đã phát: {{ booking.booking_owner_count }}/{{ booking.history_count }}
                                        </p>
                                </div>
                                <div class="">
                                    <Dropdown align="right" width="40" @click.prevent>
                                        <template #trigger>
                                            <span class="inline-flex rounded-md">
                                                <BaseButton class="bg-[#D9D9D9] border-[#D9D9D9]" :icon="mdiDotsVertical" small />
                                            </span>
                                        </template>

                                        <template #content>
                                            <div class="w-40">
                                                <div @click="edit(booking)"
                                                    class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                                    <p class="hover:text-blue-700"> Edit</p>
                                                    <BaseButton :icon="mdiPencil" small class="text-[#2264E5]" type="button"
                                                        data-toggle="modal" data-target="#exampleModal" />
                                                </div>
                                                <div @click="Delete(booking.id)"
                                                    class="flex justify-between items-center px-4  text-sm text-[#D12953] cursor-pointer  font-semibold">
                                                    <p class="hover:text-red-700"> Delete</p>
                                                    <BaseButton :icon="mdiTrashCanOutline" small class="text-[#D12953]" />
                                                </div>

                                            </div>
                                        </template>
                                    </Dropdown>
                                </div>
                                </div>
                            </div>
                        </Link>
                    </div>

                </div>
            </div>
            <pagination :links="bookings.links" />
        </SectionMain>
    </LayoutAuthenticated>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
