<script setup>
import { computed, ref, inject, watch, toRef } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { Link, useForm } from "@inertiajs/vue3";
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
import { router } from "@inertiajs/vue3";
import SearchFilter from "@/Components/SearchFilter.vue";
import throttle from "lodash/throttle";
import mapValues from "lodash/mapValues";
import pickBy from "lodash/pickBy";
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";

import MazPhoneNumberInput from 'maz-ui/components/MazPhoneNumberInput';

const phoneNumber = ref()
const results = ref()
const props = defineProps({
    filters: Object,
    customers: Object,
    product_services: Object,
    trees : Object
});
const swal = inject("$swal");
const form = useForm({
    id: null,
    search: props.filters != null ? props.filters.search : null,
    name: null,
    username: null,
    email: null,
    phone_number: null,
    created_byId: null,
    password: null,
    product_service: props.product_services.length >0 ? props.product_services[0].id:null,
    time_approve: null,
    tree: null,
});
const isModalActive = ref(false);
const isActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);
const search = toRef(props.filters.search);
const { multipleSelect } = useHelper();
// console.log(multipleSelect)
const edit = (user) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = user.id;
    form.name = user.name;
    form.email = user.email;
    form.username = user.username;
    form.phone_number = user.phone_number;
    form.created_byId = user.created_byId;
    form.time_approve = user?.product_service_owners?.time_approve
};

watch(
    search,
    throttle(() => {
        let query = { search: search.value };
        router.replace(
            route("customers.index", Object.keys(query).length ? query : null, {
                replace: true,
            })
        );
    }, 150),
    { deep: true }
);

