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
    mdiMinus,
    mdiOpenInNew,
    mdiPlus,
    mdiRefresh

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
    analysticData: Array,
    users: Array,
    sumCommissionInfo:Object
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
const fillterDashboad = (time) => {
    filter.date = time;
    router.get(route(`commission.dashboard.user`),
        filter,
        {
            preserveState: false,
            preserveScroll: true
        }
    );

}

const fillterDashboadDay = (time) => {
    filter.day = time;
    router.get(route(`commission.dashboard.user`),
        filter,
        {
            preserveState: false,
            preserveScroll: true
        }
    );

}


const handleDate = (time) => {

    router.get(route(`commission.dashboard.user`),
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

        <div class="mt-16">

            <div class="mx-6 sm:block md:block lg:flex lg:justify-between">
                <div>
                    <p class="text-xl font-bold pl-2">Danh sách chiết khấu tháng</p>
                </div>
                <div class="flex">

                    <div>
                        <Link :href="route('commission.dashboard.fresh')">
                            <BaseButton color="info" class="bg-btn_green hover:bg-[#318f02] border rounded-xl text-white p-2 "
                        :icon="mdiRefresh " small label="Làm mới" />
                        </Link>
                    </div>
                </div>
            </div>
            <div class="mx-6 my-2 p-3 border border-gray-300 border_round bg-white overflow-auto">
                <div class="items-center text-center flex ">
                    <div
                        class=" min-[320px]:grid min-[320px]:justify-between sm:justify-start md:justify-start lg:justify-start sm:flex md:flex lg:flex">
                        <div @click="fillterType('saler')"
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white "
                            :class="$page.url.includes('saler')   ? ' bg-white text-blue-500' : 'text-gray-500'">
                           Sale
                        </div>
                        <div @click="fillterType('leader-sale')"
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white "
                            :class="$page.url.includes('leader-sale') ? ' bg-white text-blue-500' : 'text-gray-500'">
                          Leader Sale
                        </div>
                        <div @click="fillterType('ctv')"
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white "
                            :class="$page.url.includes('ctv') ? ' bg-white text-blue-500' : 'text-gray-500'">
                            CTV
                        </div>

                        <div @click="fillterType('telesale')"
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg   hover:bg-white "
                            :class="$page.url.includes('telesale') ? ' bg-white text-blue-500 border-blue-500' : 'text-gray-500 bg-gray-100'">
                          Telesale
                        </div>

                    </div>
                    <div>

                    </div>
                </div>
                <div class="items-center text-center flex ">
                    <div
                        class=" min-[320px]:grid min-[320px]:justify-between sm:justify-start md:justify-start lg:justify-start sm:flex md:flex lg:flex">
                        <div @click="fillterDashboad('month')"
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white "
                            :class="filter.date == 'month' && filter.day == null ? ' bg-white text-blue-500' : 'text-gray-500'">
                            Tháng này
                        </div>
                        <div @click="fillterDashboadDay(7)"
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white "
                            :class="filter.date == 'month' && filter.day == null  ? ' bg-white text-blue-500' : 'text-gray-500'">
                            7 ngày qua
                        </div>
                        <div @click="fillterDashboad('beforMonth')"
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white "
                            :class="$page.url.includes('beforMonth') ? ' bg-white text-blue-500' : 'text-gray-500'">
                            Tháng trước
                        </div>

                        <div @click="fillterDashboad('year')"
                            class="flex w-[160px] items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg   hover:bg-white "
                            :class="$page.url.includes('year') ? ' bg-white text-blue-500 border-blue-500' : 'text-gray-500 bg-gray-100'">
                            Năm
                        </div>
                        <div class="flex px-2 items-center justify-center text-center min-[320px]:my-2 text-sm m-1 border rounded-lg  bg-gray-100 hover:bg-white text-gray-500"
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

                        <div  class="inline-flex justify-start items-center whitespace-nowrap focus:outline-none transition-colors focus:ring duration-150 border cursor-pointer rounded   dark:ring-blue-700 ring-blue-300  p-0 p-2 mr-2 my-2 bg-white" @click="handleDate">Lọc</div>
                        <div  @click="exportCSV()"  class="inline-flex justify-start items-center whitespace-nowrap focus:outline-none transition-colors focus:ring duration-150 border cursor-pointer rounded   dark:ring-blue-700 ring-blue-300  p-0 p-2 mr-2 my-2 bg-white">Xuất CSV</div>
                    </div>
                    <div>

                    </div>
                </div>
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
                                            User
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Quyền
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Hợp đồng đã ký
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Hợp đồng bị hủy
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Doanh thu thực tháng
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Mức hoa hồng (%)
                                        </th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal ">
                                            Ghi nhận HH
                                        </th>
                                        <th scope="col" class="px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Hoa Hồng <br> Đã thanh toán</th>
                                        <th scope="col" class=" px-3 py-2 text-center text-sm text-[#000000] font-normal">
                                            Hoa Hồng <br> Chưa thanh toán</th>
                                        <td class=" text-center px-3 py-2 font-normal">
                                            <!-- Hành động -->
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user, index) in users.data" :key="index">
                                        <td class=" text-center px-3 py-2 font-normal">
                                            {{ index  + users.from  }}
                                        </td>
                                        <td class=" text-left px-3 py-2 font-normal">
                                            {{ user.name }}
                                        </td>
                                        <td class=" text-left px-3 py-2 font-normal">
                                            {{ user.roles[0]?.name }}
                                        </td>
                                        <td class=" text-center px-3 py-2 text-[#00AB55] font-medium">
                                            {{ user.count_order_notdecline }}
                                        </td>
                                        <td class=" text-center px-3 py-2 text-[#00AB55] font-medium">
                                            {{ user.count_order_decline }}
                                        </td>
                                        <td class=" text-left px-3 py-2  font-normal">
                                            {{ formatPrice(user.commission_sum_amount_received) }}đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-normal">
                                            {{ user.commission.length >0 ? formatPrice(user.commission[0].commission_percentage) : 0}} (%)
                                        </td>
                                        <td class=" text-left px-3 py-2 font-normal">
                                            {{ formatPrice(user.commission_sum_commission_amount)}}đ
                                        </td>
                                        <td class=" text-left px-3 py-2 font-normal">
                                            {{ formatPrice(user.commission_sum_commission_paid)}}đ
                                        </td>
                                        <td class=" text-left px-3 py-2 font-normal">
                                            {{ formatPrice(user.commission_sum_commission_amount - user.commission_sum_commission_paid)}}đ
                                        </td>
                                        <td class=" text-center px-3 py-2 font-normal">
                                            <Link v-if="user.roles[0]?.name != 'leader-sale'" :href="route('commission.dashboard.detail', user?.id)" target="_blank"
                                                v-tooltip.top="'Chi tiết gói'">
                                                <BaseIcon :path="mdiOpenInNew"
                                                    class=" text-gray-400 rounded-lg  mr-2 hover:text-blue-700" size="20">
                                                </BaseIcon>
                                            </Link>
                                            <Link v-else :href="route('commission.dashboard.detailleaderSale', user?.id)" target="_blank"
                                                v-tooltip.top="'Chi tiết gói'">
                                                <BaseIcon :path="mdiOpenInNew"
                                                    class=" text-gray-400 rounded-lg  mr-2 hover:text-blue-700" size="20">
                                                </BaseIcon>
                                            </Link>
                                        </td>
                                    </tr>

                                </tbody>
                                <tr colum="12">
                                    <th colspan="12">
                                        <PaginationDashboard  :links="users.links" class="w-full"/>
                                    </th>
                                </tr>
                            <tfoot>
                                <tr class="sticky bottom-0 bg-white">
                                    <th scope="col" class="px-3 py-2 text-center text-sm text-[#000000] font-bold ">
                                        Tổng
                                    </th>
                                    <th scope="col" class="text-center px-3 py-2  text-sm text-[#000000] font-normal">

                                    </th>
                                    <th scope="col" class="text-center px-3 py-2  text-sm text-[#000000] font-normal">

                                    </th>
                                    <th scope="col" class="text-center px-3 py-2 t text-sm text-[#000000] font-normal">
                                        {{ formatPrice(sumCommissionInfo.sum_count_order_notdecline) }}
                                    </th>
                                    <th scope="col" class="text-center px-3 py-2  text-sm text-[#000000] font-normal ">
                                        {{ formatPrice(sumCommissionInfo.sum_count_order_decline) }}
                                    </th>
                                    <th scope="col" class=" px-3 py-2 text-left text-sm text-[#000000] font-normal">
                                        {{ formatPrice(sumCommissionInfo.sum_amount_received) }}đ
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-sm text-[#000000] font-normal">

                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-sm text-[#000000] font-normal">
                                        {{ formatPrice(sumCommissionInfo.sum_commission_amount) }}đ
                                    </th>
                                    <th scope="col" class=" px-3 py-2 text-left text-sm text-[#000000] font-normal">
                                        {{ formatPrice(sumCommissionInfo.sum_commision_paid) }}đ
                                    </th>
                                    <th scope="col" class=" px-3 py-2 text-left text-sm text-[#000000] font-normal">
                                        {{ formatPrice(sumCommissionInfo.sum_commision_unpaid) }}đ
                                    </th>
                                    <th scope="col"
                                        class="whitespace-nowrap px-3 py-2 text-left text-sm text-[#000000] font-normal">
                                    </th>
                                </tr>
                            </tfoot>
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

