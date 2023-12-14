<script setup>
import { computed, onMounted, ref, inject, reactive, toRef, toRefs } from "vue";
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
    quantities: null
});
const emit = defineEmits(['saveGift',])
const props = defineProps({
    products: Array,
    type: String,
    cart: Object

});

const cart = toRef(props.cart);


onMounted(() => {
    form.quantities = cart
}
)
const quantity = ref(1)
const product = computed(() => {
    return props.products.find((e) => e.id == product_selectd.value)
});

const cart_selected = ref([])
const product_selectd = toRef(props.products.length > 0 ? props.products[0].id : null)

const selectAllCart = computed({
    get() {
        return cart.value
            ? cart_selected.value.length == cart
            : false;
    },
    set(value) {
        var selected = [];

        if (value) {
            for (const [key, value] of Object.entries(cart.value)) {
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
    if (product.value == null) {
        swal.fire({
            title: "Lỗi?",
            text: "Chưa có thông tin khách hàng!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
        }).then((result) => {
            if (result.isConfirmed) {
                return
            }
        });
    }
    else {
        axios.post('/admin/orders/addToCart', { product: product.value, quantity: quantity.value }).then(res => {
            console.log(res.data.cart)
            cart.value = res.data.cart

        }).catch(err => {

        })
    }

}

const updateCart = (product, value) => {

    form.quantities[product.id].quantity += parseInt(value)
    let query = {
        quantity_cart: form.quantities[product.id].quantity,
        product_id: product.id
    };
    if (
        form.quantities[product.id].quantity == 0 ||
        form.quantities[product.id].quantity < 0 ||
        query.quantity_cart == 0
    ) {
        swal.fire({
            title: 'Error!',
            text: 'Số lượng không được nhỏ hơn hoặc bằng 0',
            icon: 'error',
            confirmButtonText: 'Cool'
        })
    } else {
        axios
            .post("/admin/cart/updateCart", query)
            .then(response => {
                console.log(response)
                cart.value[product.id] = response.data.item;
            })
            .catch(error => { });
    }

}
const updateCartInput = (product) => {

    let query = {
        quantity_cart: form.quantities[product.id].quantity,
        product_id: product.id
    };

    if (
        form.quantities[product.id].quantity == 0 ||
        form.quantities[product.id].quantity < 0
    ) {


        swal.fire({
            title: 'Error!',
            text: 'Số lượng không được nhỏ hơn hoặc bằng 0',
            icon: 'error',
            confirmButtonText: 'Cool'
        })
    } else {
        axios
            .post("/admin/cart/updateCart", query)
            .then(response => {

                cart.value[product.id] = response.data.item;
            })
            .catch(error => { });
    }
}
const deleteItem = (product) => {
    let query = {
        product_id: product.id
    };
    swal.fire({
        title: "Thông báo?",
        text: "Bạn có muốn xóa sản phẩm khỏi đơn hàng!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
    }).then(result => {
        if (result.isConfirmed) {

            axios
                .post("/admin/cart/removeItem", query)
                .then(response => {

                    cart.value = response.data.cart;
                })
                .catch(error => { });
        } else {
            return
        }
    });
}
const DeleteCart = () => {
    swal.fire({
        title: "Thông báo?",
        text: "Bạn  muốn xóa giỏ hàng!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
    }).then(result => {
        if (result.isConfirmed) {

            axios
                .post("/admin/cart/removeCart")
                .then(response => {
                    cart.value = response.data.cart;
                })
                .catch(error => { });
        } else {
            return
        }
    });
}
const DeleteCheckbox = () => {
    let query = {
        ids: cart_selected.value
    };
    swal.fire({
        title: "Thông báo?",
        text: "Bạn đơn các sản phẩm khỏi đơn hàng!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
    }).then(result => {
        if (result.isConfirmed) {
            axios
                .post("/admin/cart/deleteCarts", query)
                .then(response => {

                    cart.value = response.data.cart;
                    cart_selected.value = [];
                })
                .catch(error => { });

        } else {
            return
        }
    });
}
const saveGift =()=> {

        if (cart.value.length == 0) {
            swal.fire({
                title: "Lỗi?",
                text: "Chưa sản phẩm!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
            }).then((result) => {
                if (result.isConfirmed) {
                    return
                }
            });
            return
        }
        else {
            emit('saveGift')
        }

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

                                <BaseButton color="info" @click="addToCart()"
                                    class="bg-btn_green text-white p-2 hover:bg-[#008000]" :icon="mdiPlus" small
                                    label="Thêm sản phẩm" />

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Giỏ hàng -->
            <div class="bg-white rounded-lg p-3">
                <div class="flex justify-between">
                    <div>
                        <!-- <font-awesome-icon :icon="['fas', 'cart-shopping']" class="mt-1" /> -->
                        <span class="text-xl font-semibold ml-2">Giỏ hàng</span>
                    </div>
                    <button v-if="cart" class="flex text-red-600 mx-3 text-sm" @click="DeleteCart()">
                        <BaseIcon :path="mdiTrashCanOutline" class="text-red-600 hover:text-red-700 mx-1 "></BaseIcon>
                        Hủy đơn giao hàng
                    </button>
                </div>
                <hr />
                <div class="flex my-3">
                    <input v-if="cart_selected" id="default-checkbox" type="checkbox" v-model="selectAllCart"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                    <div>
                        <button v-if="cart_selected.length > 0" @click="DeleteCheckbox()"
                            class="flex text-red-600 mx-3 text-sm">
                            <BaseIcon :path="mdiTrashCanOutline" class="text-red-600 hover:text-red-700"></BaseIcon>Xóa
                            <span>({{ cart_selected.length }})</span> sản phẩm đã chọn
                        </button>
                    </div>
                </div>

                <hr />

                <div v-for="(item, index) in cart" :key="index" class="flex my-3">

                    <div class="flex">
                        <input id="default-checkbox" type="checkbox" v-model="cart_selected" :value="item.id"
                            class="icon_checkbox w-4 h-4 mt-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />

                    </div>
                    <img v-if="item.images && item.images.length > 0 && item.images[0].image" :src="item.images[0].image"
                        class="w-16 h-16 object-cover ml-3" alt="">
                    <div class="flex  justify-between items-center w-full">
                        <div class=" ml-4">
                            <h3>{{ item.name }}</h3>
                            <p class="text-red-700 text-base">{{ formatPrice(item.price) }} </p>
                            <div class="buttons_added" v-if="form.quantities">
                                <div class="buttons_added">

                                    <input class="minus is-form" type="button" value="-" @click="updateCart(item, -1)" />
                                    <input aria-label="quantity" class="input-qty_create pb-0 border" @blur="updateCartInput(item)"
                                        v-on:keyup.enter="updateCartInput(item)" v-model="form.quantities[index].quantity"
                                        max="100" min="0" name type="number"  />

                                    <input class="plus is-form" type="button" value="+" @click="updateCart(item, 1)" />

                                </div>

                            </div>
                        </div>
                        <BaseIcon @click="deleteItem(item)" :path="mdiTrashCanOutline"
                            class="text-red-600 hover:text-red-700"></BaseIcon>

                    </div>

                </div>

                <hr />

            </div>
        </div>
        <div class="mx-5">

       <div class="my-3">
           <BaseButton color="info" @click="saveGift()"
               class="bg-orange-500 hover:bg-orange-600 text-white p-2 w-full text-center justify-center rounded-lg"
               :icon="mdiContentSaveMove" small label="Lưu Giao quà" />
       </div>

   </div>
    </div>
</template>

