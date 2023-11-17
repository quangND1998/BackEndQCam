<template>
    <div class="relative shadow-md sm:rounded-lg mb-5 mt-4">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Gói sản phẩm
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Tổng
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b ">
                            <td class="px-6 py-4 ">
                                <select id="countries" @change="changeProduct($event)" v-model="product_selected"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-60 p-2.5 ">
                                    <option v-for="(product, index) in product_services" :key="index" :value="product.id">{{
                                        product.name }}</option>

                                </select>
                                <label for="first_name" class="block mb-1 mt-4 text-sm  text-gray-900 dark:text-white">
                                Áp dụng từ ngày</label>
                                <div class="flex items-center w-60 ">
                                    <VueDatePicker v-model="time_approve" time-picker-inline />
                                </div>
                            </td>

                            <td class="px-6  py-4">
                                <p type="number"
                                    class="border-[0px] text-[#686868] font-bold w-28">{{ formatPrice(product?.price) }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
    <div class="min-[320x]:w-full grid grid-cols-3 gap-4">

        <div class=" col-span-2 mt-2 w-full">

            
            <div class="bg-white rounded-lg p-3">
                <div class="flex justify-between">
                    <div>
                        <!-- <font-awesome-icon :icon="['fas', 'cart-shopping']" class="mt-1" /> -->
                        <span class="text-xl font-semibold ml-2">Quyền lợi nhận nuôi {{ product?.name }}</span>
                    </div>

                </div>
                <hr />
                <div class="my-3">
                    <div class="block ml-3 w-4/5">
                        <h3 class="text-base font-semibold">1. Thăm vườn không giới hạn</h3>
                        <p class="text-xs font-normal">Nhận {{product?.free_visit}} lần tham quan miễn phí</p>
                    </div>
                    <div class="block ml-3 w-4/5">
                        <h3 class="text-base font-semibold">2. Thu hoạch {{ product?.amount_products_received }} kg cam</h3>
                        <p class="text-xs font-normal">{{product?.number_deliveries}}lần ship hàng về tận nhà</p>
                    </div>
                    <div class="block ml-3 w-4/5">
                            <h3 class="text-base font-semibold">3. Tặng thẻ Membership</h3>
                            <p class="text-xs font-normal">Hưởng đặc quyền riêng từ trang trại</p>
                    </div>
                    <div class="block ml-3 w-4/5">
                            <h3 class="text-base font-semibold">4. Nhận nông sản sạch {{product?.number_receive_product}} lần/năm</h3>
                            <p class="text-xs font-normal">Các sản phẩm nông sản như thanh long
                                sầu riêng là quà tặng đến cho bạn</p>
                    </div>
                    <div class="block ml-3 w-4/5">
                            <h3 class="text-base font-semibold">5. Quà tặng thêm</h3>
                            <p class="text-xs font-normal">Nhiều phần quà nông sản hấp dẫn trị tổng
                                trị giá xx triệu</p>
                        </div>
                </div>
                <hr />
            </div>
        </div>

        <div class="mx-5">
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868] font-bold">Tổng</p>
                <p class="text-sm text-[#686868] font-bold">{{ formatPrice(product?.price) }} vnđ</p>
            </div>
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868] font-bold">VAT({{vat}}%)</p>
                <p class="text-sm text-[#686868] font-bold">{{ formatPrice((vat*product?.price)/100) }} vnd</p>
            </div>
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868] font-bold">Vận chuyển</p>
                <p class="text-sm text-[#686868] font-bold" v-if="shipping_fee == 0">Miễn phí</p>
                <p class="text-sm text-[#686868] font-bold" v-else>{{ formatPrice(shipping_fee) }}</p>
            </div>
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868] font-bold">Ưu đãi</p>
                <p class="text-sm text-[#686868] font-bold">{{ formatPrice(discount_deal) }}đ</p>
            </div>
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868] font-bold">Tổng cộng</p>
                <p class="text-sm text-[#686868]">{{formatPrice( product?.price )}} vnd</p>
            </div>
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868]">Đã thanh toán</p>
                <p class="text-sm text-[#686868] font-bold">1.000.000đ</p>
            </div>
            <div class="flex justify-between my-2">
                <p class="text-sm text-[#686868] font-bold">Còn lại</p>
                <p class="text-sm text-[#686868] font-bold">1.000.000đ</p>
            </div>
            <div class="my-3">
                <BaseButton color="info"  @click="save()"
                    class="bg-orange-500 hover:bg-orange-600 text-white p-2 w-full text-center justify-center rounded-lg"
                    :icon="mdiContentSaveMove" small label="Lưu hợp đồng" />
            </div>
        </div>
    </div>
