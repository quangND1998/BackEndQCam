<script setup>
import { computed, ref, inject, watch, toRef } from 'vue'
import LayoutProfileDetail from '@/Layouts/LayoutProfileDetail.vue';
import { Link,useForm } from '@inertiajs/vue3';
import SectionMain from '@/Components/SectionMain.vue'
import { Head } from '@inertiajs/vue3'
import CardBoxModal from '@/Components/CardBoxModal.vue'
import { mdiReceiptText, mdiPencilOutline, mdiPlus, mdiTrashCan, mdiTrashCanOutline, mdiPencil, mdiDotsVertical } from '@mdi/js'
import BaseButton from '@/Components/BaseButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue';
import PillTag from '@/Components/PillTag.vue'
import Multiselect from "@vueform/multiselect";
import Dropdown from '@/Components/Dropdown.vue';
import moment from 'moment';
import { useHelper } from '@/composable/useHelper';

const props = defineProps({
    history_extend: Object,
});
const { multipleSelect } = useHelper();
const swal = inject('$swal')
const form = useForm({
    id: null,
    images: null,
    state: null,
});
const totalTree = toRef(props.trees);
const isModalActive = ref(false)
const editMode = ref(false)
const edit = (contract) => {
    isModalActive.value = true
    editMode.value = true
    form.id = contract.id;
    form.state = contract.state
}

const form_reset = () => {
    console.log("reset");
};
const save = () => {

    console.log(form);
    if (editMode.value == true) {
        form.put(route("product_owner.contract.update", [ form.id , form.id]), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true;
            },
            onSuccess: () => {
                form_reset();
                form.reset();
                isModalActive.value = false;
                editMode.value = false;
                form.reset('id', 'name', 'description', 'images');
            },
        });
    } else {
        form
            .transform((data) => ({
                ...data,
                remember: data.remember ? 'on' : '',
            }))
            .post(route("product_owner.contract.store", props.history_extend.id), {
                onError: () => {
                    isModalActive.value = true;
                    editMode.value = false;
                },
                onSuccess: () => {
                    form_reset();
                    form.reset();
                    isModalActive.value = false;
                    editMode.value = false;
                    form.reset('id', 'name', 'description', 'images');
                },
            });
    }

};
const Delete = (id) => {
    swal
        .fire({
            title: "Are you sure?",
            text: "Bạn muốn xóa hợp đồng!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.delete(route("product_owner.contract.destroy", [props.history_extend?.id, id]), {
                    onSuccess: () => {
                        swal.fire(
                            "Deleted!",
                            "Đã xóa hợp đồng",
                            "success"
                        );
                    },
                });
            }
        });
};
</script>

<template>
    <LayoutProfileDetail :customer="history_extend?.product_service_owner?.customer" :crumbs="crumbs">

        <Head title="Hợp đồng" />
        <SectionMain>

            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                :title="editMode ? 'Chỉnh sửa' : 'Tạo mới'">
                <div class="p-6 flex-auto">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <InputLabel for="image" value="Bản scan hợp đồng" />
                        <div class=" flex items-center justify-center w-full">
                            <label for="dropzone-file"
                                class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                <div class="h-[160px] flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click
                                            to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">FPD
                                    </p>
                                </div>
                               
                                <input id="dropzone-file" @input="form.images = $event.target.files" type="file" multiple
                                    class="hidden" accept="application/pdf,application/vnd.ms-excel" />
                            </label>
                            <InputError class="mt-2" :message="form.errors.images" />
                        </div>


                    </div>
                </div>
            </CardBoxModal>
            <div class="p-2 flex-auto sm:w-full"> <Link :href="`/customer/${history_extend?.product_service_owner?.customer?.id}/products`" class="text-blue"> Gói dịch vụ </Link> > <Link class="text-blue" :href="`/product_owner/${history_extend?.product_service_owner?.id}/extend`"> Lịch sử gia hạn</Link> > hợp đồng </div>
            <div class="p-2 flex-auto sm:w-full">
                <div class="flex justify-between">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 hover:bg-bg_green_active" :icon="mdiPlus"
                        small @click="
                            isModalActive = true;
                        editMode = false;
                        form.reset('id', 'name', 'description', 'images');
                        form.reset();
                        form_reset();
                        " label="Tải scan hợp đồng" />
                </div>
                <div class=" relative shadow-md sm:rounded-lg mt-1">
                    <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6 text-xs">STT</th>
                                <th scope="col" class="py-3 px-6 text-xs">Link</th>
                                <th scope="col" class="py-3 px-6 text-xs">Thời gian</th>
                                <th scope="col" class="py-3 px-6 text-xs">
                                    <span class="sr-only">Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody v-if="history_extend?.contract?.history_contact">
                            <tr v-for="(history_contract, index) in history_extend?.contract?.history_contact" :key="index"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <!-- {{ history_extend?.product_service_owner?.id }} -->
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ index + 1 }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <iframe :src=" (history_contract?.images.length) > 0 ? history_contract?.images[0]?.original_url : null"></iframe>
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ history_contract?.created_at }}
                                </th>
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
                                                    <div @click="edit(history_contract)"
                                                        class="flex justify-between items-center px-4 text-sm text-[#2264E5] cursor-pointer  font-semibold">
                                                        <p class="hover:text-blue-700"> Edit</p>
                                                        <BaseButton :icon="mdiPencil" small class="text-[#2264E5]"
                                                            type="button" data-toggle="modal" data-target="#exampleModal" />
                                                    </div>
                                                    <div @click="Delete(history_contract.id)"
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
                </div>
            </div>

        </SectionMain>
    </LayoutProfileDetail>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>vv