<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;

class SysPermission extends BaseModel
{
    protected $table = 'sys_permission';
    protected $casts = [
        'menu_codes' => 'array',
    ];

    //添加权限
    public function saveData(array $menuCodes, int $id=0) :int
    {
        $data = [
            'menu_codes' => $menuCodes,
            'updated_id' => $this->uid,
        ];
        if (!$id) {
            $data['created_id'] = $this->uid;
            return self::create($data)->id;
        } else {
            return self::where(['id' => $id])->update($data) ? $id : 0;
        }
    }

    public function getCode(): string
    {
        return guid(16, 'upper');
    }

    /**
     * 反向定义模型关联
     */
    public function rolePermission()
    {
        return $this->belongsTo(SysRolePermission::class, 'permission_id', 'id');
    }
}
