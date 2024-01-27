<script setup>
import { emitter } from '@/composable/useEmitter';
import { computed } from 'vue';

const props = defineProps({
    order_transport: Object,
});

const state_text = {
    pending: 'Chờ đóng gói',
    packed: 'Chưa giao shipper',

    delivering: 'Đang vận chuyển',
    delivered: 'Đã giao hàng',
    refunding: 'Chờ hoàn',
    refund: 'Đã hoàn',
    decline: 'Hủy giao',

}
const status_color = {
    pending: 'border-[#FF6100] text-gray-800',
    packed: 'bg-[#FF6100] text-white',
    
    delivering: 'border-[#FF6100] text-[#FF6100]',
    delivered: 'bg-[#4F8D06] text-white',
    refunding: 'bg-[#27AE60] text-white',
    refund: 'bg-[#00327F] text-white',
    decline: 'border-[#FF0000] text-[#FF0000]',



}




const order_text = computed(() => {
 

})

const order_color = computed(() => {
  
})

</script>
<template>
    <span class="px-1 py-1 border rounded-md " :class="order_color">{{
        order_text }} </span>
</template>
<style ></style>
