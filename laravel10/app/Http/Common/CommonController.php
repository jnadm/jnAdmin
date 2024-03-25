<?php
namespace App\Http\Common;

class CommonController extends Controller
{
    protected $logic;
    protected $request;

    public function __construct()
    {
        //自动跟踪逻辑层与验证器
        $this->setLogic();
    }

    private function setLogic() {
        $list   = explode('\\', request()->route()->getActionName());
        $module = $list[2];
        $action = explode('Controller@', end( $list))[0] ?? '';
        $logic = "App\\Http\\{$module}\\Logic\\{$action}Logic";
        $this->logic = new $logic();
        $this->request = "App\\Http\\{$module}\\Requests\\{$action}Request";
    }
}
