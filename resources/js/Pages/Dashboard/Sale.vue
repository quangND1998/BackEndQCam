<script setup>
import { computed, ref, onMounted, reactive } from 'vue'
import { useMainStore } from '@/stores/main'
import {
    mdiAccountMultiple,
    mdiChartTimelineVariant,
    mdiMonitorCellphone,
    mdiReload,
    mdiGithub,
    mdiChartPie,
    mdiCartOutline,
    mdiMinus

} from '@mdi/js'
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import { useForm, router, usePage } from "@inertiajs/vue3";
import LineChart from '@/Components/Charts/LineChart.vue'
import { Head, Link } from '@inertiajs/vue3'
import BaseIcon from '@/Components/BaseIcon.vue'
import PaginationDashboard from '@/Components/PaginationDefault.vue'
import CardBox from '@/Components/CardBox.vue'

const props = defineProps({
    top_ten_sale_data: Array,
    week_data_user: Number,
    month_data_user: Number,
    year_data_user: Number,
    team_sale_data: Array,
    contract_infor: Object,
    ranking_team: Object,
    ranking_all_server: Object,
    order_packages: Object,
    sumGrandTotalOrder: String | Number,
    sumPricePercentOrder: String | Number,
    analysticData: Array
})

const series = reactive([
    {
        name: 'Số tiền thực thu',
        data: []
    }, {
        name: 'Số tiền theo hợp đồng',
        data: []
    }
]);

const chartOptions = reactive({
    chart: {
        height: 350,
        type: 'area'
    },
    dataLabels: {
        enabled: true
    },
    stroke: {
        curve: 'smooth'
    },
    colors: ['#E91E63', '#00d1b2'],
    xaxis: {
        categories: []
    },
    convertedCatToNumeric: false

});
const mainStore = useMainStore()

const clientBarItems = computed(() => mainStore.clients.slice(0, 4))

const transactionBarItems = computed(() => mainStore.history)

const filter = reactive({
    date: usePage().props.ziggy.query.date ? usePage().props.ziggy.query.date : 'month',
    from: null,
    to: null,
    day: null,

})

const getOptions = computed(() => {
    chartOptions.xaxis.categories = [];
    if (props.analysticData) {
        props.analysticData.forEach(element => {

            if (filter.date == 'year') {
                chartOptions.xaxis.categories.push(element.month)

            }
            else {
                chartOptions.xaxis.categories.push(element.time)
            }

        });

        return chartOptions;
    }
    return [];

})

const getSeries = computed(() => {
    series[0].data = [];
    series[1].data = [];
    if (props.analysticData) {
        props.analysticData.forEach(element => {
            series[0].data.push(parseInt(element.price_percent_sum, 10))
            series[1].data.push(parseInt(element.grand_total_sum, 10))
            // for (let i = 0; i < element.length; i++) {
            //     series.push(parseInt(element[i][1], 10))
            // }
        });
        return series;
    }
    return [];

})

const fillterDashboad = (time) => {
    filter.date = time;
    router.get(route(`dashboard`),
        { date: filter.date },
        {
            preserveState: false,
            preserveScroll: true
        }
    );

}

const fillterDashboadDay = (time) => {
    filter.day = time;
    router.get(route(`dashboard`),
        { day: filter.day },
        {
            preserveState: false,
            preserveScroll: true
        }
    );

}


const handleDate = (time) => {

    router.get(route(`dashboard`),
        { from: filter.from, to: filter.to },
        {
            preserveState: false,
            preserveScroll: true
        }
    );

}
</script>

