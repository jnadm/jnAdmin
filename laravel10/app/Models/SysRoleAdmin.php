<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SysRoleAdmin extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sys_role_admin';

    //不可批量赋值的属性
    public $guarded  = [];

}
