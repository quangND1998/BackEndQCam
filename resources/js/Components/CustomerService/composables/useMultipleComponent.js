import { ref } from 'vue';

const useMultipleComponent = (keyPrefix) => {
  const data = ref(new Map());

  const add = (key, inputObject) => {
    const objectKey = keyPrefix ? `${keyPrefix}_${key}` : key;
    if (data.value.has(objectKey)) {
      throw new Error(`Duplicate key ${objectKey}`);
    }

    data.value.set(objectKey, inputObject);
  }

  const remove = (key) => {
    const objectKey = keyPrefix ? `${keyPrefix}_${key}` : key;
    data.value.delete(objectKey);
  }

  const reset = () => {
    data.value.clear();
  }

  const get = (key) => {
    const objectKey = keyPrefix ? `${keyPrefix}_${key}` : key;
    return data.value.get(objectKey);
  }

  return [
    data,
    {
      add,
      remove,
      reset,
      get
    }
  ]
}

export default useMultipleComponent;
