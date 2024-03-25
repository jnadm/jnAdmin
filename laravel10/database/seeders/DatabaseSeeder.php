<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\SysAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //添加账号
        DB::beginTransaction();
        try{
            $adminId = $this->adminData();
            if (!$adminId) {
                return;
            }
            //添加角色
            $roleId = $this->roleData();
            //添加账号角色关联信息
            $this->roleAdminData($adminId, $roleId);
            //添加菜单数据
            $this->menuData();
            Db::commit();
        } catch (Exception $e) {
            Db::rollBack();
            dump(eMessage($e));
        }


    }

    public function adminData()
    {
        //管理员数据
        $data = [
            'username' => 'admin',
            'password' => '11f685143271aa5beaae4dcd58566d86',
            'salt' => 'a@&*6',
            'mobile' => '16666666666',
            'role_id' => 1,
            'nickname' => '超级管理员',
            'real_name' => '超级管理员',
            'avatar' => 0,
            'email' => '',
            'status' => 1,
            'fail_nums' => 0,
            'login_time' => 0,
            'login_ip' => 0,
            'created_at' => '2024-02-10 00:00:00',
            'updated_at' => '2024-02-10 00:00:00',
        ];
        if (SysAdmin::where('username', 'admin')->first()) {
            return 0;
        }
        return SysAdmin::create($data)->id;
    }

    public function roleData()
    {
        //角色数据
        $data = [
            'name' => '超级管理员',
            'status' => 1,
            'sort' => 0,
            'created_at' => '2024-02-10 00:00:00',
            'updated_at' => '2024-02-10 00:00:00',
        ];
        return \App\Models\SysRole::create($data)->id;
    }

    public function roleAdminData(int $adminId, int $roleId)
    {
        //账号角色关联信息
        $data = [
            'role_id' => $roleId,
            'admin_id' => $adminId,
            'created_at' => '2024-02-10 00:00:00',
            'updated_at' => '2024-02-10 00:00:00',
        ];
        \App\Models\SysRoleAdmin::create($data);
    }

    public function menuData()
    {
        //菜单表数据
        $data = [
            [
                'name' => '系统管理',
                'code' => 'SYSTEM01',
                'parent_code' => '0',
                'all_parent_codes' => [],
                'all_child_codes' => [],
                'path' => '',
                'component' => '',
                'type' => 1,
                'is_show' => 1,
                'sort' => 0,
                'created_at' => '2024-02-10 00:00:00',
                'updated_at' => '2024-02-10 00:00:00',
            ],
            [
                'name' => '账号管理',
                'code' => 'SYSTEM02',
                'parent_code' => 'SYSTEM01',
                'all_parent_codes' => ['SYSTEM01'],
                'all_child_codes' => [],
                'path' => '',
                'component' => '',
                'type' => 1,
                'is_show' => 1,
                'sort' => 0,
                'created_at' => '2024-02-10 00:00:00',
                'updated_at' => '2024-02-10 00:00:00',
            ],
            [
                'name' => '角色管理',
                'code' => 'SYSTEM03',
                'parent_code' => 'SYSTEM01',
                'all_parent_codes' => ['SYSTEM01'],
                'all_child_codes' => [],
                'path' => '',
                'component' => '',
                'type' => 1,
                'is_show' => 1,
                'sort' => 0,
                'created_at' => '2024-02-10 00:00:00',
                'updated_at' => '2024-02-10 00:00:00',
            ],
            [
                'name' => '菜单管理',
                'code' => 'SYSTEM05',
                'parent_code' => 'SYSTEM01',
                'all_parent_codes' => ['SYSTEM01'],
                'all_child_codes' => [],
                'path' => '',
                'component' => '',
                'type' => 1,
                'is_show' => 1,
                'sort' => 0,
                'created_at' => '2024-02-10 00:00:00',
                'updated_at' => '2024-02-10 00:00:00',
            ],
        ];
        foreach ($data as $menuInfo) {
            \App\Models\SysMenu::create($menuInfo);
        }
    }
}
