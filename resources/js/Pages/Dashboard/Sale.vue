<script setup>
import { computed, ref, onMounted } from 'vue'
import { useMainStore } from '@/stores/main'
import {
    mdiAccountMultiple,
    mdiChartTimelineVariant,
    mdiMonitorCellphone,
    mdiReload,
    mdiGithub,
    mdiChartPie,
    mdiCartOutline,

} from '@mdi/js'

import * as chartConfig from '@/Components/Charts/chart.config.js'
import LineChart from '@/Components/Charts/LineChart.vue'
import SectionMain from '@/Components/SectionMain.vue'
import CardBoxWidget from '@/Components/CardBoxWidget.vue'
import CardBox from '@/Components/CardBox.vue'
import TableSampleClients from '@/Components/TableSampleClients.vue'
import NotificationBar from '@/Components/NotificationBar.vue'
import BaseButton from '@/Components/BaseButton.vue'
import CardBoxTransaction from '@/Components/CardBoxTransaction.vue'
import CardBoxClient from '@/Components/CardBoxClient.vue'
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import SectionBannerStarOnGitHub from '@/Components/SectionBannerStarOnGitHub.vue'
import { Head, Link } from '@inertiajs/vue3'
import BaseIcon from '@/Components/BaseIcon.vue'
const chartData = ref(null)

const fillChartData = () => {
    chartData.value = chartConfig.sampleChartData()
}

onMounted(() => {
    fillChartData()
})

const mainStore = useMainStore()

const clientBarItems = computed(() => mainStore.clients.slice(0, 4))

const transactionBarItems = computed(() => mainStore.history)
</script>

