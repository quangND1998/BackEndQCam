<script setup>
import { computed, ref, inject, watch, toRef } from 'vue'
import LayoutProfileDetail from '@/Layouts/LayoutProfileDetail.vue';
import { useForm } from '@inertiajs/vue3';
import SectionMain from '@/Components/SectionMain.vue'
import { Head } from '@inertiajs/vue3'
import CardBoxModal from '@/Components/CardBoxModal.vue'
import { mdiReceiptText, mdiPencilOutline, mdiPlus, mdiTrashCan } from '@mdi/js'
import BaseButton from '@/Components/BaseButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue';
import PillTag from '@/Components/PillTag.vue'
import moment from 'moment';
const props = defineProps({
    customer: Object,
    products: Object
});
const swal = inject('$swal')
const form = useForm({
    id: props.customer.id,
    name: props.customer.name,
    username: props.customer.username,
    phone_number: props.customer.phone_number,
    email: props.customer.email,
    password: null


});
const isModalActive = ref(false)
const editMode = ref(false)
const edit = (customer) => {
    isModalActive.value = true
    editMode.value = true
    form.id = customer.id;
    form.name = customer.name;
    form.username = customer.username;
    form.phone_number = customer.phone_number;
    form.email = customer.email;
    form.password = customer.password;

}
const crumbs = ref([

    {
        route: "customer.detail.info",
        parma: props.customer.id,
        name: props.customer.name
    },
    {
        route: "customer.detail.info",
        parma: props.customer.id,
        name: "Amenities"
    }
])
const save = () => {

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


    // form.id = permission.id;
    // form.name = permission.name
};
</script>

<template>
    <LayoutProfileDetail :customer="customer" :crumbs="crumbs">

        <Head title="Product" />
        <SectionMain>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                :title="editMode ? 'Chỉnh sửa' : 'Tạo mới'">
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
                </div>
            </CardBoxModal>
            <div class="p-6 flex-auto sm:w-full">
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg mt-5">
                    <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6 text-xs">STT</th>
                                <th scope="col" class="py-3 px-6 text-xs">Gói</th>
                                <th scope="col" class="py-3 px-6 text-xs">Giá (vnđ)</th>
                                <th scope="col" class="py-3 px-6 text-xs">Thời gian</th>
                                <th scope="col" class="py-3 px-6 text-xs">Trạng thái</th>
                                <th scope="col" class="py-3 px-6 text-xs">Số cây</th>
                                <th scope="col" class="py-3 px-6 text-xs">Lượt thăm miễn phí</th>
                                <th scope="col" class="py-3 px-6 text-xs">Nông sản nhận</th>
                                <th scope="col" class="py-3 px-6 text-xs">Số lần giao hàng</th>
                                <th scope="col" class="py-3 px-6 text-xs">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody v-if="customer">
                            <tr v-for="(product_owner, index) in customer?.product_service_owners" :key="index"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ index + 1 }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ product_owner?.product?.name }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ formatPrice(product_owner?.product?.price) }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Từ {{ moment(String(product_owner.time_approve)).format('MM/DD/YYYY') }} đến
                                    {{ moment(String(product_owner.time_end)).format('MM/DD/YYYY') }}
                                </th>
                                <th class="py-3 px-6 text-xs">
                                    <PillTag :color="product_owner.state == 'active' ? 'success' : 'danger'"
                                        :label="product_owner.state" small>
                                    </PillTag>
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ product_owner?.product?.number_tree }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                   {{ product_owner?.product?.free_visit }}
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                   <Link href="/"></Link>
                                </th>
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    1/{{ product_owner?.product?.number_deliveries }}
                                </th>
                                <td class="py-4 px-6 text-right">
                                    <button @click="edit(product_owner)" type="button" data-toggle="modal"
                                        data-target="#exampleModal"
                                        class="inline-block px-6 py-2.5 bg-gray-200 text-gray-700 font-black text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out mx-2">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                            <pagination :links="customer.product_service_owners.links" />
                        </tbody>
                    </table>
                </div>
            </div>

        </SectionMain>
    </LayoutProfileDetail>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
