<script setup>
import { mdiForwardburger, mdiBackburger, mdiMenu, mdiCheckCircle, mdiAlert } from '@mdi/js'
import { ref } from 'vue'
import menuAside from '@/menuAside.js'
import menuNavBar from '@/menuNavBar.js'
import { useDarkModeStore } from '@/stores/darkMode.js'
import BaseIcon from '@/Components/BaseIcon.vue'
import FormControl from '@/Components/FormControl.vue'
import NavBar from '@/Components/NavBar.vue'
import NavBarItemPlain from '@/Components/NavBarItemPlain.vue'
import AsideMenu from '@/Components/AsideMenu.vue'
import FooterBar from '@/Components/FooterBar.vue'
import { router } from '@inertiajs/vue3'
import NotificationBar from '@/Components/NotificationBar.vue'
import SectionMain from '@/Components/SectionMain.vue'
const layoutAsidePadding = 'xl:pl-72'

const darkModeStore = useDarkModeStore()


const isAsideMobileExpanded = ref(false)
const isAsideLgActive = ref(false)


router.on('navigate', () => {
  isAsideMobileExpanded.value = false
  isAsideLgActive.value = false
})


const menuClick = (event, item) => {

  if (item.isToggleLightDark) {
    darkModeStore.set()
  }

  if (item.isLogout) {
    // Add:
    router.post(route('logout'))
  }
}
</script>

<template>
  <div :class="{
    'overflow-hidden lg:overflow-visible': isAsideMobileExpanded
  }">

    <div :class="[layoutAsidePadding, { 'ml-60 lg:ml-0': isAsideMobileExpanded }]"
      class="pt-1 min-h-screen w-screen transition-position lg:w-auto ">
      <SectionMain>
        <NotificationBar v-if="$page.props.flash.warning || Object.keys($page.props.errors).length > 0" color="warning"
          :icon="mdiAlert">
          <span v-if="Object.keys($page.props.errors).length > 0">There are {{ Object.keys($page.props.errors).length }}
            form
            errors.</span>
          <span v-if="$page.props.flash.warning">{{ $page.props.flash.warning }}
         </span>
        </NotificationBar>
        <NotificationBar v-if="$page.props.flash.success" color="success" :icon="mdiCheckCircle">
          {{ $page.props.flash.success }}
        </NotificationBar>
      </SectionMain>

      <NavBar :menu="menuNavBar" :class="['ml-0', { 'ml-60 lg:ml-0': isAsideMobileExpanded }]" @menu-click="menuClick">
        <NavBarItemPlain display="flex lg:hidden" @click.prevent="isAsideMobileExpanded = !isAsideMobileExpanded">
          <BaseIcon :path="isAsideMobileExpanded ? mdiBackburger : mdiForwardburger" size="24" />
        </NavBarItemPlain>
        <NavBarItemPlain display="hidden lg:flex xl:hidden" @click.prevent="isAsideLgActive = true">
          <BaseIcon :path="mdiMenu" size="24" />
        </NavBarItemPlain>
      </NavBar>
      <AsideMenu :is-aside-mobile-expanded="isAsideMobileExpanded" :is-aside-lg-active="isAsideLgActive" :menu="menuAside"
        @menu-click="menuClick" @aside-lg-close-click="isAsideLgActive = false" />
      <slot />
      <FooterBar>

      </FooterBar>
    </div>
  </div>
</template>
