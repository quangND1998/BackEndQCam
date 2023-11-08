<script setup>
import { mdiLogout, mdiClose } from '@mdi/js'
import { computed } from 'vue'
import AsideMenuList from '@/Components/AsideMenuList.vue'
import AsideMenuItem from '@/Components/AsideMenuItem.vue'
import BaseIcon from '@/Components/BaseIcon.vue'

defineProps({
  menu: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['menu-click', 'aside-lg-close-click'])

const logoutItem = computed(() => ({
  label: 'Logout',
  icon: mdiLogout,
  color: 'bg-[#3B5F41]',
  isLogout: true
}))
const appName = import.meta.env.VITE_APP_NAME || 'Holomia App';
const menuClick = (event, item) => {
  emit('menu-click', event, item)
}

const asideLgCloseClick = (event) => {
  emit('aside-lg-close-click', event)
}
</script>

<template>
  <aside id="aside" class=" w-60 fixed flex z-40 top-0 h-screen transition-position overflow-hidden">
    <div class="aside flex-1 flex flex-col overflow-hidden  bg-bg_green_default text-white">
      <div class="aside-brand flex flex-row h-[80px] items-center justify-between bg-bg_green text-white">
        <div class="flex items-center text-center flex-1 p-3">
            <img class="h-10 mr-4" src="/assets/Images/avatar.png" alt="">
          <b class="font-[700]">{{ $page.props.auth.user.name }}</b>
        </div>
        <button class="hidden lg:inline-block xl:hidden p-3" @click.prevent="asideLgCloseClick">
          <BaseIcon :path="mdiClose" />
        </button>
      </div>
      <div class="flex-1 overflow-y-auto overflow-x-hidden  ">
        <AsideMenuList :menu="menu" @menu-click="menuClick" />
      </div>
      <!-- <label class="text-xs font-[200] mt-4 ml-4">SETTINGS</label> -->

      <ul>
        <AsideMenuItem :item="logoutItem" @menu-click="menuClick" />
      </ul>
    </div>
  </aside>
</template>
