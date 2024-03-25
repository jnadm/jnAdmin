<?php
namespace App\Http\Admin\Controllers;

class UploadController extends BaseController
{
    /**
     * @var \App\Http\Logic\Admin\AccountLogic
     */
    public function __construct()
    {
        parent::__construct();
        $this->logic = new \App\Http\Admin\Logic\UploadLogic();
    }

    /**
     * @desc   文件上传
     * @param  resource $file 文件名
     * @author flyer
     * @date   2024/2/5
     **/
    public function index()
    {
        $file = request()->file('file');
        $key  = input('key');
        if (empty($file)) {
            return errJson('没有要上传的文件');
        }
        $data = $this->logic->index($file, $key);
        return $data['errno'] == 0 ? sucJson($data['data']) :  errJson($data['msg']);
    }

}

