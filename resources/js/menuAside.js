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
    mdiFileDocumentEditOutline
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

{
    route: 'profile.show',
    label: 'Profile',
    icon: mdiAccountCogOutline,
    permissions: null,
    route_list: null
},
{
    label: 'Quản lý phân quyền & User',
    icon: mdiFileTreeOutline,
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
    label: 'Quản lý Cây & Gói sản phẩm',
    icon: mdiFileTreeOutline,
    permissions: ['view-user'],
    route_list: ['admin.land.index', 'admin.product-retail.index', 'admin.product-service.index'],
    menu: [{
        route: 'admin.land.index',
        label: 'Lô',
        icon: mdiMinus,
        permissions: ['super-admin'],
        route_list: null
    },
    {
        route: 'admin.product-retail.index',
        label: 'Sản phẩm bán lẻ',
        icon: mdiMinus,
        permissions: ['super-admin'],
        route_list: null
    },
    {
        route: 'admin.product-service.index',
        label: 'Sản phẩm dịch vụ',
        icon: mdiMinus,
        permissions: ['super-admin'],
        route_list: null
    },


    ]
},

{
    route: 'news.index',
    label: "Tin tức",
    icon: mdiFileTreeOutline,
    permissions: ['super-admin'],
},
{
    route: 'admin.voucher.index',
    label: "Mã giảm giá",
    icon: mdiSale,
    permissions: ['super-admin'],
},
{
    label: 'Quản lý người dùng',
    icon: mdiFileTreeOutline,
    permissions: ['view-user'],
    route_list: ['customer.index'],
    menu: [{
        route: 'customer.index',
        label: 'Customer',
        icon: mdiMinus,
        permissions: ['super-admin'],
        route_list: null
    },
    {
        route: 'admin.land.index',
        label: 'Shipper',
        icon: mdiMinus,
        permissions: ['super-admin'],
        route_list: null
    }
    ]
},

{
    label: 'Quản lý đơn hàng',
    icon: mdiFileTreeOutline,
    permissions: ['order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline'],
    route_list: ['admin.orders.index'],
    menu: [{
        route: 'admin.orders.index',
        label: 'Đơn hàng',
        icon: mdiMinus,
        permissions: ['super-admin'],
        route_list: null
    },]
},
{
    label: 'SETTINGS',
},
{
    route: 'login',
    label: 'Notification',
    icon: mdiBellOutline,
    permissions: null,
    route_list: null,
},
{
    route: 'login',
    label: 'Settings',
    icon: mdiCogOutline,
    permissions: null,
    route_list: null,
},

]
