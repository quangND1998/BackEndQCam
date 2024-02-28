<template>
    <div class="modal fade rounded" id="ModelBookinActive" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog rounded" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="flex">
                        <div class="ml-2">
                            <h3 class="text-black font-bold my-1 text-base">Xác nhận khách hàng checkin</h3>

                        </div>
                    </div>


                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="orderCancel">

                        <div class="form-group" :class="form.errors.note ? 'has-error' : ''">

                            <label for="message" class="  block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ghi chú</label>
                            <textarea v-model="form.note" id="message" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                ></textarea>

                        </div>
                        <div class="text-red-500" v-if="form.errors.note">{{ form.errors.note }}</div>

                        <div class="modal-footer">
                            <button type="submit" @click.prevent="changeState()"
                                class="inline-block px-3 py-2 bg-[#4F8D06] text-white rounded-[30px]">Xác
                                nhận</button>
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

const props = defineProps({

});
const form = useForm({
    id: null,
    note: null
})
onMounted(() => {
    emitter.on('OpenModelBookinActive', (visit) => {
        // console.log(order)

        form.id = visit.id
        $("#ModelBookinActive").modal("show");
    })
});
const changeState = () => {
    form.post(route("visit.changeStateToConfirm", form.id), {
        onFinish: () => {
            form.reset();
            $("#ModelBookinActive").modal("hide");
        },
    });
}
const listener = () => {
}

</script>

<style></style>
