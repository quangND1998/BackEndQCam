<template>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form @submit.prevent="orderCancel">
                        <div class="form-group" :class="form.errors.reason ? 'has-error' : ''">

                            <label for="message" class="  block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lý
                                do</label>
                            <textarea v-model="form.reason" id="message" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Tại sao khách muốn hủy đơn hàng?"></textarea>

                        </div>
                        <div class="text-red-500" v-if="form.errors.reason">{{ form.errors.reason }}</div>

                        <div class="modal-footer">
                            <button type="button"
                                class="inline-block px-3 py-2 border text-gray-700 font-black text-sm leading-tight uppercase rounded shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out"
                                data-dismiss="modal">Quay lại</button>
                            <button type="submit" @click.prevent="orderCancel()"
                                class="inline-block px-3 py-2 bg-red-700 text-white font-black text-sm leading-tight uppercase rounded shadow-md hover:bg-red-500 hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out">Xác
                                nhận hủy đơn</button>
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
    complaint:Object
});
const form = useForm({
    id: null,
    role_id: null,
    status: props.complaint.status,
    reason: null,
    solution: null,
    detail: null
})
onMounted(() => {
    emitter.on('OpenModel', (order) => {
        // console.log(order)

        form.id = order.id,
            form.order_number = order.order_number
    })
});
const orderCancel = () => {
    form.post(route("admin.orders.package.orderCancel", form.id), {
        preserveState: true,
        onError: errors => {
            if (Object.keys(errors).length > 0) {

            }
        },
        onSuccess: page => {
            $("#exampleModal").modal("hide");
            // emitter.off('OpenModel', listener)
            form.reset();
        }
    });
}
const listener = () => {
}
onUnmounted(() => {
    emitter.off('OpenModel', listener)
})
</script>

<style></style>
