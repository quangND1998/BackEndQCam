<script setup>
import { computed, ref, inject, watch, toRef } from 'vue'
import LayoutProfileDetail from '@/Layouts/LayoutProfileDetail.vue';
import { useForm } from '@inertiajs/vue3';
import SectionMain from '@/Components/SectionMain.vue'
import { Head } from '@inertiajs/vue3'
import CardBoxModal from '@/Components/CardBoxModal.vue'
import { mdiReceiptText, mdiPencilOutline, mdiPlus, mdiTrashCan } from '@mdi/js'
import BaseButton from '@/Components/BaseButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue';
const props = defineProps({
    customer: Object
});
const swal = inject('$swal')
const form = useForm({
    id: props.customer.id,
    name: props.customer.name,
    username: props.customer.username,
    phone_number: props.customer.phone_number,
    email: props.customer.email,
    password: null


});
const isModalActive = ref(false)
const editMode = ref(false)
const edit = (customer) => {
    isModalActive.value = true
    editMode.value = true
    form.id = customer.id;
    form.name = customer.name;
    form.username = customer.username;
    form.phone_number = customer.phone_number;
    form.email = customer.email;
    form.password = customer.password;

}
const crumbs = ref([

    {
        route: "customer.detail.info",
        parma: props.customer.id,
        name: props.customer.name
    },
    {
        route: "customer.detail.info",
        parma: props.customer.id,
        name: "Amenities"
    }
])
const save = () => {

    form.put(route("customer.update", form.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true;
            },
            onSuccess: () => {
                form_reset();
                isModalActive.value = false;
                editMode.value = false;
            },
    });


    // form.id = permission.id;
    // form.name = permission.name
};
</script>

<template>
    <LayoutProfileDetail :customer="customer" :crumbs="crumbs">

        <Head title="Amenities" />
        <SectionMain>
            <div class="p-6 flex-auto lg:w-2/3 sm:w-full">
                <div class="flex flex-wrap  -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <InputLabel for="name" value="Name" />
                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full"
                            :class="form.errors.name ? 'border-red-500' : ''" required autofocus autocomplete="name" />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <InputLabel for="email" value="Email" />
                        <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full"
                            :class="form.errors.email ? 'border-red-500' : ''" autocomplete="name" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3">
                        <InputLabel for="username" value="Username" />
                        <TextInput id="username" v-model="form.username" type="text" class="mt-1 block w-full"
                            :class="form.errors.username ? 'border-red-500' : ''" autocomplete="username" />
                        <InputError class="mt-2" :message="form.errors.username" />
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <InputLabel for="name" value="Password" />

                        <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full"
                            :class="form.errors.password ? 'border-red-500' : ''" autocomplete="name" />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3">
                        <InputLabel for="phone_number" value="Phone" />
                        <TextInput id="phone_number" v-model="form.phone_number" type="text" class="mt-1 block w-full"
                            :class="form.errors.phone_number ? 'border-red-500' : ''" autocomplete="phone_number" />
                        <InputError class="mt-2" :message="form.errors.phone_number" />
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 hover:bg-color_green" :icon="mdiPlus" small
                        @click="save()" label="Save" />
                </div>
            </div>

        </SectionMain>
    </LayoutProfileDetail>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
