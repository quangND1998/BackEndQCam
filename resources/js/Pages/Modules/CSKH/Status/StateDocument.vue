<script setup>
import { arrow, useFloating } from '@floating-ui/vue';
import { ref } from 'vue';
import { computed } from 'vue';

const props = defineProps({
    order: Object,
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
    not_push: 'Bổ sung',
    approved: 'Đã Duyệt',
    not_approved: "Chưa duyệt"
}
const status_color = {
    not_push: 'bg-[#EE2736] text-white',
    approved: 'bg-[#4F8D06] text-white',
    not_approved: 'bg-[#4F8D06] text-white',

}


const order_text = computed(() => {
    return status_text[props.order.state_document]

})

const order_color = computed(() => {
    return status_color[props.order.state_document]
})

</script>
<template>
 
    <span class="px-1 py-1 border rounded-md " :class="order_color">{{
        order_text }} </span>
   
</template>
<style ></style>
