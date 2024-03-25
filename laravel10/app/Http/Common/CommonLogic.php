<?php
namespace App\Http\Common;

class CommonLogic
{
    protected $uid;
    protected $username;
    protected $role_id;
    public function __construct()
    {
        $this->uid      = request()->get('uid');
        $this->username = request()->get('username');
    }

    /**
     * @desc   成功返回值处理
     * @param  string $msg 提示信息
     * @param  int   $code 状态码
     * @param  array $data 数据
     * @author flyer
     * @date   2024/2/3
     **/
    public function success(array $data = [], string $msg = '操作成功', int $code = 0) :array
    {
        return ['errno' => $code, 'msg' => $msg, 'data' => $data];
    }

    /**
     * @desc   处理失败返回值
     * @param  string $msg 提示信息
     * @param  int   $code 状态码
     * @author flyer
     * @date   2024/2/3
     **/
    public function error(string $msg = '操作失败', int $code = 1) :array
    {
        return ['errno' => $code, 'msg' => $msg];
    }


}
