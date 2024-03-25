//路由鉴权
import router from "@/routes";

//引入进度条插件
import nprogress from 'nprogress';
//引入进度条样式
import "nprogress/nprogress.css"

//获取小仓库中的token
import useUserStore from "./store/modules/user";
import pinia from "./store";
let userStore = useUserStore(pinia)

//引入setting
import setting from "./setting";

//全局前置守卫(访问某一个路由之前触发)：to将要访问的路由对象,from从哪个路由来, next路由的放行行数
let routeFlag = false
router.beforeEach( async (to:any, _:any, next:any) => {
    nprogress.start(); //进度条开始
    document.title = setting.title + '-' + to.meta.title  //设置网页标签标题
    let token = userStore.token                          //获取token判断用户是否登录
    let username = userStore.username
    
    if (token) {
        //不允许进入login页面
        if(to.path=='/login') {
            next({path:'/'})
        } else {
            if (username) {
                if (routeFlag) {
                    next();
                } else {
                    userStore.refreshRoute()
                    routeFlag = true;
                    next({ ...to, replace: true });  
                }
            } else {
                routeFlag = false;
                try {
                    //防止刷新时异步路由还没有加载
                    next({...to});
                } catch (error) {
                    await userStore.userLogout();
                    next({path:'/login', query: {redirect: to.path}})
                }
            }
            
            // try {
            //     next();
            // } catch(error) {
            //     //token过期或者用户修改本地token
            //     await userStore.userLogout();
            //     next({path:'/login'})
            // }
           
        }
    } else {
        //只允许进入login页面
        if (to.path=='/login') {
            next();
        } else {
            next({path:'/login', query:{redirect: to.path}})
        }
    }

})
//全局后置守卫
router.afterEach((to:any, from:any) => {
    nprogress.done(); //进度条开始
})