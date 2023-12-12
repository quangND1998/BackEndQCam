<script setup>
import { computed, ref, inject, watch, toRef } from 'vue'
import LayoutProfileDetail from '@/Layouts/LayoutProfileDetail.vue';
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
import { Link, useForm } from "@inertiajs/vue3";
import moment from 'moment';
import { useHelper } from '@/composable/useHelper';
const props = defineProps({
    customer: Object,
    gifts: Array,
});
const { multipleSelect } = useHelper();
const swal = inject('$swal')
const form = useForm({
    id: null,
    state: null,
});
const totalTree = toRef(props.trees);
const isModalActive = ref(false)
const editMode = ref(false)
const isModalExtendActive = ref(false);
const edit = (product_owner) => {
    isModalActive.value = true
    editMode.value = true
    form.id = product_owner.id;
}
const extend = (product_owner) => {
    isModalExtendActive.value = true;
    form.product_service = product_owner.id;
}
const crumbs = ref([

    {
        route: "customer.detail.gift",
        parma: props.customer.id,
        name: props.customer.name
    },
    {
        route: "customer.detail.gift",
        parma: props.customer.id,
        name: "Amenities"
    }
])
const form_reset = () => {
    console.log("reset");
    totalTree.value = props.trees;
};

const limit_tree = computed(() => {
    console.log('limit_tree', form.product_service)
    let product_service = props.products.find(e => e.id == form.product_service);
    return product_service
}
);
</script>

<template>
    <LayoutProfileDetail :customer="customer" :crumbs="crumbs">

        <Head title="Product" />
        <SectionMain>

            <div class="flex-auto sm:w-full">
                <div class=" relative shadow-md sm:rounded-lg ">
                    <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6 text-xs">STT</th>
                                <th scope="col" class="py-3 px-6 text-xs">Gói</th>
                                <th scope="col" class="py-3 px-6 text-xs">Sản phẩm</th>
                                <th scope="col" class="py-3 px-6 text-xs">Thời gian</th>
                                <th scope="col" class="py-3 px-6 text-xs">Trạng thái</th>

                            </tr>
                        </thead>
                        <tbody v-if="customer">
                            <tr v-for="(gift, index) in gifts.data" :key="gift.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                                     <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ index + 1 }}
                                    </th>
                                    <th scope="row"
                                        class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ gift?.product_service?.product?.name }}
                                    </th>
                                    <th scope="row"
                                        class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <p v-for="item in gift.order_items" :key="item.id">
                                            {{ item?.product?.name }} {{ item?.quantity }}kg
                                        </p>
                                    </th>
                                    <th scope="row"
                                        class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                         {{ moment(String(gift.created_at)).format('MM/DD/YYYY') }}
                                    </th>
                                    <th class="py-3 px-6 text-xs">
                                        <PillTag :color="gift.status == 'completed' ? 'success' : 'danger'"
                                            :label="gift.status" small>
                                        </PillTag>
                                    </th>
                            </tr>
                            <!-- <pagination :links="customer.product_service_owners.links" /> -->
                        </tbody>
                    </table>
                </div>
            </div>

        </SectionMain>
    </LayoutProfileDetail>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
