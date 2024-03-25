<?php

namespace App\Http\Admin\Requests;

use Illuminate\Validation\Rule;

class PermissionRequest extends BaseRequest
{

    public function rules()
    {
        $id    = $this->data['id'] ?? 0;
        $model = "App\Models\SysPermission";
        return [
            'id' => ['required', "exists:$model,id,deleted_at,NULL"],
            'name'  => "required|max:20|unique:$model,name,NULL,id,deleted_at,NULL",
        ];
    }


    public function scene()
    {
        return [
            'add' => ['username', 'password', 'email', 'real_name', 'nickname', 'avatar'],
            'edit' => ['id', 'password', 'mobile', 'email', 'real_name', 'nickname', 'avatar'],
        ];
    }

}
