import { defineStore } from 'pinia'
import { ref } from 'vue'
export const usePopOverStore = defineStore('popover', () => {
    const data = ref(null)
    const showPopover = ref(false);
    const timeoutRef = ref();


    const openPopover = (value) => {
        data.value = value
        timeoutRef.value = setTimeout(() => { showPopover.value = true }, 200);

    }
    const closePopover = () => {
        clearTimeout(timeoutRef.value);
        showPopover.value = false;
        data.value = null
    }

    return {
        data,
        showPopover,
        openPopover,
        closePopover
    }
})