const reset = () => {
    editMode.value = false;
    search.value = null;
};
const form_reset = () => {
    form.reset("name", "id", "username", "email", "password", "phone_number");
};
const save = () => {
    console.log(form);
    if (editMode.value == true) {
        form.put(route("customer.update", form.id), {
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
        form
        .transform((data) => ({
            ...data,
            remember: data.remember ? 'on' : '',
        }))
        .post(route("customer.store"), {
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
        .post("customer/setActive", query)
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
                form.delete(route("customer.destroy", id), {
                    onSuccess: () => {
                        swal.fire("Deleted!", "Your role has been deleted.", "success");
                    },
                });
            }
        });
};

const selected = ref([])
const selectAll = computed({
    get() {
        return props.customers.data
            ? selected.value.length == props.customers.data
            : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            props.customers.data.forEach(function (image) {
                array_selected.push(image.id);
            });
        }
        selected.value = array_selected;
    }
});

const limit_tree = computed(() =>{
    console.log('limit_tree',form.product_service)
    let product_service= props.product_services.find(e=>e.id == form.product_service);

    return product_service
}
);
</script>

<template>
    <LayoutAuthenticated>

        <Head title="Customer" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiAccountLockOpen" title="User" main></SectionTitleLineWithButton>

            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 m-2 bg-white" :iconSize="20" />
                        <search-filter v-model="search" class="mr-4 w-full max-w-md" @reset="reset">
                            <label class="block text-gray-700">Trashed:</label>
                        </search-filter>
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 hover:bg-bg_green_active" :icon="mdiPlus"
                        small @click="
                            isModalActive = true;
                            form.reset();
                            reset();
                        " label="Create User" />
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
                            <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full"
                                :class="form.errors.email ? 'border-red-500' : ''" autocomplete="name" />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="username" value="Username" />
                            <TextInput id="username" v-model="form.username" type="text" class="mt-1 block w-full"
                                :class="form.errors.username ? 'border-red-500' : ''" autocomplete="username" />
                            <InputError class="mt-2" :message="form.errors.username" />
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="name" value="Password" />

                            <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full"
                                :class="form.errors.password ? 'border-red-500' : ''" autocomplete="name" />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="phone" value="Phone" />

                            <!-- <MazPhoneNumberInput v-model="form.phone_number" show-code-on-list
                                :preferred-countries="['FR', 'BE', 'DE', 'US', 'GB']" :ignored-countries="['AC']"
                                @update="results = $event" /> -->
                            <TextInput id="password" v-model="form.phone_number" type="text" class="mt-1 block w-full"
                                :class="form.errors.phone_number ? 'border-red-500' : ''" autocomplete="phone_number" />

                            <InputError class="mt-2" :message="form.errors.phone_number" />
                        </div>

                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="owner" value="Thời gian bắt đầu áp dụng" />
                            <div date-rangepicker class="flex items-center">
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <!-- <svg aria-hidden="true" class="w-5 h-5 text-gray-500 text-gray-400"
                                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg> -->
                                    </div>
                                    <input v-model="form.time_approve" type="date"
                                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5  placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Ngày bắt đầu" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <div v-if="!editMode" class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="owner" value="Gói dịch vụ" />
                            <select id="category_project_id" v-model="form.product_service" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option v-for="product in product_services" :key="product.id" :value="product.id">{{
                                    product.name }}</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <InputLabel for="owner" value="Cây" />
                            <Multiselect v-model="form.tree" mode="tags" :appendNewTag="false" :createTag="false" :limit="limit_tree?.number_tree"
                                :searchable="true" label="name" valueProp="id" trackBy="name" :options="trees"
                                class="form-control" :classes="{
                                    tagsSearch: 'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                                    container: 'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-2xl leading-snug outline-none',
                                    tags: 'flex-grow flex-shrink flex flex-wrap items-center mt-1 pl-2 rtl:pl-0 rtl:pr-2',
                                    tag: 'bg-red-600 text-white text-xs font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
                                }" />
                        </div>
                    </div>
                </div>
            </CardBoxModal>
            <div class="flex justify-end my-3">
                <BaseButton color="info" class="bg-blue-500 mx-2 text-white p-2 hover:bg-bg_green_active" :icon="mdiPlus"
                        small @click="
                            isModalActive = true;
                        " label="Create User" />
                         <BaseButton color="info" class="bg-blue-500 mx-2 text-white p-2 hover:bg-bg_green_active" :icon="mdiPlus"
                        small @click="
                            isModalActive = true;
                        " label="Create User" />
                         <BaseButton color="info" class="bg-blue-500 mx-2 text-white p-2 hover:bg-bg_green_active" :icon="mdiPlus"
                        small @click="
                            isModalActive = true;
                        " label="Create User" />
            </div>
            <div v-if="selected>1 ">
                <p class="text-red-600 text-end">Xóa (5) Customer</p>
            </div>
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg mt-5">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 flex items-center">
                        <input type="checkbox" v-model="selectAll"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                        #
                    </th>
                            <th scope="col" class="py-3 px-6 text-xs">STT</th>
                            <th scope="col" class="py-3 px-6 text-xs">name</th>
                            <th scope="col" class="py-3 px-6 text-xs">email</th>
                            <th scope="col" class="py-3 px-6 text-xs">phone</th>
                            <th scope="col" class="py-3 px-6 text-xs">username</th>
                            <th scope="col" class="py-3 px-6 text-xs">Active</th>
                            <th scope="col" class="py-3 px-6 text-xs">created at</th>
                            <th scope="col" class="py-3 px-6 text-xs">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody v-if="customers">
                        <tr v-for="(user, index) in customers.data" :key="index"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <input id="default-checkbox" type="checkbox"  v-model="selected" :value="user.id"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                {{ index + 1 }}
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.name }}
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.email }}
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.phone_number }}
                            </th>


                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.username }}
                            </th>
                            <th class="py-3 px-6 text-xs">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" value="" class="sr-only peer"
                                        :checked="user.isActive == 1 ? true : false" @change="setActive(user, $event)" />
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ formatDate(user.created_at) }}
                            </th>
                            <td class="py-4 px-6 text-right">
                                <Link :href="route('customer.detail.info', user.id)" type="button"
                                    class="inline-block px-6 py-2.5 bg-blue-600 text-white font-black text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-500 hover:shadow-lg focus:bg-blue-400 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out mx-2">
                                Detail
                                </Link>
                                <button @click="edit(user)" type="button" data-toggle="modal" data-target="#exampleModal"
                                    class="inline-block px-6 py-2.5 bg-gray-200 text-gray-700 font-black text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out mx-2">
                                    Edit
                                </button>
                                <button type=" button" @click="Delete(user.id)"
                                    class="inline-block px-6 py-2.5 bg-red-500 text-white font-black text-xs leading-tight uppercase rounded shadow-md hover:bg-red-600 hover:text-white hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <pagination :links="customers.links" />
                    </tbody>
                </table>

            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
