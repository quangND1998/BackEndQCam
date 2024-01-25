<template>
    <div class="modal fade" id="OrderCancel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl rounded-2xl mx-auto mt-10 shadow-lg max-h-modal w-8/12   md:w-9/12 lg:w-8/12 xl:w-5/12 z-50 "
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="w-full flex justify-between">
                        <h3 class="text-black font-semibold my-1 text-[16px]">Đơn hàng #{{ order?.order_number }}</h3>
                        <h3 class="text-black font-semibold my-1 text-[16px]">Ngày nhận đơn {{
                            formatDateOnly(order?.delivery_appointment) }}</h3>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body  mx-1 mb-6">
                    <div class="flex items-center ">
                        <div class="mx-1 flex flex-col items-center">
                            <img src="/assets/icon/loading-svgrepo-com.png" alt="" class="w-12 h-12 p-2">
                            <p class=" text-xl"
                                :class="isActive(['pending', 'packed', 'delivering', 'delivered']) ? 'text-[#FF0000]' : ''">
                                Chuẩn bị</p>
                        </div>
                        <div class=" arrow mx-1  " :class="isActive(['packed', 'delivering', 'delivered']) ? 'active' : ''">
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
                        <OrderStatus v-if="order" :order="order" />
                    </div>
                    <div class="w-full flex flex-col  mx-auto mt-4">

                        <h1 class="text-[#000000] text-[16px] font-semibold mr-2">Đơn có vẫn đề?
                        </h1>
                        <textarea v-model="form.reason" name="" id="" cols="30" rows="5"
                            :class="form.errors.reason ? ' border-red-500' : null"
                            class="rounded-md border border-[0.5]"></textarea>
                        <div class="text-red-500" v-if="form.errors.reason">{{ form.errors.reason }}
                        </div>
                    </div>
                    <div class="w-full flex justify-between mt-3">
                        <div class="text-black font-semibold my-1 text-[16px]">Chuyển ngày giao đến </div>
                        <div class="text-black font-semibold my-1 text-[16px]">

                            <VueDatePicker v-model="form.delivery_appointment"
                                :class="form.errors.delivery_appointment ? 'border-red-500' : ''" :min-date="minDate"
                                :clearable="true" :enable-time-picker="false" format="dd/MM/yyyy">

                            </VueDatePicker>
                            <div class="text-red-500" v-if="form.errors.delivery_appointment">{{
                                form.errors.delivery_appointment }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit" @click.prevent="orderCancel()"
                            class="inline-block px-3 py-4 bg-red-600 text-white font-black text-sm leading-tight uppercase rounded shadow-md hover:bg-red-500 hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out">Xác
                            nhận hủy đơn</button>
                    </div>
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
import moment from 'moment';

const { order
} = storeToRefs(useOrderStore())

const isActive = (status) => {
    if (order.value && status.includes(order.value.status_transport)) {
        return true
    }
    return false
}
const minDate = computed(() => moment(new Date(order.value?.delivery_appointment), "DD-MM-YYYY").add(2, 'days'));

const form = useForm({
    reason: null,
    delivery_appointment: null
})


const orderCancel = () => {

    form.post(route("admin.cskh.order.decline", order.value.id), {
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