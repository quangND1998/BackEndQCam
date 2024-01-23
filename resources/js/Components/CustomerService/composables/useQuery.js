import axios from "axios";
import { inject, ref, toValue } from "vue";

export const CUSTOMER_SERVICE_API_MAKER = {
  CREATE_REMIND: (customerId) => {
    return {
      url: `/customer-service/customer/${customerId}/reminds`,
      method: 'post'
    }
  },
  GET_NOTE: (customerId) => {
    return {
      url: `/customer-service/customer/${customerId}/notes`,
      method: 'get'
    }
  },
  CREATE_NOTE: (customerId) => {
    return {
      url: `/customer-service/customer/${customerId}/notes`,
      method: 'post',
    }
  },
  CREATE_VISIT: (customerId) => {
    return {
      url: `/customer-service/customer/${customerId}/schedule-visits`,
      method: 'post',
    }
  },
  CREATE_EXTRA_SERVICE: () => {
    return {
      url: `/extra-services`,
      method: 'post',
    }
  },
  UPDATE_EXTRA_SERVICE: () => {
    return {
      url: '/extra-services/$extraServiceId',
      method: 'put',
    }
  },
  CREATE_ORDER: (customerId) => {
    return {
      url: `/customer-service/customer/${customerId}/orders`,
      method: 'post',
    }
  },
  GET_PRODUCT_RETAIL: () => {
    return {
      url: '/customer-service/product-retails',
      method: 'get',
    }
  },
  UPDATE_ORDER: (customerId) => {
    return {
      url: `/customer-service/customer/${customerId}/orders/$orderId`,
      method: 'put',
    }
  },
  CREATE_COMPLAINT: (customerId) => {
    return {
      url: `/customer-service/customer/${customerId}/complaints`,
      method: 'post',
    }
  },
  CREATE_RETAIL_ORDER: () => {
    return {
      url: '/customer-service/orders',
      method: 'post',
    }
  },
  GET_RECENT_ACTIVITY: (customerId) => {
    return {
      url: `/customer-service/customer/${customerId}/recent-activities`,
      method: 'get',
    }
  },
  GET_USER_BY_PHONE_NUMBER: () => {
    return {
      url: `/customer-service/find-user-by-phone-number?phone_number=$phoneNumber`,
      method: 'get',
    }
  },
  GET_REMIND: () => {
    return {
      url: '/customer-service/reminds',
      method: 'get',
    }
  }
}

const updateUrl = (url, data) => {
  let newUrl = url;
  Object.keys(data).forEach(key => {
    newUrl = newUrl.replaceAll(`$${key}`, data[key]);
  });
  return newUrl;
}

const useQuery = (setupApi, inputData, successCallback, successMessage) => {
  const isLoading = ref(false);
  const responseData = ref(null);
  const error = ref(null);

  const swal = inject('$swal');

  const executeQuery = async (urlData, externalData) => {
    let { url, method } = setupApi;
    if (!url || !method) throw new Error('URL or method is not defined');

    if (urlData) {
      url = updateUrl(url, urlData);
    }

    isLoading.value = true;
    error.value = null;
    try {
      const { data } = await axios[method](url, externalData ? externalData : toValue(inputData));
      responseData.value = data;

      if (successCallback) {
        successCallback(responseData.value);
      }

      if (successMessage) {
        console.log('here');
        swal.fire({
          text: successMessage,
          timer: 2000,
          toast: true,
          position: 'top-end',
          icon: 'success',
          showConfirmButton: false,
        });
      }
    } catch (err) {
      error.value = err;
      console.log(err);
    } finally {
      isLoading.value = false;
    }
  }

  return {
    isLoading,
    data: responseData,
    error,
    executeQuery
  }
}

export default useQuery;
