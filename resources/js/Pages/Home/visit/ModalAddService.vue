<template>
    <div class="modal fade" id="modelAddService" tabindex="-1" role="dialog" aria-labelledby="modelAddService"
        aria-hidden="true">
        <div class="modal-dialog modal-xl rounded-2xl mx-auto mt-10 shadow-lg max-h-modal w-8/12   md:w-9/12 lg:w-8/12 xl:w-5/12 z-50 " role="document">
            <div class="modal-content">
                <div class="modal-header bg-[#B7AD75]">
                    <div class="flex">
                        <div class="ml-2">
                            <h3 class="text-black font-bold my-1 text-base">Thêm dịch vụ booking</h3>
                        </div>
                    </div>


                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <form @submit.prevent="addService">

                        <div class="form-group" :class="form.errors.name ? 'has-error' : ''">

                            <label for="message" class="  block mb-2 text-sm font-medium text-gray-900 dark:text-white">Thêm dịch vụ</label>

                            <div class="w-full flex ">
                                <input v-model="form.name" id="message" rows="4"
                                class="block py-2.5 px-4 w-3/5 mr-[40px] text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 "/>
                                <button type="submit" @click.prevent="addService()"
                                class="inline-block px-3 justify-center py-2 bg-[#1D75FA] text-white font-black text-sm leading-tight  focus:outline-none focus:ring-0 transition duration-150 ease-in-out">Thêm dịch vụ</button>
                            </div>

                        </div>
                        <div class="text-red-500" v-if="form.errors.name">{{ form.errors.service }}</div>

                        <div class="history">
                            <p class="my-2 mt-5 font-semibold">Lịch sử chăm sóc</p>
                            <table class="table  w-full text-sm text-left text-gray-500 bg-white ">
                                <thead class="text-xs justify-between text-gray-700 uppercase border">
                                    <tr class="">
                                        <th class="border border-[#000000]">STT</th>
                                        <th class="border border-[#000000]">Hoạt động</th>
                                        <th class="border border-[#000000]">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="justify-between  bg-white border"
                                        v-for="(service, index) in form?.serviceExtra" :key="service.id">
                                        <td class="border border-[#000000]">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="border border-[#000000]">
                                            {{ service.name }}
                                        </td>
                                        <td class="border border-[#000000]">
                                            <BaseButton color="gray"
                                                class=" text=gray=300 hover:text-black" :icon="mdiTrashCanOutline" small
                                                @click="deleteService(service.id)" />
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
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

import { useForm } from "@inertiajs/vue3";
import {
    mdiSquareEditOutline, mdiDeleteOutline, mdiCashMultiple, mdiEyeOutline, mdiCheckCircle, mdiCheckAll, mdiTrashCanOutline, mdiCheck
    , mdiOpenInNew, mdiRefresh, mdiBellRingOutline
} from '@mdi/js'
const swal = inject("$swal");
const props = defineProps({
    serviceExtra: Object
});
const form = useForm({
    name: null,
    serviceExtra: props.serviceExtra
})
onMounted(() => {
    emitter.on('OpenModalAddService', (order) => {
        console.log('open model');
        $("#modelAddService").modal("show");
    })
});
const addService = () => {
    // form.post(route("visit.extraService.createService"), {
    //     preserveState: true,
    //     onError: errors => {
    //         if (Object.keys(errors).length > 0) {

    //         }
    //     },
    //     onSuccess: page => {
    //         $("#modelAddService").modal("hide");
    //         // emitter.off('OpenModalDecline', listener)
    //         form.reset();
    //     }
    // });
    axios.post(`/visit/extraService/createService`, form, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            })
            .then(response => {
                console.log(response);
                form.serviceExtra = response.data
                form.reset();
            })
            .catch(error => {
                console.log(error);
            });
}
const deleteService = (id) => {
    swal
        .fire({
            title: "Are you sure?",
            text: "Delete this History Payment!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                // form.post(route("admin.orders.package.deleteHistoryPayment", [form.order?.id, id]), { preserveState: false }, {
                //     onSuccess: () => {
                //         swal.fire(
                //             "Deleted!",
                //             "Your tree has been deleted.",
                //             "success"
                //         );
                //     },
                // });
            }
        });
}
const listener = () => {
}
onUnmounted(() => {
    emitter.off('OpenModalAddService', listener)
})
</script>

<style></style>
