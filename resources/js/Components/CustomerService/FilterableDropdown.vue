<script setup>
import { defineModel } from 'vue';
import Dropdown from 'primevue/dropdown';

const model = defineModel();
const props = defineProps({
  options: Array,
  onChange: Function,
  placeholder: String,
  optionLabel: String,
  optionValue: String,
})

</script>

<template>
  <Dropdown v-model="model" :options="options" filter :optionLabel="optionLabel"
    @change="onChange($event)" :optionValue="optionValue" :placeholder="placeholder"
    class="w-full md:w-14rem bg-gray-50 border border-gray-300 text-gray-900 text-sm ">
    <template #value="slotProps">
        <div v-if="slotProps.value" class="flex align-items-center">
            <div>{{ slotProps.value }}</div>
        </div>
        <span v-else>
            {{ slotProps.placeholder }}
        </span>
    </template>
    <template #option="slotProps">
        <div class="flex align-items-center">
            <div>{{ slotProps.option[optionValue] }}</div>
        </div>
    </template>
  </Dropdown>
</template>
