<?php
namespace App\Http\Admin\Logic;
use App\Models\SysMenu as model;
use Illuminate\Support\Facades\DB;

class MenuLogic extends BaseLogic
{
    //添加或编辑数据
    public function save(array $params, int $id = 0) :array
    {
        $data = [
            'name'      => $params['name'],
            'parent_code' => empty($params['parent_code']) ? 0 : $params['parent_code'],
            'path'      => $params['path'],
            'component' => $params['component'],
            'type'      => $params['type'],
            'is_show'   => $params['is_show'],
            'sort'      => $params['sort'],
            'created_id' => $this->uid,
            'updated_id' => $this->uid,
        ];
        if ($id) {
            DB::beginTransaction();
            try {
                $info = model::where(['id' => $id])->first()->toArray();
                 model::where(['id' => $id])->update($data);
                if ($info['parent_code'] !== $data['parent_code']) {
                    $this->handleAllMenuEdit($data['parent_code'], $info);      //更换父元素，处理上下级关系
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error(eMessage($e));
            }
        } else {
            $data['code']           = model::getCode();
            $data['all_parent_codes'] = [];
            $data['all_child_codes']  = [];
            DB::beginTransaction();
            try {
                $id = model::create($data)->id;
                //处理上下级关系
                $this->handleAllMenuAdd($data['code'], $data['parent_code']);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->error(eMessage($e));
            }
        }
        return $this->success(['id' => $id]);
    }

    //添加菜单时处理父级数据的all_child_codes字段
    private function handleAllMenuAdd(string $code, string $parentCode)
    {
        if ($code && $parentCode) {
            //更新本节点上的所有父节点字段
            $parentInfo = model::where(['code' => $parentCode])->first()->toArray() ?? [];
            $parentCodes  = array_values(array_merge($parentInfo['all_parent_codes'], [$parentCode]));
            model::where(['code' => $code])->update(['all_parent_codes' => $parentCodes]);

            //更新所有父节点的孩子节点
            $allChildCodes = model::whereIn('code', $parentCodes)->pluck('all_child_codes', 'code')->toArray();
            foreach ($parentCodes as $pCode) {
                $newChildCodes = array_values(array_merge($allChildCodes[$pCode], [$code]));
                model::where([['code', '=', $pCode]])->update(['all_child_codes' => $newChildCodes]);
            }
        }
        return true;
    }

    //编辑菜单时处理其他菜单的all_child_codes雨all_parent_codes
    private function handleAllMenuEdit(string $parentCode, array $info)
    {
        $code = $info['code'];
        //处理本条数据all_parent_codes
        $newParentCodes = []; //新的父节点
        if ($parentCode && !in_array($parentCode, $info['all_parent_codes'])) {
            $parentInfo = model::where(['code' => $parentCode])->first();
            $newParentCodes = $parentInfo ? array_values(array_merge([$parentCode], $parentInfo->all_parent_codes)) : [$parentCode];
            model::where(['id' => $info['id']])->update(['all_parent_codes' => $newParentCodes]);
        }elseif ($parentCode == 0) {
            model::where(['id' => $info['id']])->update(['all_parent_codes' => []]);
        }

        //处理原有本节点所有父节点：
        //(1)将本节点以及本几点所有子节点从原有的所有子节点中移除 (2)将本节点以及本节点所有子节点添加到新的所有父节点中的all_child_codes
        $allParentCodeData = model::whereIn('code', $info['all_parent_codes'])->pluck('all_child_codes', 'code');

        foreach ($info['all_parent_codes'] as $tempCode) {
            $temp  = $allParentCodeData[$tempCode] ?? [];
            $codes = array_values(array_diff($temp, array_merge([$code], $info['all_child_codes'])));
            model::where('code', $tempCode)->update(['all_child_codes' => $codes]);
        }

        $allChildCodeData = model::whereIn('code', $newParentCodes)->pluck('all_child_codes', 'code');
        foreach ($newParentCodes as $tempCode) {
            $temp  = $allChildCodeData[$tempCode] ?? [];
            $codes = array_values(array_merge($temp, [$code], $info['all_child_codes']));
            model::where('code', $tempCode)->update(['all_child_codes' => $codes]);
        }

        //处理本节点所有子节点的父节点：先移除本节点原有所有父节点，并添加新的父节点
        $allParentCodeData = model::whereIn('code', $info['all_child_codes'])->pluck('all_parent_codes', 'code');
        foreach ($info['all_child_codes'] as $tempCode) {
            $temp = $allParentCodeData[$tempCode] ?? [];
            $temp = array_diff($temp, $info['all_parent_codes']);
            $codes = array_values(array_merge($temp, $newParentCodes));
            model::where('code', $tempCode)->update(['all_parent_codes' => $codes]);
        }
    }

    //详情
    public function details(int $id) :object
    {
        $field = ['id', 'name', 'code', 'parent_code', 'path', 'component', 'type', 'is_show', 'sort', 'created_at'];
        return model::select($field)->where(['id' => $id])->first();
    }

    //列表数据
    public function lists(array $params) :array
    {
        $column = [
            'name' => '菜单名称',
            'code' => '菜单标识',
            'is_show' => '是否可见'
        ];
        $fields = ['id', 'name', 'code', 'parent_code', 'component', 'type', 'is_show'];
        $lists = model::select($fields)->get()->toArray();
        $lists = list_to_tree($lists, 0, 'code', 'parent_code');
        return  array_merge(['column'=> $column], ['data' => $lists]);
    }

    //批量删除数据
    public function batchDel(array $ids) :bool
    {
        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $this->del($id);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
        return true;
    }

    //删除数据
    public function del(int $id) :bool
    {
        $info = model::where(['id' => $id])->first();
        if (empty($info)) {
            return true;
        }
        //执行删除
        $set = ['updated_id' => $this->uid, 'deleted_at' =>  date('Y-m-d H:i:s')];
        model::where(['id' => $id])->update($set);
        //删除所有子元素
        model::whereIn('code', $info->all_child_codes)->update($set);

        //将本节点以及本节点所有子节点从原有的所有父节点中移除
        $allParentCodeData = model::whereIn('code', $info['all_parent_codes'])->pluck('all_child_codes', 'id');
        foreach ($info['all_parent_codes'] as $tempCode) {
            $temp  = $allParentCodeData[$tempCode] ?? [];
            $codes = array_diff($temp, array_merge([$info->code], $info['all_child_codes']));
            model::where('code', $tempCode)->update(['all_child_codes' => $codes]);
        }
        return true;
    }

}
