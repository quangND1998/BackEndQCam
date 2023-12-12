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

    shippers: Object,
});
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    email: null,
    roles: null,
    phone_number: null,
    created_byId: null,
    password: null,
});
const searchVal = ref("");
const isModalActive = ref(false);
const isActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);

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




const form_reset = () => {
    form.reset("name", "id", "roles", "email", "password", "phone_number");
};
const searchShipper = () => {
    router.get(route(`shippers.index`),
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

        <Head title="User" />

        <SectionMain class="p-3 mt-8">
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 my-2 mr-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" @keyup="searchShipper" placeholder="Search" aria-label="Search"
                            size="24" />
                    </div>
                </div>

            </div>




            <div class="overflow-x-auto relative  sm:rounded-lg mt-5">
                <table class="w-full text-xs text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3 px-6 text-xs">STT</th>
                            <th scope="col" class="py-3 px-6 text-xs">name</th>
                            <th scope="col" class="py-3 px-6 text-xs">email</th>
                            <th scope="col" class="py-3 px-6 text-xs">phone</th>
                            <th scope="col" class="py-3 px-6 text-xs">created at</th>
                            <th scope="col" class="py-3 px-6 text-xs">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(shipper, index) in shippers.data" :key="index" class="bg-white border-b">
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ index + 1 }}
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ shipper.name }}
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ shipper.email }}
                            </th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ shipper.phone_number }}
                            </th>





                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ formatDate(shipper.created_at) }}
                            </th>
                            <td class="py-4 px-6 text-right">
                                <Link :href="route('shippers.detail', shipper.id)" type="button"
                                    class="inline-block px-6 py-2.5 bg-blue-600 text-white font-black text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-500 hover:shadow-lg focus:bg-blue-400 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out mx-2">
                                Detail
                                </Link>


                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :links="shippers.links" />
        </SectionMain>
    </LayoutAuthenticated>
</template>

