<script setup>
import { computed, ref, inject } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import PillTag from '@/Components/PillTag.vue'
import CardBoxModalFull from "@/Components/CardBoxModalFull.vue";
import {
    mdiEye,
    mdiAccountLockOpen,
    mdiPlus,
    mdiFilter,
    mdiMagnify,
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import UploadImage from '@/Components/UploadImage.vue'
// import Multiselect from '@vueform/multiselect'


import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
defineProps({
    news: Object,
});
const searchVal = ref("");
const swal = inject("$swal");
const tintuc = ref(null);
const form = useForm({
    id: null,
    title: null,
    state: null,
    type: null,
    short_description: null,
    description: null,
    images:null,
});
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);
// const value = ref([]);
// const options = ref([
//     'Batman',
//     'Robin',
//     'Joker',
// ])
const editor = ref()
const reset = () => {
    editMode.value = false;

    form.id = null;
    form.title = null;
    form.state = null;
    form.type = null;
    form.short_description = null;
    form.description = null;
    editor.value.setHTML(null)
};

const edit = (new_data) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = new_data.id;
    form.title = new_data.title;
    form.state = new_data.state;
    form.type = new_data.type;
    form.short_description = new_data.short_description;
    form.description = new_data.description;
    tintuc.value=new_data
};

const save = () => {

    if (editMode.value == true) {
        form.post(route("new.update", form.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true;
            },
            onSuccess: () => {
                form.reset("id","type","title","short_description","description","state");
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    } else {
        form.post(route("news.store"), {
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

        <Head title="News" />
        <!-- <Multiselect v-model="value" :options="options" mode="tags" :close-on-select="false" :searchable="true"
            :create-option="true" /> -->
        <SectionMain>
            <SectionTitleLineWithButton title="news" main></SectionTitleLineWithButton>
            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 m-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" placeholder="Search" aria-label="Search" size="24" />
                    </div>
                </div>
                <div class="right">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 " :icon="mdiPlus" small
                        @click="
                            isModalActive = true;
                            editMode = false;
                        form.reset();
                        reset();
                        " label="Create Land" />
                </div>
            </div>
            <CardBoxModalFull v-model="isModalActive" buttonLabel="Save" hasSave has-cancel @confirm="save"
                :title="editMode ? 'Sửa bài viết ' : 'Tạo mới'">
                <InputLabel for="title" value="Tiêu đề" />
                <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required autofocus
                    autocomplete="title" />
                <InputError class="mt-2" :message="form.errors.title" />

                <div class="flex w-full">
                    <div class="w-1/2 mr-4">
                        <InputLabel for="owner" value="Trạng thái" />
                        <select id="category_project_id" v-model="form.state"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="public">public</option>
                            <option value="private">private</option>
                        </select>
                    </div>
                    <div class="w-1/2">
                        <InputLabel for="owner" value="Loại bài viết" />
                        <select id="category_project_id" v-model="form.type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="news">Tin tức trang trại</option>
                            <option value="activity">Các hoạt động trang trại</option>
                        </select>
                    </div>

                </div>



                <InputLabel for="name" value="Mô tả ngắn (Tối đa 220 kí tự)" />
                <label class="input w-full" for="recipient-name">

                    <TextInput id="short_description" v-model="form.short_description" type="text" class="mt-1 block w-full" required autofocus
                    autocomplete="short_description" maxlength="220" />
                    <!-- <quill-editor v-model:content="form.short_description" contentType="html"></quill-editor> -->

                </label>
                <InputLabel for="name" value="Chi tiết bài viết" />
                <label class="input w-full h-80" for="recipient-name">

                    <quill-editor ref="editor" v-model:content="form.description" content-type="html"></quill-editor>

                </label>

                <InputLabel for="image" value="Image" />
                <div class="flex  w-full">
                    <UploadImage v-if="editMode ==false" :max_files="1" v-model="form.images" :multiple="true" class="w-30 justify-start" />
                    <UploadImage v-else :max_files="1" v-model="form.images" :multiple="false" :old_images="tintuc?.images" class="w-30 justify-start" />
                    <InputError class="mt-2" :message="form.errors.images" />
                </div>
            </CardBoxModalFull>
            <!-- End Modal -->
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg mt-5">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6 text-xs">STT</th>
                            <th scope="col" class="py-3 px-6 text-xs">Tiêu đề</th>
                            <th scope="col" class="py-3 px-6 text-xs">Ảnh</th>
                            <th scope="col" class="py-3 px-6 text-xs">Loại</th>
                            <th scope="col" class="py-3 px-6 text-xs">Mô tả</th>
                            <th scope="col" class="py-3 px-6 text-xs">Trạng thái</th>
                            <th scope="col" class="py-3 px-6 text-xs">
                                <span class="sr-only">Chỉnh sửa</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(new_data, index) in news.data" :key="index"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="py-4 px-6 text-[14px] text-gray-900 whitespace-nowrap dark:text-white">
                                {{ index + 1 }}
                            </th>
                            <th scope="row" class="py-4 px-6 text-[14px] text-gray-900 whitespace-nowrap dark:text-white">
                                <a class="text-blue-600" :href="new_data.link">{{ new_data.title }}</a>
                            </th>
                            <th scope="row" class="py-4 px-6 text-[14px] text-gray-900 whitespace-nowrap dark:text-white">
                               <img :src="new_data.images.length > 0 ? new_data.images[0].original_url : null" class="w-16" alt="">
                            </th>
                            <th scope="row" class="py-4 px-6 text-[14px] text-gray-900 whitespace-nowrap dark:text-white">
                                {{ new_data.type }}
                            </th>
                            <th scope="row" class="py-4 px-6 text-[14px] text-gray-900 whitespace-nowrap dark:text-white ">
                                <p class="string_long">{{ new_data.short_description }}</p>
                            </th>
                            <th scope="row" class="py-4 px-6 text-[14px] text-gray-900 whitespace-nowrap dark:text-white ">
                                <PillTag :color="new_data.state == 'public' ? 'success' : 'danger'" :label="new_data.state"
                                    small>
                                </PillTag>
                            </th>
                            <td class="py-4 px-6 text-right">
                                <button @click="edit(new_data)" type="button" data-toggle="modal"
                                    data-target="#exampleModal"
                                    class="inline-block px-6 py-2.5 bg-gray-200 text-gray-700 font-black text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out mx-2">
                                    Edit
                                </button>
                                <button type="button" @click="Delete(new_data.id)"
                                    class="inline-block px-6 py-2.5 bg-red-500 text-white font-black text-xs leading-tight uppercase rounded shadow-md hover:bg-red-600 hover:text-white hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :links="news.links" />
        </SectionMain>
    </LayoutAuthenticated>
</template>
<style scoped src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
.string_long {
    width: 200px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
</style>
