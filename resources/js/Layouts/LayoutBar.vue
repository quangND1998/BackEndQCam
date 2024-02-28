<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    statusGroup: Array
})
const totalVisit = (status) => {
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
    <div class="my-3 w-full flex justify-end ">
        <div
            class="min-[320px]:grid min-[320px]:justify-between sm:justify-start md:justify-start lg:justify-start sm:flex md:flex lg:flex">
            <Link  :href="route('visit.all')" @click.prevent
                class="min-[320px]:my-2 text-sm px-3 py-2  hover:bg-white "
                :class="$page.url.includes('all') ? 'text-[#FF6100]' : 'text-gray-800'">
            Tất cả
            <span class=" ml-1">({{ totalVisit('all') }})</span>
            </Link>
            <Link  :href="route('visit.pending')" @click.prevent
                class="min-[320px]:my-2 text-sm px-3 py-2  hover:bg-white "
                :class="$page.url.includes('pending') ? 'text-[#FF6100]' :  'text-gray-800'">
            Đặt lịch
            <span class=" ml-1">({{ totalVisit('pending') }})</span>
            </Link>
            <Link  :href="route('visit.completed')"
                class="min-[320px]:my-2 text-sm px-3 py-2  hover:bg-white "
                :class="{ 'text-[#FF6100]': $page.url.includes('complete') }">
            Hoàn thành
            <span class=" ml-1">({{ totalVisit('complete') }})</span>
            </Link>
            <Link  :href="route('visit.cancel')"
                class="min-[320px]:my-2 text-sm px-3 py-2  hover:bg-white "
                :class="{ 'text-[#FF6100]': $page.url.includes('cancel') }">
            Hủy
            <span class=" ml-1">({{ totalVisit('cancel') }})</span>
            </Link>
        </div>
        <div>

        </div>
    </div>
</template>

