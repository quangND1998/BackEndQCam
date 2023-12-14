<script setup>
import { computed, ref, inject } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import { useForm } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head } from "@inertiajs/vue3";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import {
    mdiEye,
    mdiAccountLockOpen,
    mdiPlus,
    mdiFilter,
    mdiMagnify,
    mdiPencil
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";

import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
const props =defineProps({
    terms_condition: Object,
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    description: null,
});
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);

const edit = () => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = props.terms_condition.id;
    form.description = props.terms_condition.description;
};

const save = () => {
    console.log(form);
    if (editMode.value == true) {
        form.put(route("admin.terms.update", form.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true;
            },
            onSuccess: () => {
                form.reset("id","description","state");
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    } else {
        form.post(route("admin.terms.store"), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = false;
            },
            onSuccess: () => {
                form.reset("id","type","title","short_description","description","state");
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    }

    // form.id = permission.id;
    // form.name = permission.name
};

const Delete = (id) => {
    swal
        .fire({
            title: "Are you sure?",
            text: "Delete this new!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.delete(route("news.delete", id), {
                    onSuccess: () => {
                        swal.fire(
                            "Deleted!",
                            "Your news has been deleted.",
                            "success"
                        );
                    },
                });
            }
        });
};
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Điều khoản " />

        <SectionMain>
            <SectionTitleLineWithButton title="Điều khoản" main></SectionTitleLineWithButton>


            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">


                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 " :icon="mdiPlus" small v-if="terms_condition ==null"
                        @click="
                            isModalActive = true;
                            editMode = false;
                        form.reset();
                        " label="Điều khoản" />

<BaseButton color="info" class="bg-btn_green text-white p-2 " :icon="mdiPencil" small v-if="terms_condition"
                        @click="edit()" label="Cập nhật Điều khoản" />
                </div>
            </div>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                :title="editMode ? 'Sửa điều khoản ' : 'Tạo mới'">


                <InputLabel for="name" value="Chi tiết điều khoản" />
                <label class="input w-full" for="recipient-name">

                    <quill-editor v-model:content="form.description" contentType="html"></quill-editor>
                    <InputError class="mt-2" :message="form.errors.description" />
                </label>
            </CardBoxModal>




            <div class="relative overflow-x-auto shadow-md sm:rounded-md" v-if="terms_condition" >
                <div class="px-4 py-4" v-html="terms_condition.description">

                </div>
            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>

