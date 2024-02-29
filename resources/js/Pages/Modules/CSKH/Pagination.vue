<template>
    <div v-if="links.length > 3" class="mt-8">
        <div class="flex flex-wrap -mb-1 items-center">
            <div v-for="(link, k) in links" :key="k">
                <p v-if="link.url === null" class="mr-3  text-[14px] leading-4" v-html="link.label" />
                <Link v-else class="mr-3  text-[14px] px-2 " :class="{ 'bg-blue-700 text-white': link.active }" :href="link.url"
                    v-html="link.label" />
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
