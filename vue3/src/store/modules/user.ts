//创建用户相关的小仓库
import {defineStore} from 'pinia'

import {apiLogin} from '@/api/login'

//引入路由
import {constantRoute, asyncRoute, anyRoute} from '@/routes/routes';
import router from '@/routes';

//引入深拷贝方法
//@ts-ignore
import cloneDeep from 'lodash/cloneDeep'

function filterAsyncRoute(asnycRoute:any, routes:any){
    return asnycRoute.filter((item :any) => {
        if (routes.includes(item.name)) {
            if (item.children && item.children.length > 0) {
               item.children = filterAsyncRoute(item.children, routes)
            }
            return true
        }
    })
}


//创建用户小仓库
let useUserStore = defineStore('User', {
    //小仓库存储数据
    state: () :any => {
        return {
            token: localStorage.getItem("TOKEN"), //用户唯一标识
            username:localStorage.getItem("username"),
            avatar:localStorage.getItem("avatar"),
            menuLists: JSON.parse(localStorage.getItem("menuLists") as string),
            menuRoutes:constantRoute,
        }
    },
    //异步处理逻辑
    actions: {
        //用户登录的方法
        async userLogin(data :any) {
            //登录请求
            let  res: any = await apiLogin(data)
            if (res.code == 200) {
                //pinia存储token
                this.token = res.data.token
                this.username = res.data.userInfo.username
                this.avatar = res.data.userInfo.avatar
                this.menuLists = res.data.menuLists 
                //本地持久化存储
                localStorage.setItem("TOKEN", res.data.token)
                localStorage.setItem("username", res.data.userInfo.username);
                localStorage.setItem("avatar", res.data.userInfo.avatar);
                localStorage.setItem("menuLists", JSON.stringify(res.data.menuLists));

                //计算当前用户需要展示的异步路由
                // let userAsyncRoute = filterAsyncRoute(cloneDeep(asyncRoute), res.data.menuLists);
                // this.menuRoutes = [...constantRoute, ...userAsyncRoute, ...anyRoute];
                
                // //目前路由器管理的只有常量路由：动态追加路由、任意路由追加
                // [...userAsyncRoute, ...anyRoute].forEach((route: any) => {
                //     router.addRoute(route);
                // })

                //能保证当前async函数返回一个成功的promise
                return 'ok'
            } else {
                return Promise.reject(new Error(res.data.message))
            }
        },
        refreshRoute() {
            let username = localStorage.getItem("username")
            let menuLists = JSON.parse((localStorage.getItem("menuLists") as string))
            //计算当前用户需要展示的异步路由
            let userAsyncRoute = [];
            if (username != 'admin') {
                userAsyncRoute = filterAsyncRoute(cloneDeep(asyncRoute), menuLists);
            } else {
                userAsyncRoute = cloneDeep(asyncRoute);
            } 


            this.menuRoutes = [...constantRoute, ...userAsyncRoute, ...anyRoute];
             //目前路由器管理的只有常量路由：动态追加路由、任意路由追加
             [...userAsyncRoute, ...anyRoute].forEach((route: any) => {
                router.addRoute(route);
            })
            return 'ok';
        },
        userInfo() {

        },
        userLogout(){
             //pinia存储token
             this.token = ''
             this.username = ''
             this.avatar = ''
             //本地持久化存储
             localStorage.setItem("TOKEN", '')
             localStorage.setItem("username", '');
             localStorage.setItem("avatar", '');
        }
    },
    //方法
    getters:{

    }
})

export default useUserStore