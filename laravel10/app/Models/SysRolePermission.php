<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class SysRolePermission extends BaseModel
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'sys_role_permission';

    public function permission(): HasOne
    {
        return $this->hasOne(SysPermission::class, 'id', 'permission_id');
    }




}
