<?php
namespace App\Library;
/**
 * 重写分页返回数组
 * Class LengthAwarePaginatorService
 * @package App\Services\Common
 */
class LengthAwarePaginatorService extends \Illuminate\Pagination\LengthAwarePaginator
{
    public function toArray()
    {
        return [
            'data' => $this->items->toArray(),
            'current_page' => $this->currentPage(),
            'total' => $this->total(),
        ];
    }
}
