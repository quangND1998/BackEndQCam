<script setup>
import { inject, onMounted, reactive, ref } from 'vue';
import axios from "axios";
import debounce from 'lodash/debounce'

import SpinnerIcon from "@/Components/CustomerService/SpinnerIcon.vue";
import useQuery, { CUSTOMER_SERVICE_API_MAKER } from '@/Components/CustomerService/composables/useQuery';

const { customerId } = inject('ORDER_PACKAGE_PAGE');

const visible = ref(false);
const noteForm = reactive({
  note: ''
});
const previouseNote = ref('');

const onSuccess = () => {
  previouseNote.value = noteForm.note;
}

const { isLoading, executeQuery } = useQuery(
  CUSTOMER_SERVICE_API_MAKER.CREATE_NOTE(customerId),
  noteForm,
  onSuccess,
);

const onChangeNote = debounce(async (e) => {
  if (previouseNote.value === e.target.value) return;
  noteForm.note = e.target.value;
  executeQuery();
}, 500);

const { executeQuery: queryData } = useQuery(
  CUSTOMER_SERVICE_API_MAKER.GET_NOTE(customerId),
  null,
  (data) => {
    noteForm.note = data.note;
    previouseNote.value = data.note;
  }
);
onMounted(async () => {
  queryData();
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
        :value="noteForm.note"
        @input="onChangeNote"
        class="w-96 resize-none bg-yellow-200 border-none text-sm focus:!outline-none focus:!border-none focus:!shadow-none focus:ring-0" rows="10"></textarea>
      <div class="flex invisible justify-end py-1" :class="{
        '!visible': isLoading,
      }">
        <SpinnerIcon class="text-orange-500" />
      </div>
    </div>
    <button class="w-96 bg-orange-500 rounded-full font-semibold text-white py-2" @click="visible = !visible">
      Ghi chú về khách hàng
    </button>
  </div>
</template>
