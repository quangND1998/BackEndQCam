<template>
    <div class="flex justify-between">
        <div class="left">
            <button v-if="status == 'pending' || status == 'complete' "
            class="border text-red-700 rounded-lg bg-gray-100 px-3 py-2" data-toggle="modal"
            data-target="#exampleModalDecline" @click="openDecline(order)">Hủy gói</button>
            <Link v-if="status == 'pending' " :href="`/admin/orders/package/edit/${order.id}`"
            class="border text-blue-700 rounded-lg bg-gray-100 px-3 py-2">Chỉnh sửa</Link>
            <button v-if="status == 'decline'" class="border rounded-lg bg-gray-100 px-3 py-2" @click="orderChangePending(order)">Làm mới hợp đồng</button>
            <button v-if="status == 'decline'  "
            class="border text-red-700 rounded-lg bg-gray-100 px-3 py-2" data-toggle="modal"
            data-target="#exampleModalDecline" @click="openDecline(order)">Xóa gói</button>
        </div>

         
        <div class="flex">
            <!-- <select v-if="status == 'pending' || status == 'packing' || status == 'shipping' || status == 'completed'"
                id="countries" @change="orderChangePayment( $event)"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4.5 py-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option :value="0" :selected="order.payment_status == 0 ? true : false">Chưa thanh toán</option>

                <option :value="1" :selected="order.payment_status == 1 ? true : false">Đã thanh toán</option>
            </select> -->
            <button  v-if="hasAnyPermission(['create-contract-complete']) && status == 'pending' && (order.payment_check == true)" @click="orderChangePacking(order)"
                class="px-3 py-2 ml-3 text-white border rounded-lg bg-primary">Duyệt gói</button>
            <button  v-else-if="status == 'pending'" disabled
                class="px-3 py-2 ml-3 border-gray-black text-gray-400 border rounded-lg">Duyệt gói</button>
            <button v-if="status == 'packing'" @click="orderChangeShipping(order)"
                class="px-3 py-2 ml-3 text-white border rounded-lg bg-primary">Bắt đầu giao hàng</button>
            <button v-if="status == 'shipping'" @click="orderChangeCompleted(order)"
                class="px-3 py-2 ml-3 text-white border rounded-lg bg-primary">Hoàn thành đơn hàng</button>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, inject } from "vue";
import { useForm, router,Link } from "@inertiajs/vue3";
import { emitter } from '@/composable/useEmitter';
const swal = inject("$swal");
const props = defineProps({
    status: String,
    order: Object
})
const openDecline = () => {
    emitter.emit('OpenModalDecline', props.order)
}


const openRefund = () => {
    emitter.emit('OpenModalRefund', props.order)
}
const orderChangePending = () => {
    let query = {
        status: "pending"
      };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Làm mới gói dịch vụ ${props.order.order_number} !`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {

                router.post(route("admin.orders.package.orderChangeStatus", props.order.id), query,
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
const orderChangePayment = (event) => {

    let query = {
        payment_status: event.target.value,
        id: props.order.id
      };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Chuyển trạng thái thanh toán của đơn hàng ${props.order.order_number}!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {
                router.post(route("admin.orders.package.orderChangeStatus",props.order.id), query,
                    {
                        preserveState: true,
                        preserveScroll: true
                    }, {
                    onSuccess: () => {
                        swal.fire("Deleted!", "Your role has been deleted.", "success");
                    },
                });
            }
        });
}
const orderChangeShipping = () => {
    let query = {
        status: "shipping"
      };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Chuyển trạng thái đơn hàng ${props.order.order_number} sang bắt đầu vận chuyển!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {


                router.post(route("admin.orders.orderChangeStatus", props.order.id), query,
                    {
                        preserveState: true,
                        preserveScroll: true
                    }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã thêm các sản phẩm vào mã giảm giá.", "success");
                    },
                });
            }
        });
}
const orderChangeCompleted = () => {
    let query = {
        status: "completed"
      };
    swal
        .fire({
            title: "Bạn có muốn?",
            text: `Chuyển trạng thái đơn hàng ${props.order.order_number} sang  Hoàn thành đơn hàng!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Có",
        })
        .then((result) => {
            if (result.isConfirmed) {

                router.post(route("admin.orders.package.orderChangeStatus", props.order.id), query,
                    {
                        preserveState: true,
                        preserveScroll: true
                    }, {
                    onSuccess: () => {
                        swal.fire("Thành Công!", "Đã thêm các sản phẩm vào mã giảm giá.", "success");
                    },
                });
            }
        });
}

const orderChangePacking = () => {
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
                router.post(route("admin.orders.package.orderComplete", props.order.id), query,
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

</script>

<style></style>
