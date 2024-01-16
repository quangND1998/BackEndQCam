import { onMounted, ref } from "vue";

const useCity = () => {
  const cities = ref({});
  const districts = ref({});
  const wards = ref({});
  const data = ref([]);

  onMounted(async () => {
    const response = await fetch('https://raw.githubusercontent.com/kenzouno1/DiaGioiHanhChinhVN/master/data.json');
    const jsonData = await response.json();

    data.value = jsonData;
    jsonData.forEach(city => {
      cities.value[encodeURIComponent(city.Name).slice(-12)] = city;
      city.Districts.forEach(district => {
        districts.value[encodeURIComponent(district.Name).slice(-12)] = district;
        district.Wards.forEach(ward => {
          wards.value[encodeURIComponent(ward.Name).slice(-12)] = ward;
        });
      });
    });
  });

  return {
    data,
    cities,
    districts,
    wards,
  }
}

export default useCity;
