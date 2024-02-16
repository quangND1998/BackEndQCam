<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import useQuery, { CUSTOMER_SERVICE_API_MAKER } from './composables/useQuery';

const props = defineProps({
  isCallable: Boolean,
  customer: Object,
  tableHeight: Number
});

const topPosition = computed(() => {
  if (!props.tableHeight) return;
  return `calc(${props.tableHeight + 72}px) `;
});
const hidePhoneNumber = computed(() => {
  if (props.customer.phone_number) {
    return props.customer.phone_number.slice(0, -4).padEnd(props.customer.phone_number.length, '*');
  }
  return '';
})

const onPhoneCallReady = () => {
  phoneCallReady.value = true;
}

const onCallCreated = () => {
  phoneCallStatus.value = 'CREATED';
  isActiveCall.value = true;
}

const onCallAnswered = () => {
  phoneCallStatus.value = 'ANSWERED';
  isActiveCall.value = true;
  if (intervalRef.value) clearInterval(intervalRef.value);
  intervalRef.value = setInterval(() => {
    callDuration.value += 1;
  }, 1000);
}

const onCallHangup = () => {
  clearInterval(intervalRef.value);
  callDuration.value = 0;
  phoneCallStatus.value = null;
  isActiveCall.value = false
  hasInCommingCall.value = false;
  userData.value = null;
  callingPhoneNumber.value = '';
}

const onCallReject = () => {
  if (intervalRef.value) {
    return document.dispatchEvent(new CustomEvent('CMT_CALL_HANGUP'));
  }
  document.dispatchEvent(new CustomEvent('CMT_CALL_REJECT'));

}
const onCallAccept = () => {
  document.dispatchEvent(new CustomEvent('CMT_CALL_ACCEPT'));
  if (intervalRef.value) clearInterval(intervalRef.value);
  intervalRef.value = setInterval(() => {
    callDuration.value += 1;
  }, 1000);
}

const onCall = () => {
  if (phoneCallStatus.value === 'ANSWERED') {
    document.dispatchEvent(new CustomEvent('CMT_CALL_HANGUP'));
    clearInterval(intervalRef.value);
    callDuration.value = 0;
    phoneCallStatus.value = null;
    isActiveCall.value = true;
    return;
  }

  if (phoneCallReady.value && phoneCallStatus.value !== 'RECEIVE_CALL') {
    const callEvent = new CustomEvent('CMT_CALL');
    document.dispatchEvent(callEvent);
  }
}

const formatedTime = computed(() => {
  const minutes = Math.floor(callDuration.value / 60);
  const remainingSeconds = callDuration.value  % 60;
  return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
});

const userData = ref(null);
const hasInCommingCall = ref(false);
const callingPhoneNumber = ref('');
const onReceiveCall = (e) => {
  executeQuery({ phoneNumber: e?.detail?.phoneNumber || '' });
  callingPhoneNumber.value = e?.detail?.phoneNumber || '';
  phoneCallStatus.value = 'RECEIVE_CALL';
  hasInCommingCall.value = true;
}
const { executeQuery, isLoading } = useQuery(
  CUSTOMER_SERVICE_API_MAKER.GET_USER_BY_PHONE_NUMBER(),
  undefined,
  (data) => {
    if (data.user) {
      userData.value = data;
    } else {
      userData.value = null;
    }
  }
);


const intervalRef = ref(null);
const callDuration = ref(0);
const pitelSDK = ref(null);
const phoneCallReady = ref(false);
const phoneCallStatus = ref(null);
const isActiveCall = ref(false);
onMounted(() => {
  (function (a,b) {
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.onload = ()=>{PitelSDK.k=a;b()};
    s.src = '/assets/js/sdk-1.1.test.min.js';
    // s.src = 'https://portal.tel4vn.com/pitelsdk/sdk-1.1.5.min.js';
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
  })('d1ca84ac-2d98-4faa-92d4-699a6ce14eb7', ()=>{
    console.log('Pitel SDK Loaded');
    const sdkOptions = {
      enableWidget: true,
      sipOnly: true,
      sipDomain: 'greenholidays.vn',
      wsServer: "wss://cgvcall.mobilesip.vn:7444",
      sipPassword: "Agent@@2023!!",
      contactName: 2200,
    }
    pitelSDK.value = new PitelSDK('xxx', 'xxx', '2200', {}, sdkOptions);
  });

  document.addEventListener('CMT_CALL_READY', onPhoneCallReady);
  document.addEventListener('CMT_CALL_CREATED', onCallCreated);
  document.addEventListener('CMT_CALL_ANSWERED', onCallAnswered);
  document.addEventListener('CMT_CALL_HANGUP', onCallHangup);
  document.addEventListener('CMT_RECEIVE_CALL', onReceiveCall);
});

