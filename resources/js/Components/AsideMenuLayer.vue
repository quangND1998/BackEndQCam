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
  <aside id="aside" class=" w-[260px] fixed flex z-40 top-0 h-screen transition-position overflow-hidden" style="box-shadow: var(0 0 #0000,0 0 #0000),var(0 0 #0000,0 0 #0000),var(-0 0 #0000);">
    <div class="aside flex-1 flex  items-center flex-col overflow-hidden ">
      <div class="aside-brand flex flex-row  items-center justify-between bg-white">
        <div class="items-center text-center flex-1 p-2">
            <img class="" src="/assets/images/cammattroi.png" alt="">
          <!-- <b class="font-[700]">{{ $page.props.auth.user.name }}</b> -->
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

<style scope>
.aside-menu-dropdown li:hover{
    background-color: rgb(246 247 250 / var(--tw-bg-opacity))
}
.aside-menu-dropdown li a{
    width: 75%;
    margin-left: 20%;
}
.aside-menu-dropdown li span{
    width: 10px;
    margin-right: 5px;
}
.aside-menu-dropdown li .aside-menu-item{

    border-radius: 8px;
    margin-left: 10px;
    padding: 10px 2px;
}
.aside-menu-dropdown li .aside-menu-item-active{
    color: blue !important;
    background-color: #ffffff00 !important;
}
.aside-menu-dropdown li  .aside-menu-item-active:hover{
    background-color: rgb(246 247 250 / var(--tw-bg-opacity)) !important;
}
</style>
