<?php

namespace App\Models;

class SysFile extends BaseModel
{
    protected $table = 'sys_file';

    /**
     * @desc   获取附件路径
     * @author flyer
     * @param  int $id 附件ID
     * @date   2024/1/5
     **/
    public function getPath(int $id)
    {
         $info = SysFile::select(['path'])->find($id);
         return $info ? $info['path'] : '';
    }
}
