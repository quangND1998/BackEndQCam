<script setup>
import { ref } from 'vue'
import { mdiClose, mdiDotsVertical } from '@mdi/js'
import { containerMaxW } from '@/config.js'
import BaseIcon from '@/Components/BaseIcon.vue'
import NavBarMenuList from '@/Components/NavBarMenuList.vue'
import NavBarItemPlain from '@/Components/NavBarItemPlain.vue'

defineProps({
  menu: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['menu-click'])

const menuClick = (event, item) => {
  emit('menu-click', event, item)
}

const isMenuNavBarActive = ref(false)
</script>

<template>
  <nav class="top-0 inset-x-0 fixed  h-14 z-30 transition-position w-screen lg:w-auto ">
    <div class="flex lg:items-stretch" :class="containerMaxW">
      <div class="flex flex-1 items-stretch h-14">
        <slot />
      </div>
      <div class="flex-none items-stretch flex h-14 lg:hidden">
        <NavBarItemPlain @click.prevent="isMenuNavBarActive = !isMenuNavBarActive">
          <BaseIcon :path="isMenuNavBarActive ? mdiClose : mdiDotsVertical" size="24" />
        </NavBarItemPlain>

      </div>
      <div
        class="max-h-screen-menu overflow-y-auto lg:overflow-visible absolute w-screen top-14 right-0  lg:w-auto lg:flex lg:static lg:shadow-none  left-0 justify-end"
        :class="[isMenuNavBarActive ? 'block' : 'hidden']">
        <NavBarMenuList :menu="menu" @menu-click="menuClick" />
      </div>
    </div>
  </nav>
</template>
<style scope>
.max-h-screen-menu{
    position: fixed;
    top: 0px;
}
</style>
