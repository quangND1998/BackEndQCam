<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import BaseButton from "@/Components/BaseButton.vue";
import { Head, Link } from "@inertiajs/vue3";
import {
    mdiEye,
    mdiAccountLockOpen,
    mdiPlus,
    mdiFilter,
    mdiMagnify,
    mdiDotsVertical,
    mdiTrashCanOutline,
    mdiCodeBlockBrackets,
    mdiPencil,
    mdiLandFields,
    mdiContentCopy,
    mdiCreditCardSettingsOutline,
    mdiContentSaveMove,
} from "@mdi/js";
import Multiselect from '@vueform/multiselect'
import InputError from "@/Components/InputError.vue";
import InputNumber from 'primevue/inputnumber';
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import { initFlowbite } from 'flowbite'
import Dropdown from 'primevue/dropdown';
import BaseIcon from '@/Components/BaseIcon.vue'
import NewOrderPackage from '@/Pages/Modules/Order/Create/NewOrderPackage.vue'
import UploadImage from "@/Components/UploadImage.vue"
import { vFullscreenImg } from 'maz-ui'
import axios from "axios";
const swal = inject("$swal");

const props = defineProps({
    order: Object,
    product_services: Array,
    trees: Array,
    sales: Array,
    leaders: Array,
    telesale: Array,
    ctv: Array,
    total_price: Number,
    sub_total: Number
});
const user = ref(null);
const search = ref(props.order?.customer?.name)
const searchPhone = ref(props.order?.customer?.phone_number)
const findUser = ref(false)
const flash = ref(null);
const provinces = ref(null)
const images = ref([])
const form = useForm({
    name: props.order?.customer?.name,
    phone_number: props.order?.customer?.phone_number,
    sex: props.order?.customer?.sex,
    address: props.order?.address,
    city: props.order?.city,
    district: props.order?.district,
    wards: props.order?.wards,
    vat: props.order?.vat,
    discount_deal: props.order?.discount_deal,
    type: 'retail',
    payment_method: props.order?.payment_method,
    shipping_fee: 0,
    time_reservations: props.order.time_reservations,
    price_percent: 0,
    product_selected: props.order?.product_service?.id,
    time_approve: new Date(),
    max_price: props.order.grand_total,
    images: [],
    ref_id: props.order.ref_id,
    leader_sale_id: props.order.to_id,
    type_customer_resource: props.order.customer_resources,
    customer_resource_id: props.order.customer_resources_id,
    idPackage: props.order.idPackage,
    order_number: props.order.order_number,
    total_paymented: props.order?.price_percent,

})

const getProvinces = async () => {
    const response = await fetch('https://raw.githubusercontent.com/kenzouno1/DiaGioiHanhChinhVN/master/data.json');
    const jsonData = await response.json();
    provinces.value = jsonData
};
getProvinces();

const districts = computed(() => {
    if (form.city == null) {
        return [];
    } else {
        if (provinces.value) {
            return provinces.value.find(pro => {
                return pro.Name == form.city;
            });
        }
        return []

    }
})

