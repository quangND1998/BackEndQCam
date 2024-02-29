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
    draft: "Đơn nháp"


}
const status_color = {
    pending: 'border-[#FF6100] text-[#FF6100] bg-white px-3',
    processing: 'bg-[#F0C419] border-0 text-black  px-3',
    completed: 'text-[#4F8D06] border-[#4F8D06] bg-white px-3',
    create: 'text-[#27AE60] border-[#27AE60] bg-white px-4 ',
    draft: "bg-[#b4b9bf] text-white bg-white px-3"
}




const order_text = computed(() => {
    return status_text[props.order.status]

})

const order_color = computed(() => {
    return status_color[props.order.status]
})

</script>
<template>
    <span class="px-1 py-1 border-[1px] rounded-md font-normal" :class="order_color">{{
        order_text }} </span>
</template>
<style ></style>
