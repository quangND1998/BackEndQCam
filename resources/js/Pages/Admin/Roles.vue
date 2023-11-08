<script setup>
import { computed, ref, inject } from 'vue'
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue';
import Pagination from '@/Components/Pagination.vue';
import { useForm } from '@inertiajs/vue3';
import SectionMain from '@/Components/SectionMain.vue'
import { Head } from '@inertiajs/vue3'
import CardBox from '@/Components/CardBox.vue'
import CardBoxModal from '@/Components/CardBoxModal.vue'
import { mdiEye, mdiAccountLockOpen,mdiPlus,    mdiFilter,mdiMagnify  } from '@mdi/js'
import BaseButton from '@/Components/BaseButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import PillTag from '@/Components/PillTag.vue'
import BaseButtons from '@/Components/BaseButtons.vue';
import Multiselect from '@vueform/multiselect'
import { useHelper } from '@/composable/useHelper';
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
defineProps({
    roles: Object,
    permissions: Array
});
const swal = inject('$swal')
const form = useForm({
    id: null,
    name: null,
    permission: null
});
const isModalActive = ref(false)
const editMode = ref(false)
const isModalDangerActive = ref(false)
const { multipleSelect } = useHelper();
console.log(multipleSelect)
const edit = (role) => {
    isModalActive.value = true
    editMode.value = true
    form.id = role.id;
    form.name = role.name
    form.permission = multipleSelect(role.permissions)

}

const save = () => {
    console.log(form)
    if (editMode.value == true) {
        form.put(route('roles.update', form.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true
            },
            onSuccess: () => {
                form.reset('name', 'id','permission')
                isModalActive.value = false;
                editMode.value = false
            }
        });
    }
    else {
        form.post(route('roles.store'), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = false
            },
            onSuccess: () => {
                form.reset('name', 'id','permission')
                isModalActive.value = false;
                editMode.value = false
            },
        });
    }

    // form.id = permission.id;
    // form.name = permission.name
}

const Delete = (id) => {
    swal.fire({
        title: 'Are you sure?',
        text: "Delete this permission!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log(id)
            form.delete(route('roles.delete', id), {
                onSuccess: () => {
                    swal.fire(
                        'Deleted!',
                        'Your role has been deleted.',
                        'success'
                    )
                }
            });

        }
    })
}
</script>

<template>
    <LayoutAuthenticated >

        <Head title="Roles" />
        <!-- <Multiselect v-model="value" :options="options" mode="tags" :close-on-select="false" :searchable="true"
            :create-option="true" /> -->
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiAccountLockOpen" title="Roles" main></SectionTitleLineWithButton>
            <!-- <BaseButton color="info" :icon="mdiPlus" small @click="isModalActive = true; form.reset()"
                label='Create Role' /> -->
                <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 m-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" placeholder="Search" aria-label="Search" size="24"/>
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 hover:bg-color_green" :icon="mdiPlus" small @click="
                        isModalActive = true; form.reset();
                    " label="Create Role" />
                </div>
            </div>
            <!-- Modal -->
            <CardBoxModal v-model="isModalDangerActive" title="Please confirm" button="danger" has-cancel>
                <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
                <p>This is sample modal</p>
            </CardBoxModal>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                    :title="editMode ? 'Update Role' : 'Create Role'">
                    <InputLabel for="name" value="Name" />
                    <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus
                        autocomplete="name" />
                    <InputError class="mt-2" :message="form.errors.name" />

                    <Multiselect v-model="form.permission" mode="tags" :appendNewTag="false" :createTag="false"
                        :searchable="true" label="name" valueProp="id" trackBy="name" :options="permissions"
                        class="form-control" :classes="{
                            tagsSearch: 'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                            container: 'relative mx-auto w-full flex items-center py-2 px-3 justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-2xl leading-snug outline-none',
                            tags: 'flex-grow flex-shrink flex flex-wrap items-center mt-1 pl-2 rtl:pl-0 rtl:pr-2',
                            tag: 'bg-red-600 text-white text-xs font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
                        }" />
                    <InputError class="mt-2" :message="form.errors.permission" />
            </CardBoxModal>
            <!-- End Modal -->
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg mt-5">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6 text-xs">STT</th>
                            <th scope="col" class="py-3 px-6 text-xs">name</th>
                            <th scope="col" class="py-3 px-6 text-xs">permissions</th>
                            <th scope="col" class="py-3 px-6 text-xs">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(role, index) in roles.data" :key="index"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                index + 1 }}</th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                role.name }}</th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <BaseButtons>
                                    <PillTag v-for="(permission, index) in role.permissions" :key="index" color="info"
                                        :label="permission.name" small outline=""></PillTag>
                                </BaseButtons>

                            </th>
                            <td class="py-4 px-6 text-right">
                                <button @click="edit(role)" type="button" data-toggle="modal" data-target="#exampleModal"
                                    class="inline-block px-6 py-2.5 bg-gray-200 text-gray-700 font-black text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out mx-2">Edit</button>
                                <button type="button" @click="Delete(role.id)"
                                    class="inline-block px-6 py-2.5 bg-red-500 text-white font-black text-xs leading-tight uppercase rounded shadow-md hover:bg-red-600 hover:text-white hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :links="roles.links" />
        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