</template>

<script>
import CardBoxModal from "@/Components/CardBoxModal.vue";

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

// import Multiselect from '@vueform/multiselect'
import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import MazInputNumber from 'maz-ui/components/MazInputNumber'
import Swal from 'sweetalert2'
export default {
    components: {
        BaseButton,
        InputError,
        InputLabel,
        TextInput,
        Dropdown,
        SearchInput,
        MazInputPrice,
        MazInputNumber,
        BaseIcon
    },
    props: {
        product_services: Array,
        trees: Array,
        user: Object,
        cart: Object,
        total_price: Number,
        vat: Number,
        discount_deal: Number,
        shipping_fee: Number,
        payment_method: String,
        type: String,
        sub_total: Number,
        time_reservations : Number,
        price_percent : Number,
    },
    data() {
        return {
            form: this.$inertia.form({

            }),
            quantity: 1,
            cart_selected: [],
            date: new Date(),
            product_selected: this.product_services.length > 0 ? this.product_services[0].id : null,
            mdiPlus: mdiPlus,
            mdiTrashCanOutline: mdiTrashCanOutline,
            mdiContentSaveMove: mdiContentSaveMove,
            carts: this.cart,
            subTotal: this.sub_total,
            totalPrice: this.product_selected?.price,
            time_approve : new Date(),
        }
    },
    mounted: function () {
        $("input.input-qty").each(function () {
            var $this = $(this),
                qty = $this.parent().find(".is-form"),
                min = Number($this.attr("min")),
                max = Number($this.attr("max"));
            if (min == 0) {
                var d = 0;
            } else d = min;
            $(qty).on("click", function () {
                if ($(this).hasClass("minus")) {
                    if (d > min) d += -1;
                } else if ($(this).hasClass("plus")) {
                    var x = Number($this.val()) + 1;
                    if (x <= max) d += 1;
                }
                $this.attr("value", d).val(d);
            });
        });
    },
    computed: {
        selectAllCart: {
            get: function () {
                return this.carts ? this.cart_selected.length == this.carts : false;
            },
            set: function (value) {
                var selected = [];

                if (value) {
                    for (const [key, value] of Object.entries(this.carts)) {
                        selected.push(value.id);
                    }
                }

                this.cart_selected = selected;
            }
        },
        product() {
            return this.product_services.find((e) => e.id == this.product_selected)
        }
    },
    methods: {

        save(){

            if (this.carts.length ==0) {
                Swal.fire({
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
            else{
                this.$emit('confirm')
            }

        },
        changeProduct(event) {

            this.product_selected = event.target.value
        },
        addToCart() {
            if (this.product == null || this.user == null) {
                Swal.fire({
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
                axios.post('/admin/orders/addToCart', { product: this.product, quantity: this.quantity }).then(res => {
                    console.log(res.data.cart)
                    this.carts = res.data.cart
                    this.totalPrice = res.data.total_price
                    this.subTotal = res.data.sub_total
                }).catch(err => {

                })
            }

        },
        updateCart(product, value) {
            this.$refs[`quantity${product.id}`][0].value =
                parseInt(this.$refs[`quantity${product.id}`][0].value) +
                parseInt(value);
            let query = {
                quantity_cart: this.$refs[`quantity${product.id}`][0].value,
                product_id: product.id
            };


            // this.$store.dispatch("stores/updateCart", query);
            if (
                this.$refs[`quantity${product.id}`][0].value == 0 ||
                this.$refs[`quantity${product.id}`][0].value < 0 ||
                query.quantity_cart == 0
            ) {
                Swal.fire({
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
                        this.totalPrice = response.data.total_price;
                        this.subTotal = response.data.subTotal;
                        this.carts[product.id] = response.data.item;
                    })
                    .catch(error => { });
            }
        },
        updateCartInput(product) {

            let query = {
                quantity_cart: this.$refs[`quantity${product.id}`][0].value,
                product_id: product.id
            };

            if (
                parseInt(this.$refs[`quantity${product.id}`][0].value) == 0 ||
                this.$refs[`quantity${product.id}`][0].value < 0
            ) {


                Swal.fire({
                    title: 'Error!',
                    text: 'Số lượng không được nhỏ hơn hoặc bằng 0',
                    icon: 'error',
                    confirmButtonText: 'Cool'
                })
            } else {
                axios
                    .post("/admin/cart/updateCart", query)
                    .then(response => {
                        this.totalPrice = response.data.total_price;
                        this.subTotal = response.data.subTotal;
                        this.carts[product.id] = response.data.item;
                    })
                    .catch(error => { });
            }
        },
        deleteItem(product) {
            let query = {
                product_id: product.id
            };
            Swal.fire({
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
                        this.totalPrice = response.data.total_price;
                        this.subTotal = response.data.subTotal;
                        this.carts= response.data.cart;
                    })
                    .catch(error => { });
                } else {
                    return
                }
            });
        },
        DeleteCart() {
            Swal.fire({
                title: "Thông báo?",
                text: "Bạn đơn hàng!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
            }).then(result => {
                if (result.isConfirmed) {

                    axios
                    .post("/admin/cart/removeCart", query)
                    .then(response => {
                        this.totalPrice = response.data.total_price;
                        this.subTotal = response.data.subTotal;
                        this.carts= response.data.cart;
                    })
                    .catch(error => { });
                } else {
                    return
                }
            });
        },
        DeleteCheckbox() {
            let query = {
                ids: this.cart_selected
            };
            Swal.fire({
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
                        this.totalPrice = response.data.total_price;
                        this.subTotal = response.data.subTotal;
                        this.carts= response.data.cart;
                        this.cart_selected = [];
                    })
                    .catch(error => { });

                } else {
                    return
                }
            });
            },
    }
}
</script>

