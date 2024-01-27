<script setup>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import {

    mdiTruckRemove
} from "@mdi/js";

import BaseIcon from '@/Components/BaseIcon.vue'
const props = defineProps({
    statusGroup: Array,
    count_orders: Number
})
const totalOrder = (status) => {
    var findStatus = props.statusGroup.find(e => e.status == status);
    console.log(props.statusGroup)
    if (findStatus) {
        return findStatus.total;
    } else {
        return 0;
    }
}

</script>
<template>
    <div class="my-3">
  
        <div
            class="min-[320px]:grid min-[320px]:justify-between sm:justify-start md:justify-start lg:justify-start sm:flex md:flex lg:flex">
            <Link v-if="hasAnyPermission(['order-pending'])" :href="route('admin.cskh.all')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': $page.url.includes('/admin/cskh/all') }">
            <Icon icon="queue"></Icon>

            <span class="text-gray-400 ml-1"> Tất cả({{ count_orders }})</span>
            </Link>

            <Link v-if="hasAnyPermission(['order-pending'])" :href="route('admin.cskh.pending')" @click.prevent
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': $page.url.includes('/admin/cskh/pending') }">
            <Icon icon="loading"></Icon>


            <span class="text-gray-400 ml-2"> Đơn chờ({{ totalOrder('pending') }})</span>
            </Link>

            <Link v-if="hasAnyPermission(['order-packing'])" :href="route('admin.cskh.packing')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': $page.url.includes('/admin/cskh/packing') }">
            <Icon icon="packing"></Icon>
            <span class="text-gray-400 ml-2">Đóng gói ({{ totalOrder('packing') }})</span>
            </Link>

            <!-- <Link v-if="hasAnyPermission(['order-packing'])" :href="route('admin.cskh.notShipperReceive')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': $page.url.includes('/admin/cskh/notShipperReceive') }">

            <BaseIcon :path="mdiTruckRemove" class=" text-[#1D75FA] hover:text-blue-700" size="22">
            </BaseIcon>
            <span class="text-gray-400 ml-1"> Shipper không nhận ({{ totalOrder('not_shipper_receive') }})</span>
            </Link> -->

            <Link v-if="hasAnyPermission(['order-shipping'])" :href="route('admin.cskh.shipping')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': $page.url.includes('/admin/cskh/shipping') }">
            <Icon icon="rocket-ship"></Icon>
            <span class="text-gray-400 ml-2"> Đang vận chuyển({{ totalOrder('shipping') }})</span>
            </Link>
            <Link v-if="hasAnyPermission(['order-completed'])" :href="route('admin.cskh.completed')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': $page.url.includes('/admin/cskh/completed') }">
            <Icon icon="check-green"></Icon>

            <span class="text-gray-400 ml-2"> Đã giao ({{ totalOrder('completed') }})</span>
            </Link>
            <Link v-if="hasAnyPermission(['order-refund'])" :href="route('admin.cskh.refunding')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': $page.component == 'Modules/CSKH/Refunding' }">

            <Icon icon="back" color="#AEAEAE"></Icon>
            <span class="text-gray-400 ml-2"> Chờ hoàn ({{ totalOrder('refunding') }})</span>
            </Link>
            <Link v-if="hasAnyPermission(['order-refund'])" :href="route('admin.cskh.refund')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white  text-blue-500': $page.component == 'Modules/CSKH/Refund' }">

            <Icon icon="back"></Icon>
            <span class="text-gray-400 ml-2"> Hoàn đơn ({{ totalOrder('refund') }})</span>
            </Link>
            <Link v-if="hasAnyPermission(['order-decline'])" :href="route('admin.cskh.decline')"
                class="min-[320px]:my-2 text-sm px-3 py-2  mx-1 bg-gray-100 hover:bg-white text-gray-500 flex flex-wrap mr-2"
                :class="{ 'bg-white text-blue-500': $page.url.includes('/admin/cskh/decline') }">
            <Icon icon="cancel"></Icon>
            <span class="text-gray-400 ml-2">Hủy giao ({{ totalOrder('decline') }})</span>
            </Link>
        </div>
        <div>

        </div>
    </div>
</template>

