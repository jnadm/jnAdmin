<?php
namespace App\Http\Admin\Controllers;

use App\Models\SysMenu;
use App\Models\SysRolePermission as rolePermissionModel;
use Illuminate\Support\Facades\DB;

class NoLoginController
{
    /**
     * @desc   登录
     * @param  string $username 用户名
     * @param  string $passwordd 密码
     * @author flyer
     * @date   2024/2/3
     **/
    public function login()
    {
        $username = request('username');
        $password = request('password');
        $userMsg  = Db::table('sys_admin')->where('username', $username)->first();

        if (empty($userMsg)) {
            return errJson('用户信息不存在');
        }
        if ($userMsg->status != 1) {
            return errJson('用户账号已被停用，请联系管理员');
        }
        //验证登录次数
        if (($userMsg->fail_nums) >= 5 && (time() - $userMsg->login_time) < 60*60*24) {
            return errJson("登录次数过多,请24小时之后再试");
        }
        //验证密码是否正确
        $password1 = md5('123456');
        $enPasswd = comPasswd($password, $userMsg->salt);
        if ($enPasswd !== $userMsg->password) {
            $set = [
                'fail_nums' => $userMsg->fail_nums + 1,
                'login_time' => time(),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_id' => $userMsg->id,
            ];
            $flag = Db::table('sys_admin')->where(['username' => $username])->update($set);
            if (!$flag) {
                return errJson('系统有误');
            }
            return errJson('密码错误');
        }

        //登录成功
        $set = [
            'fail_nums' => 0,
            'login_time' => time(),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_id' => $userMsg->id,
        ];

        $flag = Db::table('sys_admin')->where(['username' => $username])->update($set);
        if (!$flag) {
            return errJson('系统有误');
        }
        //获取菜单与按钮权限
        $rolePath = rolePermissionModel::where('role_id', $userMsg->role_id)->first()->permission->menu_codes ?? [];
        $sysMenu = SysMenu::where('type', 1);
        if ($username != 'admin') {
           $sysMenu = $sysMenu::whereIn('code', $rolePath);
        }
        $menuLists = $sysMenu->pluck('component');
        $userInfo = [
                    'uid' => $userMsg->id,
                    'username' => $userMsg->username,
                    'role_id' => $userMsg->role_id,
                    'avatar' => (new \App\Models\SysFile)->getPath($userMsg->avatar),
                    ];
        $token = \ComJwt::getToken($userInfo);
        $res = [
            'token' => $token,
            'userInfo' => $userInfo,
            'menuLists' => $menuLists,
        ];
        return sucJson($res);
    }

}
