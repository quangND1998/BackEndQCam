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
            <Link  :href="route('visit.pending')" @click.prevent
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url === '/visit/pending' }">
            Đơn chờ
            <span class="text-gray-400 ml-1">1</span>
            </Link>
            <Link  :href="route('visit.confirm')" @click.prevent
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url === '/visit/confirm' }">
            Xác nhận đặt lịch
            <span class="text-gray-400 ml-1">1</span>
            </Link>
            <Link  :href="route('visit.completed')"
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url === '/visit/completed' }">
            Lịch kết thúc
            <span class="text-gray-400 ml-1">1</span>
            </Link>
            <Link  :href="route('visit.cancel')"
                class="min-[320px]:my-2 text-sm px-3 py-2 border rounded-lg mx-1 bg-gray-100 hover:bg-white text-gray-500"
                :class="{ 'bg-white  text-blue-500': $page.url === '/visit/cancel' }">
            Lịch Hủy
            <span class="text-gray-400 ml-1">1</span>
            </Link>
        </div>
        <div>

        </div>
    </div>
</template>

