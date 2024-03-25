<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $url = str_replace("/index.php", '', $request->url());
        $route = explode("/",ltrim(parse_url($url, PHP_URL_PATH), "/"));
        $module = $route[0] ?? '';
        $controller = $route[1] ?? '';
        //后台管理系统登录验证
        if ($module == 'admin') {
            $exclude = ["nologin"];
            $response = new JsonResponse();
            if (!in_array($controller, $exclude)) {
                $token = $request->header('token');
                if (!$token) {
                    return $response->setData(json_decode(errJson('token不能为空')));
                }
                $tokenInfo = \ComJwt::checkToken($token);
                if ($tokenInfo['errno'] !== 0) {
                    if ($tokenInfo['errno'] == 500) {
                        return $response->setData(json_decode(errJson('鉴权信息已过期', [], 500)));
                    } else{
                        return $response->setData(json_decode(errJson('鉴权信息有误')));
                    }
                }
                //传递参数
                $request->attributes->set('uid', $tokenInfo['data']['uid']);
                $request->attributes->set('username', $tokenInfo['data']['username']);
                $request->attributes->set('role_id', $tokenInfo['data']['role_id']);
            }
        }
        return $next($request);
    }


}
