import { onMounted, ref } from "vue";

const useCity = () => {
  const cities = ref({});
  const districts = ref({});
  const data = ref([]);

  onMounted(async () => {
    const response = await fetch('https://raw.githubusercontent.com/kenzouno1/DiaGioiHanhChinhVN/master/data.json');
    const jsonData = await response.json();

    data.value = jsonData;
    jsonData.forEach(city => {
      cities.value[encodeURIComponent(city.Name)] = city;
      city.Districts.forEach(district => {
        districts.value[encodeURIComponent(district.Name)] = district;
      });
    });
  });

  return {
    data,
    cities,
    districts,
  }
}

export default useCity;
