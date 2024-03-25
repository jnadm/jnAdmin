<?php

namespace App\Http\Admin\Requests;

use Illuminate\Validation\Rule;

class RoleRequest extends BaseRequest
{
    public function rules()
    {
        $id    = $this->data['id'] ?? 0;
        $model = 'App\Models\SysRole';
        return [
            'id'   => ['required',"exists:$model,id,deleted_at,NULL"],
            'name' => !$id ? ['required', 'max:20', "unique:$model,name,NULL,id,deleted_at,NULL"]
                           : ['required', 'max:20', "unique:$model,name,NULL,id,deleted_at,NULL,id,!$id"],
            'status'    => 'required|int',
            'sort'      => 'required|numeric',

        ];
    }

    public function scene()
    {
        return [
            'add'  => ['name', 'sort', 'status'],
            'edit' => ['id', 'name', 'sort', 'status'],
            'del'  => ['id'],
            'setRolePermission' => ['id'],
            'onlyId' => ['id'],
        ];
    }

}
