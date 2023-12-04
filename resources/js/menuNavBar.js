import {
    mdiMenu,
    mdiClockOutline,
    mdiCloud,
    mdiCrop,
    mdiAccount,
    mdiCogOutline,
    mdiEmail,
    mdiLogout,
    mdiThemeLightDark,
    mdiGithub,
    mdiReact
} from '@mdi/js'

export default [{
        isCurrentUser: true,
        menu: [{
                icon: mdiAccount,
                label: 'My Profile',
                route: 'profile.show'
            },
            {
                icon: mdiCogOutline,
                label: 'Settings'
            },
            {
                icon: mdiEmail,
                label: 'Messages'
            },
            {
                isDivider: true
            },
            {
                icon: mdiLogout,
                label: 'Log Out',
                isLogout: true
            }
        ]
    },

    {
        icon: mdiLogout,
        label: 'Log out',
        isDesktopNoLabel: true,
        isLogout: true
    }
]