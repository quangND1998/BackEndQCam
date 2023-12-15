<template>
  <div v-if="links.length > 3" class="mt-8">
    <div class="flex flex-wrap -mb-1 items-center">
      <div v-for="(link, k) in links" :key="k">
        <div v-if="link.url === null " class="mr-1  px-2 py-2 text-xs leading-4 text-gray-400  rounded"
          v-html="link.label" />
        <Link v-else
          class="mr-1 mb-1 py-2 px-1 text-xs leading-4  rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
          :class="{ 'text-blue-700 border px-2 border-blue-700': link.active }" :href="link.url" v-html="link.label" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({
  links: Array,
});
</script>
