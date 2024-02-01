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

const onPhoneCallRead = () => {
  phoneCallReady.value = true;
}

const onCallCreated = () => {
  phoneCallStatus.value = 'CREATED';
}

const onCallAnswered = () => {
  phoneCallStatus.value = 'ANSWERED';
  if (intervalRef.value) clearInterval(intervalRef.value);
  intervalRef.value = setInterval(() => {
    callDuration.value += 1;
  }, 1000);
}

const onCallHangup = () => {
  clearInterval(intervalRef.value);
  callDuration.value = 0;
  phoneCallStatus.value = null;
}

const onCall = () => {
  if (phoneCallStatus.value === 'ANSWERED') {
    document.dispatchEvent(new CustomEvent('CMT_CALL_HANGUP'));
    clearInterval(intervalRef.value);
    callDuration.value = 0;
    phoneCallStatus.value = null;
    return;
  }

  if (phoneCallReady.value) {
    const callEvent = new CustomEvent('CMT_CALL', {
      detail: {
        phoneNumber: customer.phone_number,
      }
    });
    document.dispatchEvent(callEvent);
  }
}

const formatedTime = computed(() => {
  const minutes = Math.floor(callDuration.value / 60);
  const remainingSeconds = callDuration.value  % 60;
  return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
});

const intervalRef = ref(null);
const callDuration = ref(0);
const pitelSDK = ref(null);
const phoneCallReady = ref(false);
const phoneCallStatus = ref(null);
onMounted(() => {
  (function (a,b) {
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.onload = ()=>{PitelSDK.k=a;b()};
    s.src = '/assets/js/sdk-1.1.test.min.js';
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
  })('d1ca84ac-2d98-4faa-92d4-699a6ce14eb7', ()=>{
    console.log('Pitel SDK Loaded');
  });
  setTimeout(function () {
    const sdkOptions = {
      enableWidget: true,
      sipOnly: true,
      sipDomain: 'greenholidays.vn',
      wsServer: "wss://cgvcall.mobilesip.vn:7444",
      sipPassword: "Agent@@2023!!",
      contactName: 2200,
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
    pitelSDK.value = new PitelSDK('xxx', 'xxx', '2200', {}, sdkOptions);
  }, 500);

  document.addEventListener('CMT_CALL_READY', onPhoneCallRead);
  document.addEventListener('CMT_CALL_CREATED', onCallCreated);
  document.addEventListener('CMT_CALL_ANSWERED', onCallAnswered);
  document.addEventListener('CMT_CALL_HANGUP', onCallHangup);
});

onUnmounted(() => {
  pitelSDK.value.destroy();
  document.removeEventListener('CMT_CALL_READY', onPhoneCallRead);
  document.removeEventListener('CMT_CALL_CREATED', onCallCreated);
  document.removeEventListener('CMT_CALL_ANSWERED', onCallAnswered);
  document.addEventListener('CMT_CALL_HANGUP', onCallHangup);
});
</script>

<template>
  <div title="Gọi cho khách hàng" @click="onCall" class="fixed right-[-100%] transition-position duration-700 p-2 w-[200px] bg-white border !border-gray-400 rounded-l-full flex items-center gap-3 cursor-pointer" :style="`top: ${topPosition}`"
    :class="{
      '!right-0': phoneCallReady,
      '!w-[250px]': phoneCallStatus === 'ANSWERED'
    }"
  >
    <div class="w-10 h-10 flex items-center justify-center bg-[#27AE60] rounded-full relative"
      :class="{
        '!bg-[#EE2736]': phoneCallStatus === 'ANSWERED'
      }"
    >
      <span v-if="phoneCallStatus === 'CREATED'" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#27AE60] opacity-75"></span>
      <i class="fa fa-phone text-white text-2xl " aria-hidden="true"></i>
    </div>
    <div class="flex-1">
      <p class="font-medium">{{ customer.name }}</p>
      <p v-if="phoneCallStatus === 'CREATED'" class="animate-pulse">Đang gọi</p>
      <div v-else class="flex items-center justify-between w-full">
        <p class="font-medium">{{ hidePhoneNumber }}</p>
        <p v-if="phoneCallStatus === 'ANSWERED'">{{ formatedTime }}</p>
      </div>
    </div>
  </div>
  <div class="invisible">
    <div id="cs-customer-phone-number">{{ customer.phone_number }}</div>
  </div>
</template>
