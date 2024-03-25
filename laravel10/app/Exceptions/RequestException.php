<?php
namespace App\Exceptions;
/**
 * @desc   自定义异常类：用于验证器抛出的异常
 * @author flyer
 * @date   2023/12/8
 **/
class RequestException extends \Exception
{
    protected $message;
    // 重定义构造器使 message 变为必须被指定的属性
    public function __construct($message, $code = 0, Throwable $previous = null) {
        // 确保所有变量都被正确赋值
        parent::__construct($message, $code, $previous);

        $this->message = $message;
    }

    //定义异常发生时额要做的事情
    public function report()
    {

    }

    //给API返回必要的通知信息
    public function render()
    {
        return errJson($this->message);
    }
}
