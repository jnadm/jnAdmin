<?php
use Illuminate\Support\Facades\Route;
use App\Http\Admin\Controllers\IndexController;
use App\Http\Admin\Controllers\NoLoginController;
use App\Http\Admin\Controllers\AccountController;
use App\Http\Admin\Controllers\MenuController;
use App\Http\Admin\Controllers\RoleController;
use App\Http\Admin\Controllers\PermissionController;
use App\Http\Admin\Controllers\UploadController;
use App\Http\Middleware\Admin\CheckPermission;
//后台路由组
Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function() {
    #账号模块
    Route::post('account/add', [AccountController::class, 'add']);
    Route::post('account/edit', [AccountController::class, 'edit']);
    Route::post('account/details', [AccountController::class, 'details']);
    Route::post('account/lists', [AccountController::class, 'lists']);
    Route::post('account/del', [AccountController::class, 'del']);
    Route::post('account/batchDel', [AccountController::class, 'batchDel']);
    Route::post('account/export', [AccountController::class, 'export']);

    #菜单模块
    Route::post('menu/add', [MenuController::class, 'add']);
    Route::post('menu/edit', [MenuController::class, 'edit']);
    Route::post('menu/details', [MenuController::class, 'details']);
    Route::post('menu/lists', [MenuController::class, 'lists']);
    Route::post('menu/del', [MenuController::class, 'del']);
    Route::post('menu/batchDel', [MenuController::class, 'batchDel']);

    #角色模块
    Route::post('role/add', [RoleController::class, 'add']);
    Route::post('role/edit', [RoleController::class, 'edit']);
    Route::post('role/details', [RoleController::class, 'details']);
    Route::post('role/lists', [RoleController::class, 'lists']);
    Route::post('role/del', [RoleController::class, 'del']);
    Route::post('role/batchDel', [RoleController::class, 'batchDel']);
    Route::post('role/setRolePermission', [RoleController::class, 'setRolePermission']);
    Route::post('role/getRolePermission', [RoleController::class, 'getRolePermission']);

    #权限模块
    Route::post('permission/add', [PermissionController::class, 'add']);
    Route::post('permission/edit', [PermissionController::class, 'edit']);
    Route::post('permission/details', [PermissionController::class, 'details']);
    Route::post('permission/lists', [PermissionController::class, 'lists']);
    Route::post('permission/del', [PermissionController::class, 'del']);
    Route::post('permission/editUserToRole', [PermissionController::class, 'editUserToRole']);

    //无需登录验证
    Route::post('nologin/login', [NoLoginController::class, 'login'])->withoutMiddleware(CheckPermission::class);

    //文件上传接口
    Route::post('upload/index', [UploadController::class, 'index']);
});
