<?php
namespace App\Http\Admin\Controllers;

class RoleController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->logic = new \App\Http\Admin\Logic\RoleLogic();
    }

    /**
     * @desc   添加
     * @author flyer
     * @param  $name 角色名称
     * @date   2024/2/5
     */
    public function add()
    {
        validate($this->request, 'add', input());
        return tagJson($this->logic->save(input()));
    }

    /**
     * @desc   修改
     * @author flyer
     * @param  $name 角色名称
     * @date   2024/2/5
     */
    public function edit()
    {
        $params = input();
        validate($this->request, 'edit', $params);
        return tagJson($this->logic->save($params, $params['id']));
    }

    /**
     * @desc   列表
     * @param  $page 当前页
     * @author flyer
     * @date   2024/2/5
     */
    public function lists()
    {
        $pageSize = !is_numeric(input('page_size')) ? 10 : input('page_size');
        return sucJson($this->logic->lists($pageSize, input()));
    }

    /**
     * @name   详情
     * @author flyer
     * @date   2024/2/5
     */
    public function details()
    {
        $id = input('id', 0);
        validate($this->request, 'onlyId', ['id' => $id]);
        return sucJson($this->logic->details($id));
    }

    /**
     * @desc   删除
     * @param  $id 数据ID
     * @author flyer
     * @date   2024/2/5
     */
    public function del()
    {
        $id = input('id');
        validate($this->request, 'del', ['id' => $id]);
        $res = $this->logic->del($id);
        return $res['errno'] == 0 ? sucJson() : errJson($res['msg']);
    }

    /**
     * @desc   批量删除
     * @param  $ids 数据ID
     * @author flyer
     * @date   2024/2/5
     */
    public function batchDel()
    {
        $ids = input('ids');
        if (empty($ids) || !is_array($ids)) {
            errJson("参数不能为空，且必须是数组");
        }
        $res = $this->logic->batchDel($ids);
        return $res['errno'] == 0 ? sucJson() : errJson($res['msg']);
    }

    /**
     * @desc   角色配置权限
     * @param  int $id  角色id
     * @param  array $menu_codes 菜单codes
     * @author flyer
     * @date   2024/2/5
     */
    public function setRolePermission()
    {
        $roleId = input('id');
        $menuCodes = input('menu_codes', []);
        validate($this->request, 'onlyId', ['id' => $roleId]);
        return tagJson($this->logic->setRolePermission($roleId, $menuCodes));
    }

    /**
     * @desc   获取角色配置权限
     * @param  int $id 角色id
     * @author flyer
     * @date   2024/1/21
     */
    public function getRolePermission()
    {
        return sucJson($this->logic->getRolePermission(input('id')));
    }

    /**
     * @desc   角色中添加或者删除用户
     * @param  $id 角色id
     * @param  $admin_ids 管理员ids
     * @param  $type 标识(1添加到角色，2从角色中去除)
     * @author flyer
     * @date   2023/10/23
     */
    public function editUserToRole()
    {
        $params   = request()->post();
        $id       = $params['id'];
        $adminIds = $params['admin_ids'] ?? [];
        $type     = $params['type'] ?? 1;  //1添加用户到角色，2从角色中去除

        validate($this->request, 'editUserToRole', $params);
        return tagJson($this->logic->editUserToRole($id, $adminIds, $type));
    }

}

