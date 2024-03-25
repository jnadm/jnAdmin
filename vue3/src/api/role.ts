import request from "@/utils/request";

//统一管理接口
enum API {
    apiRoleLists = "/admin/role/lists",
    apiRoleAdd = "/admin/role/add",
    apiRoleEdit = "/admin/role/edit",
    apiRoleDetails = '/admin/role/details',
    apiRoleBatchDel = '/admin/role/batchDel',
    setRolePermission = '/admin/role/setRolePermission',
}

//获取用户列表页
export const apiRoleLists = (data: any) => request.post(API.apiRoleLists, data, '');
export const apiRoleAdd = (data: any) => request.post(API.apiRoleAdd, data, '');
export const apiRoleEdit = (data: any) => request.post(API.apiRoleEdit, data, '');
export const apiRoleDetails = (data: any) => request.post(API.apiRoleDetails, data, '');
export const apiRoleBatchDel = (data: any) => request.post(API.apiRoleBatchDel, data, '');
export const setRolePermission = (data: any) => request.post(API.setRolePermission, data, '');
