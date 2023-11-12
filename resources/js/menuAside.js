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
    icon: mdiAccountKey ,
    permissions: ['view-user'],
    route_list: ['permissions.index', 'roles.index', 'users.index'],
    menu: [{
        route: 'permissions.index',
        label: 'Permissions',
        icon: mdiMinus,
        permissions: ['super-admin'],
        route_list: null
    },
    {
        route: 'roles.index',
        label: 'Roles',
        icon: mdiMinus,
        permissions: ['super-admin'],
        route_list: null
    },
    {
        route: 'users.index',
        label: 'Users',
        icon: mdiMinus,
        permissions: ['view-user'],
        route_list: null
    }
    ]
},
{
    label: 'Hoạt động',
    icon: mdiFileTreeOutline,
    permissions: ['order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline'],
    route_list: ['admin.orders.index'],
    menu: [{
        route: 'admin.orders.index',
        label: 'Đơn hàng',

        permissions: ['super-admin'],
        route_list: null
    },
    {
        route: 'admin.orders.index',
        label: 'Khiếu nại',

        permissions: ['super-admin'],
        route_list: null
    },
    {
        route: 'admin.orders.index',
        label: 'Đánh giá',

        permissions: ['super-admin'],
        route_list: null
    }
]
},
{
    label: 'Sản phẩm',
    icon: mdiSale,
    permissions: ['view-user'],
    route_list: ['admin.land.index', 'admin.product-retail.index', 'admin.product-service.index'],
    menu: [{
        route: 'admin.land.index',
        label: 'Cây',

        permissions: ['super-admin'],
        route_list: null
    },
    {
        route: 'admin.product-retail.index',
        label: 'Sản phẩm bán lẻ',

        permissions: ['super-admin'],
        route_list: null
    },
    {
        route: 'admin.product-service.index',
        label: 'Sản phẩm dịch vụ',
        permissions: ['super-admin'],
        route_list: null
    },
    {
        route: 'admin.voucher.index',
        label: "Mã giảm giá",
        permissions: ['super-admin'],
    },

    ]
},

{
    label: 'Quản lý người dùng',
    icon: mdiFileTreeOutline,
    permissions: ['view-user'],
    route_list: ['customer.index'],
    menu: [{
        route: 'customer.index',
        label: 'Customer',
        permissions: ['super-admin'],
        route_list: null
    },
    {
        route: 'admin.land.index',
        label: 'Shipper',
        permissions: ['super-admin'],
        route_list: null
    }
    ]
},


{
    label: 'SETTINGS',
},

{
    label: 'Cài đặt',
    icon: mdiCogOutline,
    permissions: ['view-user'],
    route_list: ['news.index'],
    menu: [{
        route: '',
        label: 'Thông báo',
        permissions: ['super-admin'],
        route_list: null
    },
    {
        route: 'news.index',
        label: 'Tin tức',
        permissions: ['super-admin'],
        route_list: null
    },
    {
        route: '',
        label: 'Thông tin chung',
        permissions: ['super-admin'],
        route_list: null
    }
    ]
},
]
