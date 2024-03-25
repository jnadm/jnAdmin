//常量路由，所有用户都可以访问到
const constantRoute = [
    {
        path:'/',
        name:'layout', //命名路由
        meta:{
            title:'', //标题
        },
        redirect: '/home',
        component:()=>import('@/layout/index.vue'),
        children:[
            {
                path: '/home',
                meta:{
                    title:'首页',
                    hidden:false,
                    'icon':'House',
                }, 
                component: () => import('@/views/home/index.vue')
            }
        ]
    },
    {
        path:'/login',
        name:'login',
        meta:{
            title:'登录',
            icon:'Service',
            hidden:true,
            auth:true,
        },
        component:()=>import('@/views/login/index.vue'),
    },
]


//异步路由
let asyncRoute = [
    {
        path:'/system',
        component:()=>import('@/layout/index.vue'),
        name:'system',
        meta:{
            title: '系统管理',
            icon:'Setting',
        },
        redirect:'/system/user', 
        children:[
            {
                path: '/system/user',
                component:()=>import('@/views/system/user/index.vue'),
                name:'user',
                meta:{
                    title:'用户管理',
                    icon:'User',
                }
            },
            {
                path: '/system/role',
                component:()=>import('@/views/system/role/index.vue'),
                name:'role',
                meta:{
                    title:'角色管理',
                    icon:'Avatar',
                }
            },
            {
                path: '/system/menu',
                component:()=>import('@/views/system/menu/index.vue'),
                name:'menu',
                meta:{
                    title:'菜单管理',
                    icon:'Operation',
                }
            }
        ],
    },
]


//任意路由 
let anyRoute = [
    {
        path: '/:pathMatch(.*)*',//任意路由
        redirect:'/404', 
        name:'Any',
        meta:{
            title: '任意路由',
            hidden: true,
        }, 
    },
    {
        path: '/404',
        name: '404',
        meta:{
            title: '404',
            hidden:true,    //路由是否隐藏

        }, 
        component:()=>import('@/views/404/index.vue')
    },
];


// let auth = ['System'];
// let result = filterAsyncRoute(ansyRoute, auth)

export {constantRoute, asyncRoute, anyRoute}