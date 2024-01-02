<script setup>
import { computed, ref, inject, reactive } from "vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm, router } from "@inertiajs/vue3";
import SectionMain from "@/Components/SectionMain.vue";
import { Head, Link } from "@inertiajs/vue3";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModalFull.vue";
import PackageBar from "@/Pages/Modules/Order/Package/PackageBar.vue";
import ModalDecline from "./ModalDecline.vue";
import ModelRefund from "../ModelRefund.vue";
import {
    mdiSquareEditOutline, mdiDeleteOutline, mdiCashMultiple, mdiEyeOutline, mdiCheckCircle, mdiCheckAll, mdiTrashCanOutline, mdiOpenInNew
} from '@mdi/js'
import BaseButton from "@/Components/BaseButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
// import Multiselect from '@vueform/multiselect'
import Dropdown from '@/Components/Dropdown.vue';
import BaseIcon from '@/Components/BaseIcon.vue'
import SearchInput from "vue-search-input";
import "vue-search-input/dist/styles.css";
import MazInputPrice from 'maz-ui/components/MazInputPrice'
import { initFlowbite } from 'flowbite'
import OrderHome from "@/Pages/Test/OrderHome.vue"
import OrderRow from "@/Pages/Modules/Order/Package/OrderRow.vue"
import InfiniteLoading from "v3-infinite-loading";
import "v3-infinite-loading/lib/style.css";
import { vFullscreenImg } from 'maz-ui'
import UploadImage from '@/Components/UploadImage.vue'
import UploadImageAuto from '@/Components/UploadImageAuto.vue'
import { emitter } from '@/composable/useEmitter';
const props = defineProps({
    orders: Object,
    status: String,
    status: String,
    from: String,
    to: String,
    statusGroup: Array
});
const filter = reactive({
    customer: null,
    name: null,
    fromDate: null,
    toDate: null,
    search: null,
    payment_status: null,
    payment_method: null,
    type: null,
    per_page: 10,
    status: props.status

})
const customer = ref()
const searchVal = ref("");
const swal = inject("$swal");
const isModalActive = ref(false);
const isModalHistoryActive = ref(false);
const showAction = ref(false);
const images = ref([]);
const editMode = ref(false);
const isModalDangerActive = ref(false);

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
initFlowbite();

const searchCustomer = () => {
    router.get(route(`admin.orders.package.cskh`),
        { customer: filter.customer },
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}

const search = () => {
    router.get(route(`admin.orders.package.cskh`),
    filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const fillterPaymentMethod = (event) => {
    router.get(route(`admin.orders.package.cskh`),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const Fillter = (event) => {
    router.get(route(`admin.orders.package.cskh`),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const fillterType = (event) => {
    router.get(route(`admin.orders.package.cskh`),
        filter,
        {
            preserveState: true,
            preserveScroll: true
        }
    );
}
const contents = ref([
    { id: 1, text: 'Content 1' },
    { id: 2, text: 'Content 2' },
    { id: 3, text: 'Content 3' },
]);



const changeDate = () => {
    let query = {
        from: filter.fromDate,
        to: filter.toDate
    };
    router.get(route(`admin.orders.package.cskh`), query, {
        preserveScroll: true
    });
}

const showButton = () => {
    showAction.value = true
}
const hideButton = () => {
    showAction.value = false
}
const form = useForm({
    id: null,
    payment_method: 'cash',
    payment_date: new Date(),
    amount_received: null,
    amount_unpaid: null,
    status: null,
    note: null,
    order: null,
    images: [],
});
const detail = (order) => {
    isModalActive.value = true;
    isModalHistoryActive.value = false;
    form.order = order
    form.amount_unpaid = order.grand_total - order.price_percent
    form.amount_received = order.grand_total - order.price_percent
};
const edit = (history) => {
    isModalActive.value = false;
    isModalHistoryActive.value = true;
};
const loadOrder = async $state => {
    console.log("loading...");
    router.get(route(`admin.orders.package.cskh`),
        filter,
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: page => {
                if (props.orders.current_page == props.orders.last_page) $state.complete();
                else {
                    $state.loaded();
                }
                filter.per_page += 10;
            },
            onError: errors => {
                $state.error();
            },
        },

    );

};
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
                form.post(route("admin.orders.package.deleteHistoryPayment", [form.order?.id, id]), { preserveState: false }, {
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
                form.post(route("admin.orders.package.setPaymentComplete", [form.order?.id, id]), { preserveState: false }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã thêm hợp đồng với khách hàng.", "success");
                    },
                });

            }
        });
}

const save = () => {
    console.log(form);
    form.post(route("admin.orders.package.historyPayment", form.order?.id), {
        onError: () => {
            isModalActive.value = true;
            editMode.value = false;
        },
        onSuccess: () => {
            form.reset('id', 'payment_method', 'payment_date', 'amount_received', 'note');
            isModalActive.value = false;
            editMode.value = false;
        },
    });
};
const orderChangePacking = (order) => {
    let query = {
        status: "complete"
    };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Duyệt gói, thiết lập hợp đồng`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {
                router.post(route("admin.orders.package.orderComplete", order.id), query,
                    {
                        preserveState: true,
                        preserveScroll: true
                    }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã thêm hợp đồng với khách hàng.", "success");
                    },
                });

            }
        });
}
const orderChangePending = (order) => {
    let query = {
        status: "pending"
    };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Làm mới gói dịch vụ ${order.order_number} !`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {

                router.post(route("admin.orders.package.orderChangeStatus", order.id), query,
                    {
                        preserveState: true,
                        preserveScroll: true
                    }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã làm mới hợp đồng, chuyển sang trạng thái chờ duyệt.", "success");
                    },
                });
            }
        });
}
const openDecline = (order) => {
    emitter.emit('OpenModalDecline', order)
}
const deleteOrder = (order) => {
    emitter.emit('OpenModalDelete', order)
}

</script>
<template class="body_fix">
    <Head title="Quản lý chăm sóc kh" />
    <LayoutAuthenticated>


    </LayoutAuthenticated>
</template>

<script>
(function (a,b) {
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.onload = ()=>{PitelSDK.k=a;b()};
        s.src = 'https://portal.tel4vn.com/pitelsdk/sdk-1.1.test.min.js';
        var x = document.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(s, x);
      })('d1ca84ac-2d98-4faa-92d4-699a6ce14eb7', ()=>{
        console.log('Pitel SDK Loaded');
      });

    setTimeout(function(){
    let sdkOptions = {
        enableWidget: true,
        sipOnly: true,
        sipDomain: 'demo.cgvtelecom.vn:5060',
        wsServer: "wss://cgvcall.mobilesip.vn:7444",
        sipPassword: "Cgv@@2023##"
    }
    let pitelSDK = new PitelSDK('xxx', 'xxx', '102', {}, sdkOptions)
    setTimeout(function() {
    // pitelSDK.call(103, {
    //   extraHeaders: ['x-PROCESS-ID: 123']
    // })
    }, 5000)

    },500);
</script>

