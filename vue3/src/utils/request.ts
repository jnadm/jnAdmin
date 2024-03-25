//import axios,  { AxiosRequestConfig , AxiosResponse, AxiosError} from 'axios';
import axios from 'axios';
import router from "@/routes";
import { ElMessage } from 'element-plus';
import qs from 'qs'

//引入用户相关的仓库
import useUserStore from '@/store/modules/user';

//添加配置项
let axiosCfg = axios.create({
    //基础路径
    baseURL: import.meta.env.VITE_API_URL,
    timeout: 5000,
})

//配置请求拦截器
axiosCfg.interceptors.request.use((config) => {
    //获取用户相关的小仓库，提取token
    let userStore = useUserStore();
    if (userStore.token) {
        config.headers.token = userStore.token;
    }
    return config
})

//配置响应拦截器
axiosCfg.interceptors.response.use((response:any) => {
    if (response.data.code !== 200) {
        ElMessage({
            type: 'error',
            message: response.data.msg
        })
        if (response.data.code == 500) {
            let userStore = useUserStore();
            userStore.userLogout()
            router.push({name:'login',query: {redirect: router.currentRoute.value.fullPath}})
        }
        return Promise.reject();
    }
    //成功回调
    return response.data;
}, (error) => {
    //失败回调：处理http网络错误
    let message = '' //存储网络错误信息
    let status = error.response.status //http状态码
    switch (status) {
        case 401:
            message = "请求信息有误"
            break;
        case 403:
            message = "无权访问"
            break;
        case 404:
            message = "请求地址错误"
            break;
        case 500:
            message = "服务器错误"
            break;
        default:
            message = "网络出现错误"
            break;
    }
    ElMessage({
        type: 'error',
        message
    })
    return Promise.reject(error);
})


/**
 * 封装请求方式
 */
const request =
{
    /**
     * @desc  封装axios get方法
     * @param url 请求连接
     * @param params 请求参数
     * @param callback 回调方法
     */
    get(url: string, params: any, callback: any) 
    {
        return new Promise((resolve, reject) => {
            axiosCfg.get(url, {
                    params: params
                })
                .then(res => {
                    callback ? resolve(callback(res.data)) : resolve(res.data);
                })
                .catch(err => {
                    reject(err);
                });
        });
    },

     /**
     * @desc  封装axios post方法
     * @param url 请求连接
     * @param params 请求参数
     * @param callback 回调方法
     */
     post(url: string, params: any, callback: any) 
     {
         return new Promise((resolve, reject) => {
            axiosCfg.post(url, qs.stringify(params))
                 .then(res => {
                    callback ? resolve(callback(res)) : resolve(res);
                 })
                 .catch(err => {
                     reject(err);
                 });
         });
   
     },

     /**
     * @desc  put请求封装
     * @param url 请求连接
     * @param params 请求参数
     * @param callback 回调方法
     */
    put(url: string, params: any, callback: any) 
    {
        return new Promise((resolve, reject) => {
            axiosCfg.put(url, params)
                .then(res => {
                    callback ? resolve(callback(res.data)) : resolve(res.data);
                }, err => {
                    reject(err)
                })
        })
    },

    /**
     * @desc  请求失败后的错误统一处理
     * @param {Number} status 请求失败的状态码
     */
    error(status:any, other:any)
    {
        // 状态码判断
        switch (status) {
            // 401: 未登录状态，跳转登录页
            case 401:
                // toLogin();
                break;
            // 403 token过期
            // 清除token并跳转登录页
            case 403:
                // tip('登录过期，请重新登录');
                // localStorage.removeItem('token');
                // store.commit('loginSuccess', null);
                setTimeout(() => {
                    // toLogin();
                }, 1000);
                break;
            // 404请求不存在
            case 404:
                // tip('请求的资源不存在');
                break;
            default:
                console.log(other);
        }
    }
}

export default request;
