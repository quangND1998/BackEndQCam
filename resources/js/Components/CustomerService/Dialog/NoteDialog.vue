<script setup>
import { inject, onMounted, ref } from 'vue';
import axios from "axios";
import debounce from 'lodash/debounce'

import SpinnerIcon from "@/Components/CustomerService/SpinnerIcon.vue";

const { customerId } = inject('ORDER_PACAGE_PAGE');

const visible = ref(false);
const note = ref('');
const isSaving = ref(false);
const previouseNote = ref('');

const onChangeNote = debounce(async (e) => {
  if (previouseNote.value === e.target.value) {
    return;
  }

  try {
    note.value = e.target.value;
    isSaving.value = true;
    await axios.post(`/customer-service/customer/${customerId}/notes`, {
      note: note.value,
    });
  } catch (error) {
    // console.log(error);
  } finally {
    previouseNote.value = note.value;
    isSaving.value = false;
  }
}, 500);

onMounted(async () => {
  try {
    const { data } = await axios.get(`/customer-service/customer/${customerId}/notes`);
    note.value = data.note;
    previouseNote.value = data.note;
  } catch (error) {
    // Do something
  }
});
</script>

<template>
  <div class="relative">
    <div v-if="visible" class="w-96 rounded-lg bg-yellow-200 pb-1 shadow-lg absolute -top-[340px]">
      <div class="flex items-center justify-between rounded-t-lg bg-yellow-500 pr-3 pl-4 py-2">
        <p class="font-semibold">
          Thông tin khách hàng
        </p>
        <i class="fa fa-times text-2xl cursor-pointer text-white" aria-hidden="true" @click="visible = false"/>
      </div>
      <textarea
        :value="note"
        @input="onChangeNote"
        class="w-96 resize-none bg-yellow-200 border-none text-sm focus:!outline-none focus:!border-none focus:!shadow-none focus:ring-0" rows="10"></textarea>
      <div class="flex invisible justify-end py-1" :class="{
        '!visible': isSaving,
      }">
        <SpinnerIcon class="text-orange-500" />
      </div>
    </div>
    <button class="w-96 bg-orange-500 rounded-full font-semibold text-white py-2" @click="visible = !visible">
      Ghi chú về khách hàng
    </button>
  </div>
</template>
