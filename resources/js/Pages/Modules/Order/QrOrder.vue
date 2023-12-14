<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination } from 'swiper/modules';
import 'swiper/css/pagination';
import 'swiper/css';
import Icon from '@/Components/Icon.vue'
import VueQrcode from '@chenfengyuan/vue-qrcode';
const props = defineProps({
    order: Object,
    contact: Object
});
</script>


<template>
    <div class="m-2  rounded-xl p-2" style="box-shadow: 0px 0px 3px 0px #00000040;">
        <div class="flex ">
            <div>
                <vue-qrcode :value="`https://qly.cammattroi.com/order/qrcode/${order?.order_number}`" :options="{ width: 100 }" level="H"  class="h-16"></vue-qrcode>
                <!-- <img src="/assets/images/qr.png" class="h-36" alt=""> -->
                <!-- <qrcode-vue :value="`https://qly.cammattroi.com/order/qrcode/${order?.order_number}`" size="160" level="H"  class="h-36"></qrcode-vue> -->
            </div>
            <div class="mt-2">
                <h3 class="text-base font-bold">Đơn hàng: {{ order?.order_number }}</h3>
                <p class="text-sm font-bold">Trạng thái: <span :class="order?.status == 'pending' ? 'text-[#4F8D06]' : order?.status == 'completed' ? 'text-[#F78F43]' : 'text-[#ED5B00]'">{{ order?.status == 'pending' ? 'Đang giao' : order?.status == 'completed' ? 'Hoàn thành' : 'Hẹn giao' }}</span> </p>
                <p class="text-sm font-bold" >
                    Sản phẩm:
                     <span class="font-normal" v-for="item in order?.order_items" :key="item.id">
                        <h2 class="font-semibold ">{{item?.product?.name}} {{item?.quantity}} kg</h2>
                    </span>
                </p>
            </div>
        </div>
        <div v-if="order.type != 'retail' ">
            <p class="text-sm mx-3 mb-3">Đơn hàng được giao theo hợp đồng nhận nuôi 1 cây cam {{ order?.product_service?.trees?.[0].address }} có hiệu lực từ ngày {{formatDateOnly(order?.product_service?.time_approve) }} đến ngày {{ formatDateOnly(order?.product_service?.time_end) }} được Cam Mặt Trời phân phối!</p>
        </div>

    </div>
    <div class="m-2  rounded-xl px-2 py-4 flex justify-center" style="box-shadow: 0px 0px 3px 0px #00000040;">
        <a href="#tab_detail" class="mx-3">
            <div class="bg-[#F79521] px-4  py-4 rounded-2xl ">
                <Icon icon="detail" class=" " />
            </div>
            <h4 class="text-xs font-bold text-center">Chi tiết</h4>


        </a>
        <a href="#tab_detail2" class="mx-3">
            <div class="bg-[#2E67A9] px-4  py-4 rounded-2xl ">
                <Icon icon="farm" class=" " />
            </div>
            <h4 class="text-xs font-bold text-center">Trang trại</h4>


        </a>
        <a href="#tab_detail3" class="mx-3">
            <div class="bg-[#35C901] px-4  py-4 rounded-2xl ">
                <Icon icon="check" class=" " />
            </div>
            <h4 class="text-xs font-bold text-center">Xác thực</h4>


        </a>
    </div>
    <div id="tab_detail" class="m-2  rounded-sm  border-l-2  border-[#F79521] " style="box-shadow: 0px 0px 3px 0px #00000040;">
        <div class="flex justify-between w-full">
            <div class="flex items-baseline">
                <div class="bg-[#F79521] px-3 py-1">
                    <Icon icon="detail_small" />
                </div>
                <h1 class="text-base font-bold ml-4 mt-2">Chi tiết cây</h1>
            </div>
            <a href="https://cammattroi.vn/" class="px-5 py-2 mr-3 mt-2 rounded-3xl bg-[#F79521] text-white">Xem chi tiết</a>
        </div>
        <div class="m-3 pb-5">
            <p class="text-sm" v-html="order?.product_service?.trees?.[0].description"> </p>
        </div>

    </div>
    <div id="tab_detail2" class="m-2  rounded-sm  border-l-2  border-[#2E67A9] " style="box-shadow: 0px 0px 3px 0px #00000040;">
        <div class="flex justify-between w-full">
            <div class="flex items-baseline">
                <div class="bg-[#2E67A9] px-3 py-1">
                    <Icon icon="farm_small" />
                </div>
                <h1 class="text-base font-bold ml-4 mt-2">Trang trại</h1>
            </div>
            <a href="https://cammattroi.vn/" class="px-5 py-2 mr-3 mt-2 rounded-3xl bg-[#2E67A9] text-white">Xem chi tiết</a>
        </div>
        <div class="m-3 pb-3">
            <img src="/assets/images/cammattroi.png" alt="">
            <h1 class="text-lg font-bold">CÔNG TY CỔ PHẦN CAM MẶT TRỜI</h1>
            <p class="text-sm">Địa chỉ: {{ contact?.address }}</p>
            <p class="text-sm">Điện thoại: {{ contact?.hotline }} </p>
            <p class="text-sm">Địa chỉ trang trại: </p>
           <div v-html="contact.map"></div>
        </div>

    </div>
    <div id="tab_detail3" class="m-2  rounded-sm  border-l-2  border-[#35C901] " style="box-shadow: 0px 0px 3px 0px #00000040;">
        <div class="flex justify-between w-full">
            <div class="flex items-baseline">
                <div class="bg-[#35C901] px-3 py-1">
                    <Icon icon="check_small" />
                </div>
                <h1 class="text-base font-bold ml-4 mt-2">Xác thực</h1>
            </div>

        </div>
        <div class="m-3 pb-3">
            <p class="text-sm">Đây là tin nhắn xác nhận rằng bạn đang truy xuất nguồn sản phẩm tại Cam Mặt Trời. Nếu có nghi ngờ về bất kỳ thông tin giả mạo nào vui lòng liên hệ với chúng tôi để được trợ giúp!</p>
        </div>

    </div>
    <div class="my-5 mx-5">
        <h1 class="text-base font-bold">Tải App Cam Mặt Trời để tận hưởng!</h1>
        <img src="/assets/images/appstore.png" class="m-auto" alt="">
        <img src="/assets/images/ggplay.png" class="m-auto" alt="">
        <p class="text-lg italic mt-2">Cảm ơn quý khách đã sử dụng dịch vụ của cam mặt trời!</p>
    </div>
</template>
<style >
iframe{
    width: 90vw !important;
    height: 300px;
    margin-top: 5px;
}
</style>
