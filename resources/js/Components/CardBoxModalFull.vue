<script setup>
import { computed, ref } from 'vue'
import { mdiClose } from '@mdi/js'
import BaseButton from '@/Components/BaseButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue'
import CardBox from '@/Components/CardBox.vue'
import OverlayLayer from '@/Components/OverlayLayer.vue'
import CardBoxComponentTitle from '@/Components/CardBoxComponentTitle.vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  button: {
    type: String,
    default: 'info'
  },
  buttonLabel: {
    type: String,
    default: 'Done'
  },
  hasCancel: Boolean,
  hasSave: Boolean,
  modelValue: {
    type: [String, Number, Boolean],
    default: null
  },
  classSize:{
    type: String,
    default: null
  },
})

const emit = defineEmits(['update:modelValue', 'cancel', 'confirm',])

const value = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

const confirmCancel = (mode) => {
  value.value = false
  emit(mode)
}

const confirm = () => confirmCancel('confirm')

const cancel = () => confirmCancel('cancel')

window.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && value.value) {
    cancel()
  }
})
const defaultClass= ref('shadow-lg max-h-modal w-8/12  md:w-11/12 lg:w-8/12 xl:w-8/12 z-50 overflow-auto')
</script>

<template>
  <OverlayLayer v-show="value" @overlay-click="cancel">
    <CardBox v-show="value" :class="classSize ? classSize :defaultClass" is-modal>
      <CardBoxComponentTitle :title="title">
        <BaseButton v-if="hasCancel" :icon="mdiClose" color="whiteDark" small rounded-full @click.prevent="cancel" />
      </CardBoxComponentTitle>

      <div class="space-y-3">
        <slot />
      </div>

      <template #footer>
        <BaseButtons class="flex justify-end">
          <BaseButton v-if="hasCancel" label="Hủy" :color="button" outline @click="cancel" />
          <BaseButton v-if="hasSave" :label="buttonLabel" :color="button" @click="confirm" />

        </BaseButtons>
      </template>
    </CardBox>
  </OverlayLayer>
</template>
