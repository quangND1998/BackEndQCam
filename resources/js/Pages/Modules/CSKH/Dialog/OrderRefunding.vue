<template>
    <div class="modal fade" id="OrderRefunding" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl rounded-2xl mx-auto mt-10 shadow-lg max-h-modal w-8/12   md:w-9/12 lg:w-8/12 xl:w-5/12 z-50 "
            role="document">
            <div class="modal-content">

                <div class="modal-header" v-if="ids.length > 1">
                    <div class="w-full flex justify-between">
                        <h3 class="text-black font-semibold my-1 text-[16px]">Xác nhận hoàn đơn</h3>

                    </div>
                    <button type="button" class="close" @click="closeModal" data-dismiss="modal" aria-label="Close">
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
                                :class="isActive(['pending', 'packing', 'shipping', 'delivered']) ? 'text-[#FF0000]' : ''">
                                Chuẩn bị</p>
                        </div>
                        <div class=" arrow mx-1  " :class="isActive(['packing', 'shipping', 'delivered']) ? 'active' : ''">
                        </div>
                        <div class=" mx-1 flex flex-col items-center">
                            <img src="/assets/icon/box.png" alt="" class="w-12 h-12 p-2">
                            <p class="text-base"
                                :class="isActive(['packing', 'shipping', 'delivered']) ? 'text-[#FF0000]' : ''">
                                Đóng gói</p>
                        </div>
                        <div class="arrow mx-1" :class="isActive(['shipping', 'delivered']) ? 'active' : ''">
                        </div>
                        <div class="mx-1 flex flex-col items-center">
                            <img src="/assets/icon/ship.png" alt="" class="w-12 h-12 p-2">
                            <p class=" text-base" :class="isActive(['shipping', 'delivered']) ? 'text-[#FF0000]' : ''">
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
                    <div class="w-full flex flex-col  mx-auto mt-4">

                        <h1 class="text-[#000000] text-[16px] font-semibold mr-2">Yêu cầu hoàn đơn?
                        </h1>

                        <textarea v-model="form.reason" name="" id="" cols="30" rows="5"
                            :class="form.errors.reason ? ' border-red-500' : null"
                            class="rounded-md border border-[0.5]"></textarea>
                        <div class="text-red-500" v-if="form.errors.reason">{{ form.errors.reason }}
                        </div>
                    </div>


                </div>

                <div class="modal-footer">

                    <button type="submit" @click.prevent="orderRefunding()" v-if="ids.length > 0"
                        class="inline-block rounded-2xl px-2 py-3 bg-[#4F8D06] text-white font-black text-sm leading-tight uppercase  shadow-md hover:bg-[#4F8D06] hover:shadow-lg focus:bg-[#4F8D06] focus:shadow-lg focus:outline-none focus:ring-0 active:bg-[#4F8D06] active:shadow-lg transition duration-150 ease-in-out">Tạo
                        yêu cầu hoàn đơn</button>
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
import OrderTransportState from '@/Pages/Modules/CSKH/Status/OrderTransportState.vue'
const props = defineProps({
    ids: Array
})
const { order, order_transport
} = storeToRefs(useOrderStore())
const form = useForm({
    reason: null,

})


const isActive = (status) => {
    if (order_transport.value && status.includes(order_transport.value.state)) {
        return true
    }
    return false
}


const orderRefunding = () => {

    let query = {
        ids: props.ids,
        reason: form.reason
    };


    router.post(route("admin.cskh.order.refunding"), query, {
        onError: () => { },
        onSuccess: () => {
            form.reset();
            $("#OrderRefunding").modal("hide");
        },
    });


}
const listener = () => {
}
onUnmounted(() => {

    emitter.off('OrderRefunding', listener)
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