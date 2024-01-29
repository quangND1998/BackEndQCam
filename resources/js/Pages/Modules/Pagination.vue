
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
    <div class="flex flex-wrap -mb-1">
      <div v-for="(link, k) in data.links" :key="k">

        <div class="mr-3  text-[14px] " :class="{ 'bg-blue-700 text-white': link.active }" v-html="link.label" />
      </div>
    </div>
  </div>
</template>



<style></style>