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

  const swal = inject('$swal')

  const executeQuery = async (urlData, externalData) => {
    let { url, method } = setupApi;
    if (!url || !method) throw new Error('URL or method is not defined');

    if (urlData) {
      url = updateUrl(url, urlData);
      console.log(urlData, url);
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
