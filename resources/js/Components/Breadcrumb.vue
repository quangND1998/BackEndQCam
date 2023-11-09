<template>
  <nav class="flex px-5 pb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
      <li class="inline-flex items-center ">
        <a href="#"
          class="inline-flex items-center text-sm font-normal text-color_dark_gray hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
          <Icon icon="qlda" class=" mr-2 text-color_dark_gray" />
          Quản lý
        </a>
      </li>

      <li aria-current="page" v-for="(crumb, index) in crumbs" :key="index">
        <div class="flex items-center">
          <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 9 4-4-4-4" />
          </svg>
          <Link :href="crumb.parma ? route(crumb.route, crumb.parma) : route(crumb.route)"
            class="ml-1 text-xs font-medium text-gray-700 hover:text-gray-900 md:ml-2 dark:text-gray-400 dark:hover:text-white">
          {{ crumb.name }}</Link>
        </div>
      </li>
    </ol>
  </nav>
</template>

<script setup>
import Icon from '@/Components/Icon.vue';
import { Link } from '@inertiajs/vue3';
defineProps({
  crumbs: Array
})
</script>
<style></style>