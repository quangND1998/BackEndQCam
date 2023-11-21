<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination } from 'swiper/modules';
import 'swiper/css/pagination';
import 'swiper/css';
import Icon from '@/Components/Icon.vue'
const props = defineProps({
    tree: Object,
    contact: Object
});

</script>


<template>
    <div class="m-2  rounded-xl p-2" style="box-shadow: 0px 0px 3px 0px #00000040;">
        <swiper :slides-per-view="3" :space-between="4"     :navigation="true"
    :modules="modules" class="my-3 h-32">
            <swiper-slide class="rounded-lg " v-for="image in tree?.images" :key="image.id">
                <router-link to="" class=" w-full">
                    <div class="w-full">
                        <img :src="image?.original_url" class="h-32 w-full object-cover rounded-lg" alt="">
                    </div>
                </router-link>
            </swiper-slide>

        </swiper>
        <div class=" my-2 text-center">
            <h1 class="font-bold text-lg">CAM MẶT TRỜI</h1>
            <p class="text-xs font-bold text-[#8A8585]">MÃ CÂY</p>
            <h1 class="font-bold text-lg">{{ tree?.address }}</h1>
            <p class="text-sm font-medium text-[#8A8585]">Trạng thái: <span class="text-[#FF0000]">{{ tree?.product_service_owner ? 'đã được nhận nuôi' :  'Chưa được nhận nuôi'}}</span>
            </p>
            <div v-if="tree?.product_service_owner">
                <p class="text-sm font-medium text-[#8A8585]">Người nhận nuôi: <span class="text-[#FF0000]">Ông (Bà) {{ tree?.product_service_owner?.customer.name }}</span> </p>
                <p class="text-sm font-medium text-[#8A8585]">Thời gian nhận nuôi:</p>
                <p class="text-sm font-bold">từ ngày: {{ tree?.product_service_owner?.time_approve }} đến ngày {{ tree?.product_service_owner?.time_end }}</p>
            </div>
        </div>
    </div>
    <div id="tab" class="m-2  rounded-xl px-2 py-4 flex justify-center" style="box-shadow: 0px 0px 3px 0px #00000040;">
        <a class="mx-3" href="#tab">
            <div class="bg-[#F79521] px-4  py-4 rounded-2xl ">
                <Icon icon="detail" class=" " />
            </div>
            <h4 class="text-xs font-bold text-center">Chi tiết</h4>
        </a>
        <a class="mx-3" href="#tab_detail">
            <div class="bg-[#2E67A9] px-4  py-4 rounded-2xl ">
                <Icon icon="farm" class=" " />
            </div>
            <h4 class="text-xs font-bold text-center">Trang trại</h4>
        </a>
        <a class="mx-3"  href="#tab_farm">
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
            <p class="text-sm" v-html="tree?.description"> </p>
        </div>

    </div>
    <div id="tab_farm" class="m-2  rounded-sm  border-l-2  border-[#2E67A9] " style="box-shadow: 0px 0px 3px 0px #00000040;">
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
            {{ contact.map }}
        </div>

    </div>
    <div id="tab_verify" class="m-2  rounded-sm  border-l-2  border-[#35C901] " style="box-shadow: 0px 0px 3px 0px #00000040;">
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
<style scope>
/* .swiper-button-prev, .swiper-button-next {
    position: absolute;
    position: absolute;
    top: 40%;
    background-color: #00000087;
    z-index: 1000000;
    left: -10px;
    color: white;
}
.swiper-button-prev:after {
    content: '<';
} */
</style>
