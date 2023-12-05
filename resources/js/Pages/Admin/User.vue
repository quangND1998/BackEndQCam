<script setup>
import { computed, ref, inject, watch, toRef } from "vue";
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
    mdiFileDocumentOutline,
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import PillTag from "@/Components/PillTag.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import Multiselect from "@vueform/multiselect";
import { useHelper } from "@/composable/useHelper";
import { router, Link } from "@inertiajs/vue3";
import SearchFilter from "@/Components/SearchFilter.vue";
import throttle from "lodash/throttle";
import mapValues from "lodash/mapValues";
import pickBy from "lodash/pickBy";
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazPhoneNumberInput from 'maz-ui/components/MazPhoneNumberInput'
const phoneNumber = ref()
const results = ref()
const props = defineProps({
    filters: Object,
    roles: Array,
    users: Object,

});
const swal = inject("$swal");
const form = useForm({
    id: null,
    search: null,
    name: null,
    email: null,
    roles: null,
    phone_number: null,
    created_byId: null,
    password: null,
});
const isModalActive = ref(false);
const isActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);
const search = ref(null);
const { multipleSelect } = useHelper();
// console.log(multipleSelect)
const edit = (user) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = user.id;
    form.name = user.name;
    form.email = user.email;
    form.phone_number = user.phone_number;
    form.created_byId = user.created_byId;
    form.roles = multipleSelect(user.roles);
};

