<template>
    <div class="modal fade" id="modelAddBooking" tabindex="-1" role="dialog" aria-labelledby="modelAddService"
        aria-hidden="true">
        <div class="modal-dialog modal-xl rounded-2xl mx-auto mt-10 shadow-lg max-h-modal w-8/12   md:w-9/12 lg:w-8/12 xl:w-5/12 z-50 "
            role="document">
            <div class="modal-content">
                <div class="modal-header bg-[#B7AD75] ">
                    <div class="flex w-full">
                        <div class="ml-2 flex w-full justify-between">
                            <div>
                                <div class="flex">
                                    <h3 class="text-black font-bold my-1 text-base ">Thêm booking</h3>
                                    <div class="mx-3 w-[200px] text-[12px]">

                                        <Multiselect v-model="form.package" :createOption="false" :canClear="true"
                                            @select="findCustomer" :searchable="false" label="idPackages" valueProp="id"
                                            trackBy="idPackages" :options="packages" placeholder="Chọn hợp đồng"
                                            :appendNewOption="false"
                                            :classes="{
                                                tagsSearch: 'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                                                container: 'relative  border_round mx-auto w-full bg-gray-50  items-center  box-border cursor-pointer border border-gray-300 rounded bg-gray-50 text-sm  '
                                            }"
                                            >
                                            <template v-slot:singlelabel="{ value }">
                                                <div class="multiselect-single-label">
                                                    {{ value.idPackage }}
                                                </div>
                                            </template>

                                            <template v-slot:option="{ option }">
                                                {{ option.idPackage }}
                                            </template>
                                        </MultiSelect>
                                    </div>
                                </div>
                                <div class="w-full text-white py-3">
                                    <p class="">Tên Khách hàng: {{ form.name }}</p>
                                    <p>CCCD: {{ form.cccd }}</p>
                                    <p>SĐT: {{ form.phone }}</p>
                                </div>
                            </div>

                            <div class="">
                                <!-- <input name="start" type="date" v-model="form.date_time" time-picker-inline
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full pl-10 p-2.5 placeholder-gray-40"
                                    placeholder="Ngày bắt đầu" /> -->
                                    <VueDatePicker class=" "
                                    placeholder="Ngày bắt đầu" v-model="form.date_time" time-picker-inline />
                            </div>
                        </div>

                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body mx-3">
                    <div class="my-3 flex items-center w-full">
                        <label for="number_adult" class="block mx-2 w-1/5 text-sm  text-gray-900 dark:text-white">
                            Số người lớn *</label>


                        <MazInputNumber v-model="form.number_adult" placeholder="Enter number" :min="1" :max="10000"
                            size="md" color="secondary" style="width: 200px;" />
                    </div>
                    <div class="my-3 flex items-center w-full">
                        <label for="number_children" class="block mx-2 w-1/5 text-sm  text-gray-900 dark:text-white">
                            Số trẻ em *</label>

                        <MazInputNumber v-model="form.number_children" placeholder="Enter number" :min="0"
                            :max="10000" size="md" color="secondary" style="width: 200px;" />
                    </div>
                    <div class="my-3 flex items-center w-full">
                        <label for="number_adult" class="block mx-2 w-1/5 text-sm  text-gray-900 dark:text-white">
                            Dịch vụ *</label>
                        <div class="mx-3 flex gap-5 !flex-wrap">
                            <div class="flex items-center" v-for="service in serviceExtra" :key="service.id">
                                <input v-model="form.services" :id="`service_${service.id}`" :value="service.id"
                                    type="checkbox" class="focus:outline-none focus:ring-0 rounded" />
                                <label :for="`service_${service.id}`" class="m-0 select-none pl-2">{{ service.name
                                }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex mb-4">
                        <p class="w-28">Ghi chú</p>
                        <textarea v-model="form.description"
                            class="flex-1 resize-none rounded focus:border-[#AEAEAE] border-[#AEAEAE] px-2 py-1 text-sm focus:outline-none focus:ring-0"
                            rows="5"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button class="rounded-md bg-[#1D75FA] text-white font-semibold px-3 py-2 mb-2"
                            @click="executeQuery">
                            {{ form.id ? 'Cập nhật' : 'Đặt lịch' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<script setup>
import { emitter } from '@/composable/useEmitter';
import cloneDeep from 'lodash/cloneDeep';
import { computed, ref, inject, onMounted, onUnmounted, reactive } from "vue";
import Multiselect from '@vueform/multiselect'
import { useForm } from "@inertiajs/vue3";
import MazInputNumber from 'maz-ui/components/MazInputNumber'
import {
    mdiSquareEditOutline, mdiDeleteOutline, mdiCashMultiple, mdiEyeOutline, mdiCheckCircle, mdiCheckAll, mdiTrashCanOutline, mdiCheck
    , mdiOpenInNew, mdiRefresh, mdiBellRingOutline
} from '@mdi/js'
const swal = inject("$swal");
import { useHelper } from '@/composable/useHelper';
const { multipleSelect, formatDateOnly } = useHelper();
const props = defineProps({
    serviceExtra: Array,
    packages: Array
});
const form = useForm({
    id:null,
    name: null,
    cccd: null,
    phone: null,
    date_time: null,
    number_adult: 1,
    number_children: 0,
    serviceExtra: props.serviceExtra,
    description: null,
    package: null,
    services: [],
    product_service_owner_id: null
})
onMounted(() => {
    emitter.on('OpenModalAddBooking', (visit) => {
        form.reset();
        console.log('open model');
        $("#modelAddBooking").modal("show");
        console.log(visit);
        form.id = visit.id;
        form.package = visit.product_owner_service.order_package.id;
        form.name = visit.product_owner_service.customer?.name,
        form.cccd = visit.product_owner_service.customer?.cic_number,
        form.phone = visit.product_owner_service.customer?.phone_number,
        form.services =  multipleSelect(visit.extra_services)
        form.date_time = visit.date_time
        form.number_adult = visit.number_adult
        form.number_children = visit.number_children
        form.description = visit.description
    })
});
const findCustomer = () => {
    if (form.package) {
        console.log(form.package)
        const orderPackage = props.packages.find((package_) => package_.id == form.package)
        if (orderPackage) {
            form.name = orderPackage.customer?.name;
            form.cccd = orderPackage.customer?.cic_number;
            form.phone = orderPackage.customer?.phone_number;
            form.product_service_owner_id = orderPackage.product_service_owner?.id;
        }
        else {

        }
    }
}
const executeQuery = () => {
    console.log(form);
    if(form.id == null){
        form.post(route('visit.saveShedule'), {
            onError: () => {
            },
            onSuccess: () => {
                form.reset();
                $("#modelAddBooking").modal("hide");
            }
        });
    }else{
        form.post(route('visit.updateShedule', form.id), {
        onError: () => {

        },
        onSuccess: () => {
            form.reset();
                $("#modelAddBooking").modal("hide");

        }
    });
    }

}
const deleteService = (id) => {
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
                // form.post(route("admin.orders.package.deleteHistoryPayment", [form.order?.id, id]), { preserveState: false }, {
                //     onSuccess: () => {
                //         swal.fire(
                //             "Deleted!",
                //             "Your tree has been deleted.",
                //             "success"
                //         );
                //     },
                // });
            }
        });
}
const listener = () => {
}
onUnmounted(() => {
    emitter.off('OpenModalAddBooking', listener)
})
</script>

<style></style>
