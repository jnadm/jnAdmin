<?php
namespace App\Facades;;

use Illuminate\Support\Facades\Facade;

class ExcelSpout extends Facade
{
    static protected function getFacadeAccessor(){
        return "ExcelSpout";
    }
}


