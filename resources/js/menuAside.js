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
                route: 'commission.index',
                label: 'Cài đặt hoa hồng',
                permissions: ['view-user'],
                route_list: null
            },
            {
                route: 'commission.dashboard.user',
                label: 'Hoa hồng',
                permissions: ['view-user'],
                route_list: null
            }
        ]
    },
    {
        label: 'Hoạt động',
        icon: mdiFileTreeOutline,
        permissions: ['super-admin', 'order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline', 'contract-pending', 'contract-create', 'contract-cancle', 'contract-complete'],
        route_list: ['admin.orders.package.index', 'admin.orders.package.decline', 'admin.orders.package.complete',
            'admin.orders.package.partiallyPaid', 'admin.orders.index', 'admin.orders.create', 'admin.orders.package.create',
            'admin.orders.pending', 'admin.orders.packing', 'admin.orders.completed', 'admin.orders.addToCart', 'admin.orders.decline', 'admin.orders.shipping',
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
                route: 'visit.pending',
                label: 'Đặt lịch tham quan',

                permissions: ['super-admin', 'order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline'],
                route_list: ['visit.pending', 'visit.confirm', 'visit.completed']
            },
            // {
            //     route: 'visit.pending',
            //     label: 'Khiếu nại',

            //     permissions: ['super-admin'],
            //     route_list: null
            // },
            {
                route: 'admin.review.index',
                label: 'Đánh giá',

                permissions: ['super-admin'],
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
        label: 'Quản lý người dùng',
        icon: mdiFileTreeOutline,
        permissions: ['view-user', 'viewer-custommer', 'leader-shipper'],
        route_list: ['customer.index', 'shippers.index'],
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