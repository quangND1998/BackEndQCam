import { ref } from "vue";
import { v4 as uuid } from 'uuid'

const useOrderControl = () => {
  const latestOrderNumber = ref(0);
  const newOrders = ref({});

  const onAddOrder = (orderPackage, packageIndex) => {
    if (Object.keys(newOrders).length === 12) return;
    const id = uuid();
    newOrders.value = {
      ...newOrders.value,
      [id]: {
        id,
        orderPackage,
        packageIndex,
      }
    };
  }

  const onCloseOrder = (orderId) => {
    delete newOrders.value[`order_${orderId}`];
  }

  const setLatestOrderNumber = (orderNumber) => {
    latestOrderNumber.value = orderNumber;
  }

  return {
    newOrders,
    setLatestOrderNumber,
    onAddOrder,
    onCloseOrder,
  }
}

export default useOrderControl;
