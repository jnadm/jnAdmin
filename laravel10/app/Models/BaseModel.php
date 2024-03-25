<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    //使用deleted_at字段
    use SoftDeletes;
    //不可批量赋值的属性
    public $guarded  = [];

    public $uid = 0;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        //获取用户uid
        if (!empty(request()->header('token'))) {
            $tokenInfo = \ComJwt::checkToken(request()->header('token'));
            if ($tokenInfo['errno'] == 0) {
                $this->uid =  $tokenInfo['data']['uid'];
            }
        }
    }

    //日期格式正常输出
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    //获取唯一编码字段
    protected function getCode()
    {
        while (true) {
            $code = guid(8, 'upper');
            if (!(new (get_called_class()))::where('code', $code)->first()) {
                break;
            }
        }
        return $code;
    }


}
