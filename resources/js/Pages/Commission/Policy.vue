<script setup>
import { computed, ref, onMounted,inject, reactive } from 'vue'
import { useMainStore } from '@/stores/main'
import {
    mdiAccountMultiple,
    mdiChartTimelineVariant,
    mdiMonitorCellphone,
    mdiReload,
    mdiGithub,
    mdiChartPie,
    mdiCartOutline,
    mdiMinus,
    mdiOpenInNew,
    mdiPlus,
    mdiRefresh,
    mdiPencil,
    mdiTrashCanOutline

} from '@mdi/js'
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import { useForm, router, usePage } from "@inertiajs/vue3";
import LineChart from '@/Components/Charts/LineChart.vue'
import { Head, Link } from '@inertiajs/vue3'
import BaseIcon from '@/Components/BaseIcon.vue'
import BaseButton from "@/Components/BaseButton.vue";
import PaginationDashboard from '@/Components/PaginationDefault.vue'
const chartData = ref(null)
import CardBox from '@/Components/CardBox.vue'
const props = defineProps({
    commissionSettings: Object,
})
const swal = inject("$swal");
const filter = reactive({
    date: usePage().props.ziggy.query.date ? usePage().props.ziggy.query.date : 'month',
    from: null,
    to: null,
    day: null,

})

const fillterType = (role) => {
    filter.role = role;
    router.get(route(`commission.dashboard.user`),
        filter,
        {
            preserveState: false,
            preserveScroll: true
        }
    );
}
const changeStatus = (data, event) => {
    let query = {
        id: data.id,
        status: event.target.checked
    };
    console.log(data);
    console.log(event);
    router.post(route("commission.changeStatusPolicy", data.id), query, {
        preserveState: false
        // only: ["image360s", "errors", 'flash'],
    });
}
const form = useForm({
    id: null
});
const Delete = (id) => {
    swal
        .fire({
            title: "Bạn có chắc chắn?",
            text: "Xóa chính sách!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.post(route("commission.destroyCommissionSetting", id), {
                    onSuccess: () => {
                        swal.fire(
                            "Deleted!",
                            "Đã xóa sản phẩm.",
                            "success"
                        );
                    },
                });
            }
        });
    }
</script>

<template>
    <LayoutAuthenticated class="bg-gray-100 ">

        <Head title="Dashboard" />

        <div class="mt-16">

            <div class="mx-6 sm:block md:block lg:flex lg:justify-between">
                <div>
                    <p class="text-xl font-bold pl-2">Danh sách chiết khấu tháng</p>
                </div>
                <div class="flex">

                    <div>
                        <Link :href="route('commission.dashboard.fresh')">
                        <BaseButton color="info" class="bg-btn_green hover:bg-[#318f02] border rounded-xl text-white p-2 "
                            :icon="mdiPlus" small label="Tạo chính sách mới" />
                        </Link>
                    </div>
                </div>
            </div>
            <div class="mx-6 my-2 p-3 border border-gray-300 border_round bg-white overflow-auto">
                <div class="w-full mt-2">
                    <div class="flex flex-col">
                        <div class=" overflow-y-auto inline-block min-w-full  sm:px-6 lg:px-8 m-0 p-0 h-[75vh]">
                            <table
                                class="border_round table_grip min-w-full text-center text-sm font-light overflow-y-auto ">
                                <thead class="bg-[#FFEBC3]  border_round sticky top-0 z-10">
                                    <tr>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal ">
                                            #
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Ngày áp dụng
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Trạng thái
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Chi tiết
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(setting, index) in commissionSettings.data" :key="index">
                                        <td class=" text-center px-3 py-2 font-normal">
                                            {{ index + commissionSettings.from }}
                                        </td>
                                        <td class="  px-3 py-2 font-normal">
                                            Từ {{ setting.dateFrom }} - {{ setting.dateTo }}
                                        </td>
                                        <td class="  px-3 py-2 font-normal">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="sr-only peer" :checked="setting.status"
                                                    @change="changeStatus(setting, $event)">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                                </div>
                                            </label>
                                        </td>
                                        <td class="  px-3 py-2 text-[#2264E5] font-medium">
                                            <Link :href="`/commission/policy/${setting.id}`">Xem</Link>
                                        </td>
                                        <td class=" w-full justify-center text-center flex px-3 py-2 text-[#2264E5] font-medium">
                                            <!-- <Link
                                                class=" text-[#2264E5] cursor-pointer  font-semibold">
                                                <p class="hover:text-blue-700">Sửa</p>
                                            </Link> -->
                                            <div @click="Delete(setting.id)"
                                                class=" items-center px-4  text-sm text-[#D12953] cursor-pointer  font-semibold">
                                                <p class="hover:text-red-700"> Xóa</p>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                                <tr colum="12">
                                    <th colspan="12">
                                        <PaginationDashboard :links="commissionSettings.links" class="w-full" />
                                    </th>
                                </tr>
                            </table>


                        </div>
                    </div>
                    <div>

                    </div>
                </div>
            </div>
        </div>
    </LayoutAuthenticated>
</template>

