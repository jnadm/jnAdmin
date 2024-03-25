<?php
namespace App\Http\Admin\Controllers;

class AccountController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->logic = new \App\Http\Admin\Logic\AccountLogic();
    }

    /**
     * @desc   添加用户
     * @author flyer
     * @param  string $username 用户名
     * @param  string $password 密码
     * @param  string $mobile 手机
     * @param  string $real_name 真实姓名
     * @param  string $nickname  昵称
     * @param  string $email 邮箱
     * @param  int $avatar 头像
     * @date   2024/2/3
     */
    public function add()
    {
        validate($this->request, 'add', input());
        $res = $this->logic->save(input());
        return $res['errno'] == 0 ? sucJson(['id' => $res['data']]) : errJson($res['msg']);
    }

    /**
     * @desc   修改用户
     * @author flyer
     * @param  string $username 用户名
     * @param  string $password 密码
     * @param  string $mobile 手机号
     * @param  string $real_name 真实姓名
     * @param  string $nickname  昵称
     * @param  string $email 邮箱
     * @param  string $avatar 头像
     * @param  int $is_update_pass 是否修改密码
     * @date   2024/2/3
     */
    public function edit()
    {
        $params = input();
        validate($this->request, 'edit', $params);
        $res = $this->logic->save($params,  $params['id']);
        return $res['errno'] == 0 ? sucJson(['id' => $res['data']]) : errJson($res['msg']);
    }

    /**
     * @desc   用户列表
     * @param  int $page 当前页
     * @param  int $page_size 每页条数
     * @author flyer
     * @date   2024/2/3
     */
    public function lists()
    {
        $pageSize = !is_numeric(input('page_size')) ? 10 : input('page_size');
        return sucJson($this->logic->lists($pageSize, input()));
    }

    /**
     * @desc   详情页
     * @param  int $id 数据id
     * @author flyer
     * @date   2024/2/3
     **/
    public function details()
    {
        $id = input('id', 0);
        validate($this->request, 'onlyId', ['id' => $id]);
        return sucJson($this->logic->details($id));
    }

    /**
     * @desc   删除
     * @param  int $id 用户ID
     * @author flyer
     * @date  2024/2/3
     */
    public function del()
    {
        $id = input('id', 0);
        validate($this->request, 'delIds', ['ids' => [$id]]);
        return tagJson($this->logic->batchDel([$id]));
    }

    /**
     * @desc 批量删除
     * @param array $ids 用户ids
     * @author flyer
     * @date 2024/2/3
     */
    public function batchDel()
    {
        $ids = input('ids');
        validate($this->request, 'delIds', ['ids' => $ids]);
        return empty($ids) || !is_array($ids) ? errJson("参数不能为空，且必须是数组") : tagJson($this->logic->batchDel($ids));
    }

    /**
     * @desc   导出数据test
     * @author flyer
     * @date   2024/3/5
     **/
    public function export()
    {
        return  tagJson($this->logic->export());
    }

}

