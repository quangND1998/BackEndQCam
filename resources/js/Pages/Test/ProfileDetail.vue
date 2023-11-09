<script setup>
import { computed, ref, inject,reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import {
    mdiEye,
    mdiAccountLockOpen,
    mdiPlus,
    mdiFilter,
    mdiMagnify,
    mdiDotsVertical,
    mdiTrashCanOutline,
    mdiCodeBlockBrackets,
    mdiPencil,
    mdiLandFields
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
// import Multiselect from '@vueform/multiselect'
import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'

defineProps({
    lands: Object,
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    name: null,
    state: null,
});
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);

const state = reactive({
    content: '<p>2333</p>',
    _content: '',
    editorOption: {
        placeholder: 'core',
        modules: {
           
        },
      
    },
    disabled: false
})

</script>
<template>
    <LayoutAuthenticated>

        <Head title="Profile Detail" />
        <SectionMain>
            <SectionTitleLineWithButton title="Profile Detail" main></SectionTitleLineWithButton>

         <div class="h-52 w-96 bg-white rounded-xl " style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">
            <div class="p-3 ">
                <img src="../../../../public/assets/images/new4.png" class="h-24 w-24 my-2 rounded-full m-auto object-cover" alt="">
                <h3 class="font-bold text-xl text-center">Hoàng Huyền</h3>
                <p class="text-center">hoanghuyenn2k@gmail.com</p>

            </div>
           
         </div>
         
         <div class="  my-5 bg-white  dark:bg-gray-500 rounded-lg"
            style="box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
            <ul class="flex flex-wrap">
                <li
                    class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] dark:text-white inline-block my-2">

                    <a href="" class="flex">
                        <Icon icon="info" class=" mr-2" />Project Information
                    </a>
                </li>
                <li class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] flex dark:text-white inline-block my-2"
                    v-tooltip.top="'Block'"
                  >

                    <Link  class="flex">
                    <Icon icon="buildings" class=" mr-2" />Blocks</Link>
                </li>
                <li class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] flex dark:text-white inline-block my-2"
                    v-tooltip.top="'Amenities'"
                   >

                    <Link class="flex">
                    <Icon icon="amentities" class=" mr-2" />Amentities</Link>
                </li>
                <li class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] flex dark:text-white inline-block my-2"
                    v-tooltip.top="'Image'"
          >

                    <Link href="" class="flex">
                    <Icon icon="gallery" class=" mr-2" />Images</Link>
                </li>
               

                <li class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] flex dark:text-white inline-block my-2"
                    v-tooltip.top="'Brochure'"
                    >
                    <Link href="" class="flex">
                    <Icon icon="clipboard" class=" mr-2" />
                    Brochure</Link>
                </li>
               
            </ul>
        </div>
            
        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
