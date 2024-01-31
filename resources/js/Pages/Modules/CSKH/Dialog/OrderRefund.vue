<template>
    <div class="modal fade rounded-sm" id="OrderRefund" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl rounded-2xl mx-auto mt-10 shadow-lg max-h-modal w-8/12   md:w-9/12 lg:w-8/12 xl:w-5/12 z-50 "
            role="document">
            <div class="modal-content">

                <div class="modal-header" v-if="ids.length > 1">
                    <div class="w-full flex justify-between">
                        <h3 class="text-black font-semibold my-1 text-[16px]">Xác nhận hoàn đơn</h3>

                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-header" v-if="order_transport">
                    <div class="w-full flex justify-between">
                        <h3 class="text-black font-semibold my-1 text-[16px]">Đơn hàng #{{
                            order_transport.order?.order_number }}</h3>
                        <h3 class="text-black font-semibold my-1 text-[16px]">Ngày nhận đơn {{
                            formatDateOnly(order_transport.order?.delivery_appointment) }}</h3>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body  mx-1 mb-6" v-if="order_transport">
                    <div class="flex items-center ">
                        <div class="mx-1 flex flex-col items-center">
                            <img src="/assets/icon/loading-svgrepo-com.png" alt="" class="w-12 h-12 p-2">
                            <p class=" text-xl"
                                :class="isActive(['pending', 'packing', 'delivering', 'delivered']) ? 'text-[#FF0000]' : ''">
                                Chuẩn bị</p>
                        </div>
                        <div class=" arrow mx-1  "
                            :class="isActive(['packing', 'delivering', 'delivered']) ? 'active' : ''">
                        </div>
                        <div class=" mx-1 flex flex-col items-center">
                            <img src="/assets/icon/box.png" alt="" class="w-12 h-12 p-2">
                            <p class="text-base"
                                :class="isActive(['packed', 'delivering', 'delivered']) ? 'text-[#FF0000]' : ''">
                                Đóng gói</p>
                        </div>
                        <div class="arrow mx-1" :class="isActive(['delivering', 'delivered']) ? 'active' : ''">
                        </div>
                        <div class="mx-1 flex flex-col items-center">
                            <img src="/assets/icon/ship.png" alt="" class="w-12 h-12 p-2">
                            <p class=" text-base" :class="isActive(['delivering', 'delivered']) ? 'text-[#FF0000]' : ''">
                                Vận chuyển</p>
                        </div>
                        <div class="arrow mx-1" :class="isActive(['delivered']) ? 'active' : ''">
                        </div>
                        <div class="mx-1 flex flex-col items-center">
                            <img src="/assets/icon/success.png" alt="" class="w-12 h-12 p-2">
                            <p class="text-base" :class="isActive(['delivered']) ? 'text-[#FF0000]' : ''">
                                Thành công </p>
                        </div>
                        <div class="arrow mx-1" :class="isActive(['refund']) ? 'active' : ''">
                        </div>
                        <div class="mx-1 flex flex-col items-center">
                            <img src="/assets/icon/backward.png" alt="" class="w-12 h-12 p-2">
                            <p class="text-base" :class="isActive(['refund']) ? 'text-[#FF0000]' : ''">
                                Hoàn
                            </p>
                        </div>
                    </div>
                    <div class="w-full flex items-center mx-auto mt-4">

                        <p class="text-[#000000] text-base mr-4">Trạng thái hiện tại
                        </p>
                        <OrderTransportState v-if="order_transport" :order_transport="order_transport" />
                    </div>


                </div>
                <div class="modal-body  mx-1 mb-6">
                    <div class="flex items-center " v-if="order_transport">
                        <div class="relative overflow-x-auto">
                            <p class="text-[#000000] text-base font-semibold mr-4 mb-3">Chọn sản phẩm hoàn
                            </p>
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Sản phẩm
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            ĐVT
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            SL Hoàn
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="(item, index) in order_transport?.order?.order_items" :key="index"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ item.product.name }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ item.product.unit }}
                                        </td>
                                        <td class="px-6 py-4" v-if="form.products.length > 0">

                                            <MazInputNumber placeholder="Enter number"
                                                v-model="form.products[index].quantity" :min="0" :max="item.quantity"
                                                size="md" color="secondary" style="width: 200px;" />
                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>


                    </div>
                    <div class="w-full flex items-center mx-auto mt-4">

                        <p class="text-[#000000] text-base mr-4">Có lưu kho hay không?
                        </p>
                    </div>
                    <div class="">

                        <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <input v-model="form.check"
                                class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:border-primary dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                                type="radio" name="inlineRadioOptions" id="inlineRadio1" :value="true" />
                            <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="inlineRadio1">Có
                            </label>
                        </div>


                        <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <input v-model="form.check"
                                class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:border-primary dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                                type="radio" name="inlineRadioOptions" id="inlineRadio2" :value="false" />
                            <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                for="inlineRadio2">Không</label>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit" @click.prevent="orderRefund()"
                        class="inline-block rounded-2xl px-2 py-2 bg-[#4F8D06] text-white font-black text-sm leading-tight uppercase  shadow-md hover:bg-[#4F8D06] hover:shadow-lg focus:bg-[#4F8D06] focus:shadow-lg focus:outline-none focus:ring-0 active:bg-[#4F8D06] active:shadow-lg transition duration-150 ease-in-out">Xác
                        nhận hoàn đơn</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { emitter } from '@/composable/useEmitter';
import { computed, ref, inject, onMounted, onUnmounted } from "vue";
import { storeToRefs } from 'pinia'
import { useForm, router } from "@inertiajs/vue3";
import { useOrderStore } from '@/stores/order.js'
import OrderStatus from '@/Pages/Modules/CSKH/OrderStatus.vue'
import MazInputNumber from 'maz-ui/components/MazInputNumber'
import OrderTransportState from '@/Pages/Modules/CSKH/Status/OrderTransportState.vue'
const swal = inject("$swal");
const props = defineProps({
    ids: Array
})
const { order, order_transport
} = storeToRefs(useOrderStore())
const { closeModal } = useOrderStore()
const form = useForm({
    check: false,
    products: []
})
const selectedProducts = ref([]);

onMounted(() => {

    emitter.on('OrderRefund', (data) => {
        form.products = []
        data.order.order_items.forEach(item => {
            form.products.push({
                id: item.product.id,
                name: item.product.name,
                unit: item.product.unit,
                quantity: item.quantity,

            })
        })
    })

});
const isActive = (status) => {
    if (order_transport.value && status.includes(order_transport.value.state)) {
        return true
    }
    return false
}


const orderRefund = () => {
    let query = {
        ids: props.ids,
        check: form.check,
        products: form.products
    };

    router.post(route("admin.cskh.order.refund"), query, {
        onError: () => { },
        onSuccess: () => {
            form.reset();
            $("#OrderRefund").modal("hide");
        },
    });

}
const listener = () => {
}
onUnmounted(() => {

    emitter.off('OrderRefund', listener)
})
</script>

<style>
.arrow {
    position: relative;
    height: 2px;
    width: 60px;

}

.arrow:before {
    content: "";
    position: absolute;
    top: -2px;
    left: -3px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: #AEAEAE;
}

.arrow:after {
    content: " ";
    width: 50px;
    height: 2px;
    background-color: #AEAEAE;
    position: absolute;
}

.active {
    background-color: #FF6100;

}

.active:after {
    background-color: #FF6100;
}

.active:before {
    background-color: #FF6100;
}
</style>