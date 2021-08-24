import Full from 'Container/Full'

// CRM components
const UserProfile = () => import('Views/users/UserProfile');
const UsersList = () => import('Views/users/UsersList');
const MyProfile = () => import('Views/users/MyProfile');
const Statistics = () => import('Views/statistics/Statistics');
const Settings = () => import('Views/settings/Settings');
const TradingAccount = () => import('Views/trading/TradingAccount');
const ProvideSignal = () => import('Views/trading/ProvideSignal');
const SignalDetail = () => import('Views/trading/SignalDetail');
const CopyDetail = () => import('Views/trading/CopyDetail');
const copyingSignals = () => import('Views/trading/copyingSignals');
const AvailableSignals = () => import('Views/trading/AvailableSignals');
const ContactUs = () => import('Views/contactus');

export default {
    path: '/',
    component: Full,
    redirect: '/available-sources',
    children: [
        // users
        {
            path: '/my-profile',
            name: 'my-profile',
            component: MyProfile,
            props: true,
            meta: {
                requiresAuth: true,
                title: 'message.myProfile',
                breadcrumb: [
                    {
                        breadcrumbInactive: 'User /'
                    },
                    {
                        breadcrumbActive: 'My Profile'
                    }
                ]
            }
        },
        {
            path: '/user-profile/:user_id',
            name: 'user-profile',
            component: UserProfile,
            props: true,
            meta: {
                requiresAuth: true,
                title: 'message.userProfile',
                breadcrumb: [
                    {
                        breadcrumbInactive: 'Admin /'
                    },
                    {
                        breadcrumbActive: 'User Profile'
                    }
                ]
            }
        },
        {
            path: '/users-list',
            name: 'users-list',
            component: UsersList,
            meta: {
                requiresAuth: true,
                title: 'message.usersList',
                breadcrumb: [
                    {
                        breadcrumbInactive: 'Admin /'
                    },
                    {
                        breadcrumbActive: 'Users List'
                    }
                ]
            }
        },

        {
            path: '/statistics',
            name: 'statistics',
            component: Statistics,
            meta: {
                requiresAuth: true,
                title: 'message.statistics',
                breadcrumb: [
                    {
                        breadcrumbInactive: 'Admin /'
                    },
                    {
                        breadcrumbActive: 'Statistics'
                    }
                ]
            }
        },

        {
            path: '/settings',
            name: 'settings',
            component: Settings,
            meta: {
                requiresAuth: true,
                title: 'message.settings',
                breadcrumb: [
                    {
                        breadcrumbInactive: 'Admin /'
                    },
                    {
                        breadcrumbActive: 'Settings'
                    }
                ]
            }
        },

        {
            path: '/trading-accounts',
            name: 'trading-accounts',
            component: TradingAccount,
            meta: {
                requiresAuth: true,
                title: 'message.tradingAccounts',
                breadcrumb: [
                    {
                        breadcrumbInactive: 'User /'
                    },
                    {
                        breadcrumbActive: 'Trading Accounts'
                    }
                ]
            }
        },

        {
            path: '/provide-signal',
            name: 'provide-signal',
            component: ProvideSignal,
            meta: {
                requiresAuth: true,
                title: 'message.provideSignal',
                breadcrumb: [
                    {
                        breadcrumbInactive: 'User /'
                    },
                    {
                        breadcrumbActive: 'Provide Signal'
                    }
                ]
            }
        },

        {
            path: '/signal-detail/:account_number/:broker',
            name: 'signal-detail',
            component: SignalDetail,
            meta: {
                requiresAuth: true,
                title: 'message.signalDetail',
                breadcrumb: [
                    {
                        breadcrumbInactive: 'User /'
                    },
                    {
                        breadcrumbActive: 'Provide Signal Detail'
                    }
                ]
            }
        },

        {
            path: '/copy-detail/:account_number/:broker',
            name: 'copy-detail',
            component: CopyDetail,
            meta: {
                requiresAuth: true,
                title: 'message.copyDetail',
                breadcrumb: [
                    {
                        breadcrumbInactive: 'User /'
                    },
                    {
                        breadcrumbActive: 'Provide Copy Detail'
                    }
                ]
            }
        },

        {
            path: '/copying-signal',
            name: 'copying-signal',
            component: copyingSignals,
            meta: {
                requiresAuth: true,
                title: 'message.copyingSignals',
                breadcrumb: [
                    {
                        breadcrumbInactive: 'User /'
                    },
                    {
                        breadcrumbActive: 'Copy Signal'
                    }
                ]
            }
        },

        {
            path: '/available-sources',
            name: 'available-sources',
            component: AvailableSignals,
            meta: {
                requiresAuth: true,
                title: 'message.leaderboard',
                breadcrumb: [
                    {
                        breadcrumbInactive: 'User /'
                    },
                    {
                        breadcrumbActive: 'Leaderboard'
                    }
                ]
            }
        },

        {
            path: '/contact-us',
            name: 'contact-us',
            component: ContactUs,
            meta: {
                requiresAuth: true,
                title: 'message.contactUs',
                breadcrumb: [
                    {
                        breadcrumbInactive: 'User /'
                    },
                    {
                        breadcrumbActive: 'Contact Us'
                    }
                ]
            }
        },
    ]
}
