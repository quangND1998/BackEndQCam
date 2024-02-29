import { defineStore } from 'pinia'
import axios from "axios"
import { useToast } from 'vue-toastification'

const toast = useToast()
export const useFailProduct = defineStore("failProduct", {
    state: () => ({
        products: null,
        per_page: 10,
        isLoading: false
    }),
    getters: {

    },
    actions: {
        async fetchProducts(params) {
            this.isLoading = true
            try {
                const data = await axios.get('/admin/product_fail/index', { params })
                this.products = data.data
                this.isLoading = false
            } catch (error) {
                this.isLoading = false
                console.log(error)
            }
        },

        async delete(id) {
            this.isLoading = true
            await axios.post(`/admin/product_fail/destroy/${id}`).then(response => {
                this.fetchProducts();
                toast.success(response.data, {
                    position: "bottom-center",
                    timeout: 5000,
                    closeOnClick: true,
                    pauseOnFocusLoss: true,
                    pauseOnHover: true,
                    draggable: true,
                    draggablePercent: 0.6,
                    showCloseButtonOnHover: false,
                    hideProgressBar: true,
                    closeButton: "button",
                    icon: true,
                    rtl: false
                });
            }).catch(error => {
                toast.warning(error.response.data, {
                    position: "bottom-center",
                    timeout: 5000,
                    closeOnClick: true,
                    pauseOnFocusLoss: true,
                    pauseOnHover: true,
                    draggable: true,
                    draggablePercent: 0.6,
                    showCloseButtonOnHover: false,
                    hideProgressBar: true,
                    closeButton: "button",
                    icon: true,
                    rtl: false
                });
                this.isLoading = false
            })
        },

    },
})
