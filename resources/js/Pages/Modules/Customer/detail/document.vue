<script setup>
import { computed, ref, inject, watch, toRef } from "vue";
import LayoutProfileDetail from "@/Layouts/LayoutProfileDetail.vue";
import SectionMain from "@/Components/SectionMain.vue";
import { Head } from "@inertiajs/vue3";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import {
  mdiReceiptText,
  mdiPencilOutline,
  mdiPlus,
  mdiTrashCan,
  mdiTrashCanOutline,
  mdiPencil,
  mdiDotsVertical,
  mdiImageOutline,
  mdiFilePdfBox 
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseIcon from '@/Components/BaseIcon.vue'
import PillTag from "@/Components/PillTag.vue";
import Multiselect from "@vueform/multiselect";
import Dropdown from "@/Components/Dropdown.vue";
import { Link, useForm } from "@inertiajs/vue3";
import moment from "moment";
import { useHelper } from "@/composable/useHelper";
const props = defineProps({
  customer: Object,
  orderFiles: Array,
});
const { multipleSelect } = useHelper();
const swal = inject("$swal");
const form = useForm({
  id: null,
  state: null,
});
const totalTree = toRef(props.trees);
const isModalActive = ref(false);
const editMode = ref(false);
const isModalExtendActive = ref(false);
const edit = (product_owner) => {
  isModalActive.value = true;
  editMode.value = true;
  form.id = product_owner.id;
};
const extend = (product_owner) => {
  isModalExtendActive.value = true;
  form.product_service = product_owner.id;
};
const crumbs = ref([
  {
    route: "customer.detail.gift",
    parma: props.customer.id,
    name: props.customer.name,
  },
  {
    route: "customer.detail.gift",
    parma: props.customer.id,
    name: "Amenities",
  },
]);
const form_reset = () => {
  console.log("reset");
  totalTree.value = props.trees;
};

const limit_tree = computed(() => {
  console.log("limit_tree", form.product_service);
  let product_service = props.products.find(
    (e) => e.id == form.product_service
  );
  return product_service;
});
</script>

<template>
  <LayoutProfileDetail :customer="customer" :crumbs="crumbs">
    <Head title="Product" />
    <SectionMain>
      <div class="filter w-full">
        <select
          id="countries"
          class="w-[20%] ml-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg"
        >
         <option selected>Loại</option>
          <option value="image">Ảnh</option>
          <option value="pdf">FDF</option>
          <option value="word">Word</option>
        </select>
         <select
          class="w-[20%] ml-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg"
        >
         <option selected>Dịch vụ</option>
          <option value="contract">Hợp đồng</option>
          <option value="order">Đặt hàng</option>
          <option value="gift">Giao quà</option>
        </select>
        <select
          class="w-[20%] ml-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg"
        >
         <option selected>Sắp xếp</option>
          <option value="contract">Hôm nay</option>
          <option value="order">7 ngày qua</option>
          <option value="gift">1 tháng qua</option>
        </select>
      </div>

      <div class="flex-auto sm:w-full">
        <div class="relative mt-3 flex  flex-wrap shadow-md sm:rounded-lg">
            <div class="item w-[240px] flex flex-col items-center" v-for="file in orderFiles" :key="file.id" :title="file.file_name">
                <div class="flex">
                 <BaseIcon :path="file.mime_type == 'application/pdf' ? mdiFilePdfBox : mdiImageOutline  " class="flex-none "  w="w-16" :size="20" />
                <!-- {{ file }} -->
                <p class="text" >{{file.file_name}}</p> 
                </div>

                <div class="preview p-3">
                    <iframe v-if="file.mime_type == 'application/pdf'" :src="'/storage/' + file.id + '/' + file.file_name" class="w-36 overflow-hidden" frameborder="0"></iframe>
                    <img v-else  :src="'/storage/' + file.id + '/' + file.file_name" frameborder="0" class="img w-full h-full"/>
                </div>
                
            </div>
        </div>
      </div>
    </SectionMain>
  </LayoutProfileDetail>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scope>
.text {
   overflow: hidden;
   width: 120px;
   display: -webkit-box;
   -webkit-line-clamp: 1; /* number of lines to show */
           line-clamp: 1; 
   -webkit-box-orient: vertical;
}
.item{
    background-color: #edf2fc;
    border-radius: 20px;
    margin: 10px;
    padding: 10px;
}
</style>