<template>
    <LayoutAuthenticated>

        <Head title="Dashboard" />
        <div class="mt-16 bg-gray-100 ">
            <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-3 sm:grid-cols-3  gap-4 ml-6 mr-6 py-2">
                <div class="col-span-1  p-3 border border-gray-300 border_round bg-white items-center text-center">
                    <p class="text-sm text-[#000000] mt-6">Doanh thu trong tuần</p>
                    <h3 class="text-[32px] text-[#70A234] font-bold py-1">7000000 đ</h3>
                    <p class="text-sm text-[#000000]">Hoa hồng</p>
                    <h5 class="text-[24px] text-[#FF0000] font-semibold">7000000 đ</h5>
                </div>
                <div class="col-span-1   p-3 border border-gray-300 border_round bg-white items-center text-center">
                    <p class="text-sm text-[#000000]  mt-6">Doanh thu trong tuần</p>
                    <h3 class="text-[32px] text-[#FF0000] font-bold py-1">7000000 đ</h3>
                    <p class="text-sm text-[#000000]">Hoa hồng</p>
                    <h5 class="text-[24px] text-[#FF0000] font-semibold">7000000 đ</h5>
                </div>
                <div class="col-span-1  p-3 border border-gray-300 border_round bg-white items-center text-center">
                    <p class="text-sm text-[#000000]  mt-6">Doanh thu trong tuần</p>
                    <h3 class="text-[32px] text-[#00AB55] font-bold py-1">7000000 đ</h3>
                    <p class="text-sm text-[#000000]">Hoa hồng</p>
                    <h5 class="text-[24px] text-[#FF0000] font-semibold">7000000 đ</h5>
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
                                            <tr>
                                                <td class="whitespace-nowrap text-center px-3 py-2 font-medium">
                                                    Nguyễn Văn B
                                                </td>
                                                <td class="whitespace-nowrap text-center px-3 py-2 font-medium">
                                                    Bùi Đình Dũng
                                                </td>
                                                <td
                                                    class="whitespace-nowrap text-center px-3 py-2 text-[#ED5B00] font-bold">
                                                    <p>100.000.000đ</p>
                                                </td>
                                                <td class="whitespace-nowrap text-center px-3 py-2 text-gray-500">
                                                    1
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-1 mt-3  p-3 border border-gray-300 border_round bg-white">
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
                                            <tr>
                                                <td class="whitespace-nowrap text-center px-3 py-2 font-medium">
                                                    Nguyễn Văn B
                                                </td>
                                                <td class="whitespace-nowrap text-center px-3 py-2 font-medium">
                                                    Bùi Đình Dũng
                                                </td>
                                                <td
                                                    class="whitespace-nowrap text-center px-3 py-2 text-[#ED5B00] font-bold">
                                                    <p>100.000.000đ</p>
                                                </td>
                                                <td class="whitespace-nowrap text-center px-3 py-2 text-gray-500">
                                                    1
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
                        <h3 class="text-[30px] text-[#FF0C0C] font-bold py-1">100</h3>
                        <p class="text-md text-[#FF0C0C] font-bold">Xếp hạng tháng toàn hệ thống</p>
                        <h3 class="text-[30px] text-[#FF0C0C] font-bold py-1">12</h3>
                        <p class="text-md text-[#FF0C0C] font-bold">Xếp hạng năm toàn hệ thống</p>
                        <h3 class="text-[46px] text-[#FF0C0C] font-bold py-1">12</h3>
                        <p class="text-md text-[#FF0C0C] font-bold">Xếp hạng tuần trong team</p>
                        <h3 class="text-[24px] text-[#FF0C0C] font-bold py-1">12</h3>
                        <p class="text-md text-[#FF0C0C] font-bold">Xếp hạng tháng trong team</p>
                        <h3 class="text-[24px] text-[#FF0C0C] font-bold py-1">5</h3>
                        <p class="text-md text-[#FF0C0C] font-bold">Xếp hạng năm trong team</p>
                        <h3 class="text-[40px] text-[#FF0C0C] font-bold py-1">3</h3>
                    </div>
                    <div class="px-4 mt-3">
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path  :d="mdiCartOutline" />
                            </svg>
                            <p class="text-md  ml-2">Tổng số hợp đồng : 10</p>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path  :d="mdiCartOutline" />
                            </svg>
                            <p class="text-md  ml-2">Đã ký: 7</p>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path  :d="mdiCartOutline" />
                            </svg>
                            <p class="text-md  ml-2">Thanh toán 1 phần: 5</p>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path  :d="mdiCartOutline" />
                            </svg>
                            <p class="text-md  ml-2">Đã hoàn thành: 3</p>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path  :d="mdiCartOutline" />
                            </svg>
                            <p class="text-md  ml-2">Bị huỷ: 1</p>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path  :d="mdiCartOutline" />
                            </svg>
                            <div>
                                <p class="text-md  ml-2">Doanh thu dự kiến: </p>
                                <p class="text-md text-[#4F8D06]">300.000.000đ</p>
                            </div>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path  :d="mdiCartOutline" />
                            </svg>
                            <div>
                                <p class="text-md  ml-2">Doanh thu thực: </p>
                                <p class="text-md text-[#2E67A9]">300.000.000đ</p>
                            </div>
                        </div>
                        <div class="flex text-center items-center px-2 py-1.5">
                            <svg viewBox="0 0 24 24" :width="28" :height="28" class="inline-block">
                                <path  :d="mdiCartOutline" />
                            </svg>
                            <div>
                                <p class="text-md  ml-2">Hoa hồng thực nhận: </p>
                                <p class="text-md text-[#2E67A9]">300.000.000đ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-xl ml-6 font-bold mt-10 pl-2">Đơn hàng của bạn (DVT 1000đ)</p>
            <div class="mx-6 my-2 p-3 border border-gray-300 border_round bg-white">
                <div class="my-3 items-center text-center">
                    <div
                        class="min-[320px]:grid min-[320px]:justify-between sm:justify-start md:justify-start lg:justify-start sm:flex md:flex lg:flex">
                        <div
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                            :class="{ 'bg-white  text-blue-500': $page.url.includes('all') }">
                        Năm
                        </div>
                        <div
                        class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                            :class="{ 'bg-white  text-blue-500': $page.url.includes('complete') }">
                        Tháng trước
                        </div>
                        <div  @click.prevent
                        class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                            :class="{ 'bg-white  text-blue-500': $page.url.includes('pending') }">
                        Tháng này
                        </div>
                        <div
                        class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                            :class="{ 'bg-white  text-blue-500': $page.url.includes('partiallyPaid') }">
                        7 ngày qua
                        </div>
                        <div
                        class="flex items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                            :class="{ 'bg-white  text-blue-500': $page.url.includes('draf') }">
                            Tùy chỉnh
                            <div class="ml-2 relative">
                                <VueDatePicker time-picker-inline/>
                            </div>
                            <span class="mx-1 text-gray-500">-</span>
                            <div class="relative">
                                <VueDatePicker time-picker-inline />
                            </div>
                        </div>
                        <div class="flex w-[160px] p-2 justify-between lg:w-[160px] sm:w-full">
                            <button>Lọc</button>

                        </div>
                        <button>Xuất CSV</button>
                    </div>
                    <div>

                    </div>
                </div>
                <div class="w-full mt-2">
                    <div class="flex flex-col">
                        <div class="overflow-auto inline-block min-w-full  sm:px-6 lg:px-8 m-0 p-0 h-[40vh]">
                            <table
                                class="border_round table_grip min-w-full text-center text-sm font-light overflow-x-auto ">
                                <thead class="bg-[#FFEBC3]  border_round sticky top-0 z-10">
                                    <tr>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal ">Họ
                                            Đơn hàng
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            KH
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Thời gian</th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">Hợp
                                            Tổng giá trị</th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal ">Họ
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
                                        <th scope="col"
                                            class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Hợp
                                            Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody class="relative">
                                    <tr>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            127636
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Bùi Đình Dũng Nhi Trang
                                        </td>
                                        <td class=" text-center px-3 py-2 text-[#00AB55] font-medium">
                                            <p>12/8/2024</p>
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            100.000000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            <p>3.000đ</p>
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            2.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            1.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Processing
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            127636
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Bùi Đình Dũng Nhi Trang
                                        </td>
                                        <td class=" text-center px-3 py-2 text-[#00AB55] font-medium">
                                            <p>12/8/2024</p>
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            100.000000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            <p>3.000đ</p>
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            2.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            1.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Processing
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            127636
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Bùi Đình Dũng Nhi Trang
                                        </td>
                                        <td class=" text-center px-3 py-2 text-[#00AB55] font-medium">
                                            <p>12/8/2024</p>
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            100.000000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            <p>3.000đ</p>
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            2.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            1.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Processing
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            127636
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Bùi Đình Dũng Nhi Trang
                                        </td>
                                        <td class=" text-center px-3 py-2 text-[#00AB55] font-medium">
                                            <p>12/8/2024</p>
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            100.000000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            <p>3.000đ</p>
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            2.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            1.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Processing
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            127636
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Bùi Đình Dũng Nhi Trang
                                        </td>
                                        <td class=" text-center px-3 py-2 text-[#00AB55] font-medium">
                                            <p>12/8/2024</p>
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            100.000000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            <p>3.000đ</p>
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            2.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            1.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Processing
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            127636
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Bùi Đình Dũng Nhi Trang
                                        </td>
                                        <td class=" text-center px-3 py-2 text-[#00AB55] font-medium">
                                            <p>12/8/2024</p>
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            100.000000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            <p>3.000đ</p>
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            2.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            1.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Processing
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            127636
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Bùi Đình Dũng Nhi Trang
                                        </td>
                                        <td class=" text-center px-3 py-2 text-[#00AB55] font-medium">
                                            <p>12/8/2024</p>
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            100.000000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            <p>3.000đ</p>
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            2.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            1.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Processing
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            127636
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Bùi Đình Dũng Nhi Trang
                                        </td>
                                        <td class=" text-center px-3 py-2 text-[#00AB55] font-medium">
                                            <p>12/8/2024</p>
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            100.000000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            <p>3.000đ</p>
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            2.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            1.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Processing
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            127636
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Bùi Đình Dũng Nhi Trang
                                        </td>
                                        <td class=" text-center px-3 py-2 text-[#00AB55] font-medium">
                                            <p>12/8/2024</p>
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            100.000000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            <p>3.000đ</p>
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            2.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            1.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Processing
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            127636
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Bùi Đình Dũng Nhi Trang
                                        </td>
                                        <td class=" text-center px-3 py-2 text-[#00AB55] font-medium">
                                            <p>12/8/2024</p>
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            100.000000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            50.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2  font-medium">
                                            <p>3.000đ</p>
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            2.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            1.000đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-medium">
                                            Processing
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <table
                                class="border_round table_grip min-w-full text-center text-sm font-light overflow-x-auto">
                                <tbody class="relative">
                                    <tr class="sticky bottom-0 bg-white">
                                        <th scope="col" class="px-3 py-2 text-center text-sm text-[#000000] font-bold ">
                                           Tổng
                                        </th>
                                        <th scope="col" class="px-3 py-2 text-center text-sm text-[#000000] font-normal">

                                        </th>
                                        <th scope="col" class="px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                        </th>
                                        <th scope="col" class="px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            300.000đ
                                        </th>
                                        <th scope="col" class="px-3 py-2 text-center text-sm text-[#000000] font-normal ">
                                            170.000đ
                                        </th>
                                        <th scope="col" class="px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            170.000đ
                                        </th>
                                        <th scope="col" class="px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            170.000đ
                                        </th>
                                        <th scope="col" class="px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            170.000đ
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            170.000đ
                                        </th>
                                        <th scope="col"
                                            class="whitespace-nowrap px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
            <p class="text-xl ml-6 font-bold mt-10 pl-2">Biểu đồ doanh thu  x là  thời gian, y là số tiền</p>
            <div class="mx-6 my-2 p-3 border border-gray-300 border_round bg-white">
                <div class="my-3 items-center text-center">
                    <div
                        class="min-[320px]:grid min-[320px]:justify-between sm:justify-start md:justify-start lg:justify-start sm:flex md:flex lg:flex">
                        <div
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                            :class="{ 'bg-white  text-blue-500': $page.url.includes('all') }">
                        Năm
                        </div>
                        <div
                        class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                            :class="{ 'bg-white  text-blue-500': $page.url.includes('complete') }">
                        Tháng trước
                        </div>
                        <div  @click.prevent
                        class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                            :class="{ 'bg-white  text-blue-500': $page.url.includes('pending') }">
                        Tháng này
                        </div>
                        <div
                        class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                            :class="{ 'bg-white  text-blue-500': $page.url.includes('partiallyPaid') }">
                        7 ngày qua
                        </div>
                        <div
                        class="flex items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
                            :class="{ 'bg-white  text-blue-500': $page.url.includes('draf') }">
                            Tùy chỉnh
                            <div class="ml-2 relative">
                                <VueDatePicker time-picker-inline/>
                            </div>
                            <span class="mx-1 text-gray-500">-</span>
                            <div class="relative">
                                <VueDatePicker time-picker-inline />
                            </div>
                        </div>
                        <div class="flex w-[160px] p-2 justify-between lg:w-[160px] sm:w-full">
                            <button>Lọc</button>

                        </div>
                        <button>Xuất CSV</button>
                    </div>
                    <div>

                    </div>
                </div>
            </div>
        </div>
    </LayoutAuthenticated>
</template>
<style scoped>
.table_grip tr th{
    width: 120px !important;
}
.table_grip tr td{
    width: 120px !important;
}
</style>
