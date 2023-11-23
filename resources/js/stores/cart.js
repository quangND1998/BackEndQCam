import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useCartStore = defineStore('cart', () => {
  const cart = ref();
  const total_price = ref(0)
  const sub_total = ref(0)
    const updateCart =(data)=>{
      cart.value =data.cart;
      total_price.value =data.total_price
      sub_total.value =data.sub_total
    }
  return {
    cart,
    sub_total,
    total_price,
    updateCart
  }
})