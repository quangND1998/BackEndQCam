<script setup>
import { computed, ref, inject, reactive, toRef, toRefs } from "vue";
import { useForm } from "@inertiajs/vue3";
import {
    mdiEye,
    mdiAccountLockOpen,
    mdiPlus,
    mdiContentSaveMove,
    mdiCreditCardSettingsOutline,
    mdiContentCopy,
    mdiImport,
    mdiTrashCanOutline

} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import MazInputNumber from 'maz-ui/components/MazInputNumber'
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    state: null,
    number: null,
    price: null,
    count: null
});
const product = computed(() => {
    return props.products.find((e) => e.id == product_selectd.value)
});


const product_selectd = toRef(props.products.length > 0 ? props.products[0].id : null)

const quantity = ref(1)
const props = defineProps({
    products: Array,
    user: Object,
    type: String,

});

const selectAllproduct = computed({
    get() {
        return props.cart
            ? cart_selected.value.length == props.cart
            : false;
    },
    set(value) {
        var selected = [];

        if (value) {
            for (const [key, value] of Object.entries(props.cart)) {
                selected.push(value.id);
            }
        }
        cart_selected.value = selected;

    }
});

const changeProduct = (event) => {

    product_selectd.value = event.target.value
}
const addToCart = () => {


}


</script>
<template>
    <div class="min-[320x]:w-full grid grid-cols-3 gap-4">

        <div class=" col-span-2 mt-2 w-full">

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-5 mt-4">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                #
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Số lượng
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Giá
                            </th>

                            <th scope="col" class="px-6 py-3">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b ">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                <span></span>
                            </th>
                            <td class="px-6 py-4 ">
                                <select id="countries" @change="changeProduct($event)" v-model="product_selectd"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-44 p-2.5 ">
                                    <option v-for="(product, index) in products" :key="index" :value="product.id">{{
                                        product.name }}</option>

                                </select>
                            </td>
                            <td class="px-6 py-4">

                                <MazInputNumber v-model="quantity" label="Enter number" :min="1" :max="10000" :step="1"
                                    size="md" color="secondary" />
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" readonly :value="product?.price"
                                    class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 hover:border-gray-500 w-28">
                            </td>
                            <!-- <td class="px-6 py-4">
                                <input type="number"
                                    class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 hover:border-gray-500 w-32">
                            </td> -->
                            <td class="px-6 py-4">

                                <!-- <BaseButton color="info" @click="addToCart()"
                                    class="bg-btn_green text-white p-2 hover:bg-[#008000]" :icon="mdiPlus" small
                                    label="Thêm sản phẩm" /> -->

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </div>

    </div>
</template>

<style scoped ></style>