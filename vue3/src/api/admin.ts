import request from "@/utils/request";

//统一管理接口
enum API {
    apiAccountLists = "/admin/account/lists",
    apiAccountAdd = "/admin/account/add",
    apiAccountEdit = "/admin/account/edit",
    apiAccountDetails = '/admin/account/details',
    apiAccountBatchDel = '/admin/account/batchDel',
    apiUpload = '/admin/upload/index',
}

//获取用户列表页
export const apiAccountLists = (data: any) => request.post(API.apiAccountLists, data, '');
export const apiAccountAdd = (data: any) => request.post(API.apiAccountAdd, data, '');
export const apiAccountEdit = (data: any) => request.post(API.apiAccountEdit, data, '');
export const apiAccountDetails = (data: any) => request.post(API.apiAccountDetails, data, '');
export const apiAccountBatchDel = (data: any) => request.post(API.apiAccountBatchDel, data, '');
export const apiUpload = (data: any) => request.post(API.apiUpload, data, '')
