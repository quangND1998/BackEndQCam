<template>
    <div class="modal fade " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog  modal-xl " role="document">
            <div class="modal-content rounded">
                <div class="modal-body mx-3 my-3">
                    <form @submit.prevent="orderCancel">
                        <div class="flex ">
                            <div class="w-1/3 mx-2">
                                <InputLabel for="ballot_code" value="Bộ phận xử lý" />
                                <Multiselect v-model="form.role_id" :searchable="true" label="name" valueProp="id"
                                    trackBy="name" placeholder="Chọn bộ phận xử lý" :options="roles" :classes="{
                                        tagsSearch: 'absolute  inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                                        container: 'relative  mx-auto w-full bg-gray-50  items-center  box-border cursor-pointer border border-gray-300 rounded bg-gray-50 text-sm  '
                                    }">
                                    <template v-slot:singlelabel="{ value }">
                                        <div class="multiselect-single-label">
                                            {{ value.name }}
                                        </div>
                                    </template>

                                    <template v-slot:option="{ option }">
                                        {{ option.name }}
                                    </template>
                                </Multiselect>
                            </div>
                            <div class="w-2/3 flex mx-2 justify-between ">
                                <div class="w-1/2 ">
                                <InputLabel for="ballot_code" value="Trạng thái" />
                                <select v-model="form.status" name="" id="" class="w-2/3 border rounded py-2.5">
                                    <option selected value="no_process">Chưa xử lý</option>
                                    <option value="pending">Đang xử lý</option>
                                    <option value="planning">Xem phương án</option>
                                    <option value="complete">Đã giải quyết</option>
                                    <option value="cancel">Hủy</option>
                                </select>
                            </div>
                            <div class="w-1/2 ">
                                <InputLabel for="ballot_code" value="Mức độ" />
                                <select v-model="form.severity" name="" id="" class="w-full border rounded py-2.5">
                                    <option value="normal">Bình thường</option>
                                    <option value="urgent">Xử lý sớm</option>
                                    <option value="critical">Nghiêm trọng</option>
                                </select>
                            </div>
                            </div>

                        </div>
                        <div v-if="form.status == 'cancel'" class="w-full my-3">
                            <div class="form-group" :class="form.errors.reason ? 'has-error' : ''">

                                <label for="message"
                                    class="  block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lý
                                    do</label>
                                <textarea v-model="form.reason" id="message" rows="4"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Lý do hủy"></textarea>

                            </div>

                            <div class="text-red-500" v-if="form.errors.reason">{{ form.errors.reason }}</div>
                        </div>
                        <div v-else class="w-full flex my-3">
                            <div class="w-1/3 mx-2">
                                <div class="form-group" :class="form.errors.solution ? 'has-error' : ''">

                                    <label for="message"
                                        class="  block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phương án xử lý</label>
                                    <textarea v-model="form.solution" id="message" rows="4"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        ></textarea>
                                </div>
                                <div class="text-red-500" v-if="form.errors.solution">{{ form.errors.solution }}</div>
                            </div>
                            <div class="w-2/3 mx-2">
                                <div class="form-group" :class="form.errors.detail ? 'has-error' : ''">

                                    <label for="message"
                                        class="  block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chi tiết xử lý</label>
                                    <textarea v-model="form.detail" id="message" rows="4"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        ></textarea>
                                </div>
                                <div class="text-red-500" v-if="form.errors.detail">{{ form.errors.detail }}</div>
                            </div>

                        </div>
                        <div class="w-full flex justify-end ">
                            <button type="submit" @click.prevent="save()"
                                class="inline-block px-3 py-2 bg-[#4F8D06] text-white font-black text-sm leading-tight rounded shadow-md transition duration-150 ease-in-out">
                               Thêm phương án</button>
                        </div>


                        <div class="modal-footer my-3 flex justify-start w-full">
                            <div v-if="complainData != null" class="w-full">
                                <div>
                                    {{ formatDate(complainData?.created_at) }} {{ complainData.user?.name }} đã thêm khiếu nại {{ complainData.severity }} về vấn đề Hợp đồng
                                </div>
                                <div>
                                    Nội dung: {{ complainData.description }}
                                </div>
                                <table class="text-center mx-auto text-sm font-light overflow-x-auto">
                                    <thead class="font-medium">
                                        <tr>
                                            <th class="px-3 py-2 text-left">STT</th>
                                            <th class="px-3 py-2 text-left">Phương án xử lý</th>
                                            <th class="px-4 py-2 text-left">Chi tiết xử lý</th>
                                            <th class="px-3 py-2 text-left">User</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-left w-full">
                                        <tr v-for="(problem_solution, index) in complainData.problem_solution" :key="index">
                                            <td class=" text-left px-3 py-2 text-gray-500">{{ index + 1 }}</td>
                                            <td class=" text-left px-3 py-2 text-gray-500">{{ problem_solution.solution }}</td>
                                            <td class=" text-left px-3 py-2 text-gray-500">{{ problem_solution.detail }}</td>
                                            <td class=" text-left px-3 py-2 text-gray-500">{{ problem_solution.role?.name }}</td>
                                        </tr>

                                    </tbody>
                                </table>



                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { emitter } from '@/composable/useEmitter';
import { computed, ref, inject, onMounted, onUnmounted } from "vue";
import Multiselect from '@vueform/multiselect'
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
const props = defineProps({
    roles: Object
});
const form = useForm({
    id: null,
    role_id: null,
    status: null,
    severity: null,
    reason: null,
    solution: null,
    detail: null
})
let complainData = null;
onMounted(() => {
    emitter.on('OpenModalHandle', (complain) => {
        console.log(complain)
        form.id = complain.id
        form.status = complain.status
        form.severity = complain.severity
        complainData = complain;
    })
});
const save = () => {
    // form.post(route("complaint.saveSolution", form.id), {
    //     preserveState: true,
    //     onError: errors => {
    //         if (Object.keys(errors).length > 0) {

    //         }
    //     },
    //     onSuccess: page => {
    //         $("#exampleModal").modal("hide");
    //         // emitter.off('OpenModalHandle', listener)
    //         form.reset();
    //     }
    // });
    axios.post(`/complaint/saveSolution/${form.id}`, form, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            })
            .then(response => {
                console.log(response);
                complainData = response.data;
                form.reset('reason','detail','solution');
            })
            .catch(error => {
                console.log(error);
            });
}
const listener = () => {
}
onUnmounted(() => {
    emitter.off('OpenModalHandle', listener)
})
</script>
<style src="@vueform/multiselect/themes/default.css"></style>


