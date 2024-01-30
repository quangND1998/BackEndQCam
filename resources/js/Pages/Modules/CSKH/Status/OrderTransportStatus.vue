<script setup>
import { arrow, useFloating } from '@floating-ui/vue';
import { ref } from 'vue';
import { computed } from 'vue';

const props = defineProps({
    order_transport: Object,
});
const reference = ref(null);
const floating = ref(null);
const floatingArrow = ref(null);

const { floatingStyles, middlewareData } = useFloating(
    reference,
    floating,
    {
        middleware: [arrow({ element: floatingArrow })],
    },
);
const status_text = {
    wait_package: 'Chờ đóng gói',
    not_shipper_owner: 'Chưa giao shipper',
    not_shipping: "Chưa vận chuyển",
    not_delivered: 'Chưa giao',
    delivered: 'Đã giao hàng',
    wait_refund: "Chờ hoàn",
    refund: 'Đã hoàn',
    wait_decline: 'Chờ hủy',
    decline: "Đã hủy"
}
const status_transport_color = {
    wait_package: 'bg-[#FFFFFF] ',
    not_shipper_owner: 'bg-[#FF6100] text-white',
    not_shipping: 'bg-[#EE2736] text-white',
    not_delivered: 'bg-[#1D75FA] text-white',
    delivered: 'bg-[#4F8D06] text-white',
    wait_refund: 'bg-[#27AE60] text-white',
    refund: 'bg-[#00327F] text-white',
    wait_decline: ' text-[#FF0000]',
    decline: 'bg-[#FF0000] text-white',

}


const order_transport_text = computed(() => {
    if (status_text[props.order_transport.status]) {
        return status_text[props.order_transport.status]
    }
    else {
        return 'Chưa giao'
    }

})

const order_transport_color = computed(() => {
    return status_transport_color[props.order_transport.status]
})

</script>
<template>
    <span class="px-1 py-1 border rounded-md " :class="order_transport_color">{{
        order_transport_text }} </span>
</template>
<style ></style>
