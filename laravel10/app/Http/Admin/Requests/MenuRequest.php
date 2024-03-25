<?php

namespace App\Http\Admin\Requests;

use Illuminate\Validation\Rule;

class MenuRequest extends BaseRequest
{

    public function rules()
    {
        $parentCode = $this->data['parent_code'] ?? '';
        $model = "App\Models\SysMenu";
        return [
            'id'        => ['required', "exists:$model,id,deleted_at,NULL"],
            'name'      => 'required|max:20',
//            'parent_id' => !$parentId
//                            ? ["present", "numeric"]
//                            : ["present", "exists:$model,id,deleted_at,NULL",
//                                function ($attribute, $value, $fail) use($model) {
//                                    $info = $model::where(['id' => $value])->first();
//                                    if ($info && $info->type == 2) {
//                                        $fail('按钮不能作为父级菜单项');
//                                    }
//                            }],
            'parent_code' => !$parentCode
                ? ["string"]
                : ["present", "exists:$model,code,deleted_at,NULL",
                    function ($attribute, $value, $fail) use($model) {
                        $info = $model::where(['code' => $value])->first();
                        if ($info && $info->type == 2) {
                            $fail('按钮不能作为父级菜单项');
                        }
                    }],
            'path'      => 'present|max:200',
            'component' => 'present|max:200',
            'type'      => 'present|in:1,2',
            'sort'      => 'required|numeric',
        ];

    }


    public function scene()
    {
        return [
            'add'  => ['name', 'parent_code', 'path', 'component', 'type', 'sort'],
            'edit' => ['id', 'name', 'parent_code', 'path', 'component', 'type', 'sort'],
            'onlyId' => ['id'],
        ];
    }

}
