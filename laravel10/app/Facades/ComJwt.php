<?php
namespace App\Facades;;

use Illuminate\Support\Facades\Facade;

class ComJwt extends Facade
{
    static protected function getFacadeAccessor(){
        return "ComJwt";
    }
}


