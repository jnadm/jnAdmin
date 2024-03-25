<?php
namespace App\Http\Admin\Logic;
use Illuminate\Support\Facades\DB;
use App\Models\SysAdmin as model;
use App\Models\SysRoleAdmin as roleAdminModel;
use App\Models\SysRole as roleModel;

class AccountLogic extends BaseLogic
{
    //添加或编辑数据
    public function save(array $params, int $id = 0)  :array
    {
        $data = [
            'mobile'     =>  $params['mobile'],
            'role_id'    =>  $params['role_id'],
            'real_name'  =>  $params['real_name'],
            'nickname'   =>  $params['nickname'],
            'email'      =>  $params['email'],
            'avatar'     =>  $params['avatar'],
            'status'     =>  $params['status'],
            'updated_id' => $this->uid,
        ];
        $rand = comRand(6);

        if (empty($params['password'])) {
            $params['password'] = $params['mobile'];
        }
        DB::beginTransaction();
        try{
            if (!$id) {
                //添加
                $dataOther = [
                    'username' =>  $params['username'],
                    'password' =>  comPasswd($params['password'], $rand),
                    'salt'     =>  $rand,
                    'created_id' => $this->uid,
                ];
                $id = model::create(array_merge($data, $dataOther))->id;
            } else {
                //修改
                if ($params['is_update_pass']) {
                    $data['password'] =  comPasswd($params['password'], $rand);
                    $data['salt'] = $rand;
                }
                model::where(['id' => $id])->update($data);
            }
            //处理账号与角色关联关系
            $this->handleRoleAdmin($id, $params['role_id']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
        return $this->success(['id' => $id]);
    }

    //处理账号与角色关联关系
    private function handleRoleAdmin(int $adminId, int $roleId) :bool
    {
        if (!$roleId) {
            return true;
        }
        $info = roleAdminModel::where('admin_id', $adminId)->first();
        if ($info) {
            roleAdminModel::where('admin_id', $adminId)->update(['role_id' => $roleId, 'updated_id' => $this->uid]);
        } else {
            $set = [
                'admin_id' => $adminId,
                'role_id' => $roleId,
                'created_id' => $this->uid,
                'updated_id' => $this->uid,
            ];
            roleAdminModel::create($set)->id;
        }
        return true;
    }

    //列表数据
    public function lists(int $pageSize, array $params) :array
    {
        $column = [
             ['colKey' => 'username', 'value' =>  "用户名"],
             ['colKey' => 'nickname', 'value' => "昵称"],
             ['colKey' => 'mobile', 'value' => "电话"],
             ['colKey' => 'created_at', 'value' => "创建时间"],
        ];
        $where = [];
        if (!empty($params['username'])) {
            $where[] = ['username', 'like', "%{$params['username']}%"];
        }
        if(!empty($params['mobile'])) {
            $where[] = ['mobile', 'like', "%{$params['mobile']}%"];
        }

        $lists = model::select(array_merge(['id'], array_column($column, 'colKey')))
            ->where($where)
            ->orderBy('id', 'desc')
            ->paginate($pageSize);
        return ['column' => $column, 'lists' => $lists];
    }

    //详情
    public function details(int $id) :object
    {
        $field = ['id', 'username', 'mobile', 'nickname', 'real_name', 'avatar', 'email', 'status', 'fail_nums',
                    'login_time', 'login_ip', 'created_at', 'updated_at', 'role_id'];
        $data = model::select($field)->where('id', $id)->first();
        $data->avatar_url = (new \App\Models\SysFile)->getPath($data['avatar']);
        $data->role_name = roleModel::where('id', $data['role_id'])->value('name') ?? '';
        return $data;
    }

    //批量删除
    public function batchDel(array $ids) :int
    {
        return model::whereIn('id', $ids)->delete();
    }

    //导出数据
    public function export()
    {
        $field = ['id', 'username', 'mobile'];
        $lists = model::select($field)->get()->toArray();
        $path = storage_path('app/public') . "/admin/avatar/a.xlsx";
        return \ExcelSpout::export($path, $field, $lists);
    }

}
