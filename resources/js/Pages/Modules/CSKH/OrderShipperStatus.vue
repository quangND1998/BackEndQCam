<script setup>
import { emitter } from '@/composable/useEmitter';
import { computed } from 'vue';
const props = defineProps({
    order: Object,
});

const status_text = {
    pending: 'Chưa vận chuyển',
    shipping: 'Đang giao hàng',
    delivered: 'Đã giao hàng',
    refund: 'Đóng gói',
    decline: 'Shipper không nhận đơn',


}
const status_color = {
    pending: 'bg-[#EE2736] text-white',
    shipping: 'bg-[#F0C419]',
    delivered: 'border-[#AEAEAE] text-[#000000]',
    refund: 'border-[#AEAEAE] text-[#000000]',
    decline: 'border-[#FF6100] text-[#FF6100]',




}

const order_text = computed(() => {
    return status_text[props.order.shipper_status]
})

const order_color = computed(() => {
    return status_color[props.order.shipper_status]
})
</script>
<template>
    <span v-if="order.shipper_status" class="px-1 py-1 border rounded-sm " :class="order_color">{{
        order_text }}</span>
    <span v-else class="px-1 py-1 border rounded-sm  bg-[#FF6100] text-white">Chưa giao shipper</span>
</template>
<style ></style>
