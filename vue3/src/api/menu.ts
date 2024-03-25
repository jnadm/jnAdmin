import request from "@/utils/request";

//统一管理接口
enum API {
    apiMenuLists = "/admin/menu/lists",
    apiMenuAdd = "/admin/menu/add",
    apiMenuEdit = "/admin/menu/edit",
    apiMenuDetails = '/admin/menu/details',
    apiMenuBatchDel = '/admin/menu/batchDel',
    apiUpload = '/admin/upload/index',
}

//获取用户列表页
export const apiMenuLists = (data: any) => request.post(API.apiMenuLists, data, '');
export const apiMenuAdd = (data: any) => request.post(API.apiMenuAdd, data, '');
export const apiMenuEdit = (data: any) => request.post(API.apiMenuEdit, data, '');
export const apiMenuDetails = (data: any) => request.post(API.apiMenuDetails, data, '');
export const apiMenuBatchDel = (data: any) => request.post(API.apiMenuBatchDel, data, '');
export const apiUpload = (data: any) => request.post(API.apiUpload, data, '')
