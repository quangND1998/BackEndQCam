<script setup>
import { computed, inject, onMounted, onUnmounted, ref } from 'vue';

const { customer } = inject('ORDER_PACKAGE_PAGE');

const props = defineProps({
  tableHeight: Number
});

const topPosition = computed(() => {
  return `calc(${props.tableHeight + 72}px) `;
});
const hidePhoneNumber = computed(() => {
  if (customer.phone_number) {
    return customer.phone_number.slice(0, -4).padEnd(customer.phone_number.length, '*');
  }
  return '';
})

const onCall = () => {
//   pitelSDK.value.call(customer.phone_number, {
//     extraHeaders: ['CALL-FROM: Cam mặt trời'],
//   });
pitelSDK.value.call('0968967624', {
    extraHeaders: ['CALL-FROM: Cam mặt trời'],
  });
}

const pitelSDK = ref(null);
const phoneCallReady = ref(true);
onMounted(() => {
  (function (a,b) {
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.onload = ()=>{PitelSDK.k=a;b()};
    s.src = 'https://portal.tel4vn.com/pitelsdk/sdk-1.1.5.min.js';
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
  })('d1ca84ac-2d98-4faa-92d4-699a6ce14eb7', ()=>{
    console.log('Pitel SDK Loaded');
  });
  setTimeout(function () {
    const sdkOptions = {
      enableWidget: false,
      sipOnly: true,
      sipDomain: 'greenholidays.vn',
      wsServer: "wss://cgvcall.mobilesip.vn:7444",
      sipPassword: "Agent@@2023!!",
      contactName: 'Cam mặt trời'
    }
    const sdkHook = {
      onRegistered: () => {
        console.log('onRegistered');
        phoneCallReady.value = true;
      },
      onUnregistered: () => {
        console.log('onUnregistered');
        phoneCallReady.value = false;
      },
      onCallCreated: () => {
        // Chỗ này để đổi UI thành đang gọi
        console.log('onCallCreated');
      },
      onCallAnswered: () => {
        // Chỗ này đổi UI thành đang nói chuyện
        console.log('onCallAnswered');
      },
      onCallHangup: () => {
        // Chỗ này đổi UI thành ngắt cuộc gọi
        console.log('onCallHangup');
      },
    }
    pitelSDK.value = new PitelSDK('xxx', 'xxx', '2200', sdkHook, sdkOptions);
  }, 500);
});

onUnmounted(() => {
  pitelSDK.value.destroy();
});
</script>

<template>
  <div title="Gọi cho khách hàng" @click="onCall" class="fixed right-[-100%] transition-position duration-700 p-2 pr-5 bg-white border !border-gray-400 rounded-l-full flex items-center gap-3 cursor-pointer" :style="`top: ${topPosition}`"
    :class="{
      '!right-0': phoneCallReady
    }"
  >
    <div class="w-10 h-10 flex items-center justify-center bg-[#27AE60] rounded-full">
      <i class="fa fa-phone text-white text-2xl" aria-hidden="true"></i>
    </div>
    <div>
      <p class="font-medium">{{ customer.name }}</p>
      <p class="font-medium">{{ hidePhoneNumber }}</p>
    </div>
  </div>

</template>
