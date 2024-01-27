import { defineStore } from 'pinia'
import { ref } from 'vue'
export const useOrderStore = defineStore('order', () => {
    const order = ref(null)
    const isShowModal = ref(false);
   


    const showDetailOrder = (value) => {
        order.value = value
        isShowModal.value =true

    }
    const closeModal= () => {
        order.value = null
        isShowModal.value =false
    }

    return {
        order,
        showDetailOrder,
        closeModal
    }
})