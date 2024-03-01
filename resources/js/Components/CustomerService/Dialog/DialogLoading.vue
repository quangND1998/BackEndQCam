<script setup>
  import SpinnerIcon from "@/Components/CustomerService/SpinnerIcon.vue";

  defineProps({
    text: String,
  });
</script>

<template>
  <div class="w-full h-[100vh] fixed top-0 left-0 bg-gray-400/40 flex items-center justify-center font-bold text-lg">
    <SpinnerIcon />
    <p>{{ text }}...</p>
    </div>
</template>
