<script setup>
import { emitter } from '@/composable/useEmitter';
import { computed } from 'vue';

const props = defineProps({
    order: Object,
});

const status_text = {
    pending: 'Chờ đóng gói',
    packed: 'Chưa giao shipper',
    not_shipper_receive: 'Shipper không nhận đơn',
    delivering: 'Đang vận chuyển',
    delivered: 'Đã giao hàng',
    refunding: 'Chờ hoàn',
    refund: 'Đã hoàn',
    decline: 'Hủy giao',

}
const status_color = {
    pending: 'border-[#FF6100] text-gray-800',
    packed: 'bg-[#FF6100] text-white',
    not_shipper_receive: 'border-[#FF6100] text-[#FF6100]',
    delivering: 'border-[#FF6100] text-[#FF6100]',
    delivered: 'bg-[#4F8D06] text-white',
    refunding: 'bg-[#27AE60] text-white',
    refund: 'border-[#00327F] text-white',
    decline: 'border-[#FF0000] text-[#FF0000]',



}



const status_shipper_text = {
    pending: 'Chưa vận chuyển',
    shipping: 'Chưa giao',
    delivered: 'Đã giao hàng',
    refund: 'Đóng gói',
    decline: 'Shipper không nhận đơn',


}
const status_shipper_color = {
    pending: 'bg-[#EE2736] text-white',
    shipping: 'bg-[#1D75FA] text-white',
    delivered: 'bg-[#4F8D06] text-white',
    refund: 'border-[#AEAEAE] text-[#000000]',
    decline: 'border-[#FF6100] text-[#FF6100]',




}

const order_text = computed(() => {
    if (props.order.shipper_status == 'pending' && props.order.status_transport == 'not_shipper_receive') {
        return status_text[props.order.status_transport]
    }
    else if (props.order.shipper_status == null && props.order.status_transport !== 'packed') {
        return status_text[props.order.status_transport]

    }
    else if (props.order.shipper_status == null && props.order.status_transport == 'packed') {
        return status_text[props.order.status_transport]

    }
    else {
        return status_shipper_text[props.order.shipper_status]
    }

})

const order_color = computed(() => {
    if (props.order.shipper_status == 'pending' && props.order.status_transport == 'not_shipper_receive') {
        return status_color[props.order.status_transport]
    }

    else if (props.order.shipper_status == null && props.order.status_transport !== 'packed') {
        return status_color[props.order.status_transport]

    }
    else if (props.order.shipper_status == null && props.order.status_transport == 'packed') {
        return status_color[props.order.status_transport]

    }
    else {
        return status_shipper_color[props.order.shipper_status]
    }
})

</script>
<template>
    <span class="px-1 py-1 border rounded-sm " :class="order_color">{{
        order_text }} </span>
</template>
<style ></style>
