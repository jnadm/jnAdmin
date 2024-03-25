<?php
namespace App\Http\Admin\Logic;
use App\Models\SysRole as model;
use App\Models\SysRoleAdmin as roleAdminModel;
use App\Models\SysPermission as permissionModel;
use App\Models\SysRolePermission as rolePermissionModel;
use Illuminate\Support\Facades\DB;

class RoleLogic extends BaseLogic
{
    //添加或编辑数据
    public function save(array $params, int $id = 0) :int
    {
        $data = [
            'name' =>  $params['name'],
            'status' => $params['status'],
            'sort' => $params['sort'],
            'updated_id' => $this->uid,
        ];

        if (!$id) {
            $data['created_id'] = $this->uid;
            return model::create($data)->id;
        } else {
            return model::where(['id' => $id])->update($data);
        }
    }

    //列表数据
    public function lists(int $pageSize, array $params) :array
    {
        $column = [
            ['colKey' => 'name', 'value' =>  "名称"],
            ['colKey' => 'status', 'value' => "状态"],
            ['colKey' => 'created_at', 'value' => "创建时间"],
        ];
        $where = [];
        if (!empty($params['name'])) {
            $where[] = ['name', 'like', "%{$params['name']}%"];
        }
        if (!empty($params['status'])) {
            $where[] = ['status', '=', $params['status']];
        }
        $lists = model::select(array_merge(['id'], array_column($column, 'colKey')))
            ->where($where)
            ->orderBy('id', 'desc')
            ->paginate($pageSize);
        //获取角色对应的权限
        $permission = rolePermissionModel::select(['role_id', 'permission_id'])
                        ->with(['permission' => function($query) {return $query->select(['id', 'menu_codes']);}])
                        ->whereIn('role_id', $lists->modelKeys())->get();
        $permission = $permission->pluck('permission', 'role_id');

        $lists->each(function($lists) use ($permission) {
            $lists->menuCodes = $permission[$lists->id]->menu_codes ?? [];
        });
        return  ['column' => $column, 'lists' => $lists];
    }

    //详情
    public function details(int $id) :object
    {
        return model::select(['id', 'name', 'status', 'sort', 'created_id', 'updated_id'])->where([ 'id' => $id])->first();
    }

    //删除数据
    public function del(int $id) :array
    {
        $info = roleAdminModel::where(['role_id' => $id])->first();
        if (!empty($info)) {
            return $this->error('角色中有账号数据');
        }
        return model::where(['id' => $id])->delete() ? $this->success() : $this->error();
    }

    //批量删除角色
    public function batchDel(array $ids) :array
    {
        //检测角色下面是否有用户
        $info = roleAdminModel::whereIn('role_id', $ids)->first();
        if (!empty($info)) {
            return $this->error('选择角色中有账号被使用');
        }
        return model::whereIn('id', $ids)->delete() ? $this->success() : $this->error();
    }

    //角色配置权限
    public function setRolePermission(int $roleId, array $menuCodes) :bool
    {
        $info = rolePermissionModel::select('permission_id')->where(['role_id' => $roleId])->first();
        $permissionId = $info ? (permissionModel::find($info->permission_id) ? $info->permission_id : 0) : 0;
        DB::beginTransaction();
        try {
            $permissionId = (new permissionModel)->saveData($menuCodes, $permissionId);
            $data = [
                'role_id' => $roleId,
                'permission_id' => $permissionId,
                'created_id' => $this->uid,
                'updated_id' => $this->uid,
            ];
            if (!$info) {
               rolePermissionModel::create($data);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
        return true;
    }

    //获取角色权限
    public function getRolePermission(int $roleId) :array
    {
         return rolePermissionModel::where('role_id', $roleId)->first()->permission->menu_codes ?? [];
    }

    //角色中添加或者删除用户
    public function editUserToRole(int $id, array $adminIds, int $type) :bool
    {
        DB::beginTransaction();
        try {
            foreach ($adminIds as $adminId) {
                $info = roleAdminModel::where(['role_id' => $id, 'admin_id' => $adminId])->first();
                if ($type == 1 && !$info) {
                    roleAdminModel::create(['role_id' => $id, 'admin_id' => $adminId, 'created_id' => $this->uid, 'updated_id' => $this->uid]);
                } elseif ($type == 2 && $info) {
                    roleAdminModel::where(['id' => $info['id']])->delete();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
        return true;
    }

}
