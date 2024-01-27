<script setup>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import {

    mdiTruckRemove
} from "@mdi/js";

import BaseIcon from '@/Components/BaseIcon.vue'
import { computed } from 'vue';
const props = defineProps({
    statusGroup: Array,
    count_orders: Number,
    status: String
})
const totalOrder = (status) => {
    var findStatus = props.statusGroup.find(e => e.transport_state == status);
    console.log(props.statusGroup)
    if (findStatus) {
        return findStatus.total;
    } else {
        return 0;
    }
}

const emit = defineEmits(['change-status'])

const changeStatus = (status) => {
    emit('change-status', status)
}
</script>
<template>
    <div class="my-3">

        <div
            class="min-[320px]:grid min-[320px]:justify-between sm:justify-start md:justify-start lg:justify-start sm:flex md:flex lg:flex">
            <div v-if="hasAnyPermission(['order-pending'])" @click="changeStatus()"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': status == null }">
                <Icon icon="queue"></Icon>

                <span class="text-gray-400 ml-1"> Tất cả ({{ count_orders }})</span>
            </div>

            <div v-if="hasAnyPermission(['order-pending'])" @click="changeStatus('pending')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': status == 'pending' }">
                <Icon icon="loading"></Icon>


                <span class="text-gray-400 ml-2"> Đơn chờ({{ totalOrder('pending') }})</span>
            </div>

            <div v-if="hasAnyPermission(['order-packing'])" @click="changeStatus('packing')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': status == 'packing' }">
                <Icon icon="packing"></Icon>
                <span class="text-gray-400 ml-2">Đóng gói ({{ totalOrder('packing') }})</span>
            </div>


            <div v-if="hasAnyPermission(['order-shipping'])" @click="changeStatus('shipping')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': status == 'shipping' }">
                <Icon icon="rocket-ship"></Icon>
                <span class="text-gray-400 ml-2"> Đang vận chuyển({{ totalOrder('shipping') }})</span>
            </div>
            <div v-if="hasAnyPermission(['order-completed'])" @click="changeStatus('completed')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': status == 'completed' }">
                <Icon icon="check-green"></Icon>

                <span class="text-gray-400 ml-2"> Đã giao ({{ totalOrder('completed') }})</span>
            </div>
            <div v-if="hasAnyPermission(['order-refund'])" @click="changeStatus('refunding')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': status == 'refunding' }">

                <Icon icon="back" color="#AEAEAE"></Icon>
                <span class="text-gray-400 ml-2"> Chờ hoàn ({{ totalOrder('refunding') }})</span>
            </div>
            <div v-if="hasAnyPermission(['order-refund'])" @click="changeStatus('refund')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': status == 'refund' }">

                <Icon icon="back"></Icon>
                <span class="text-gray-400 ml-2"> Hoàn đơn ({{ totalOrder('refund') }})</span>
            </div>
            <div v-if="hasAnyPermission(['order-decline'])" @click="changeStatus('decline')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white text-blue-500': status == 'decline' }">
                <Icon icon="cancel"></Icon>
                <span class="text-gray-400 ml-2">Hủy giao ({{ totalOrder('decline') }})</span>
            </div>
        </div>
        <div>

        </div>
    </div>
</template>

