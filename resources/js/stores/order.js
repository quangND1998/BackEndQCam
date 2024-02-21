import { defineStore } from 'pinia'
import { ref } from 'vue'
export const useOrderStore = defineStore('order', () => {
    const order = ref(null)
    const isShowModal = ref(false);
    const order_transport = ref(null)


    const showDetailOrder = (value) => {
        order.value = value
        isShowModal.value = true

    }

    const showDetailOrderTransport = (value) => {
        order_transport.value = value
        isShowModal.value = true

    }
    const closeModal = () => {
        order.value = null;
        order_transport.value = null
        isShowModal.value = false
    }




    return {
        order,
        order_transport,
        showDetailOrder,
        closeModal,
        showDetailOrderTransport
    }
})