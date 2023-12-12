<script setup>
import { computed, ref, inject, reactive } from "vue";
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

        <Head title="Product Retail" />
        <SectionMain>
            <div class="w-3/5 mt-10 m-auto">
                <div class="flex">
                    <SectionTitleLineWithButton title="Xin chào, " main></SectionTitleLineWithButton>
                    <h1 class="text-3xl ml-3 text-[#F78F43]">Cam Mặt Trời</h1>
                </div>

                <div class="flex items-center">
                    <p class="text-[#64748B] text-base">Hiện bạn đang có <strong class="text-lg">05</strong> đơn hàng mới
                        đang đợi bạn. </p>
                    <a href="" class="text-blue-500 ml-3"> Xem ngay</a>
                </div>

                <div class="mt-10">
                    <h2 class="text-base text-[#334155] text-3xl font-semibold">TỔNG QUAN ĐƠN HÀNG</h2>
                    <div class="min-[320px]:block sm:grid sm:grid-cols-3 gap-4 my-5">
                        <div class="p-4 rounded-xl bg-blue-500 text-white">
                            <p>Đơn mới</p>
                            <h1 class="my-2"><strong class="text-3xl">05</strong> đơn hàng</h1>
                        </div>
                        <div class="p-4 rounded-xl  text-[#64748B]"
                            style="box-shadow: 0px 1px 2px -1px #0000001A;box-shadow: 0px 1px 3px 0px #0000001A;">
                            <p>Đặt lịch</p>
                            <h1 class="my-2"><strong class="text-3xl text-black">05</strong> đơn hàng</h1>
                        </div>
                        <div class="p-4 rounded-xl  text-[#64748B]"
                            style="box-shadow: 0px 1px 2px -1px #0000001A;box-shadow: 0px 1px 3px 0px #0000001A;">
                            <p>Hoạt động</p>
                            <h1 class="my-2"><strong class="text-3xl text-black">35</strong> đơn hàng</h1>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="p-4 rounded-xl  text-[#64748B]"
                            style="box-shadow: 0px 1px 2px -1px #0000001A;box-shadow: 0px 1px 3px 0px #0000001A;">
                            <p>Doanh thu (tháng này)</p>
                            <h1 class="my-2 text-3xl font-bold text-blue-500">153,640,000 đ</h1>
                        </div>
                    </div>
                    <div class="mt-10">
                        <h2 class="text-base text-[#334155] text-3xl font-semibold">TÁC VỤ NHANH</h2>
                        <div class="py-5 px-3 rounded-xl mt-5"
                            style="box-shadow: 0px 1px 2px -1px #0000001A;box-shadow: 0px 1px 3px 0px #0000001A;">
                            <a href="" class="text-base text-[#334155] pb-3 border-b border-gray-200 flex justify-between hover:text-blue-500">

                                <p>Xem đơn hàng</p>
                                <p class="text-lg ">></p>
                                <!-- <BaseButton color="info"  :icon="mdiChevronRight " small  /> -->
                            </a>
                            <a href="" class="text-base text-[#334155] py-3 border-b border-gray-200 flex justify-between hover:text-blue-500">

                                <p>Đăng sản phẩm</p>
                                <p class="text-lg ">></p>
                                <!-- <BaseButton color="info"  :icon="mdiChevronRight " small  /> -->
                            </a>
                            <a href="" class="text-base text-[#334155] py-3  flex justify-between hover:text-blue-500">

                                <p>Đăng bài viết</p>
                                <p class="text-lg ">></p>
                                <!-- <BaseButton color="info"  :icon="mdiChevronRight " small  /> -->
                            </a>
                        </div>
                    </div>
                </div>
            </div>



        </SectionMain>
</LayoutAuthenticated></template>
<style scoped src="@vueform/multiselect/themes/default.css"></style>
