//stores/users.js

import { defineStore } from 'pinia'
// Import axios to make HTTP requests
import axios from "axios"
export const useCSKHStore = defineStore("cskh", {
    state: () => ({
        orders_transport: null,
        per_page: 10,
        statusGroup: null,
        count_orders: null,
        isLoading: false
    }),
    getters: {
      
    },
    actions: {
        async fetchOrdersTransport(params) {
            this.isLoading = true
            try {
                const data = await axios.get('/admin/cskh/fetchOrdersTransport', { params })
                this.orders_transport = data.data
                this.isLoading = false
            } catch (error) {
                this.isLoading = false
                console.log(error)
            }
        },
        async fetchStatusOrdersTransport() {
            try {
                const data = await axios.get('/admin/cskh/fetchOrdersTransportGroup')
                this.statusGroup = data.data.statusGroup
                this.count_orders = data.data.count_orders
            } catch (error) {

                console.log(error)
            }
        }
    },
})