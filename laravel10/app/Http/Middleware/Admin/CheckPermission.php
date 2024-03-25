<?php
namespace App\Http\Middleware\Admin;

use App\Models\SysRolePermission as rolePermissionModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use App\Models\SysMenu;
class CheckPermission {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = new JsonResponse();
        $roleId = request()->get('role_id');
        $username = request()->get('username');
        if ($username != 'admin') {
            $route = strtolower(Route::current()->uri);
            $routeCode = SysMenu::where('path', $route)->value('code');
            $rolePath = rolePermissionModel::where('role_id', $roleId)->first()->permission->menu_codes ?? [];
            if ($routeCode && !in_array($routeCode, $rolePath)) {
                return $response->setData(json_decode(errJson('无访问权限')));
            }
        }
        return $next($request);
    }
}
