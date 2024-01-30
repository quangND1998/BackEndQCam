<script setup>
import { emitter } from '@/composable/useEmitter';
import { computed } from 'vue';

const props = defineProps({
    order_transport: Object,
});

const status_text = {
    pending: 'Đơn chờ',
    packing: 'Chưa giao shipper',
    shipping: "Chưa vận chuyển",
    delivered: 'Chưa giao',
    delivered: 'Đã giao hàng',
    refunding: "Chờ hoàn",
    refund: 'Đã hoàn',
    decline: "Hủy giao"


}
const status_color = {
    pending: 'bg-[#FFFFFF] ',
    packing: 'bg-[#FF6100] text-white',
    shipping: 'bg-[#EE2736] text-white',
    delivered: 'bg-[#4F8D06] text-white',
    refunding: 'bg-[#4F8D06] text-white',
    refund: 'bg-[#27AE60] text-white',
    decline: 'bg-[#FF0000] text-white',



}




const order_transport_text = computed(() => {
    return status_text[props.order_transport.state]

})

const order_transport_color = computed(() => {
    return status_color[props.order_transport.state]
})

</script>
<template>
    <span class="px-1 py-1 border rounded-md " :class="order_transport_color">{{
        order_transport_text }} </span>
</template>
<style ></style>