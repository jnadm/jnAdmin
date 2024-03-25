<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    //重写render
    public function render($request, Throwable $e)
    {
        switch ($e) {
            case ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) :
                $message = '访问地址错误';
                break;
            case ($e instanceof  \Symfony\Component\HttpKernel\Exception\NotFoundHttpException):
                $message = "请求地址不存在";
                break;
            case ($e instanceof  \App\Exceptions\RequestException) :
                $message = $e->getMessage();
                break;
            default:
                $message = $e->getFile() . ' Line:' . $e->getLine() . ' Error:' . $e->getMessage();
                break;
        }
        return response()->json(['code' => 300, 'msg' => $message]);
    }
}
