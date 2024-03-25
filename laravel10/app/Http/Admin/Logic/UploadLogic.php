<?php
namespace App\Http\Admin\Logic;
use App\Models\SysFile as model;

class UploadLogic extends BaseLogic
{
    //文件上传处理
    public function index(object $file, string $key) :array
    {
        $default = config('filesystems.default');
        $config  = config("filesystems.disks.{$default}.config.{$key}");
        if (!$config) {
            return $this->error('配置参数有误');
        }
        $ext    = $file->extension(); //扩展名
        $size   = $file->getSize(); //大小

        if (!in_array($ext, $config['ext']) || $size > $config['size']) {
            return $this->error($config['tips']);
        }

        $data = [
            'name' =>  $file->hashName(),                       //名称
            'origin_name' => $file->getClientOriginalName(),    //原名
            'ext' => $ext,  //扩展名
            'size' => $size,   //文件大小
            'path' => '/storage/' . $file->store($config['root']),
            'thumb' => '',
            'table_name' => $config['table_name'],
            'field_name' => $config['field_name'],
            'created_id' => $this->uid,
            'updated_id' => $this->uid,
        ];
        $id = model::create($data)->id;
        if (!$id) {
            return $this->error();
        }
        return $this->success(['upload_id' => $id, 'path' => $data['path']]);
    }
}