onUnmounted(() => {
  pitelSDK.value.destroy();
  document.removeEventListener('CMT_CALL_READY', onPhoneCallReady);
  document.removeEventListener('CMT_CALL_CREATED', onCallCreated);
  document.removeEventListener('CMT_CALL_ANSWERED', onCallAnswered);
  document.removeEventListener('CMT_CALL_HANGUP', onCallHangup);
  document.removeEventListener('CMT_RECEIVE_CALL', onReceiveCall);
});
</script>

<template>
  <template v-if="isCallable">
    <div title="Gọi cho khách hàng" @click="onCall" class="fixed right-[-100%] transition-position duration-700 p-2 w-[200px] bg-white border !border-gray-400 rounded-l-full flex items-center gap-3 cursor-pointer" :style="`top: ${topPosition}`"
      :class="{
        '!right-0': phoneCallReady,
        '!w-[250px]': phoneCallStatus === 'ANSWERED' && isActiveCall
      }"
    >
      <div class="w-10 h-10 flex items-center justify-center bg-[#27AE60] rounded-full relative"
        :class="{
          '!bg-[#EE2736]': phoneCallStatus === 'ANSWERED' && isActiveCall
        }"
      >
        <span v-if="phoneCallStatus === 'CREATED'" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#27AE60] opacity-75"></span>
        <i class="fa fa-phone text-white text-2xl " aria-hidden="true"></i>
      </div>
      <div class="flex-1">
        <p class="font-medium">{{ customer?.name }}</p>
        <p v-if="phoneCallStatus === 'CREATED' && isActiveCall" class="animate-pulse">Đang gọi</p>
        <div v-else class="flex items-center justify-between w-full">
          <p class="font-medium">{{ hidePhoneNumber }}</p>
          <p v-if="phoneCallStatus === 'ANSWERED' && isActiveCall">{{ formatedTime }}</p>
        </div>
      </div>
    </div>
    <div class="invisible">
      <div id="cs-customer-phone-number">{{ customer?.phone_number }}</div>
    </div>
  </template>
  <div v-if="phoneCallStatus === 'RECEIVE_CALL'" class="fixed w-[200px] !right-0 h-[65px] bg-gray-200/40 cursor-not-allowed z-10 rounded-l-full" :style="`top: ${topPosition}`" />
  <div v-if="hasInCommingCall" class="fixed w-[302px] rounded-[10px] bottom-[100px] right-[30px]">
    <div class="rounded-t-[10px] bg-[#EE2736] py-2 px-3 text-white text-base font-semibold">
      Cuộc gọi đến...
    </div>
    <div class="bg-[#FFDFCB] min-h-[325px] rounded-b-[10px]">
      <div class="flex flex-col pt-3 justify-center items-center">
        <p v-if="isLoading" class="text-black text-base font-semibold mb-4 leading-[52px]">Đang kiểm tra thông tin...</p>
        <template v-else>
          <p class="text-black text-base font-semibold mb-1">
            {{ userData ? userData.user.name : 'Chưa có trong hệ thống' }}
          </p>
          <p class="text-black text-base font-semibold mb-4">
            {{ userData ? userData.user.phone_number.slice(0, -4).padEnd(userData.user.phone_number.length, '*') : callingPhoneNumber }}
          </p>
        </template>
        <p v-if="intervalRef" class="text-black font-medium text-2xl mb-3">{{ formatedTime }}</p>
        <div class="flex items-center gap-10">
          <div>
            <div class="w-[55px] h-[55px] cursor-pointer flex items-center justify-center bg-[#27AE60] rounded-full relative"
              @click="onCallAccept"
            >
              <i class="fa fa-phone text-white text-[34px] " aria-hidden="true"></i>
            </div>
            <p class="text-black text-base font-semibold mt-2">Đồng ý</p>
          </div>
          <div>
            <div class="w-[55px] h-[55px] cursor-pointer  flex items-center justify-center bg-[#EE2736] rounded-full relative"
              @click="onCallReject"
            >
              <i class="fa fa-phone text-white text-[34px] " aria-hidden="true"></i>
            </div>
            <p class="text-black text-base font-semibold mt-2">Từ chối</p>
          </div>
        </div>
      </div>
      <div class="px-6 mt-4" v-if="!isLoading && userData">
        <p class="text-black text-sm font-semibold mb-1">Hợp đồng hoạt động: {{ userData?.acitvePackage || 0 }}</p>
        <p class="text-black text-sm font-semibold">Hợp đồng ngừng: {{ userData?.endPackage || 0 }}</p>
      </div>
    </div>
  </div>
</template>