const wards = computed(() => {
    if (form.city == null && form.district == null) {
        return [];
    } else if (form.city !== null && form.district == null) {
        return [];
    } else {
        if (provinces.value) {
            let array = provinces.value.find(pro => {
                return pro.Name == form.city;
            });
            console.log('wards', array)
            if (array.Districts) {
                return array.Districts.find(district => {
                    return district.Name == form.district;
                });
            }
            return []

        }
        return []
    }
})
const onChangeCity = (event) => {
    form.district = null;
    form.wards = null;
}
const onChangeUser = (event) => {
    form.user_id = event.target.value
}
const onChangeDistrict = (event) => {
    // this.form.wards = null;
}
const foundUser = (data) => {
    form.name = data.name
    form.phone_number = data.phone_number
    form.sex = data.sex
    form.address = data.address
    form.city = data.city
    form.district = data.district
    form.wards = data.wards
    search.value = data.name;
    searchPhone.value = data.phone_number
}
const onSearchUser = async () => {
    if(search.value.length > 7 && search.value.includes(" ") == false && searchPhone.value != props.order?.customer?.phone_number){
    return axios.get(`/admin/orders/searchUser?search=${search.value}`).then(res => {
        console.log(res);
        if (res.data) {
                user.value = res.data;
                swal.fire({
                    text: "Số điện thoại này đã tồn tại, vui lòng kiểm tra lại",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                }).then((result) => {
                    if (result.isConfirmed) {
                    //     foundUser(res.data)
                    //     findUser.value = true;
                    // }else{
                        console.log('not user');
                        user.value = null
                        // flash.value = err.response.data
                        findUser.value = false;
                        search.value = props.order?.customer.name;
                        searchPhone.value = null;
                        form.reset()
                    }
                });
                // foundUser(res.data)

                flash.value = null;
        }
        }).catch(err => {
            console.log('not user');
        })
    }else{
        //  user.value = null
        //  form.reset()
        // searchPhone.value = null;
        //  findUser.value = false;
    }

}
const isNumber =  (value) => {
  return typeof value === 'number';
}
const onSearchUserPhone = async () => {
    console.log(isNumber(searchPhone.value));
    if(searchPhone.value.length > 7 && searchPhone.value != props.order?.customer?.phone_number){
    return axios.get(`/admin/orders/searchUser?search=${searchPhone.value}`).then(res => {
        console.log(res);
        if (res.data) {
                user.value = res.data;
                swal.fire({
                    text: "Số điện thoại này đã tồn tại, vui lòng kiểm tra lại",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                }).then((result) => {
                    if (result.isConfirmed) {
                         console.log('not user');
                        user.value = null
                        // flash.value = err.response.data
                        findUser.value = false;
                        search.value = props.order?.customer.name;
                        searchPhone.value = null;
                        form.reset()
                    }
                });
                // foundUser(res.data)

                flash.value = null;
        }
        }).catch(err => {
            console.log('not user');
        })
    }else{
        // user.value = null
        // form.reset()
        // search.value = null;
        // findUser.value = false;
    }
}
const save = () => {
    form.name = search.value;
    form.phone_number = searchPhone.value;
    if (form.name == null || form.phone_number == null) {
        swal.fire({
            title: "Lỗi?",
            text: "Chưa có thông tin khách hàng!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
        }).then((result) => {
            if (result.isConfirmed) {

            }
        });
    }
    else {
        form.post(route('admin.orders.package.editToCartPackage', props.order.id), {
            onError: () => {
            },
            onSuccess: () => {
                form.reset()

            }
        });
    }
}
const changeProduct = (event) => {
    form.product_selected = event.target.value;
    // if (product.value) {
    //     form.max_price = product.value.price
    // }

}
const product = computed(() => {
    // props.products.find(e => e.id == form.product_service);
    return props.product_services.find((e) => e.id == form.product_selected)
})
const DeleteImage = (index) => {
    form.images.splice(index, 1);
    images.value.splice(index, 1);
}
const onFileChange = (e) => {
    const files = e.target.files;

    if (files.length > 0) {
        for (var i = 0; i < files.length; i++) {
            form.images.push(files[i])
            images.value.push({
                name: files[i].name,
                image: URL.createObjectURL(files[i])
            });
        }
    }
    console.log(form.images)
}



