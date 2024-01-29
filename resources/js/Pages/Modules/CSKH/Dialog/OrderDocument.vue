<template>
    <div class="space-y-4 text-gray-700 rounded-2xl " v-click-outside="closePopover">
        <div v-if="showPopover && data"
            class="modal-dialog modal-xl rounded-2xl overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
            <div class="modal-content relative w-auto my-6 mx-auto max-w-3xl">
                <div
                    class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
                    <div class="flex items-start justify-between p-2  border-blueGray-200 rounded-t">
                        <div class="ml-2">
                            <h3 class="text-black font-base my-1 text-base">Đơn hàng #{{ data.order.order_number }}</h3>
                        </div>

                    </div>
                    <div class="modal-body  mx-1 mb-6">
                        <div class="flex items-center ">
                            <div class="mx-1 flex flex-col items-center">
                                <img src="/assets/icon/loading-svgrepo-com.png" alt="" class="w-12 h-12 p-2">
                                <p class=" text-xl"
                                    :class="isActive(['pending', 'packed', 'delivering', 'delivered']) ? 'text-[#FF0000]' : ''">
                                    Chuẩn bị</p>
                            </div>
                            <div class=" arrow mx-1  "
                                :class="isActive(['packed', 'delivering', 'delivered']) ? 'active' : ''">
                            </div>
                            <div class=" mx-1 flex flex-col items-center">
                                <img src="/assets/icon/box.png" alt="" class="w-12 h-12 p-2">
                                <p class="text-base"
                                    :class="isActive(['packed', 'delivering', 'delivered']) ? 'text-[#FF0000]' : ''">
                                    Đóng gói</p>
                            </div>
                            <div class="arrow mx-1" :class="isActive(['delivering', 'delivered']) ? 'active' : ''">
                            </div>
                            <div class="mx-1 flex flex-col items-center">
                                <img src="/assets/icon/ship.png" alt="" class="w-12 h-12 p-2">
                                <p class=" text-base"
                                    :class="isActive(['delivering', 'delivered']) ? 'text-[#FF0000]' : ''">
                                    Vận chuyển</p>
                            </div>
                            <div class="arrow mx-1" :class="isActive(['delivered']) ? 'active' : ''">
                            </div>
                            <div class="mx-1 flex flex-col items-center">
                                <img src="/assets/icon/success.png" alt="" class="w-12 h-12 p-2">
                                <p class="text-base" :class="isActive(['delivered']) ? 'text-[#FF0000]' : ''">
                                    Thành công </p>
                            </div>
                            <div class="arrow mx-1" :class="isActive(['refund']) ? 'active' : ''">
                            </div>
                            <div class="mx-1 flex flex-col items-center">
                                <img src="/assets/icon/backward.png" alt="" class="w-12 h-12 p-2">
                                <p class="text-base" :class="isActive(['refund']) ? 'text-[#FF0000]' : ''">
                                    Hoàn
                                </p>
                            </div>
                        </div>
                        <div class="w-full flex  items-center mx-auto mt-3 ">
                            <img src="/assets/icon/note.png" alt="" class="w-12 h-12 p-2">
                            <p class="text-[#000000] text-base">Ghi chú: {{ data.order.note }}
                            </p>
                        </div>

                        <div class="w-full flex items-center mx-auto ">
                            <img src="/assets/icon/book.png" alt="" class="w-12 h-12 p-2">
                            <p class="text-[#000000] text-base ">Đ/c nhận hàng: {{ data.order.customer.address
                                + ' ,' + data.order.customer.wards + ' ,' + data.order.customer.district + ' ,'
                                + data.order.customer.city }}
                            </p>
                        </div>
                        <div class="w-full flex items-center mx-auto justify-between ">
                            <div class="flex items-center">
                                <img src="/assets/icon/phone.png" alt="" class="w-12 h-12 p-2">
                                <p class="text-[#000000] text-base">SĐT nhận hàng:
                                    {{ hasAnyPermission(['super-admin']) ? data.order.customer?.phone_number :
                                        hidePhoneNumber(data?.customer?.phone_number) }}
                                </p>
                            </div>
                            <div class="flex items-center">
                                <img src="/assets/icon/phone.png" alt="" class="w-12 h-12 p-2">
                                <p class="text-[#000000] text-base">SĐT phụ: {{ hasAnyPermission(['super-admin']) ?
                                    data.order.customer?.phone_number :
                                    hidePhoneNumber(data.order.customer?.phone_number) }}</p>
                            </div>

                        </div>
                        <div class="w-full flex items-center mx-auto mt-4">
                            <Icon icon="card-user" />
                            <p class="text-[#000000] text-base ml-2">Hồ sơ giao hàng
                            </p>

                            <div class="flex  w-full">

                                <UploadImages v-if="data" :disabled="disabled" :multiple="true"
                                    :old_images="data.order.order_shipper_images"
                                    :url="`/admin/cskh/${data.id}/uploadImages`" class="w-30 justify-start" />
                                <InputError class="mt-2" :message="form.errors.images" />
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="submit" v-if="data.order.state_document == 'not_approved'"
                            @click="confirmStateDocument"
                            class="inline-flex px-2 py-3 bg-[#4F8D06] text-white font-black text-sm leading-tight uppercase rounded shadow-md hoverbg-[#4F8D06] hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out">
                            <Icon icon="flip" />Duyệt
                            hồ sơ
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { emitter } from '@/composable/useEmitter';
import { computed, ref, inject, onMounted, onUnmounted } from "vue";
import { usePopOverStore } from '@/stores/popover.js'
import { storeToRefs } from 'pinia'
import { useForm, router } from "@inertiajs/vue3";
import Icon from '@/Components/Icon.vue'
import UploadImages from '@/Components/UploadImages.vue';
const form = useForm({
    images: null
})
const { data,

    showPopover,
} = storeToRefs(usePopOverStore())
const { openPopover,
    closePopover } = usePopOverStore();
const isActive = (status) => {
    if (data.value && status.includes(data.value.state)) {
        return true
    }
    return false
}

const disabled = computed(() => {
    if (data.value.order.state_document == 'approved' || data.value.state == 'decline') {
        return true
    }
    return false
})
const confirmStateDocument = () => {
    let query = {
        ids: [data.value.id],
    };

    router.post(route("admin.cskh.confirm-document"), query, {
        onError: () => { },
        onSuccess: () => {
            form.reset();
        },
    });

}


</script>

<style  scoped>
.arrow {
    position: relative;
    height: 2px;
    width: 60px;

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

.active {
    background-color: #FF6100;

}

.active:after {
    background-color: #FF6100;
}

.active:before {
    background-color: #FF6100;
}
</style>
