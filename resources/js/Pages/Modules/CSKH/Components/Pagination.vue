
<script setup>
import { mdiChevronLeft, mdiChevronRight } from '@mdi/js'
import BaseButton from '@/Components/BaseButton.vue';
defineProps({
    data: Object
})
const emit = defineEmits(['change-page'])
const changePage = (page) => {
    emit('change-page', page)
}

</script>
<template>
    <div v-if="data.links.length > 3" class="mt-8">
        <div class="flex flex-wrap -mb-1 items-center">
            <div class="mr-2"> Trang</div>
            <div v-for="(link, k) in data.links" :key="k">

                <div v-if="Number.isInteger(parseInt(link.label, 10))" @click="changePage(link.label)"
                    class="mr-1 mb-1 py-2 px-1 text-xs leading-4  rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                    :class="{ 'text-blue-700 border px-2 border-blue-700': link.active }" v-html="link.label" />
                <div v-if="link.label == '...'" class="mr-3  text-[14px] " v-html="link.label" />
            </div>
        </div>
    </div>
</template>



<style></style>