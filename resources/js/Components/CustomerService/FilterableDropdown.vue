<script setup>
import { defineModel } from 'vue';
import Dropdown from 'primevue/dropdown';

const model = defineModel();
const props = defineProps({
  options: Array,
  onChange: {
    required: false,
    default: () => {},
  },
  placeholder: String,
  optionLabel: String,
  optionValue: String,
  disabled: Boolean,
  normalizeValue: {
    required: false,
    default: (value) => value,
  }
});

</script>

<template>
  <Dropdown v-model="model" :options="options" filter :optionLabel="optionLabel" :disabled="disabled"
    @change="onChange" :optionValue="optionValue" :placeholder="placeholder"
    class="filter-dropdown w-full rounded focus:!outline-none !border focus:ring-0 focus:!border-gray-400 !border-gray-400 !text-xs !p-0 !shadow-none"
    :class="{'!bg-gray-200': disabled}"
  >
    <template #value="slotProps">
      <div v-if="slotProps.value" class="flex align-items-center text-sm text-[#212529]" :class="{ 'text-gray-600': disabled }">
        <div>{{ normalizeValue(slotProps.value) }}</div>
      </div>
      <span v-else class="text-sm">
        {{ slotProps.placeholder }}
      </span>
    </template>
    <template #option="slotProps">
      <div class="flex align-items-center">
        <div>{{ slotProps.option[optionLabel] }}</div>
      </div>
    </template>
  </Dropdown>
</template>

<style>
.filter-dropdown .p-dropdown-label {
  padding: 8px 4px 8px 8px !important;
}

.filter-dropdown .p-dropdown-trigger {
  width: 18px !important;
  margin-right: 4px;
}
</style>
