<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    statusGroup: Array
})
const totalOrder = (status) => {
    var findStatus = props.statusGroup.find(e => e.status == status);
    if (findStatus) {
        return findStatus.total;
    } else {
        return 0;
    }
}

const handleNativeClick =()=>{
    console.log("Native click event");
    router.post(route("admin.orders.pending"), query, {
        // preserveState: false

    });
}
</script>
<template>
    <div class="my-3">
        <div
            class="min-[320px]:grid min-[320px]:justify-between sm:justify-start md:justify-start lg:justify-start sm:flex md:flex lg:flex">


            <Link v-if="hasAnyPermission(['order-pending'])" :href="route('admin.orders.pending')" @click.prevent
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url.includes('/admin/orders/pending') }">
            Đơn chờ
            <span class="text-gray-400 ml-1">({{ totalOrder('pending') }})</span>
            </Link>
            <Link v-if="hasAnyPermission(['order-packing'])" :href="route('admin.orders.packing')" @click.prevent
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url.includes('/admin/orders/packing') }">
            Đóng gói
            <span class="text-gray-400 ml-1">({{ totalOrder('packing') }})</span>
            </Link>
            <Link v-if="hasAnyPermission(['order-shipping'])" :href="route('admin.orders.shipping')"
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url.includes('/admin/orders/shipping') }">
            Đang vân chuyển
            <span class="text-gray-400 ml-1">({{ totalOrder('shipping') }})</span>
            </Link>

            <Link v-if="hasAnyPermission(['order-completed'])" :href="route('admin.orders.completed')"
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url.includes( '/admin/orders/completed') }">
            Giao thành công
            <span class="text-gray-400 ml-1">({{ totalOrder('completed') }})</span>
            </Link>
            <Link v-if="hasAnyPermission(['order-refund'])" :href="route('admin.orders.refund')"
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url.includes( '/admin/orders/refund') }">
            Hoàn đơn
            <span class="text-gray-400 ml-1">({{ totalOrder('refund') }})</span>
            </Link>
            <Link v-if="hasAnyPermission(['order-decline'])" :href="route('admin.orders.decline')"
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white text-blue-500': $page.url.includes('/admin/orders/decline') }">
            Đơn hủy
            <span class="text-gray-400 ml-1">({{ totalOrder('decline') }})</span>
            </Link>
        </div>
        <div>

        </div>
    </div>
</template>