<style>
.list_icon_crud {
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;

    right: -40px;
    top: 20px;
    z-index: 111;
    display: inline-grid;
}

.btn_crud {
    font-size: 20px;
}

.icon_giam {
    width: 32px;
    height: 32px;
}

/*  */
.buttons_added {
    opacity: 1;
    display: inline-block;
    display: -ms-inline-flexbox;
    display: inline-flex;
    white-space: nowrap;
    vertical-align: top;
}

.is-form {
    overflow: hidden;
    position: relative;
    background-color: #f9f9f9;
    height: 2.2rem;
    width: 1.9rem;
    padding: 0;
    text-shadow: 1px 1px 1px #fff;
    border: 1px solid #ddd;
}

.is-form:focus,
.input-text:focus {
    outline: none;
}

.is-form.minus {
    border-radius: 4px 0 0 4px;
}

.is-form.plus {
    border-radius: 0 4px 4px 0;
}

.input-qty {
    background-color: #fff;
    height: 2.2rem;
    text-align: center;
    font-size: 1rem;
    display: inline-block;
    vertical-align: top;
    margin: 0;
    border-top: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    border-left: 0;
    border-right: 0;
    padding: 0;
    width: 35px;
}

.input-qty::-webkit-outer-spin-button,
.input-qty::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
