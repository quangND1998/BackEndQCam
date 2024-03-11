import {
    mdiAccountCircle,
    mdiMonitor,
    mdiGithub,
    mdiLock,
    mdiAlertCircle,
    mdiSquareEditOutline,
    mdiTable,
    mdiViewList,
    mdiTelevisionGuide,
    mdiResponsive,
    mdiPalette,
    mdiReact,
    mdiAccountSupervisor,
    mdiAccountLockOpen,
    mdiReceiptText,
    mdiGridLarge,
    mdiShapeOutline,
    mdiFileTreeOutline,
    mdiAccountCogOutline,
    mdiHomeOutline,
    mdiMinus,
    mdiBellOutline,
    mdiCogOutline,
    mdiSale,
    mdiFileDocumentEditOutline,
    mdiAccountKey
} from '@mdi/js'


export default [{
        label: 'MAIN',
    },
    {
        route: 'dashboard',
        icon: mdiHomeOutline,
        label: 'Dashboard',
        permissions: null,
        route_list: null
    },

    // {
    //     route: 'profile.show',
    //     label: 'Profile',
    //     icon: mdiAccountCogOutline,
    //     permissions: null,
    //     route_list: null
    // },
    {
        label: 'Phân quyền',
        icon: mdiAccountKey,
        permissions: ['view-user'],
        route_list: ['permissions.index', 'roles.index', 'users.index'],
        menu: [{
                route: 'permissions.index',
                label: 'Permissions',
                permissions: ['super-admin'],
                route_list: null
            },
            {
                route: 'roles.index',
                label: 'Roles',
                permissions: ['super-admin'],
                route_list: null
            },
            {
                route: 'users.index',
                label: 'Users',
                permissions: ['view-user'],
                route_list: null
            },
            {
                route: 'commission.policy',
                label: 'Quản lý chính sách',
                permissions: ['setting-commersion', 'super-admin'],
                route_list: null
            },
            {
                route: 'commission.index',
                label: 'Cài đặt hoa hồng',
                permissions: ['setting-commersion', 'super-admin'],
                route_list: null
            },
            {
                route: 'commission.dashboard.user',
                label: 'Hoa hồng',
                permissions: ['view-commersion', 'super-admin'],
                route_list: null
            }
        ]
    },
    {
        label: 'Hoạt động',
        icon: mdiFileTreeOutline,
        permissions: ['super-admin', 'order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline', 'contract-pending', 'contract-create', 'contract-cancle', 'contract-complete'],
        route_list: ['admin.orders.package.index', 'admin.orders.package.decline', 'admin.orders.package.complete',
            'admin.orders.package.partiallyPaid', 'admin.orders.index', 'admin.orders.createOrder', 'admin.orders.package.create',
            'admin.orders.pending', 'admin.orders.completed', 'admin.orders.addToCart', 'admin.orders.decline', 'admin.orders.create', 'admin.orders.processing',
            'visit.pending', 'visit.confirm', 'visit.completed', 'admin.review.index', 'admin.orders.refund'
        ],
        menu: [{
                route: 'admin.orders.index',
                label: 'Đơn hàng',

                permissions: ['super-admin', 'order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline'],
                route_list: null
            },
            {
                route: 'admin.orders.package.all',
                label: 'Hợp đồng',
                permissions: ['super-admin', 'order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline'],
                route_list: null
            },
            {
                route: 'admin.orders.package.cskh',
                label: 'CSKH',
                permissions: ['super-admin', 'viewer-custommer', 'order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline'],
                route_list: null
            },
            {
                route: 'visit.all',
                label: 'Đặt lịch tham quan',

                permissions: ['super-admin', 'order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline'],
                route_list: ['visit.pending', 'visit.confirm', 'visit.completed']
            },
            {
                route: 'complaint.index',
                label: 'Khiếu nại',

                permissions: ['super-admin'],
                route_list: null
            },
            {
                route: 'admin.review.index',
                label: 'Đánh giá',

                permissions: ['super-admin'],
                route_list: null
            }
        ]
    },
    {
        // trưởng phòng CSKH
        label: 'CSKH',
        icon: mdiFileTreeOutline,
        permissions: ['super-admin', 'cskh-booking', 'cskh-gift-delivery', 'cskh-role-package', 'cskh-distribute-call', 'cskh-pending', 'cskh-call-center'],
        route_list: ['admin.booking.index', 'admin.booking.detail'],
        menu: [{
                route: 'admin.booking.index',
                label: 'QLHS',
                // phat ma booking
                permissions: ['super-admin', 'cskh-booking'],
                route_list: ['admin.booking.index', 'admin.booking.detail']
            },
            {
                route: 'admin.gift_distribute.index',
                label: 'Bảng PB quà',
                permissions: ['super-admin', 'cskh-gift-delivery'],
                route_list: null
            },
            {
                route: 'admin.gift_distribute.role',
                label: 'Quyền hợp đồng',
                permissions: ['super-admin', 'cskh-role-package'],
                route_list: null
            },
            {
                route: 'admin.call_distribute.schedule',
                label: 'Lên KH',
                permissions: ['super-admin', 'cskh-distribute-call'],
                route_list: null
            },
            {
                route: 'customer-service.weekly-plan',
                label: 'Kế hoạch CS',
                permissions: ['cskh-detail'],
                route_list: null
            },
            {
                route: 'pending.index',
                label: 'Pending',
                permissions: ['super-admin', 'cskh-pending'],
                route_list: null
            },
            {
                route: 'admin.orders.package.all',
                label: 'Call Center',
                permissions: ['super-admin', 'cskh-call-center'],
                route_list: null
            },
        ]
    },
    {
        label: 'Quản lý đơn hàng',
        icon: mdiFileTreeOutline,
        permissions: ['super-admin', 'view-order-all', 'view-order-pending', 'view-order-packing', 'view-order-shipping', 'view-order-completed', 'view-order-refunding', 'view-order-refund', 'view-order-decline'],
        route_list: ['admin.cskh.all', 'admin.cskh.pending', 'admin.cskh.packing',
            'admin.cskh.shipping', 'admin.cskh.shipping', 'admin.cskh.completed', 'admin.cskh.refunding',
            'admin.cskh.refund', 'admin.cskh.decline',
        ],
        menu: [{
                route: 'admin.cskh.all',
                label: 'Tất cả',
                permissions: ['super-admin', 'view-order-all'],
                route_list: null
            },
            {
                route: 'admin.cskh.pending',
                label: 'Đang chờ',
                permissions: ['super-admin', 'view-order-pending'],
                route_list: null
            },

            {
                route: 'admin.cskh.packing',
                label: 'Đóng gói',
                permissions: ['super-admin', 'view-order-packing'],
                route_list: null
            },

            {
                route: 'admin.cskh.shipping',
                label: 'Vận chuyển',
                permissions: ['super-admin', 'view-order-shipping'],
                route_list: null
            },
            {
                route: 'admin.cskh.completed',
                label: 'Đã giao',
                permissions: ['super-admin', 'view-order-completed'],
                route_list: null
            },
            {
                route: 'admin.cskh.refunding',
                label: 'Chờ hoàn',
                permissions: ['super-admin', 'view-order-refunding'],
                route_list: null
            },
            {
                route: 'admin.cskh.refund',
                label: 'Hoàn đơn',
                permissions: ['super-admin', 'view-order-refund'],
                route_list: null
            },
            {
                route: 'admin.cskh.decline',
                label: 'Hủy',
                permissions: ['super-admin', 'view-order-decline'],
                route_list: null
            }

        ]
    },
    {
        label: 'Sản phẩm',
        icon: mdiSale,
        permissions: ['super-admin', 'view-land', 'create-land', 'update-land', 'delete-land', 'view-product', 'create-product', 'update-product', 'delete-product'],
        route_list: ['admin.land.index', 'admin.product-retail.index', 'admin.product-service.index', 'admin.land.tree.index', 'admin.voucher.index'],
        menu: [{
                route: 'admin.land.index',
                permissions: ['super-admin', 'create-land', 'create-land', 'update-land', 'delete-land'],
                label: 'Cây',

                permissions: ['super-admin', 'create-land', 'create-land', 'update-land', 'delete-land'],
                route_list: null
            },
            {
                route: 'admin.activity-care.index',
                label: 'Hoạt động chăm sóc cây',

                permissions: ['view-care', 'create-care', 'update-care', 'delete-care'],
                route_list: null
            },
            {
                route: 'admin.product-retail.index',
                label: 'Sản phẩm bán lẻ',

                permissions: ['view-product', 'create-product', 'update-product', 'delete-product'],
                route_list: null
            },
            {
                route: 'admin.product-service.index',
                label: 'Sản phẩm dịch vụ',
                permissions: ['view-product', 'create-product', 'update-product', 'delete-product'],
                route_list: null
            },
            {
                route: 'admin.voucher.index',
                label: "Mã giảm giá",
                permissions: ['view-product', 'create-product', 'update-product', 'delete-product'],
            },

        ]
    },
    {
        label: 'Quản lý kho',
        icon: mdiSale,
        permissions: ['super-admin'],
        route_list: ['admin.land.index'],
        menu: [{
                route: 'admin.importProduct.index',
                permissions: ['super-admin'],
                label: 'Đơn tiếp nhận',
                permissions: ['super-admin'],
                route_list: null
            },
            {
                route: 'admin.activity-care.index',
                label: 'Sản phẩm',
                permissions: ['view-care', 'create-care', 'update-care', 'delete-care'],
                route_list: null
            },
            {
                route: 'admin.product-retail.index',
                label: 'Quản lý kho',
                permissions: ['view-product', 'create-product', 'update-product', 'delete-product'],
                route_list: null
            },

        ]
    },
    {
        label: 'Quản lý người dùng',
        icon: mdiFileTreeOutline,
        permissions: ['viewer-custommer'],
        route_list: ['customer.index'],
        menu: [{
                route: 'customer.index',
                label: 'Customer',
                permissions: ['super-admin', 'viewer-custommer']
            },

        ]
    },
    {
        label: 'Quản lý Shipper',
        icon: mdiFileTreeOutline,
        permissions: ['view-shipper'],
        route_list: ['shippers.index'],
        menu: [{
            route: 'shippers.index',
            label: 'Shipper',
            permissions: ['view-shipper'],
            route_list: null
        }]
    },

    {
        label: 'SETTINGS',
    },

    {
        label: 'Cài đặt',
        icon: mdiCogOutline,
        permissions: ['view-user', 'view-news', 'create-news', 'update-news', 'delete-news', 'view-setting', 'view-notification', 'view-setting-privacy', 'view-setting-contact'],
        route_list: ['news.index', 'admin.terms.index', 'admin.contact.index', 'admin.FAQs.index'],
        menu: [{
                route: '',
                label: 'Thông báo',
                permissions: ['super-admin', 'view-notification'],
                route_list: null
            },
            {
                route: 'news.index',
                label: 'Tin tức',
                permissions: ['super-admin', 'view-news'],
                route_list: null
            },
            {
                route: '',
                label: 'Thông tin chung',
                permissions: ['super-admin', 'view-setting'],
                route_list: null
            },
            {
                route: 'admin.terms.index',
                label: 'Điều khoản',
                permissions: ['super-admin', 'view-setting-privacy'],
                route_list: null
            },
            {
                route: 'admin.contact.index',
                label: 'Liên hệ',
                permissions: ['super-admin', 'view-setting-contact'],
                route_list: null
            },
            {
                route: 'admin.FAQs.index',
                label: 'FAQs',
                permissions: ['super-admin', 'view-setting-faq'],
                route_list: null
            }
        ]
    },
]
