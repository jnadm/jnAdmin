<?php
namespace App\Http\Admin\Logic;
use App\Models\SysPermission as model;
use App\Models\SysRolePermission as rolePermissionModel;

class PermissionLogic extends BaseLogic
{
    //添加或编辑数据
    public function save(array $params, int $id = 0) :int
    {
        $data = [
            'name' =>  $params['name'],
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
    public function lists() :object
    {
        return model::select(['name'])->paginate(10);
    }

    //删除数据
    public function del(int $id) :bool
    {

        $info = rolePermissionModel::where(['role_id' => $id, 'is_deleted' => 0])->first();
        if (!empty($info)) {
            return '权限中中有角色数据，无法删除';
        }

        $set = ['updated_id' => $this->uid, 'is_deleted' => 1];
        $result = model::where(['id' => $id, 'is_deleted' => 0])->update($set);
        if (!$result) {
            return  false;
        }
        return true;
    }

    //权限中添加或者删除角色
    public function editPermissionRole(array $params) :bool
    {
        $id       = $params['id'];
        $adminIds = $params['admin_ids'] ?? [];
        $type     = $params['type'] ?? 1;  //1添加用户到角色，2从角色中去除

        foreach ($adminIds as $adminId) {
            $info = rolePermissionModel::where(['role_id' => $id, 'admin_id' => $adminId, 'is_deleted' => 0])->first();
            if (empty($info) && $type == 1) {
                $data = [
                    'role_id' => $id,
                    'admin_id' => $adminId,
                ];
                $data['created_id'] = $data['updated_id'] = $this->uid;
                rolePermissionModel::create($data);
            }

            if (!empty($info) && $type == 2) {
                rolePermissionModel::where(['id' => $info['id']])->update(['is_deleted' => 1, 'updated_id' => $this->uid]);
            }
        }
        return true;
    }

}
