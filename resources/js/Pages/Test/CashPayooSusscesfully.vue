<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import OrderBar from "@/Pages/Modules/Order/OrderBar.vue";

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
import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import { initFlowbite } from 'flowbite'
import Contract from '@/Pages/Test/Contract.vue'
import InvoiceInformation from '@/Pages/Test/InvoiceInformation.vue'


const props = defineProps({
    orders: Object,
    status: String,
    status: String,
    from: String,
    to: String,
    statusGroup: Array
});


</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đơn hàng" />
        <SectionMain>
            <div class="mx-5 mt-10">
                <div class="">
                    <div class="grid grid-cols-2 gap-4 border-b border-gray-300">
                        <div>
                            <div class="pb-3 ">
                                <img src="/assets/images/cammattroi.png" alt="">
                                <h1 class="text-base font-semibold">CÔNG TY CỔ PHẦN CAM MẶT TRỜI</h1>
                                <p class="text-sm text-[#5F5F5F]">Địa chỉ:</p>
                                <p class="text-sm text-[#5F5F5F]">Farm:</p>
                                <p class="text-sm text-[#5F5F5F]">Điện thoại:</p>
                                <p class="text-sm text-[#5F5F5F]">Email:</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold mt-3">Tải App Cam Mặt Trời để tận hưởng!</h3>
                            <div class="flex">
                                <img src="/assets/images/appstore.png" class="mr-2 w-44 h-auto" alt="">
                                <img src="/assets/images/ggplay.png" class="mx-2 w-44" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 w-3/5">
                        <strong>Cảm ơn quý khách đã thanh toán đơn hàng #12535 </strong> <br>
                        Cam Mặt Trời sẽ liên hệ với quý khách khi chúng tôi nhận được tiền từ quý khách!
                        Mọi thắc mắc/ góp ý vui lòng liên hệ hotline: 08999333...
                    </div>

                </div>

            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>