<template>
    <LayoutAuthenticated class="bg-gray-100 ">

        <Head title="Dashboard" />

        <div class="mt-16 ">
            <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-3 sm:grid-cols-3  gap-4 ml-6 mr-6 py-2">
                <div class="col-span-1  p-3 border border-gray-300 border_round bg-white items-center text-center">
                    <p class="text-sm text-[#000000] mt-6">Doanh thu trong tuần</p>
                    <h3 class="text-[32px] text-[#70A234] font-bold py-1">{{ formatPrice(week_data_user) }} đ</h3>
                    <p class="text-sm text-[#000000]">Hoa hồng</p>
                    <!-- <h5 class="text-[24px] text-[#FF0000] font-semibold">7000000 đ</h5> -->
                </div>
                <div class="col-span-1   p-3 border border-gray-300 border_round bg-white items-center text-center">
                    <p class="text-sm text-[#000000]  mt-6">Doanh thu trong tháng</p>
                    <h3 class="text-[32px] text-[#FF0000] font-bold py-1">{{ formatPrice(month_data_user) }} đ</h3>
                    <p class="text-sm text-[#000000]">Hoa hồng</p>
                    <!-- <h5 class="text-[24px] text-[#FF0000] font-semibold">7000000 đ</h5> -->
                </div>
                <div class="col-span-1  p-3 border border-gray-300 border_round bg-white items-center text-center">
                    <p class="text-sm text-[#000000]  mt-6">Doanh thu trong năm</p>
                    <h3 class="text-[32px] text-[#00AB55] font-bold py-1">{{ formatPrice(year_data_user) }} đ</h3>
                    <p class="text-sm text-[#000000]">Hoa hồng</p>
                    <!-- <h5 class="text-[24px] text-[#FF0000] font-semibold">7000000 đ</h5> -->
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-3 sm:grid-cols-3 gap-4 mx-6 my-2 ">
                <div class="col-span-2 ">
                    <div class="col-span-1  p-3 border border-gray-300 border_round bg-white">
                        <p class="text-sm text-[#FF0000] font-bold">Top 10 doanh thu trong tuần toàn hệ thống</p>
                        <div class="w-full mt-2">
                            <div class="flex flex-col">
                                <div class="overflow-auto inline-block min-w-full  sm:px-6 lg:px-8 m-0 p-0 h-[40vh]">
                                    <table
                                        class="border_round table_stripe min-w-full text-center text-sm font-light overflow-x-auto">
                                        <thead class="bg-[#FFEBC3] border_round">
                                            <tr>
                                                <th scope="col"
                                                    class="px-3 py-2 text-center text-sm text-[#000000] font-normal ">Họ tên
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-2 text-center text-sm text-[#000000] font-normal">Team
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-2 text-center text-sm text-[#000000] font-normal">Doanh
                                                    thu thực</th>
                                                <th scope="col"
                                                    class="px-3 py-2 text-center text-sm text-[#000000] font-normal">Hợp
                                                    đồng ký</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(sale, index) in top_ten_sale_data" :key="index">
                                                <td class="whitespace-nowrap text-center px-3 py-2 font-medium">
                                                    {{ sale.name }}
                                                </td>
                                                <td class="whitespace-nowrap text-center px-3 py-2 font-medium">
                                                    {{ sale.team?.name }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap text-center px-3 py-2 text-[#ED5B00] font-bold">
                                                    <p>{{ formatPrice(sale.history_payments_sum_amount_received) }} đ</p>
                                                </td>
                                                <td class="whitespace-nowrap text-center px-3 py-2 text-gray-500">
                                                    {{ sale.ref_order_packages_count }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-1 mt-3  p-3 border border-gray-300 border_round bg-white">
                        <p class="text-sm text-[#FF0000] font-bold">Top 10 doanh thu tuần nhóm </p>
                        <div class="w-full mt-2">
                            <div class="flex flex-col">
                                <div class="overflow-auto inline-block min-w-full  sm:px-6 lg:px-8 m-0 p-0 h-[40vh]">
                                    <table
                                        class="border_round table_stripe min-w-full text-center text-sm font-light overflow-x-auto">
                                        <thead class="bg-[#FFEBC3] border_round">
                                            <tr>
                                                <th scope="col"
                                                    class="px-3 py-2 text-center text-sm text-[#000000] font-normal ">Họ tên
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-2 text-center text-sm text-[#000000] font-normal">Team
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-2 text-center text-sm text-[#000000] font-normal">Doanh
                                                    thu thực</th>
                                                <th scope="col"
                                                    class="px-3 py-2 text-center text-sm text-[#000000] font-normal">Hợp
                                                    đồng ký</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(sale, index) in team_sale_data" :key="index">
                                                <td class="whitespace-nowrap text-center px-3 py-2 font-medium">
                                                    {{ sale.name }}
                                                </td>
                                                <td class="whitespace-nowrap text-center px-3 py-2 font-medium">
                                                    {{ sale.team?.name }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap text-center px-3 py-2 text-[#ED5B00] font-bold">
                                                    <p>{{ formatPrice(sale.history_payments_sum_amount_received) }}đ</p>
                                                </td>
                                                <td class="whitespace-nowrap text-center px-3 py-2 text-gray-500">
                                                    {{ sale.ref_order_packages_count }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1  p-3  border border-gray-300 border_round bg-white text-center">
                    <div class="py-2">
                        <p class="text-md text-[#FF0C0C] font-bold">Xếp hạng tuần toàn hệ thống</p>
                        <h3 class="text-[30px] text-[#FF0C0C] font-bold py-1">{{ ranking_all_server.week }}</h3>
                        <p class="text-md text-[#FF0C0C] font-bold">Xếp hạng tháng toàn hệ thống</p>
                        <h3 class="text-[30px] text-[#FF0C0C] font-bold py-1">{{ ranking_all_server.month }}</h3>
                        <p class="text-md text-[#FF0C0C] font-bold">Xếp hạng năm toàn hệ thống</p>
                        <h3 class="text-[46px] text-[#FF0C0C] font-bold py-1">{{ ranking_team.year }}</h3>
                        <p class="text-md text-[#FF0C0C] font-bold">Xếp hạng tuần trong team</p>
                        <h3 class="text-[24px] text-[#FF0C0C] font-bold py-1">{{ ranking_team.week }}</h3>
                        <p class="text-md text-[#FF0C0C] font-bold">Xếp hạng tháng trong team</p>
                        <h3 class="text-[24px] text-[#FF0C0C] font-bold py-1">{{ ranking_team.month }}</h3>
                        <p class="text-md text-[#FF0C0C] font-bold">Xếp hạng năm trong team</p>
                        <h3 class="text-[40px] text-[#FF0C0C] font-bold py-1">{{ ranking_team.year }}</h3>
                    </div>
                    <div class="px-4 mt-3" v-if="contract_infor">
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path :d="mdiCartOutline" />
                            </svg>
                            <p class="text-md  ml-2">Tổng số hợp đồng : {{ contract_infor.ref_order_packages_count }}</p>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path :d="mdiCartOutline" />
                            </svg>
                            <p class="text-md  ml-2">Đã ký: {{ contract_infor.contract_completed }}</p>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path :d="mdiCartOutline" />
                            </svg>
                            <p class="text-md  ml-2">Thanh toán 1 phần: {{ contract_infor.contract_partiallyPaid }}</p>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path :d="mdiCartOutline" />
                            </svg>
                            <p class="text-md  ml-2">Đã hoàn thành: {{ contract_infor.contract_paid }}</p>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path :d="mdiCartOutline" />
                            </svg>
                            <p class="text-md  ml-2">Bị huỷ: {{ contract_infor.contract_decline }}</p>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path :d="mdiCartOutline" />
                            </svg>
                            <div>
                                <p class="text-md  ml-2">Doanh thu dự kiến: </p>
                                <p class="text-md text-[#4F8D06]">
                                    {{ formatPrice(contract_infor.ref_order_packages_sum_grand_total) }}đ</p>
                            </div>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path :d="mdiCartOutline" />
                            </svg>
                            <div>
                                <p class="text-md  ml-2">Doanh thu thực: </p>
                                <p class="text-md text-[#2E67A9]">
                                    {{ formatPrice(contract_infor.ref_order_packages_sum_price_percent) }}đ</p>
                            </div>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path :d="mdiCartOutline" />
                            </svg>
                            <div>
                                <p class="text-md  ml-2">Hoa hồng thực nhận: </p>
                                <!-- <p class="text-md text-[#2E67A9]">300.000.000đ</p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-xl ml-6 font-bold mt-10 pl-2">Đơn hàng của bạn (DVT 1000đ)</p>
            <div class="mx-6 my-2 p-3 border border-gray-300 border_round bg-white overflow-auto">
                <div class="my-3 items-center text-center flex ">
                    <div
                        class=" min-[320px]:grid min-[320px]:justify-between sm:justify-start md:justify-start lg:justify-start sm:flex md:flex lg:flex">
                        <div @click="fillterDashboad('year')"
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg   hover:bg-white "
                            :class="$page.url.includes('year') ? ' bg-white text-blue-500 border-blue-500' : 'text-gray-500 bg-gray-100'">
                            Năm
                        </div>
                        <div @click="fillterDashboad('beforMonth')"
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white "
                            :class="$page.url.includes('beforMonth') ? ' bg-white text-blue-500' : 'text-gray-500'">
                            Tháng trước
                        </div>
                        <div @click="fillterDashboad('month')"
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white "
                            :class="$page.url.includes('month') ? ' bg-white text-blue-500' : 'text-gray-500'">
                            Tháng này
                        </div>
                        <div @click="fillterDashboadDay(7)"
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white "
                            :class="$page.url.includes('day') ? ' bg-white text-blue-500' : 'text-gray-500'">
                            7 ngày qua
                        </div>
                        <div class="flex items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                            :class="{ 'bg-white  text-blue-500': $page.url.includes('draf') }">
                            Tùy chỉnh
                            <div class="ml-2 relative">
                                <VueDatePicker time-picker-inline v-model="filter.from" />
                            </div>
                            <span class="mx-1 text-gray-500">-</span>
                            <div class="relative">
                                <VueDatePicker time-picker-inline v-model="filter.to"  />
                            </div>
                        </div>
                        <div class="flex w-[160px] p-2 justify-between lg:w-[160px] sm:w-full">
                            <button @click="handleDate">Lọc</button>

                        </div>
                        <button class="right">Xuất CSV</button>
                    </div>
                    <div>

                    </div>
                </div>
                <div class="w-full mt-2">
                    <div class="flex flex-col">
                        <div class=" overflow-y-auto inline-block min-w-full  sm:px-6 lg:px-8 m-0 p-0 h-[40vh]">
                            <table
                                class="border_round table_grip min-w-full text-center text-sm font-light overflow-y-auto ">
                                <thead class="bg-[#FFEBC3]  border_round sticky top-0 z-10">
                                    <tr>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal ">
                                            Đơn hàng
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            KH
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Thời gian</th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Tổng giá trị</th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal ">
                                            Đã thu
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Còn lại
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Ghi nhận HH</th>
                                        <th scope="col" class="px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Hoa Hồng <br> Đã thanh toán</th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Hoa Hồng <br> Chưa thanh toán</th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Hợp
                                            Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order, index) in order_packages.data" :key="index">
                                        <td class=" text-left px-3 py-2 font-normal">
                                            {{ order.order_number }}
                                        </td>
                                        <td class=" text-left px-3 py-2 font-normal">
                                            {{ order.customer?.name }}
                                        </td>
                                        <td class=" text-left px-3 py-2 text-[#00AB55] font-medium">
                                            <p> {{ formatDate(order.created_at) }}</p>

                                        </td>
                                        <td class=" text-left px-3 py-2  font-normal">

                                            <p v-if="order.status == 'decline'"> - {{ formatPrice(order.grand_total) }}đ</p>
                                            <p v-else> {{ formatPrice(order.grand_total) }}đ</p>
                                        </td>
                                        <td class=" text-left px-3 py-2 font-normal">
                                            <p v-if="order.status == 'decline'"> - {{ formatPrice(order.price_percent) }}đ
                                            </p>
                                            <p v-else> {{ formatPrice(order.price_percent) }}đ</p>

                                        </td>
                                        <td class=" text-left px-3 py-2 font-normal">
                                            {{ formatPrice(order.grand_total - order.price_percent) }}đ

                                        </td>
                                        <td class=" text-left px-3 py-2  font-normal">
                                            <p>3.000đ</p>
                                        </td>
                                        <td class=" text-left px-3 py-2 font-normal">
                                            2.000đ
                                        </td>
                                        <td class=" text-left px-3 py-2 font-normal">
                                            1.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-normal">
                                            {{ order.status }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <PaginationDashboard :links="order_packages.links" />
                        </div>
                        <table class="border_round table_grip min-w-full text-center text-sm font-light overflow-x-auto">
                            <tbody class="relative">
                                <tr class="sticky bottom-0 bg-white">
                                    <th scope="col" class="px-3 py-2 text-left text-sm text-[#000000] font-bold ">
                                        Tổng
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-sm text-[#000000] font-normal">

                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-sm text-[#000000] font-normal">
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-sm text-[#000000] font-normal">
                                        300.000đ
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-sm text-[#000000] font-normal ">
                                        170.000đ
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-sm text-[#000000] font-normal">
                                        170.000đ
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-sm text-[#000000] font-normal">
                                        170.000đ
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-sm text-[#000000] font-normal">
                                        170.000đ
                                    </th>
                                    <th scope="col" class=" px-3 py-2 text-left text-sm text-[#000000] font-normal">
                                        170.000đ
                                    </th>
                                    <th scope="col"
                                        class="whitespace-nowrap px-3 py-2 text-left text-sm text-[#000000] font-normal">
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>

                    </div>
                </div>
            </div>
            <p class="text-xl ml-6 font-bold mt-10 pl-2">Biểu đồ doanh thu x là thời gian, y là số tiền</p>
            <div class="mx-6 my-2 p-3 border border-gray-300 border_round bg-white">
                <div class="my-3 items-center text-center">
                    <div
                        class="min-[320px]:grid min-[320px]:justify-between sm:justify-start md:justify-start lg:justify-start sm:flex md:flex lg:flex">
                        <div   @click="fillterDashboad('year')" class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                            :class="$page.url.includes('year') ? ' bg-white text-blue-500 border-blue-500' : 'text-gray-500 bg-gray-100'">
                            Năm
                        </div>
                        <div  @click="fillterDashboad('beforMonth')" class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                        :class="$page.url.includes('beforMonth') ? ' bg-white text-blue-500' : 'text-gray-500'">
                            Tháng trước
                        </div>
                        <div @click="fillterDashboad('month')" class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                        :class="$page.url.includes('month') ? ' bg-white text-blue-500' : 'text-gray-500'">
                            Tháng này
                        </div>
                        <div  @click="fillterDashboadDay(7)" class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                        :class="$page.url.includes('day') ? ' bg-white text-blue-500' : 'text-gray-500'">
                            7 ngày qua
                        </div>
                        <div class="flex items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                            :class="{ 'bg-white  text-blue-500': $page.url.includes('draf') }">
                            Tùy chỉnh
                            <div class="ml-2 relative">
                                <VueDatePicker time-picker-inline v-model="filter.from" />
                            </div>
                            <span class="mx-1 text-gray-500">-</span>
                            <div class="relative">
                                <VueDatePicker  time-picker-inline v-model="filter.to" />
                            </div>
                        </div>
                        <div class="flex w-[160px] p-2 justify-between lg:w-[160px] sm:w-full">
                            <button @click="handleDate">Lọc</button>

                        </div>
                        <button>Xuất CSV</button>
                    </div>
                    <div class="w-full flex justify-center items-center">
                        <div class="w-1/6">
                            <div class="flex text-center items-center px-2 py-1.5">
                                <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                    <path fill="#ff3860" :d="mdiMinus" />
                                </svg>
                                <p class="text-md  ml-2">Số tiền thực thu</p>
                            </div>
                            <div class="flex text-center items-center px-2 py-1.5">
                                <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                    <path fill="#00d1b2" :d="mdiMinus" />
                                </svg>
                                <p class="text-md  ml-2">Số tiền theo hợp đồng</p>
                            </div>
                        </div>
                        <div class="w-5/6">
                            <CardBox class="mb-6">


                                <div id="chart" v-if="chartOptions">
                                    <apexchart type="area" height="350" :options="getOptions" :series="getSeries">
                                    </apexchart>
                                </div>
                            </CardBox>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </LayoutAuthenticated>
</template>
<style scoped>
.table_grip tr th {
    width: 120px !important;
}

.table_grip tr td {
    width: 120px !important;
}
</style>