const maxPrice = computed({


    get() {
        if (form.max_price > 0) {
            let maxPrice = form.max_price
            if (form.discount_deal > 0) {
                maxPrice = maxPrice - ((maxPrice * form.discount_deal) / 100)
            }
            if (form.vat > 0) {
                maxPrice += ((maxPrice * form.vat) / 100)
            }
            // form.price_percent = maxPrice;
            return maxPrice;
        }
        else {
            form.price_percent = 0;
            return 0
        }
    },
    // setter
    set(newValue) {

        console.log(newValue)
        if (newValue > 0) {
            let maxPrice = newValue
            if (form.discount_deal > 0) {
                maxPrice = newValue - ((newValue * form.discount_deal) / 100)
            }
            if (form.vat > 0) {
                maxPrice += ((maxPrice * form.vat) / 100)
            }
            console.log(maxPrice)
            // form.price_percent = maxPrice;
        }
        else {
            form.price_percent = 0;
        }
    }
})
const openAcceptPayment = (id) => {
    let query = {
        status: "complete"
      };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Duyệt khoản thanh toán này không`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {
                form.post(route("admin.orders.package.setPaymentComplete", [props.order?.id,id]),  { preserveState: false },{
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã thêm hợp đồng với khách hàng.", "success");
                    },
                });

            }
        });
}
const deleteHistory = (id) => {
    swal
        .fire({
            title: "Are you sure?",
            text: "Delete this History Payment!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.post(route("admin.orders.package.deleteHistoryPayment", [props.order?.id,id]), { preserveState: false }, {
                    onSuccess: () => {
                        swal.fire(
                            "Deleted!",
                            "Your tree has been deleted.",
                            "success"
                        );
                    },
                });
            }
        });
};
const date = ref(new Date());
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Quản lý đơn hàng" />
        <SectionMain class="p-3 mt-8">
            <div class="lg:container m-auto mt-10">
                <div class="min-[320px]:block sm:block md:block lg:grid grid-cols-3 gap-4 mt-10 p-2">
                    <div class="col-span-2">
                        <div class="min-[320px]:block md:flex border-b border-gray-200 pb-4">
                            <div class="min-[320px]:w-full md:w-1/2 px-0">
                                <div class="pb-3 ">
                                    <img src="/assets/images/cammattroi.png" alt="">
                                    <h1 class="text-base font-semibold uppercase">CÔNG TY CỔ PHẦN {{
                                        $page.props.company_infor?.name }}</h1>
                                    <p class="text-sm text-[#5F5F5F]">Địa chỉ: {{ $page.props.company_infor?.address }}</p>
                                    <p class="text-sm text-[#5F5F5F]">Farm:</p>
                                    <p class="text-sm text-[#5F5F5F]">Điện thoại: {{ $page.props.company_infor?.hotline }}
                                    </p>
                                    <p class="text-sm text-[#5F5F5F]">Email: {{ $page.props.company_infor?.email }}</p>
                                </div>
                            </div>
                            <div class="min-[320px]:w-full min-[320px]:mt-3 min-[320px]:px-0 md:w-1/2 md:mt-0 md:px-2">
                                <div class="w-full">
                                    <div class="flex items-center w-full">
                                        <p class="text-sm text-[#5F5F5F] w-28 ">Đơn hàng #</p>
                                        <input type="text" id="first_name" disabled v-model="form.order_number"
                                            class="  bg-gray-50 border-0 border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            :placeholder="form.order_number">
                                    </div>
                                    <div class="flex items-center w-full my-2">
                                        <p class="text-sm text-[#5F5F5F] w-28 ">Số phiếu #</p>
                                        <div class="w-full">
                                            <input type="text" id="first_name" v-model="form.idPackage"
                                                class="  bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="" required>
                                            <InputError class="mt-2" :message="form.errors.idPackage" />
                                        </div>
                                    </div>

                                    <div class="flex items-center w-full my-2">
                                        <p class="text-sm text-[#5F5F5F] w-28 ">Ngày</p>
                                        <div class="relative w-full">
                                            <VueDatePicker class="rounded-lg" v-model="date" time-picker-inline />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-3">
                            <p class="my-3 px-4 py-2 text-white bg-red-500 border border_round max-w-fit w-2/3"
                                v-if="order.exist_accept == false">Đơn hàng đã có khoản thanh toán được duyệt từ kế
                                toán, bạn nên lưu ý trước khi sửa. </p>
                            <div class="flex  items-center">

                                <h3 class="text-[17px] font-bold mr-[20px]">Thông tin liên hệ</h3>
                                <!-- <input type="string" id="first_name" v-model="search" @keyup="onSearchUser()"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-1/5 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Tìm kiếm SĐT" required> -->
                            </div>

                            <div class="min-[320px]:block md:grid grid-cols-2 gap-4 mt-2">
                                <div>
                                    <div class="my-3">
                                        <label for="name" class="block mb-2 text-sm  text-gray-900 dark:text-white">Tên
                                            Khách
                                            Hàng
                                            *</label>
                                        <input type="text" id="name" v-model="search" @keyup="onSearchUser()"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                        <div class="text-red-500" v-if="flash"> {{ flash }}</div>
                                    </div>
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Số điện thoại *</label>
                                        <input type="text" id="first_name"   v-model="searchPhone" @keyup="onSearchUserPhone()"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                    </div>
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Giới tính</label>
                                        <div class="flex">
                                            <div class="flex items-center ">
                                                <input id="default-radio-1" type="radio" value="male" name="default-radio"
                                                    v-model="form.sex"
                                                    class="w-4 h-4  text[#F78F43] bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="default-radio-1"
                                                    class="ms-2 text-sm  text-gray-900 dark:text-gray-300">Nam</label>
                                            </div>
                                            <div class="flex items-center mx-5">
                                                <input checked id="default-radio-2" type="radio" value="female"
                                                    v-model="form.sex" name="default-radio"
                                                    class="w-4 h-4 text[#F78F43] bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="default-radio-2"
                                                    class="ms-2 text-sm  text-gray-900 dark:text-gray-300">Nữ</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="min-[320px]:ml-0 md:ml-3">
                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Địa chỉ *</label>
                                        <input type="text" id="first_name" v-model="form.address" :disabled="findUser && user?.address != null"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="" required>
                                    </div>

                                    <div class="my-3">
                                        <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                            Thành phố *</label>
                                        <Dropdown v-model="form.city" :options="provinces" filter optionLabel="Name" :disabled="findUser && user?.city != null"
                                            @change="onChangeCity($event)" optionValue="Name" placeholder="Chọn tỉnh thành"
                                            class="w-full md:w-14rem bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round">
                                            <template #value="slotProps">

                                                <div v-if="slotProps.value" class="flex align-items-center">

                                                    <div>{{ slotProps.value }}</div>
                                                </div>
                                                <span v-else>
                                                    {{ slotProps.placeholder }}
                                                </span>
                                            </template>
                                            <template #option="slotProps">
                                                <div class="flex align-items-center">

                                                    <div>{{ slotProps.option.Name }}</div>
                                                </div>
                                            </template>
                                        </Dropdown>
                                        <InputError class="mt-2" :message="form.errors.city" />
                                    </div>
                                    <div class="my-3 min-[320px]:block md:flex">
                                        <div class="min-[320px]:w-full md:w-1/2 mr-2">
                                            <label for="first_name"
                                                class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                                Quận/huyện *</label>

                                            <Dropdown v-model="form.district" :options="districts.Districts" filter :disabled="findUser && user?.district != null"
                                                @change="onChangeDistrict($event)" optionLabel="Name" optionValue="Name"
                                                placeholder="Chọn Quận/huyện"
                                                class="w-full md:w-14rem bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round">
                                                <template #value="slotProps">

                                                    <div v-if="slotProps.value" class="flex align-items-center">

                                                        <div>{{ slotProps.value }}</div>
                                                    </div>
                                                    <span v-else>
                                                        {{ slotProps.placeholder }}
                                                    </span>
                                                </template>
                                                <template #option="slotProps">
                                                    <div class="flex align-items-center">

                                                        <div>{{ slotProps.option.Name }}</div>
                                                    </div>
                                                </template>
                                            </Dropdown>
                                            <InputError class="mt-2" :message="form.errors.district" />
                                        </div>
                                        <div class="min-[320px]:w-full md:w-1/2 ">
                                            <label for="first_name"
                                                class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                                Phường xã*</label>


                                            <Dropdown v-model="form.wards" :options="wards.Wards" filter optionLabel="Name" :disabled="findUser && user?.wards != null"
                                                optionValue="Name" placeholder="Chọn Phường xã"
                                                class="w-full md:w-14rem bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round">
                                                <template #value="slotProps">

                                                    <div v-if="slotProps.value" class="flex align-items-center">

                                                        <div>{{ slotProps.value }}</div>
                                                    </div>
                                                    <span v-else>
                                                        {{ slotProps.placeholder }}
                                                    </span>
                                                </template>
                                                <template #option="slotProps">
                                                    <div class="flex align-items-center">

                                                        <div>{{ slotProps.option.Name }}</div>
                                                    </div>
                                                </template>
                                            </Dropdown>
                                            <InputError class="mt-2" :message="form.errors.wards" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="overflow-x-auto col-span-2 mt-2 w-full">
                                <div class="relative shadow-md sm:border_round mb-2 mt-4">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">
                                                    Gói sản phẩm
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Tổng
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="bg-white border-b ">
                                                <td class="px-6 py-4 ">
                                                    <select id="countries" @change="changeProduct"
                                                        v-model="form.product_selected"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-60 p-2.5 ">
                                                        <option v-for="(product, index) in product_services" :key="index"
                                                            :value="product.id">{{
                                                                product.name }}</option>

                                                    </select>
                                                    <label for="first_name"
                                                        class="block mb-1 mt-4 text-sm  text-gray-900 dark:text-white">
                                                        Áp dụng từ ngày</label>
                                                    <div class="flex items-center w-60 ">
                                                        <VueDatePicker v-model="form.time_approve" time-picker-inline />
                                                    </div>
                                                </td>

                                                <td class="px-6  py-4">
                                                    <p type="number" class="border-[0px] text-[#686868] font-bold w-28">{{
                                                        formatPrice(product?.price) }}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="px-3">
                                    <p class="my-2 mt-3 mb-3 font-semibold">Lịch sử thanh toán</p>
                                    <table
                                        class="table table-striped w-full text-sm text-left text-gray-500 bg-white rounded-lg">
                                        <thead class="text-xs justify-between text-gray-700 uppercase">
                                            <tr class="info">
                                                <th>#</th>
                                                <th>Loại</th>
                                                <th>Ngày</th>
                                                <th>Số tiền</th>
                                                <th>Trạng thái duyệt</th>
                                                <th>Proof</th>
                                                <th>Người duyệt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="justify-between border-0 bg-white info"
                                                v-for="(payment, index) in props.order?.history_payment" :key="payment.id">
                                                <td class="border-0">
                                                    {{ index + 1 }}
                                                </td>
                                                <td class="border-0">
                                                    {{ payment?.payment_method == 'banking' ? 'Chuyển khoản' :
                                                        payment?.payment_method == "cash" ? 'Tiền mặt' : 'Payoo' }}
                                                </td>
                                                <td class="border-0">{{ payment?.payment_date }}</td>
                                                <td class="border-0">{{ formatPrice(payment?.amount_received) }}₫</td>
                                                <td class="border-0">
                                                    <p class="btn_label"
                                                        :class="payment?.status != 'complete' ? 'partiallyPaid' : 'paid'">
                                                        {{ payment?.status != 'complete' ? 'Chờ duyệt' : 'Đã duyệt' }}</p>
                                                </td>
                                                <td class="border-0 flex">

                                                    <div class="list_image flex"
                                                        v-for="image in payment.order_package_payment" :key="image.id">
                                                        <img  v-fullscreen-img class="w-[50px] h-[50px]" :src="image.original_url" alt="">
                                                    </div>
                                                </td>
                                                <td class="border-0">
                                                    {{ payment.status == 'complete' ? payment.user?.name : null }}
                                                    <button v-if="payment.status != 'complete'"
                                                        class="px-3 py-2 ml-3 text-white border rounded-lg bg-primary"
                                                        @click="openAcceptPayment(payment.id)">Duyệt </button>
                                                </td>
                                                <td class="border-0">
                                                    <BaseButton v-if="payment.status != 'complete' || hasAnyRoles(['Kế toán','super-admin'])" color="gray"
                                                        class="border-0 text=gray=300 hover:text-black"
                                                        :icon="mdiTrashCanOutline" small
                                                        @click="deleteHistory(payment.id)" />
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="bg-white border_round p-3">
                                    <div class="flex justify-between">
                                        <div class="mb-2">
                                            <!-- <font-awesome-icon :icon="['fas', 'cart-shopping']" class="mt-1" /> -->
                                            <span class="text-xl font-semibold ml-2">Quyền lợi nhận nuôi {{ product?.name
                                            }}</span>
                                        </div>

                                    </div>
                                    <hr />
                                    <div class="my-3">
                                        <div class="block ml-2 w-4/5 mb-2">
                                            <h3 class="text-base font-semibold">1. Thăm vườn không giới hạn</h3>
                                            <p class="text-xs font-normal">Nhận {{ product?.free_visit }} lần tham quan miễn
                                                phí</p>
                                        </div>
                                        <div class="block ml-2 w-4/5 mb-2">
                                            <h3 class="text-base font-semibold">2. Thu hoạch {{
                                                product?.amount_products_received }} kg
                                                cam</h3>
                                            <p class="text-xs font-normal">{{ product?.number_deliveries }} lần ship hàng về
                                                tận nhà</p>
                                        </div>
                                        <div class="block ml-2 w-4/5 mb-2">
                                            <h3 class="text-base font-semibold">3. Tặng thẻ Membership</h3>
                                            <p class="text-xs font-normal">Hưởng đặc quyền riêng từ trang trại</p>
                                        </div>
                                        <div class="block ml-2 w-4/5 mb-2">
                                            <h3 class="text-base font-semibold">4. Nhận nông sản sạch
                                                {{ product?.number_receive_product / product?.life_time }} lần/năm</h3>
                                            <p class="text-xs font-normal">Các sản phẩm nông sản như thanh long
                                                sầu riêng là quà tặng đến cho bạn</p>
                                        </div>
                                        <div class="block ml-2 w-4/5 mb-2">
                                            <h3 class="text-base font-semibold">5. Quà tặng thêm</h3>
                                            <p class="text-xs font-normal">Nhiều phần quà nông sản hấp dẫn trị tổng
                                                trị giá xx triệu</p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>


                    </div>
                    <div class="min-[320px]:mx-0 md:mx-5">

                        <div class="w-full mb-3 flex">
                            <div class="w-1/4 mr-3">
                                <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                    VAT(%)</label>
                                <!-- <input type="number" id="first_name" min="0" max="100" v-model="form.vat"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="" required> -->
                                <InputNumber v-model="form.vat" :min="0" :max="100"
                                    inputClass="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                            </div>
                            <div class="w-1/4 mr-3">
                                <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                    Ưu đãi (%)</label>
                                <!-- <input type="number" id="first_name" v-model="form.discount_deal" min="0" max="100"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="" required> -->
                                <InputNumber v-model="form.discount_deal" :min="0" :max="100"
                                    inputClass="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                            </div>
                            <div class="w-1/2">
                                <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                    Thời gian giữ chỗ (ngày)</label>
                                <!-- <input type="number" id="first_name" v-model="form.time_reservations"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="" required> -->
                                <InputNumber v-model="form.time_reservations" :min="0" :max="100"
                                    inputClass="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />

                            </div>
                        </div>
                        <div class="w-full flex">
                            <div class="w-1/2 mr-3">
                                <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                    Số tiền</label>
                                <!-- <MazInputPrice v-model="form.price_percent" currency="VND" locale="vi-VN" :min="0" :disabled="order?.history_payment.length > 0"
                                    :max="maxPrice" @formatted="formattedPrice = $event" /> -->
                                    <InputNumber v-model="form.price_percent" :min="0"  :max="maxPrice"
                                    inputClass="bg-gray-50 border border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                            </div>
                            <div class="w-1/2">
                                <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                    Thanh toán</label>
                                <select id="countries" v-model="form.payment_method"
                                    class="bg-gray-50 border border_round border-gray-300 text-gray-900 text-sm border_round focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="cash">Tiền mặt</option>
                                    <option value="banking">Chuyển khoản</option>
                                    <option value="payoo">Payoo</option>
                                </select>
                            </div>
                        </div>
                        <p class="my-3 px-2 py-2 text-white bg-red-500 border border_round max-w-fit text-[12px] "
                                v-if="order?.history_payment.length > 0">Muốn sửa khoản thanh toán cần xóa hết thanh toán đã có.</p>
                        <div class="my-2">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                NV tư vấn bán hàng(Ref)</label>
                            <!-- <Multiselect v-model="form.ref_id"  :appendNewTag="false" :createTag="false"
                            :searchable="true" label="name" valueProp="id" trackBy="name" :options="sales"  placeholder="None"
                           /> -->

                            <Multiselect v-model="form.ref_id" :searchable="true" label="name" valueProp="id"
                                trackBy="name" placeholder="None" :options="sales" :classes="{
                                    tagsSearch: 'absolute  inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                                    container: 'relative border_round mx-auto w-full bg-gray-50  items-center  box-border cursor-pointer border border-gray-300 rounded bg-gray-50 text-sm  '
                                }">
                                <template v-slot:singlelabel="{ value }">
                                    <div class="multiselect-single-label">
                                        {{ value.name }} (Team: {{ value.team?.name }})
                                    </div>
                                </template>

                                <template v-slot:option="{ option }">
                                    {{ option.name }} (Team: {{ option.team?.name }})
                                </template>
                            </Multiselect>
                        </div>
                        <div class="my-2">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Chọn TO(Người chốt đơn)</label>
                            <!-- <Multiselect v-model="form.leader_sale_id" :appendNewTag="false" :createTag="false"
                                :searchable="true" label="name" valueProp="id" trackBy="name" :options="leaders"
                                placeholder="None" /> -->
                            <Multiselect v-model="form.leader_sale_id" :searchable="true" label="name" valueProp="id"
                                trackBy="name" placeholder="None" :options="leaders" :classes="{
                                    tagsSearch: 'absolute  inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                                    container: 'relative border_round mx-auto w-full bg-gray-50  items-center  box-border cursor-pointer border border-gray-300 rounded bg-gray-50 text-sm  '
                                }">
                                <template v-slot:singlelabel="{ value }">
                                    <div class="multiselect-single-label">
                                        {{ value.name }} ({{ value.email }})
                                    </div>
                                </template>

                                <template v-slot:option="{ option }">
                                    {{ option.name }} ({{ option.email }})
                                </template>
                            </Multiselect>
                        </div>
                        <div class="my-2">
                            <label for="first_name" class="block mb-2 text-sm  text-gray-900 dark:text-white">
                                Nguồn khách hàng</label>
                            <div class="flex items-center justify-center mb-2">

                                <input class=" mr-2" type="radio" id="one" value="telesale"
                                    v-model="form.type_customer_resource" />
                                <label for="one" class="w-[80px] mr-2">Telesale</label>
                                <Multiselect v-model="form.customer_resource_id" :appendNewTag="false" :createTag="false"
                                    :disabled="form.type_customer_resource == 'telesale' ? false : true" :searchable="true"
                                    label="name" valueProp="id" trackBy="name" :options="telesale" placeholder="None"
                                    :classes="{
                                        container: 'relative border_round mx-auto w-full bg-gray-50  items-center  box-border cursor-pointer border border-gray-300 rounded bg-gray-50 text-sm  '
                                    }" />
                            </div>


                            <div class="flex items-center justify-center mb-2">

                                <input class=" mr-2" type="radio" id="one" value="ctv"
                                    v-model="form.type_customer_resource" />
                                <label for="one" class="w-[80px] mr-2">CTV</label>
                                <!-- <Multiselect v-model="form.customer_resource_id" :appendNewTag="false" :createTag="false"
                                    :disabled="form.type_customer_resource == 'ctv' ? false : true" :searchable="true"
                                    label="name" valueProp="id" trackBy="name" :options="ctv" placeholder="None" /> -->
                                <Multiselect v-model="form.customer_resource_id"
                                    :disabled="form.type_customer_resource == 'ctv' ? false : true" :searchable="true"
                                    label="name" valueProp="id" trackBy="name" placeholder="None" :options="ctv" :classes="{
                                        container: 'relative mx-auto border_round w-full bg-gray-50  items-center  box-border cursor-pointer border border-gray-300 rounded bg-gray-50 text-sm  '
                                    }">
                                    <template v-slot:singlelabel="{ value }">
                                        <div class="multiselect-single-label">
                                            {{ value.name }} ({{ value.email }})
                                        </div>
                                    </template>

                                    <template v-slot:option="{ option }">
                                        {{ option.name }} ({{ option.email }})
                                    </template>
                                </Multiselect>
                            </div>
                            <div class=" mb-2">

                                <input class=" mr-2" type="radio" id="one" value="private"
                                    v-model="form.type_customer_resource" />
                                <label for="one" class="w-[80px] mr-2">Private</label>
                            </div>
                            <div class="my-3" v-if="order?.history_payment.length == 0">
                                <UploadImage :max_files="4" v-model="form.images" :multiple="true"
                                    :label="`Chứng từ liên quan`" />
                                <InputError class="mt-2" :message="form.errors.images" />
                                <div v-for="(error, index) in images" :key="index">
                                    <InputError class="mt-2" :message="form.errors[`images.${index}`]" />
                                </div>
                            </div>
                            <div class="p-2 bg-gray-100">
                                <div class="flex justify-between my-2">
                                    <p class="text-sm text-[#686868] font-bold">Tổng</p>
                                    <p class="text-sm text-[#686868] font-bold">{{ formatPrice(product?.price) }} VND</p>
                                </div>
                                <div class="flex justify-between my-2">
                                    <p class="text-sm text-[#686868] font-bold">VAT({{ form.vat }}%)</p>
                                    <p class="text-sm text-[#686868] font-bold">{{ formatPrice(form.vat * (product?.price +
                                        ((form.discount_deal * product?.price) / 100)) / 100) }} vnd
                                    </p>
                                </div>
                                <div class="flex justify-between my-2">
                                    <p class="text-sm text-[#686868] font-bold">Vận chuyển</p>
                                    <p class="text-sm text-[#686868] font-bold">Miễn phí</p>
                                </div>
                                <div class="flex justify-between my-2">
                                    <p class="text-sm text-[#686868] font-bold">Ưu đãi</p>
                                    <p class="text-sm text-[#686868] font-bold">{{ formatPrice((form.discount_deal *
                                        product?.price) /
                                        100) }} VND
                                    </p>
                                </div>
                                <div class="flex justify-between my-2">
                                    <p class="text-sm text-[#686868] font-bold">Tổng cộng</p>
                                    <p class="text-sm text-[#686868]">{{ formatPrice(maxPrice) }} vnd</p>
                                </div>
                                <div class="flex justify-between my-2">
                                    <p class="text-sm text-[#686868]">Đã thanh toán</p>
                                    <p class="text-sm text-[#686868] font-bold">{{ formatPrice(form.total_paymented + form.price_percent) }} VND
                                    </p>
                                </div>
                                <div class="flex justify-between my-2">
                                    <p class="text-sm text-[#686868] font-bold" v-if="((product?.price +
                                        ((form.vat * product?.price) / 100) - ((form.discount_deal * product?.price) / 100)) -
                                        form.price_percent) > 0">Còn thiếu</p>
                                    <p v-else class="text-sm text-[#686868] font-bold">Còn thừa</p>
                                    <p class="text-sm text-[#ec5353] font-bold">{{ formatPrice(maxPrice - form.total_paymented -
                                        form.price_percent) }} VND</p>
                                </div>
                                <div class="my-3">
                                    <BaseButton color="info" @click="save()"
                                        class="bg-orange-500 hover:bg-orange-600 text-white p-2 w-full text-center justify-center border_round"
                                        :icon="mdiContentSaveMove" small label="Lưu hợp đồng" />
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- <NewOrderPackage :product_services="product_services" :trees="trees" :user="user" :cart="cart" :total_price="total_price"
                :vat="form.vat" :discount_deal="form.discount_deal" :shipping_fee="form.shipping_fee" :time_reservations="form.time_reservations"
                :price_percent="form.price_percent" :product_selected ="form.product_selected" :time_approve ="form.time_approve"
                :payment_method="form.payment_method" :type="form.type" :sub_total="sub_total" @confirm="save" /> -->
            <div class="min-[320x]:block sm:block md:grid grid-cols-3 gap-4">

            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>










































