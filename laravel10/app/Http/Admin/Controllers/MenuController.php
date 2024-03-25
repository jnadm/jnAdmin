<?php
namespace App\Http\Admin\Controllers;


class MenuController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->logic = new \App\Http\Admin\Logic\MenuLogic();
    }

    /**
     * @desc   添加菜单
     * @author flyer
     * @param  string $name 菜单名称 必填
     * @param  int $parent_id 父级菜单 必填
     * @param  string $path 后端路由
     * @param  string $component 前段路由
     * @param  int $type 类型(1菜单，2按钮)
     * @param  int $is_show 是否显示
     * @param  int $sort 序号
     * @date   2024/2/3
     */
    public function add()
    {
        validate($this->request, 'add', input());
        $res = $this->logic->save(input());
        return $res['errno'] == 0 ? sucJson(['id' => $res['data']]) : errJson($res['msg']);
    }

    /**
     * @desc   编辑菜单
     * @author flyer
     * @param  int $id 菜单ID
     * @param  string $name 菜单名称 必填
     * @param  int $parent_id 父级菜单 必填
     * @param  string $path 后端路由  必填
     * @param  string $component 前段路由
     * @param  int $type 是否显示 昵称
     * @param  int $sort 序号
     * @date  2024/2/3
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
     * @author flyer
     * @date   2024/2/3
     */
    public function lists()
    {
        return sucJson($this->logic->lists(input()));
    }

    /**
     * @desc   菜单详情
     * @param  int $id 菜单ID
     * @author flyer
     * @date   2024/2/3
     */
    public function details()
    {
        $id = input('id', 0);
        validate($this->request, 'onlyId', ['id' => $id]);
        return sucJson($this->logic->details($id));
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
        return empty($ids) || !is_array($ids) ? errJson("参数不能为空，且必须是数组") : tagJson($this->logic->batchDel($ids));
    }

    /**
     * @desc   删除
     * @param  $id int 用户ID
     * @author flyer
     * @date   2024/2/3
     */
    public function del()
    {
        return tagJson($this->logic->batchDel([input('id',0)]));
    }

}

