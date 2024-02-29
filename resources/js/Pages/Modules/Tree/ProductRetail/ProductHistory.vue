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
    mdiLandFields,
    mdiPencilOutline, mdiTrashCan,
    mdiSquareEditOutline,mdiCheckCircle, mdiCancel
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from '@/Components/BaseButtons.vue';
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
import WareHouse from "@/Pages/Modules/Tree/ProductRetail/WareHouse.vue"
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
    // state: 'Nhập kho',
    // state_confirm: 'Chờ hàng'
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
    product_retail.value = historyAdd;
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
            text: "Xóa lịch sử nhập hàng!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Xóa!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.delete(route("admin.importProduct.destroy", id), {
                    onSuccess: () => {
                        swal.fire(
                            "Deleted!",
                            "Đã xóa lịch sử nhập hàng.",
                            "success"
                        );
                    },
                });
            }
        });
};
const Confirm = (id) => {
    swal
        .fire({
            title: "Are you sure?",
            text: "Xác nhận hàng đã nhập kho!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Xóa!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                form.post(route("admin.importProduct.confirm", id), {
                    onSuccess: () => {
                        swal.fire(
                            "Deleted!",
                            "Đã đã nhập kho.",
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
                        @click="isModalActive = true; form.reset();">Thêm nhập hàng</Button>
                </div>
            </div>
            <div v-if="isModalActive" buttonLabel="Save" has-cancel @confirm="save" :title="editMode ? 'Sửa' : 'Nhập hàng'">
                <div class="flex  ">
                    <div class="w-[300px]">
                        <InputLabel for="ballot_code" value="Mặt hàng" />
                        <Multiselect v-model="form.product_retail_id" :searchable="true" label="name" valueProp="id"
                            trackBy="name" placeholder="Chọn mặt hàng" :options="product_retails" :classes="{
                                tagsSearch: 'absolute bg-[#E9E9E9]  inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans  w-full',
                                container: 'relative  mx-auto w-full bg-gray-50  items-center  box-border cursor-pointer border border-gray-300 rounded bg-[#E9E9E9] text-sm  '
                            }">
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
                        <input type="number" class="border rounded " :min="0" v-model="form.actual_quantity">
                    </div>
                    <div class=" px-2">
                        <InputLabel for="ballot_code" value="Ngày nhập" />
                        <VueDatePicker class="py-1 date_round" v-model="form.date_add" time-picker-inline>
                        </VueDatePicker>
                    </div>
                    <div class=" px-2">
                        <InputLabel for="ballot_code" value="Hạn sử dụng" />
                        <VueDatePicker class="py-1 date_round" v-model="form.date_expire" time-picker-inline>
                        </VueDatePicker>
                    </div>
                    <!-- <div class=" px-2 ">
                        <InputLabel class="py-[2px]" for="ballot_code" value="Tình trạng" />
                        <select v-model="form.state" class="border rounded text-black w-[200] py-2">
                            <option value="Chưa về">Chưa về</option>
                            <option value="Nhập kho"> Nhập kho</option>
                        </select>
                    </div> -->
                    <div class=" px-2">
                        <button class="p-2 mt-[32px] bg-[#4F8D06] rounded text-white" @click="save()">Thêm</button>
                    </div>
                </div>
            </div>
            <div class="mt-3">

                <div class="w-full mt-2 ">
                    <div class="bg-[#AEAEAE] text-black  grid grid-cols-12 divide-x">
                        <div class="text-center py-2 ">STT</div>
                        <div class="text-center py-2 ">Mã</div>
                        <div class="text-center py-2 ">Mặt hàng dự kiến</div>
                        <div class="text-center py-2 ">SL dự kiến</div>
                        <div class="text-center py-2 ">Đơn vị tính</div>
                        <div class="text-center py-2 ">Date về kho</div>
                        <div class="text-center py-2">Hạn sử dụng</div>
                        <div class="text-center py-2">Tình trạng</div>
                        <div class="text-center py-2">Số lượng về</div>
                        <div class="text-center py-2">Trạng thái</div>
                        <div class="text-center py-2">Chi tiết</div>
                        <div class="text-center py-2">Xác nhận</div>

                    </div>
                    <div v-for="(history, index) in historyAdds.data" :key="history.id"
                        class="grid grid-cols-12 text-center  divide-x divide-black border-gray-400 border text-sm bg-white">
                        <div class="text-center px-2 py-2">{{ index + historyAdds.from }}</div>
                        <div class="text-center text-[#FF0000] px-2 py-2">{{ history.product_retail?.code }}</div>
                        <div class="text-center text-[#FF0000] px-2 py-2">{{ history.product_retail?.name }}</div>
                        <div class="text-center px-2 py-2">{{ history.expected_quantity }}</div>
                        <div class="text-center px-2 py-2">hộp</div>
                        <div class="text-center px-2 py-2">
                            {{ history.date_add }}
                        </div>
                        <div class="text-center px-2 py-2">{{ history.date_expire }}</div>
                        <div class="text-center px-2 py-2">{{ history.state }}</div>
                        <div class="text-center px-2 py-2">{{ history.actual_quantity }}</div>
                        <div class="text-center px-2 py-2 font-bold" :class="history.state_confirm == 'Đã xác nhận' ? 'text-[#4F8D06]' : '' ">{{ history.state_confirm }}</div>
                        <div class="text-center px-2 py-2">xem</div>
                        <div class="text-center px-2 py-2">
                            <!-- <BaseButton color="contrast" :icon="mdiPencilOutline" @click="edit(history)" type="button"
                                data-toggle="modal" data-target="#exampleModal" />
                            <BaseButton color="danger" :icon="mdiTrashCan" @click="Delete(history.id)" /> -->

                            <BaseIcon  :path="mdiSquareEditOutline"
                                @click="edit(history)" class=" text-[#FF6100] rounded-lg mr-2 hover:text-blue-700"
                                data-toggle="modal" data-target="#exampleModal"
                                v-tooltip.top="'Chỉnh sửa'" size="20">
                            </BaseIcon>
                            <!-- </Link> -->
                            <BaseIcon :path="mdiCheckCircle" @click="Confirm(history.id)"
                                class=" text-[#4F8D06] rounded-lg mr-2 hover:text-blue-700" v-tooltip.top="'Xác nhận'"
                                size="20">
                            </BaseIcon>
                            <button @click="Delete(history.id)" class="">
                                <BaseIcon :path="mdiCancel" class="text-[#FF0000]" size="20"></BaseIcon>
                            </button>
                        </div>
                    </div>
                </div>
                <pagination :links="historyAdds.links" />


            </div>

            <!-- trạng thái kho -->
            <div class="mt-5">
                <div class="min-[320px]:block sm:block md:block lg:flex lg:justify-between">
                        <h2 class="font-semibold  flex mr-2">
                            Trạng thái kho
                        </h2>
                        <Link class="px-3 py-2 rounded-[3px] bg-[#4F8D06] text-white"> Xem chi tiết</Link>
                </div>
                <div class="w-full mt-2 ">
                    <div class="bg-[#AEAEAE] text-black  grid grid-cols-12 divide-x">
                        <div class="text-center py-2 ">STT</div>
                        <div class="text-center py-2 ">Mặt hàng</div>
                        <div class="text-center py-2 ">DVT</div>
                        <div class="text-center py-2 ">Tồn kho</div>
                        <div class="text-center py-2 ">Đã lên đơn</div>
                        <div class="text-center py-2 ">SL sẵn có</div>
                        <div class="text-center py-2"></div>

                    </div>
                    <div v-for="(product_retail, index2) in product_retails" :key="product_retail.id"
                        class="grid grid-cols-12 text-center  divide-x divide-black border-gray-400 border text-sm bg-white">
                        <div class="text-center px-2 py-2">{{ index2 }}</div>
                        <div class="text-center text-[#FF0000] px-2 py-2">{{ product_retail?.name }}</div>
                        <div class="text-center text-[#FF0000] px-2 py-2">{{ product_retail?.unit }}</div>
                        <div class="text-center px-2 py-2">{{ product_retail.available_quantity + product_retail.orderd_quantity }}</div>
                        <div class="text-center px-2 py-2">{{ product_retail.orderd_quantity }}</div>
                        <div class="text-center px-2 py-2">{{ product_retail.available_quantity }}</div>
                    </div>
                </div>
                <pagination :links="historyAdds.links" />
            </div>

            <WareHouse />
        </SectionMain>
    </LayoutAuthenticated>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
<style scope>
.dp__input {
    border-radius: 5px !important;
}
</style>
