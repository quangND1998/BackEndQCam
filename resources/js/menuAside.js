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
// {
//     label: 'Quản lý Danh Mục',
//     icon: mdiShapeOutline,
//     permissions: ['view-project'],
//     group: ['project'],
//     route_list: ['admin.projects', 'admin.projects.index', 'admin.category-project.index', 'admin.category-apartment.index', 'admin.sale-policy.index'],
//     menu: [{
//         route: 'admin.projects.index',
//         label: 'Dự Án',
//         icon: mdiReceiptText,
//         permissions: ['view-project']
//     },
//     {
//         route: 'admin.category-project.index',
//         label: 'Loại hình dự án',
//         icon: mdiGridLarge,
//         permissions: ['super-admin']
//     },
//     {
//         route: 'admin.category-apartment.index',
//         label: 'Loại hình Căn hộ',
//         icon: mdiGridLarge,
//         permissions: ['super-admin']
//     },
//     {
//         route: 'admin.sale-policy.index',
//         label: 'Sale Policy',
//         icon: mdiFileDocumentEditOutline,
//         permissions: ['super-admin']
//     }
//     ]
// },
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