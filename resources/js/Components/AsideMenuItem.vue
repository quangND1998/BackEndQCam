<script setup>
import { ref, computed } from 'vue'
import { mdiMinus, mdiPlus, mdiChevronDown, mdiChevronUp } from '@mdi/js'
import { getButtonColor } from '@/colors.js'
import BaseIcon from '@/Components/BaseIcon.vue'
import AsideMenuList from '@/Components/AsideMenuList.vue'
import { Link } from '@inertiajs/vue3'
import { useDarkModeStore } from '@/stores/darkMode.js'
const darkModeStore = useDarkModeStore()

const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  isDropdownList: Boolean
})
const itemHref = computed(() => (props.item.route ? route(props.item.route) : props.item.href))
const activeInactiveStyle = computed(() => {
  //  console.log('activeInactiveStyle', props.item.route && route().current(props.item.route))
  //  console.log('current route', route().current(props.item.route) );
  if( props.item.route_list != null && props.item.route_list.includes(route().current(props.item.route)) == true){
    isDropdownActive.value = true;
  }
  if( props.item.group != null && route().current(props.item.route).includes(props.item.group) == true){
    isDropdownActive.value = true;
  }
//    console.log(props.item.route_list.includes(route().current(props.item.route)) );
  return ((props.item.route && route().current(props.item.route) ) || isDropdownActive.value == true )
    ? 'aside-menu-item-active font-bold'
    : ''
})
const activeMenuInactiveStyle = computed(() => {
  // console.log('activeInactiveStyle', props.item.route && route().current(props.item.route))
  return ( props.item.route && route().current(props.item.route) )
    ? 'bg-aside_menu_item_active'
    : ''
})
const emit = defineEmits(['menu-click'])

const hasColor = computed(() => props.item && props.item.color)

const asideMenuItemActiveStyle = computed(() =>
  hasColor.value ? '' : 'aside-menu-item-active text-aside_menu_item_active font-bold'
)

const isDropdownActive = ref(false)

const componentClass = computed(() => [
  props.isDropdownList ? 'py-3 px-6 text-sm' : 'py-3',
  hasColor.value
    ? getButtonColor(props.item.color, false, true)
    : `aside-menu-item dark:text-slate-300 dark:hover:text-white`
])

const hasDropdown = computed(() => !!props.item.menu)

const menuClick = (event) => {
  emit('menu-click', event, props.item)

  if (hasDropdown.value) {
    isDropdownActive.value = !isDropdownActive.value
  }
}
const checkOpenMenu = () => {

}
</script>

<template>
  <li class="text-black py-2.5">

    <component v-if="hasAnyPermission(item.permissions)" :is="item.route ? Link : 'div'" :href="itemHref"
      v-tooltip="item.label" :target="item.target ?? null" class="flex cursor-pointer"
      :class="[activeInactiveStyle]" @click="menuClick">
      <BaseIcon v-if="item.icon" :path="item.icon" class="flex-none " :class="activeInactiveStyle" w="w-16" :size="20" />
      <div v-else class="flex-none p-2" />
      <span v-if="item.icon" class="grow text-ellipsis line-clamp-1 text-[14px]" ::class="[{ 'pr-12': !hasDropdown }, activeInactiveStyle]">{{
        item.label }} </span>
      <span v-else class="grow text-ellipsis line-clamp-1 text-[12px]" ::class="[{ 'pr-12': !hasDropdown }, activeInactiveStyle]">{{
        item.label }}  </span>
      <BaseIcon v-if="hasDropdown" :path="isDropdownActive ? mdiChevronDown : mdiChevronUp" class="flex-none"
        :class="activeInactiveStyle" w="w-12" />

    </component>
    <AsideMenuList v-if="hasDropdown" :menu="item.menu"
      :class="['aside-menu-dropdown',  'block ' ]" is-dropdown-list />
  </li>

  <!-- :class="['aside-menu-dropdown', isDropdownActive ? 'block ' : 'hidden']" is-dropdown-list /> -->
</template>
<style >
/* scroll */
/* width */
::-webkit-scrollbar {
  width: 4px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1a8;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #d4d1d17e;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #cecccc5b;
}
</style>
