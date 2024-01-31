<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModalFull.vue";
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
    mdiLandFields
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";

import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import Multiselect from '@vueform/multiselect'
import VueDatepickerUi from 'vue-datepicker-ui'
import 'vue-datepicker-ui/lib/vuedatepickerui.css';
const props = defineProps({
    product_retails: Object,
    historyAdds: Object,
});
const searchVal = ref("");
const swal = inject("$swal");
const form = useForm({
    id: null,
    unit: null,
    expected_quantity: null,
    actual_quantity: null,
    date_add: null,
    date_expire: null,
    product_retail_id: null,
});
const isModalActive = ref(false);
const editMode = ref(false);
const isModalDangerActive = ref(false);
const product_retail = ref(null)
const state = reactive({
    content: '<p>2333</p>',
    _content: '',
    editorOption: {
        placeholder: 'core',
        modules: {

        },

    },
    disabled: false
})

const edit = (historyAdd) => {
    isModalActive.value = true;
    editMode.value = true;
    form.id = historyAdd.id;
    form.unit = historyAdd.unit;
    form.expected_quantity = historyAdd.expected_quantity;
    form.actual_quantity = historyAdd.actual_quantity;
    form.date_add = historyAdd.date_add;
    form.date_expire = historyAdd.date_expire;
    form.state = historyAdd.state;
    form.state_confirm = historyAdd.state_confirm;
    form.product_retail_id = historyAdd.product_retail_id;
    product_retail.value= historyAdd;
};

const save = () => {
    console.log(form);
    if (editMode.value == true) {
        form.post(route("admin.importProduct.update", form.id), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = true;
            },
            onSuccess: () => {
                form.reset();
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    } else {
        form.post(route("admin.importProduct.store"), {
            onError: () => {
                isModalActive.value = true;
                editMode.value = false;
            },
            onSuccess: () => {
                form.reset();
                isModalActive.value = false;
                editMode.value = false;
            },
        });
    }

};


const Delete = (id) => {
    swal
        .fire({
            title: "Are you sure?",
            text: "Xóa Sản phẩm!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Xóa!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.delete(route("admin.product-retail.destroy", id), {
                    onSuccess: () => {
                        swal.fire(
                            "Deleted!",
                            "Đã xóa sản phẩm.",
                            "success"
                        );
                    },
                });
            }
        });
};
</script>
<template>
    <LayoutAuthenticated>
        <SectionMain class="p-3 mt-8">
            <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                <div>
                    <h2 class="font-semibold  flex mr-2">
                        Bảng kế hoạch hàng về
                    </h2>
                </div>
            </div>
            <div class="flex my-2">
                <div class="right">
                    <Button color="info" class="bg-[#4F8D06] text-white py-2 px-3 rounded "
                     @click="isModalActive = true;form.reset();"  >Thêm nhập hàng</Button>
                </div>
            </div>
            <div v-if="isModalActive" buttonLabel="Save" has-cancel @confirm="save"
                :title="editMode ? 'Sửa' : 'Nhập hàng'" >
                <div class="flex  ">
                    <div class="w-[300px]">
                        <InputLabel for="ballot_code" value="Mặt hàng" />
                        <Multiselect v-model="form.product_retail_id" :searchable="true" label="name" valueProp="id" trackBy="name" placeholder="Chọn mặt hàng"  :options="product_retails" :classes="{
                            tagsSearch: 'absolute bg-[#E9E9E9]  inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
                            container: 'relative border_round mx-auto w-full bg-gray-50  items-center  box-border cursor-pointer border border-gray-300 rounded bg-[#E9E9E9] text-sm  '
                            }" >
                                <template v-slot:singlelabel="{ value }">
                                    <div class="multiselect-single-label">
                                        {{ value.name }} ({{ value.code }})
                                    </div>
                                </template>

                                <template v-slot:option="{ option }">
                                    {{ option.name }} ({{ option.code }})
                                </template>
                        </Multiselect>
                    </div>
                    <div class=" px-2">
                        <InputLabel for="ballot_code" value="Số lượng dự kiến" />
                        <input type="number" class="border rounded " :min="0" v-model="form.expected_quantity">
                    </div>
                    <div class=" px-2">
                        <InputLabel for="ballot_code" value="Số lượng thực tế" />
                        <input type="number" class="border rounded-xl " :min="0" v-model="form.actual_quantity">
                    </div>
                    <div class=" px-2">
                        <InputLabel for="ballot_code" value="Ngày nhập" />
                        <VueDatePicker class="py-1" v-model="form.date_add" time-picker-inline>
                                        </VueDatePicker>
                    </div>
                    <div class=" px-2">
                        <InputLabel for="ballot_code" value="Hạn sử dụng" />
                        <VueDatePicker class="py-1" v-model="form.date_expire" time-picker-inline>
                                        </VueDatePicker>
                    </div>
                    <div class=" px-2">
                        <button class="p-2 mt-[32px] bg-[#4F8D06] rounded text-white" @click="save()">Thêm</button>
                    </div>
                </div>
            </div>
            <div class="mt-3">

                <div class="w-full mt-2 ">
                    <div class="bg-[#AEAEAE] text-white  grid grid-cols-12 divide-x">
                        <div class="text-center py-2 border">STT</div>
                        <div class="text-center py-2 border">Mã</div>
                        <div class="text-center py-2 border">Mặt hàng dự kiến</div>
                        <div class="text-center py-2 border">SL dự kiến</div>
                        <div class="text-center py-2 border">Đơn vị tính</div>
                        <div class="text-center py-2 border">Date về kho</div>
                        <div class="text-center py-2 border">Hạn sử dụng</div>
                        <div class="text-center py-2 border">Tình trạng</div>
                        <div class="text-center py-2 border">Số lượng về</div>
                        <div class="text-center py-2 border">Trạng thái</div>
                        <div class="text-center py-2 border">Chi tiết</div>
                        <div class="text-center py-2 border">Xác nhận</div>

                    </div>
                    <div v-for="(history, index) in historyAdds.data" :key="history.id"
                    class="grid grid-cols-12 divide-x divide-gray-400 border-gray-400 border-b border-x text-sm bg-white">
                        <div class="text-center border">{{ index + historyAdds.from }}</div>
                        <div class="pl-2 text-[#FF0000] border">{{ history.product_retail?.cod }}</div>
                        <div class="text-center border">{{ history.product_retail?.name }}</div>
                        <div class="text-center border">{{ history.expected_quantity }}</div>
                        <div class="text-center border">{{ history.unit }}</div>
                        <div class="text-center border">
                            {{ history.date_add }}
                        </div>
                        <div class="text-center border">{{ history.date_expire }}</div>
                        <div class="text-center border">{{ history.state }}</div>
                        <div class="text-center border">{{ history.actual_quantity }}</div>
                        <div class="text-center border">{{ history.state_confirm }}</div>
                        <div class="text-center border">xem</div>
                        <div class="text-center border"></div>
                    </div>
                </div>
                <pagination :links="historyAdds.links" />


            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
