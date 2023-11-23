<template>
    <div class="modal fade" id="exampleModalShipping" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="flex">
                        <img src="img/icon_delete.png" alt="">
                        <div class="ml-2">
                            <h3 class="text-black font-bold my-1 text-base">Chọn Shipper cho đơn hàng ?</h3>
                            <p class="text-gray-500 text-sm">Đơn hàng sẽ được chuyển đến tab "Đang vận chuyển"</p>
                        </div>
                    </div>


                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="orderShipping">

                        <div class="form-group" :class="form.errors.reason ? 'has-error' : ''">

                            <label for="message" class="  block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn
                                shipper</label>
                            <!-- <textarea v-model="form.reason" id="message" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Tại sao khách muốn hủy đơn hàng?"></textarea> -->

                            <select id="countries" v-model="form.shipper"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option :value="null" selected>Chọn shipper</option>
                                <option v-for="(shipper, index) in shippers" :key="index" :value="shipper.id">{{
                                    shipper.name }}
                                </option>

                            </select>
                            <div class="text-red-500" v-if="form.errors.shipper">{{ form.errors.shipper }}</div>
                        </div>


                        <div class="modal-footer">
                            <button type="button"
                                class="inline-block px-3 py-2 border text-gray-700 font-black text-sm leading-tight uppercase rounded shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out"
                                data-dismiss="modal">Quay lại</button>
                            <button type="submit" @click.prevent="orderShipping()"
                                class="inline-block px-3 py-2 bg-red-700 text-white font-black text-sm leading-tight uppercase rounded shadow-md hover:bg-red-500 hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out">Xác
                                nhận</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { emitter } from '@/composable/useEmitter';
import { computed, ref, inject, onMounted, onUnmounted } from "vue";

import { useForm } from "@inertiajs/vue3";
const props = defineProps({
    shippers: Array
});
const form = useForm({
    id: null,
    order_number: null,
    shipper: null,
    status: 'shipping'
})
onMounted(() => {
    emitter.on('openModalShipping', (order) => {
        // console.log(order)

        form.id = order.id;
        form.order_number = order.order_number;
        form.shipper = order.shipper?.id
    })
});
const orderShipping = () => {
    form.post(route("admin.orders.orderChangeShipping", form.id), {
        preserveState: true,
        onError: errors => {
            if (Object.keys(errors).length > 0) {

            }
        },
        onSuccess: page => {
            $("#exampleModalShipping").modal("hide");
            form.reset();
        }
    });
}
const listener = () => {
}
onUnmounted(() => {
    emitter.off('openModalShipping', listener)
})
</script>

<style></style>