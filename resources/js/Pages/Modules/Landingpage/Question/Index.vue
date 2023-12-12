<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm , Head, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";
import PillTag from '@/Components/PillTag.vue'
import CardBoxModal from "@/Components/CardBoxModal.vue";
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



import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
defineProps({
    question_answers: Object,
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    status: null,
    question: null,
    answer: null,
    status: null,
    type: null,
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

const edit = (new_data) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = new_data.id;
    form.question = new_data.question;
    form.answer = new_data.answer;
    form.type = new_data.type;
    form.status = new_data.status;
};

const filter= reactive({
    search:null,
    type:null,
    status:null
})
const save = () => {
    console.log(form);
    if (editMode.value == true) {
        form.put(route("admin.FAQs.update", form.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true;
            },
            onSuccess: () => {
                form.reset();
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    } else {
        form.post(route("admin.FAQs.store"), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = false;
            },
            onSuccess: () => {
                form.reset();
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
                form.delete(route("admin.FAQs.delete", id), {
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

const seachFilter =()=>{
    let query = {
        search: searchVal.value,
        type: filter.type,
        status: filter.status
    };
    router.get(route("admin.FAQs.index"), query, {
        preserveState: true
        // only: ["image360s", "errors", 'flash'],
    });
}
</script>
<template>
    <LayoutAuthenticated>

        <Head title="FAQs" />
        <!-- <Multiselect v-model="value" :options="options" mode="tags" :close-on-select="false" :searchable="true"
            :create-option="true" /> -->
        <SectionMain>
            <SectionTitleLineWithButton title="FAQs" main></SectionTitleLineWithButton>

            <!-- Modal -->

            <div class="flex justify-between">
                <div class="left">
                    <div class="flex content-center items-center">
                        <BaseButton color="default" :icon="mdiFilter" small class="p-2 m-2 bg-white" :iconSize="20" />
                        <SearchInput v-model="searchVal" placeholder="Search" aria-label="Search" size="24"  @keyup="seachFilter"/>
                    </div>
                    <div class="min-[320px]:block sm:flex my-3">
                            <div class="min-[320px]:w-full sm:w-5/12 mr-3 text-gray-500">
                                <label for>Loại câu hỏi</label>
                            </div>
                            <div class="min-[320px]:w-full sm:w-9/12">
                                <select id="countries"   v-model="filter.type" @change="seachFilter"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                    <option selected :value="null">Tất cả</option>
                                    <option value="product">Sản phẩm</option>
                                    <option value="service">Dịch vụ</option>
                                    <option value="farm">Nông trại</option>

                                </select>
                            </div>
                        </div>
                        <div class="min-[320px]:block sm:flex my-3">
                            <div class="min-[320px]:w-full sm:w-5/12 mr-3 text-gray-500">
                                <label for>Trạng thái</label>
                            </div>
                            <div class="min-[320px]:w-full sm:w-9/12">
                                <select id="staus"    v-model="filter.status"  @change="seachFilter"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2  border-gray-600 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500">
                                    <option selected :value="null">Tất cả</option>

                                    <option :value="1">Kích hoạt</option>
                                    <option :value="0">Không kích hoạt</option>

                                </select>
                            </div>
                        </div>
                </div>

                <div class="right">
                    <BaseButton color="info" class="bg-btn_green text-white p-2 " :icon="mdiPlus" small @click="
                        isModalActive = true;
                    editMode = false;
                    form.reset();
                    " label="Tạo mới câu hỏi" />
                </div>
            </div>
            <CardBoxModal v-model="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                :title="editMode ? 'Sửa Câu hỏi ' : 'Tạo mới'">
                <InputLabel for="owner" value="Loại câu hỏi" />

                <select id="category_project_id" v-model="form.type"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="product">Sản phẩm</option>
                    <option value="service">Dịch vụ</option>
                    <option value="farm">Nông trại</option>
                </select>
                <InputError class="mt-2" :message="form.errors.type" />
                <InputLabel for="owner" value="Trạng thái" />

                <select id="category_project_id" v-model="form.status"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option :value="1">Kích hoạt</option>
                    <option :value="0">Không kích hoạt</option>

                </select>
                <InputError class="mt-2" :message="form.errors.status" />

                <InputLabel for="title" value="Câu hỏi" />
                <TextInput id="title" v-model="form.question" type="text" class="mt-1 block w-full" required autofocus
                    autocomplete="title" />
                <InputError class="mt-2" :message="form.errors.question" />

                <InputLabel for="name" value="Câu trả lời" />
                <label class="input w-full" for="recipient-name">

                    <quill-editor v-model:content="form.answer" contentType="html"></quill-editor>
                    <InputError class="mt-2" :message="form.errors.answer" />
                </label>


            </CardBoxModal>
            <!-- End Modal -->
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg mt-5">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6 text-xs">STT</th>
                            <th scope="col" class="py-3 px-6 text-xs">Câu hỏi</th>
                            <th scope="col" class="py-3 px-6 text-xs">Type</th>
                            <th scope="col" class="py-3 px-6 text-xs">Câu trả lời</th>
                            <th scope="col" class="py-3 px-6 text-xs">Trạng thái</th>
                            <th scope="col" class="py-3 px-6 text-xs">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(question, index) in question_answers.data" :key="index"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="py-4 px-6 text-[14px] text-gray-900 whitespace-nowrap dark:text-white">
                                {{ index + 1 }}
                            </th>
                            <th scope="row" class="py-4 px-6 text-[14px] text-gray-900 whitespace-nowrap dark:text-white">
                                {{ question.question }}
                            </th>
                            <th scope="row" class="py-4 px-6 text-[14px] text-gray-900 whitespace-nowrap dark:text-white">
                                <!-- {{ question.type == 'product' ? 'Sản phẩm' :  question.type =='service' ?  'Dịch vụ' : question.type =='farm'? 'Nông trại': }} -->
                                <PillTag color="primary" small :label="question.type == 'product' ? 'Sản phẩm' :  question.type =='service' ?  'Dịch vụ' : question.type =='farm'? 'Nông trại': ''" >
                                </PillTag>
                            </th>
                            <th scope="row" class="py-4 px-6 text-[14px] text-gray-900 whitespace-nowrap dark:text-white ">
                                <p class="string_long" v-html="question.answer"></p>
                            </th>
                            <th scope="row" class="py-4 px-6 text-[14px] text-gray-900 whitespace-nowrap dark:text-white ">
                                <PillTag :color="question.status == '1' ? 'success' : 'danger'" :label="question.status ==1 ? `Kích hoạt`: `Không kích hoạt`" small>
                                </PillTag>
                            </th>
                            <td class="py-4 px-6 text-right">
                                <button @click="edit(question)" type="button" data-toggle="modal"
                                    data-target="#exampleModal"
                                    class="inline-block px-6 py-2.5 bg-gray-200 text-gray-700 font-black text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out mx-2">
                                    Edit
                                </button>
                                <button type="button" @click="Delete(question.id)"
                                    class="inline-block px-6 py-2.5 bg-red-500 text-white font-black text-xs leading-tight uppercase rounded shadow-md hover:bg-red-600 hover:text-white hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :links="question_answers.links" />
        </SectionMain>
    </LayoutAuthenticated>
</template>
<<<<<<< HEAD
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
=======
<style scope>
>>>>>>> origin/quangnd
.string_long {
    width: 200px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
</style>
