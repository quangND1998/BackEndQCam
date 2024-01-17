<template>
    <div class="modal fade" id="ModelShipping" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog rounded-2xl mx-auto mt-10 shadow-lg max-h-modal w-8/12  md:w-9/12 lg:w-8/12 xl:w-5/12 z-50 overflow-auto"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="flex">
                        <div class="ml-2">
                            <h3 class="text-black font-base my-1 text-base">Đơn hàng #{{ form.order?.order_number }}</h3>

                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body  mx-1 mb-6">
                    <div class="flex items-center ">
                        <div class="mx-1 flex flex-col items-center">
                            <img src="/assets/icon/loading-svgrepo-com.png" alt="" class="w-12 h-12 p-2">
                            <p class="text-[#FF0000] text-xl">Chuẩn bị</p>
                        </div>
                        <div class="arrow mx-1 "></div>
                        <div class="mx-1 flex flex-col items-center">
                            <img src="/assets/icon/box.png" alt="" class="w-12 h-12 p-2">
                            <p class="text-[#FF0000] text-base">Đóng gói</p>
                        </div>
                        <div class="arrow mx-1"></div>
                        <div class="mx-1 flex flex-col items-center">
                            <img src="/assets/icon/ship.png" alt="" class="w-12 h-12 p-2">
                            <p class="text-[#FF0000] text-base">Vận chuyển</p>
                        </div>
                        <div class="arrow mx-1"></div>
                        <div class="mx-1 flex flex-col items-center">
                            <img src="/assets/icon/success.png" alt="" class="w-12 h-12 p-2">
                            <p class="text-[#FF0000] text-base">Thành công</p>
                        </div>
                        <div class="arrow mx-1"></div>
                        <div class="mx-1 flex flex-col items-center">
                            <img src="/assets/icon/backward.png" alt="" class="w-12 h-12 p-2">
                            <p class="text-[#FF0000] text-base">Hoàn</p>
                        </div>
                    </div>
                    <div class="w-full flex items-center mx-auto mt-3 ">
                        <img src="/assets/icon/note.png" alt="" class="w-12 h-12 p-2">
                        <p class="text-[#000000] text-base">Ghi chú: Khách hàng nhận hàng trước 9h {{ form.order?.reason }}
                        </p>
                    </div>
                    <div class="w-full flex items-center mx-auto ">
                        <img src="/assets/icon/book.png" alt="" class="w-12 h-12 p-2">
                        <p class="text-[#000000] text-base">Đ/c nhận hàng: {{ form.order?.customer.address
                            + ' ,' + form.order?.customer.wards + ' ,' + form.order?.customer.district + ' ,'
                            + form.order?.customer.city }}
                        </p>
                    </div>
                    <div class="w-full flex items-center mx-auto justify-between ">
                        <div class="flex items-center">
                            <img src="/assets/icon/phone.png" alt="" class="w-12 h-12 p-2">
                            <p class="text-[#000000] text-base">SĐT nhận hàng:
                                {{ hasAnyPermission(['super-admin']) ? form.order?.customer?.phone_number :
                                    hidePhoneNumber(form.order?.customer?.phone_number) }}
                            </p>
                        </div>
                        <div class="flex items-center">
                            <img src="/assets/icon/phone.png" alt="" class="w-12 h-12 p-2">
                            <p class="text-[#000000] text-base">SĐT phụ: {{ hasAnyPermission(['super-admin']) ?
                                form.order?.customer?.phone_number :
                                hidePhoneNumber(form.order?.customer?.phone_number) }}</p>
                        </div>

                    </div>
                    <div class="w-full flex items-center mx-auto mt-4">
                        <img src="/assets/icon/ship2.png" alt="" class="w-12 h-12 p-2">
                        <p class="text-[#000000] text-base">Logs đơn hàng
                        </p>
                    </div>
                    <div class="w-full mx-auto border min-h-30 px-3 py-4">
                        <p v-for="(history, index) in form.order?.shipping_history" :key="index">
                            {{ formatDate(history.created_at) }} : {{ history.note }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { emitter } from '@/composable/useEmitter';
import { computed, ref, inject, onMounted, onUnmounted } from "vue";
import BaseIcon from '@/Components/BaseIcon.vue'
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
    mdiSquareEditOutline,
    mdiArrowLeftBoldCircleOutline,
    mdiLayersTripleOutline,
    mdiPhone
} from "@mdi/js";
import { useForm } from "@inertiajs/vue3";
const form = useForm({
    order: null
})
onMounted(() => {
    emitter.on('ModelShipping', (order) => {
        console.log(order)
        form.order = order;
        // $("#ModelShipping").modal("show");
    })
});

const orderRefund = () => {
    // console.log(this.form)
    form.post(route("admin.orders.orderRefund", form.id), {
        preserveState: true,
        onError: errors => {
            if (Object.keys(errors).length > 0) {

            }
        },
        onSuccess: page => {
            $("#exampleModalRefund").modal("hide");
            form.reset();
        }
    });
}
const listener = () => {
}
onUnmounted(() => {
    console.log("ngaaa");
    $("#ModelShipping").modal("hide");
    emitter.off('ModelShipping', listener)
})

</script>

<style>
.arrow {
    position: relative;
    height: 2px;
    width: 60px;
    background-color: #AEAEAE;
}

.arrow:before {
    content: "";
    position: absolute;
    top: -2px;
    left: -3px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: #AEAEAE;
}

.arrow:after {
    content: " ";
    width: 50px;
    height: 2px;
    background-color: #AEAEAE;
    position: absolute;
}
</style>
