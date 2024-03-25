<?php

namespace App\Models;

class SysMenu extends BaseModel
{
    protected $table = 'sys_menu';
    protected $casts = [
      'all_parent_codes' => 'array',
      'all_child_codes' => 'array',
    ];
}
