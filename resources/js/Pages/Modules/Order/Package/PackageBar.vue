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
        <div class="min-[320px]:grid min-[320px]:justify-between sm:justify-start md:justify-start lg:justify-start sm:flex md:flex lg:flex">
            <!-- <Link v-if="hasAnyPermission(['order-shipping'])" :href="route('admin.orders.package.all')"
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url === '/admin/orders/package/all' }">
            Tất cả
            <span class="text-gray-400 ml-1">({{ totalOrder('all') }})</span>
            </Link> -->
            <Link v-if="hasAnyPermission(['order-shipping'])" :href="route('admin.orders.package.complete')"
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url === '/admin/orders/package/complete' }">
            Đã duyệt
            <span class="text-gray-400 ml-1">({{ totalOrder('complete') }})</span>
            </Link>
            <Link v-if="hasAnyPermission(['order-pending'])" :href="route('admin.orders.package.index')" @click.prevent
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mr-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url === '/admin/orders/package/all' }">
            Chờ duyệt
            <span class="text-gray-400 ml-1">({{ totalOrder('pending') }})</span>
            </Link>
            <Link v-if="hasAnyPermission(['order-pending'])" :href="route('admin.orders.package.partiallyPaid')"
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url === '/admin/orders/package/partiallyPaid' }">
                Thanh toán 1 phần
            <span class="text-gray-400 ml-1">({{ totalOrder('partiallyPaid') }})</span>
            </Link>
            <Link v-if="hasAnyPermission(['order-pending'])" :href="route('admin.orders.package.draf')"
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url === '/admin/orders/package/draf' }">
                Đơn nháp
            <span class="text-gray-400 ml-1">({{ totalOrder('draf') }})</span>
            </Link>
            <Link v-if="hasAnyPermission(['order-packing'])" :href="route('admin.orders.package.decline')" @click.prevent
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url === '/admin/orders/package/decline' }">
            Đã hủy
            <span class="text-gray-400 ml-1">({{ totalOrder('decline') }})</span>
            </Link>


        </div>
        <div>

        </div>
    </div>
</template>

