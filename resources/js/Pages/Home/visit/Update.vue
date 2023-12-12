<script setup>
import { computed, ref, inject, reactive, toRef, onMounted } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import Dropdown from 'primevue/dropdown';
import BaseIcon from '@/Components/BaseIcon.vue'
import BaseButton from '@/Components/BaseButton.vue'
import InputNumber from 'primevue/inputnumber';
import MazInputNumber from 'maz-ui/components/MazInputNumber'
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
    mdiContentCopy,
    mdiCreditCardSettingsOutline,
    mdiContentSaveMove
} from "@mdi/js";

import InputError from "@/Components/InputError.vue";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import { initFlowbite } from 'flowbite'
import axios from "axios";
import MazSelect from 'maz-ui/components/MazSelect'
import { useCartStore } from "@/stores/cart";
import Multiselect from '@vueform/multiselect'
const swal = inject("$swal");

const props = defineProps({

    schedule: Object,

});
const store = useCartStore();
const search = ref(null)
// const user = ref(null);
const flash = ref(null);
const provinces = ref(null)
const images = ref([])
const form = useForm({

    date_time: props.schedule.date_time,
    number_adult: props.schedule.number_adult,
    number_children: props.schedule.number_children,



})



const save = () => {
    form.post(route('visit.updateShedule', props.schedule.id), {
        onError: () => {


        },
        onSuccess: () => {
            form.reset()

        }
    });

}




</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đơn hàng" />

        <SectionMain class="p-3 mt-8">

            <div class="container m-auto mt-10 pb-4">
                <div class="min-[320px]:block sm:block md:block lg:grid grid-cols-3 gap-4 mt-10">
                    <div class="col-span-2">

                        <div class="my-3">
                                        <label for="date_of_birth"
                                            class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Thời gian </label>
                                        <div class="relative w-full">
                                            <VueDatePicker v-model="form.date_time"  />
                                        </div>
                                        <InputError class="mt-2" :message="form.errors.date_time" />
                                    </div>

                       

                        <div class="my-3">

                            <div class="grid grid-cols-2 gap-4">
                                <div>

                                    <div class="my-3">
                                    <label for="number_adult" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                        Số người lớn *</label>


                                    <MazInputNumber v-model="form.number_adult" placeholder="Enter number" :min="1"
                                        :max="10000" size="md" color="secondary" style="width: 200px;" />
                                    <InputError class="mt-2" :message="form.errors.number_adult" />
                                </div>
                                  
                                </div>


                                <div class="ml-3">
                                    <div class="my-3">
                                        <label for="number_children"
                                            class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Số trẻ em *</label>

                                        <MazInputNumber v-model="form.number_children" placeholder="Enter number" :min="0"
                                            :max="10000" size="md" color="secondary" style="width: 200px;" />
                                        <InputError class="mt-2" :message="form.errors.number_children" />
                                    </div>
                                </div>

                            </div>
                        </div>



                    </div>
                    <div class="min-[320px]:mx-0 md:mx-5">

                        <div class="my-3">
                            <BaseButton color="info" @click="save()"
                                class="bg-[#F78F43] text-white p-2 w-full text-center justify-center rounded-lg"
                                :icon="mdiContentSaveMove" small label="Cập nhật" />
                        </div>
                        <div class="my-3">
                            <BaseButton :routeName="'users.index'" color="info"
                                class="bg-green-500 text-white p-2 w-full text-center justify-center"
                                :icon="mdiTrashCanOutline" small label="Hủy" />
                        </div>

                    </div>
                </div>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>












































