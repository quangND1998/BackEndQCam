//stores/users.js

import { defineStore } from 'pinia'
// Import axios to make HTTP requests
import axios from "axios"
export const useCSKHStore = defineStore("cskh", {
    state: () => ({
        orders: null,
        per_page: 10,
        statusGroup: null,
        count_orders: null,
        isLoading: false
    }),
    getters: {
        getOrders(state) {
            return state.users
        }
    },
    actions: {
        async fetchOrders(params) {
            this.isLoading = true
            try {
                const data = await axios.get('/admin/cskh/fetchOrders', { params })
                this.orders = data.data
                this.isLoading = false
            } catch (error) {
                this.isLoading = false
                console.log(error)
            }
        },
        async fetchStatusOrders() {
            try {
                const data = await axios.get('/admin/cskh/fetchStatusOrders')
                this.statusGroup = data.data.statusGroup
                this.count_orders = data.data.count_orders
            } catch (error) {

                console.log(error)
            }
        }
    },
})