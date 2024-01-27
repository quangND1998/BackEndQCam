<script setup>
import { emitter } from '@/composable/useEmitter';
import { computed } from 'vue';

const props = defineProps({
    order: Object,
});

const status_text = {
    pending: 'Pending',
    processing: 'Đang xử lý',
    completed: 'Thành công',
    create: 'Tạo mới',
   

}
const status_color = {
    pending: 'border-[#FF6100] text-gray-800',
    processing: 'bg-[#FF6100] text-white',
    completed: 'bg-[#4F8D06] text-white',
    create: 'bg-[#27AE60] text-white',



}




const order_text = computed(() => {
    return status_text[props.order.status]

})

const order_color = computed(() => {
    return status_color[props.order.status]
})

</script>
<template>
    <span class="px-1 py-1 border rounded-md " :class="order_color">{{
        order_text }} </span>
</template>
<style ></style>
