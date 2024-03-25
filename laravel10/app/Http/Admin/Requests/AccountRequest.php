<?php

namespace App\Http\Admin\Requests;

use Illuminate\Validation\Rule;
use App\Models\SysRole as roleModel;
class AccountRequest extends BaseRequest
{

    public function rules()
    {
        $id    = $this->data['id'] ?? 0;
        $model = 'App\Models\SysAdmin';
        return [
            'id'        => ['required',"int","exists:$model,id,deleted_at,NULL"],
            'username'  => "required|regex:'^[a-zA-Z][a-zA-Z0-9_]*$'|max:20|unique:$model,username,NULL,id,deleted_at,NULL",
            'role_id'   => ['required', 'int',
                            function($attribute, $value, $fail) {
                                if(!roleModel::find($value)) {
                                    $fail("角色不存在");
                                }
                            }],
            'is_update_pass' => 'required|in:0,1',
            'password'  => !$id ? 'min:6' : "exclude_if:is_update_pass,1,required|min:6",
            'email'     => 'present|email',
            'real_name' => 'present|max:20',
            'nickname'  => 'present|max:20',
            'avatar'    => 'required|int',
            'status'    => 'required|int',
            'mobile' => !$id ? ['required', 'regex:/^1[^0-2]\d{9}$/', "unique:$model,mobile,NULL,id,deleted_at,NULL"]
                             : ['required', 'regex:/^1[^0-2]\d{9}$/', "unique:$model,mobile,NULL,id,deleted_at,NULL,id,!$id"],
            'ids'    => ['array',
                            function($attribute, $value, $fail) use ($model)  {
                                if($model::whereIn('id', $value)->where('username', 'admin')->first()) {
                                    $fail("不能删除管理员账号");
                                }
                          }],
        ];
    }


    public function scene()
    {
        return [
            'add' => ['username', 'role_id', 'password', 'mobile', 'email', 'real_name', 'nickname', 'avatar', 'status'],
            'edit' => ['id', 'role_id', 'password', 'mobile', 'email', 'real_name', 'nickname', 'avatar','status', 'is_update_pass'],
            'onlyId' => ['id'],
            'delIds' => ['ids'],
        ];
    }

}
