import request from "@/utils/request";

//统一管理接口
enum API {
    login = "/admin/nologin/login"
}

//登录接口
export const apiLogin = (data: any) => request.post(API.login, data, '');
