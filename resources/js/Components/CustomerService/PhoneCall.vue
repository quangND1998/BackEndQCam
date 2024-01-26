<script setup>
import { computed, inject, onMounted, onUnmounted, ref } from 'vue';

const { customer } = inject('ORDER_PACKAGE_PAGE');

const props = defineProps({
  tableHeight: Number
});

const topPosition = computed(() => {
  return `calc(${props.tableHeight + 56}px) `;
});
const hidePhoneNumber = computed(() => {
  if (customer.phone_number) {
    return customer.phone_number.slice(0, -4).padEnd(customer.phone_number.length, '*');
  }
  return '';
})

const onCall = () => {
  pitelSDK.value.call(customer.phone_number, {
    extraHeaders: ["CALL-FROM: PitelSDK"]
  })
}

const pitelSDK = ref(null);
const phoneCallReady = ref(false);
onMounted(() => {
  (function (a, b) {
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.onload = () => { PitelSDK.k = a; b() };
    s.src = '/assets/js/sdk-1.1.test.min.js';
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
  })('d1ca84ac-2d98-4faa-92d4-699a6ce14eb7', () => {
      console.log('Pitel SDK Loaded');
  });
  setTimeout(function () {
    const sdkOptions = {
      enableWidget: true,
      sipOnly: true,
      sipDomain: 'demo.cgvtelecom.vn:5060',
      wsServer: "wss://cgvcall.mobilesip.vn:7444",
      sipPassword: "Cgv@@2023##",
    }
    const sdkHook = {
      onRegistered: () => {
        phoneCallReady.value = true;
      },
      onUnregistered: () => {
        phoneCallReady.value = false;
      },
    }
    pitelSDK.value = new PitelSDK('xxx', 'xxx', '102', sdkHook, sdkOptions);
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
    <div class="w-10 h-10 flex items-center justify-center bg-emerald-500 rounded-full">
      <i class="fa fa-phone text-white text-2xl" aria-hidden="true"></i>
    </div>
    <div>
      <p class="font-medium">{{ customer.name }}</p>
      <p class="font-medium">{{ hidePhoneNumber }}</p>
    </div>
  </div>
</template>
