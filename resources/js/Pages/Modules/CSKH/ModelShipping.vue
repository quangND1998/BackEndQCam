<template>
    <div class="modal fade" id="ModelShipping" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="flex">
                        <div class="ml-2">
                            <h3 class="text-black font-base my-1 text-base">Đơn hàng #{{ form.order?.order_number }}</h3>
                            <p class="text-gray-500 text-sm">Đơn hàng sẽ được chuyển đến tab "Đơn hoàn"</p>
                        </div>
                    </div>


                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { emitter } from '@/composable/useEmitter';
import { computed, ref, inject, onMounted, onUnmounted } from "vue";

import { useForm } from "@inertiajs/vue3";
const form = useForm({
    order: null
})
onMounted(() => {
        emitter.on('ModelShipping', (order) => {
            console.log(order)
            form.order = order;
            // $("#ModelShipping").modal("show");
        })
    });

const orderRefund=()=> {
            // console.log(this.form)
            form.post(route("admin.orders.orderRefund", form.id), {
                preserveState: true,
                onError: errors => {
                    if (Object.keys(errors).length > 0) {

                    }
                },
                onSuccess: page => {
                    $("#exampleModalRefund").modal("hide");
                    form.reset();
                }
            });
        }
const listener =()=> {
}
onUnmounted(() => {
    emitter.off('ModelShipping', listener)
})

</script>

<style></style>
