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
    customers: Array

});
const store = useCartStore();
const search = ref(null)
// const user = ref(null);
const flash = ref(null);
const provinces = ref(null)
const images = ref([])
const form = useForm({
    user_id: null,
    name: null,
    date_time: null,
    number_adult: 0,
    number_children: 0,
    product_service_owner_id: null



})


const districts = computed(() => {
    if (form.city == null) {
        return [];
    } else {
        return provinces.value.find(pro => {
            return pro.Name == form.city;
        });
    }
})
const user = computed({


    get() {
        if (form.user_id) {
            console.log(form.user_id)
            const user = props.customers.find((customer) => customer.id == form.user_id)
            if (user) {
                foundUser(user)
                return user
            }
            else {
                form.name = form.user_id
            }


        }
        else {
            form.reset()
            return null
        }
    },
    // setter
    set(newValue) {
        return newValue
        // Note: we are using destructuring assignment syntax here.
        console.log(newValue)
    }
})
const selectUser = (option) => {
    console.log('selectUser', option)

    console.log(user)

}

const onChangeUser = (value, select$) => {

    if (typeof value === 'string') {
        console.log('onChangeUser', form.name)
        form.name = value
    }
    if (typeof value === 'number') {
        console.log('onChangeUser', form.name)
        form.user_id = value
    }
}


const foundUser = (data) => {
    form.user_id = data.id
    form.name = data.name

}
const onSearchUser = async () => {
    axios.get(`/admin/orders/searchUser?search=${search.value}`).then(res => {
        if (res.data) {
            user.value = res.data;
            foundUser(res.data)
        }
    }).catch(err => {
        // user.value = null
        flash.value = err.response.data
        form.reset()
    })

}


const save = () => {
    form.post(route('visit.saveShedule'), {
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
                          
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="my-3">
                                        <label for="name" class="block mb-2 text-sm  text-gray-900 dark:text-white">Tên
                                            Khách Hàng
                                            *</label>
                                        <Multiselect v-model="form.user_id" @change="onChangeUser" @select="selectUser"
                                            :createOption="false" :canClear="true" :searchable="false" label="name"
                                            valueProp="id" trackBy="name" :options="customers" placeholder="Chọn khác hàng"
                                            :appendNewOption="false">

                                            <template v-slot:singlelabel="{ value }">

                                                <div class="multiselect-single-label">
                                                    {{ value.name ? value.name : value }} ({{ value.phone_number ?
                                                        value.phone_number.split('').slice(-4).join('') : null }})
                                                </div>
                                            </template>
                                        </MultiSelect>
                                        <InputError class="mt-2" :message="form.errors.name" />

                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="my-3">
                                        <label for="number_adult" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Số người lớn *</label>
                                       

                                        <MazInputNumber v-model="form.number_adult" placeholder="Enter number" :min="1"
                                            :max="10000" size="md" color="secondary" style="width: 200px;" />
                                        <InputError class="mt-2" :message="form.errors.number_adult" />
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="my-3">

                            <div class="grid grid-cols-2 gap-4">
                                <div>


                                    <div class="my-3">
                                        <label for="date_of_birth"
                                            class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Thời gian </label>
                                        <div class="relative w-full">
                                            <VueDatePicker v-model="form.date_time"  />
                                        </div>
                                        <InputError class="mt-2" :message="form.errors.date_time" />
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
                        <div class="mb-3" v-if="user">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Theo gói</label>
                            <select id="countries" v-model="form.product_service_owner_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option v-for="(service, index) in user.product_service_owners" :key="index"
                                    :value="service.id">{{
                                        service.product.name }} ({{ service.trees.length > 0 ? service.trees[0].name : null }})
                                </option>

                              
                            </select>
                            <InputError class="mt-2" :message="form.errors.product_service_owner_id" />
                        </div>


                    </div>
                    <div class="min-[320px]:mx-0 md:mx-5">

                        <div class="my-3">
                            <BaseButton color="info" @click="save()"
                                class="bg-[#F78F43] text-white p-2 w-full text-center justify-center rounded-lg"
                                :icon="mdiContentSaveMove" small label="Tạo" />
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
<style src="@vueform/multiselect/themes/default.css"></style>











































