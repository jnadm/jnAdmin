<?php
namespace App\Http\Admin\Controllers;

/**
 * @desc   权限管理模块
 * @author flyer
 * @date   2023/10/23
 */


class PermissionController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->logic = new \App\Http\Admin\Logic\PermissionLogic();
    }

    /**
     * @desc   添加
     * @author flyer
     * @param  string $name 名称
     * @date   2024/2/15
     */
    public function add()
    {
        $params = request()->post();
        unset($params['id']);
        validate($this->request, 'add', $params);
        tagJson($this->logic->save($params));
    }

    /**
     * @desc   修改
     * @author flyer
     * @param  $name 角色名称
     * @date   2024/2/15
     */
    public function edit()
    {
        $params = request()->post();
        validate($this->request, 'edit', $params);
        tagJson($this->logic->save($params, $params['id']));
    }

    /**
     * @desc   列表
     * @param  $page 当前页
     * @author flyer
     * @date   2024/2/15
     */
    public function lists()
    {
        $params = Request('params', []);
        sucJson($this->logic->lists($params));
    }

    /**
     * @name   详情
     * @author flyer
     * @date   2024/2/15
     */
    public function details()
    {
        $id = Request('id', 0);
        sucJson($this->logic->details($id));
    }

    /**
     * @desc   删除
     * @param  $id 用户ID
     * @author flyer
     * @date   2024/2/15
     */
    public function del()
    {
        $id = request()->post('id', 0);
        tagJson($this->logic->del($id));
    }

    /**
     * @desc   权限中添加或者移除角色
     * @param  $id 权限ID
     * @param  $role_ids 角色ids
     * @param  $type 标识(1添加，2移除)
     * @author flyer
     * @date   2024/2/15
     */
    public function editPermissionRole()
    {
        $params = request()->post();
        validate($this->request, 'editPermissionRole', $params);
        tagJson($this->logic->editPermissionRole($params));
    }

}