// watch(
//     search,
//     throttle(() => {
//         let query = { search: search.value };
//         router.replace(
//             route("users.index", Object.keys(query).length ? query : null, {
//                 replace: true,
//             })
//         );
//     }, 150),
//     { deep: true }
// );
const searchUser=()=>{
    router.get(route(`users.index`),
        {search:search.value},
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const reset = () => {
    search.value = null;
};
const form_reset = () => {
    form.reset("name", "id", "roles", "email", "password", "phone_number");
};
const save = () => {
    console.log(form);
    if (editMode.value == true) {
        form.put(route("users.update", form.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true;
            },
            onSuccess: () => {
                form_reset();
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    } else {
        form.post(route("users.store"), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = false;
            },
            onSuccess: () => {
                form_reset();
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    }

    // form.id = permission.id;
    // form.name = permission.name
};
const setActive = (data, event) => {

    if (event.target.checked) {
        isActive.value = 1;
    } else {
        isActive.value = 0;
    }
    let query = {
        id: data.id,
        active: isActive.value
    };
    axios
        .post("users/setActive", query)
        .then(response => { })
        .catch(function (error) {
            // handle error
            console.log(error);
        });
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
                form.delete(route("users.destroy", id), {
                    onSuccess: () => {
                        swal.fire("Deleted!", "Your role has been deleted.", "success");
                    },
                });
            }
        });
};
</script>

<template>
    <LayoutAuthenticated>

        <Head title="User" />
        <SectionMain class="p-3 mt-16">
            <SectionTitleLineWithButton title="User" main></SectionTitleLineWithButton>
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 mr-2 my-2 bg-white" :iconSize="20" />

                        <!-- <search-filter v-model="search" class="mr-4 w-full max-w-md" @reset="reset">
                            <label class="block text-gray-700">Trashed:</label>
                        </search-filter> -->
                        <!-- <SearchInput v-model="search"   placeholder="Search..." aria-label="Search" size="24"/> -->
                        <input    v-model="search" @keyup="searchUser"
                            class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            type="search" name="search" placeholder="Search…"  />
                    </div>
                </div>
                <div class="right">
                    <BaseButton :routeName="'users.create'" color="info"
                        class="bg-btn_green hover:bg-[#318f02] text-white p-2 hover:bg-[#008000]" :icon="mdiPlus" small
                        label="Create User" />
                </div>
            </div>
            <!-- Modal -->
            <CardBoxModal v-model="isModalDangerActive" title="Please confirm" button="danger" has-cancel>
                <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
                <p>This is sample modal</p>
            </CardBoxModal>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                :title="editMode ? 'Update User' : 'Create User'">
                <div class="p-6 flex-auto">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <InputLabel for="name" value="Name" />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full"
                                :class="form.errors.name ? 'border-red-500' : ''" required autofocus autocomplete="name" />

                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="email" value="Email" />
                            <!-- <input
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border text-xl border-gray-300 rounded py-2 px-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    id="grid-last-name" type="text" placeholder="examp@example" v-model="form.email"
                                    :class="form.errors.email ? 'border-red-500' : ''" /> -->
                            <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full"
                                :class="form.errors.email ? 'border-red-500' : ''" autocomplete="name" />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="name" value="Password" />

                            <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full"
                                :class="form.errors.password ? 'border-red-500' : ''" autocomplete="name" />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="phone" value="Phone" />

                            <!-- <TextInput id="value" v-model="form.phone_number" type="text" class="mt-1 block w-full"
                                :class="form.errors.phone_number ? 'border-red-500' : ''" autocomplete="name" /> -->
                            <MazPhoneNumberInput v-model="form.phone_number" show-code-on-list
                                :preferred-countries="['FR', 'BE', 'DE', 'US', 'GB']" :ignored-countries="['AC']"
                                @update="results = $event" />

                            <InputError class="mt-2" :message="form.errors.phone_number" />
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="name" value="Roles" />

                            <Multiselect v-model="form.roles" mode="tags" :appendNewTag="false" :createTag="false"
                                :searchable="true" label="name" valueProp="id" trackBy="name" :options="roles"
                                class="form-control" :classes="{
                                    tagsSearch:
                                        'absolute inset-0 border-0  w-full outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                                    container:
                                        'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-2xl leading-snug outline-none',
                                    tags: 'flex-grow flex-shrink flex flex-wrap items-center mt-1 pl-2 rtl:pl-0 rtl:pr-2',
                                    tag: 'bg-red-600 text-white text-xs font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
                                }" />
                            <InputError class="mt-2" :message="form.errors.roles" />
                        </div>


                    </div>
                </div>
                <!-- <InputLabel for="name" value="Name" />
                    <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus
                        autocomplete="name" />
                    <InputError class="mt-2" :message="form.errors.name" />

                    <Multiselect v-model="form.roles" mode="tags" :appendNewTag="false" :createTag="false"
                        :searchable="true" label="name" valueProp="id" trackBy="name" :options="roles" class="form-control"
                        :classes="{
                            tagsSearch: 'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                            container: 'relative mx-auto w-full flex items-center py-2 px-3 justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-2xl leading-snug outline-none',
                            tags: 'flex-grow flex-shrink flex flex-wrap items-center mt-1 pl-2 rtl:pl-0 rtl:pr-2',
                            tag: 'bg-red-600 text-white text-xs font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
                        }" />
                    <InputError class="mt-2" :message="form.errors.permission" /> -->
            </CardBoxModal>
            <div class="overflow-x-auto relative mt-0">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700  bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-2 px-6 text-xs">STT</th>
                            <th scope="col" class="py-2 px-6 text-xs">Name</th>
                            <th scope="col" class="py-2 px-6 text-xs">Email</th>
                            <th scope="col" class="py-2 px-6 text-xs">Phone</th>
                            <th scope="col" class="py-2 px-6 text-xs">Role</th>
                            <!-- <th scope="col" class="py-2 px-6 text-xs">Active</th> -->
                            <th v-if="hasAnyPermission(['super-admin'])" scope="col" class="py-2 px-6 text-xs">Team</th>
                            <th scope="col" class="py-2 px-6 text-xs">Created at</th>
                            <th scope="col" class="py-2 px-6 text-xs">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in users.data" :key="index"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="py-2 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ index + 1 }}
                            </th>
                            <th scope="row" class="py-2 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.name }}
                            </th>
                            <th scope="row" class="py-2 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.email }}
                            </th>
                            <th scope="row" class="py-2 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.phone_number }}
                            </th>


                            <th scope="row" class="py-2 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <BaseButtons>
                                    <PillTag v-for="(role, index) in user.roles" :key="index" color="info"
                                        :label="role.name" small outline=""></PillTag>
                                </BaseButtons>
                            </th>

                            <!-- <th class="py-2 px-6 text-xs">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" value="" class="sr-only peer"
                                        :checked="user.isActive == 1 ? true : false" @change="setActive(user, $event)" />
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </th> -->

                            <th v-if="hasAnyPermission(['super-admin'])" scope="row"
                                class="py-2 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.team?.name }}
                            </th>
                            <th scope="row" class="py-2 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ formatDate(user.created_at) }}
                            </th>
                            <td class="py-2 px-6 text-right">
                                <Link :href="route('users.edit', user.id)" type="button" data-toggle="modal"
                                    data-target="#exampleModal"
                                    class="inline-block px-6 py-2 bg-gray-200 text-gray-700 font-black text-xs leading-tight  rounded shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out mx-2 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                Edit
                                </Link>
                                <button type=" button" @click="Delete(user.id)"
                                    class="inline-block px-6 py-2 bg-red-500 text-white font-black text-xs leading-tight  rounded shadow-md hover:bg-red-600 hover:text-white hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :links="users.links" />
        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
