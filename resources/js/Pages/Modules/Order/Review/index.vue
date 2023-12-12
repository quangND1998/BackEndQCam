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
    mdiDotsVertical,
    mdiStar,
    mdiStarOutline
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
import Dropdown from '@/Components/Dropdown.vue';
import MazPhoneNumberInput from 'maz-ui/components/MazPhoneNumberInput';
import BaseIcon from '@/Components/BaseIcon.vue'
const phoneNumber = ref()
const results = ref()
const props = defineProps({
    filters: Object,
    reviews: Object,
});
const swal = inject("$swal");
const form = useForm({
    id: null,
    search: props.filters != null ? props.filters.search : null,
    name: null
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

const selected = ref([])
const selectAll = computed({
    get() {
        return props.reviews.data
            ? selected.value.length == props.reviews.data
            : false;
    },
    set(value) {
        var array_selected = [];

        if (value) {
            props.reviews.data.forEach(function (image) {
                array_selected.push(image.id);
            });
        }
        selected.value = array_selected;
    }
});

</script>

<template>
    <LayoutAuthenticated>

        <SectionMain class="p-3 mt-16">
            <SectionTitleLineWithButton :icon="mdiStarPlus" title="Đánh giá" main></SectionTitleLineWithButton>



            <div class="  relative  sm:rounded-lg mt-2">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-3 py-2 ">
                                <input type="checkbox" v-model="selectAll"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                #
                            </th>
                            <th scope="col" class="py-2 px-3 text-xs">Đơn hàng</th>
                            <th scope="col" class="py-2 px-3 text-xs">Sao</th>
                            <th scope="col" class="py-2 px-3 text-xs">User</th>
                            <th scope="col" class="py-2 px-3 text-xs">KeyWord</th>
                            <th scope="col" class="py-2 px-3 text-xs">Mô tả</th>
                            <th scope="col" class="py-2 px-3 text-xs">Ngày</th>
                        </tr>
                    </thead>
                    <tbody v-if="reviews">
                        <tr v-for="(review, index) in reviews.data" :key="index"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                            <th>
                                <input id="default-checkbox" type="checkbox" v-model="selected" :value="review.id"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 mr-2">
                                {{ index + 1 }}
                            </th>
                            <th>
                                <a v-if="review.order" :href="route('admin.payment.orderCashBankingPayment', review?.order?.id)"
                                    class="text-blue-500">
                                    {{ review?.order?.order_number }}
                                </a>

                            </th>
                            <th class=" text-center items-center">
                                <div class="flex">
                                    <p class="py-2" v-for="n in review.star" :key="n">
                                    <BaseIcon :path="mdiStar" color="#F78F43" size="20" />
                                    </p>
                                </div>

                            </th>
                            <th>
                                <a :href="route('customer.detail.info', review?.user?.id)" class="text-blue-500">
                                    {{ review?.user?.name }}
                                </a>
                            </th>
                            <th  class="">
                                <p class="mr-2 mb-1 h-full text-xs font-mono keyword btn_key text-center " v-for="keyword in review.data" :key="keyword.id">
                                    {{ keyword }}
                                </p>
                            </th>
                            <th class="py-3 px-3 text-xs">
                                {{ review?.description }}
                            </th>
                            <th>
                                {{ formatDate(review.created_at) }}
                            </th>
                        </tr>

                    </tbody>
                </table>
                <pagination :links="reviews.links" />
            </div>

        </SectionMain>
    </LayoutAuthenticated></template>
<style src="@vueform/multiselect/themes/default.css"></style>
