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
    mdiReact
} from '@mdi/js'

export default [{
    route: 'dashboard',
    icon: mdiMonitor,
    label: 'Dashboard'
},

{
    route: 'profile.show',
    label: 'Profile',
    icon: mdiAccountCircle
},
{
    label: 'User Managerment',
    icon: mdiViewList,
    menu: [{
        route: 'permissions.index',
        label: 'Permissions',
        icon: mdiAccountCircle
    },
    {
        label: 'Item Two'
    }
    ]
},

{
    route: 'login',
    label: 'Login',
    icon: mdiLock
},

]