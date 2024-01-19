<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
const props = defineProps({
    booking: Object
})

</script>
<template class="body_fix">
    <Head :title="`Quản lý mã booking: ${booking.ballot_number}`" />
    <h2 class="font-semibold text-[30px] w-full text-center">
                    Bảng ký nhận phiếu Booking
    </h2>
    <div class="p-3 mx-3 mt-10 rounded-xl">
        <div class="">
            <p class="w-full font-bold">Ngày xuất: <span class="px-3"></span></p>
            <p class="w-full font-bold">Đầu phiếu: <span class="px-3">{{ booking.ballot_number }}</span> </p>
            <p class="w-full font-bold">Số lượng: <span class="px-3">{{ booking.history_count }}</span> </p>

            <p class="w-full mt-2 font-bold">Từ <span class="px-3">{{ booking.history[0].ballot_code }} -  {{ booking.last_history.ballot_code }}</span> </p>
        </div>
        <div class="">
            <div class="w-full mt-2 ">
                <div class="flex flex-col">
                    <div class="overflow-x-auto inline-block min-w-full  sm:px-6 lg:px-8 m-0 p-0 ">
                        <table class="text-center border mx-auto text-sm font-light overflow-x-auto">
                            <thead class="font-medium">
                                <tr>
                                    <th class="px-3 border py-2 text-left">STT</th>
                                    <th class="px-3 border py-2 text-left">Mã booking</th>
                                    <th class="px-4 border py-2 text-left">Ref nhận</th>
                                    <th class="px-3 border py-2 text-left">Date giao</th>
                                    <th class="px-3 border py-2 text-left">Date thu về</th>
                                    <th class="px-3 border py-2 text-left">Tình trạng</th>
                                    <th class="px-3 border py-2 text-left">Ký nhận</th>

                                </tr>
                            </thead>
                            <tbody class="text-left">
                                <tr v-for="(code, index) in booking.history" :key="index">
                                    <td class="border text-left px-3 py-2 text-gray-500">{{ index + 1 }}</td>
                                    <td class="border text-left px-3 py-2 font-bold ">{{
                                        code?.ballot_code
                                    }}</td>
                                    <td class="border text-left  px-4">
                                        {{ code.ref?.name }} {{ code.ref?.phone_number }}
                                    </td>
                                    <td class="border text-left px-3 py-2  text-gray-500  ">
                                        <div class="flex items-center justify-between">
                                            {{ code.dateStart != null ? formatTimeDayMonthyear(code?.dateStart) :
                                                "dd/mm/yy" }}
                                        </div>
                                    </td>
                                    <td class="border text-left px-3 py-2  text-gray-500  ">
                                        <div class="flex items-center justify-between">
                                            {{ code.dateEnd != null ? formatTimeDayMonthyear(code?.dateEnd) : "dd/mm/yy"
                                            }}
                                        </div>

                                    </td>
                                    <td class="border text-left px-3 py-2 ">{{ code?.status }}</td>
                                    <td class="  text-left px-3   action flex items-center">

                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
