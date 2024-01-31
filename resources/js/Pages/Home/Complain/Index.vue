<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import PillTag from '@/Components/PillTag.vue'

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
    mdiLandFields,
    mdiCancel
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
import LayoutBar from '@/Layouts/LayoutBar.vue';

defineProps({
    complains: Object,
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
initFlowbite();
const changeState = (visit) => {
    console.log(visit.id);
    form.post(route('visit.changeStateToConfirm', visit.id), {
        onFinish: () => {
            form.reset();
        },
    });
}
const cancelState = (visit) => {
    console.log(visit.id);
    form.post(route('visit.cancelState', visit.id), {
        onFinish: () => {
            form.reset();
        },
    });
}
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý khiếu nại" />
        <SectionMain class="p-3 mt-16">
            <SectionTitleLineWithButton class="font-semibold flex mr-2" title="Quản lý khiếu nại" main>
            </SectionTitleLineWithButton>
            <div>
                <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">

                </div>
                <div class="p-2 rounded-lg col-md-12">
                    <div class="panel panel-default">
                        <div class="overflow-x-auto relative  sm:rounded-lg ">
                            <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700  bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6 text-xs">STT</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Mức độ nghiêm trọng</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Thời gian tạo</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Khách hàng</th>
                                        <th scope="col" class="py-3 px-6 text-xs">SĐT</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Nội dung</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Trạng thái xử lý</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Phòng ban</th>
                                        <th scope="col" class="py-3 px-6 text-xs">Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody v-if="complains">
                                    <tr v-for="(complain, index) in complains.data" :key="index"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ index + complains.from }}
                                        </th>
                                        <th class="py-3 px-6 text-xs">
                                            <div class="px-1 py-1 border rounded-2xl w-20 text-center text-[10px]"
                                                :class="complain.severity == 'urgent' ? 'bg-red' : complain.severity == 'critical' ? 'bg-[#FF6100] text-white' : 'bg-[#FCFBB4]'">
                                                {{ complain.severity == 'urgent' ? 'Xử lý sớm' : complain.severity ==
                                                    'critical' ? 'Nghiêm trọng' : 'Thông thường' }}</div>
                                        </th>
                                        <th class="py-3 px-6 text-xs">
                                            <div>{{ formatDateOnly(complain.created_at) }}</div>
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ complain?.user?.name }}
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ hasAnyPermission(['super-admin']) ? complain?.user?.phone_number :
                                                hidePhoneNumber(complain?.user?.phone_number) }}
                                        </th>
                                        <th class="py-3 px-6 text-xs">
                                            <div>{{ complain.description }}</div>
                                        </th>
                                        <th class="py-3 px-6 text-xs" >
                                            <div class="px-2 w-20 text-center py-1 rounded opacity-35" :class="complain.state == 0 ? 'bg-green' : 'đã xử lý'">{{ complain.state == 0 ? 'Chờ xử lý' : 'đã xử lý' }}</div>
                                        </th>
                                        <th scope="row"
                                            class="py-1 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        </th>

                                        <th class="py-1 px-6 text-right flex justify-end my-1">
                                        </th>
                                    </tr>
                                    <pagination :links="complains.links" />
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </SectionMain>
    </LayoutAuthenticated></template>
