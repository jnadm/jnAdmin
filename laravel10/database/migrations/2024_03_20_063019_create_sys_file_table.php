<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sys_file', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('name', 255)->comment('附件名称');
            $table->string('origin_name', 255)->comment('附件原名称');
            $table->string('ext', 10)->comment('附件扩展名');
            $table->unsignedInteger('size')->default(0)->comment('附件大小');
            $table->string('path', 255)->comment('存储路径');
            $table->string('thumb', 255)->comment('缩略图路径');
            $table->string('table_name', 255)->comment('使用附件的表名');
            $table->string('field_name', 255)->comment('使用附件的字段名');
            $table->unsignedInteger('created_id')->default(0)->comment('创建人');
            $table->unsignedInteger('updated_id')->default(0)->comment('修改人');
            $table->timestamp('created_at')->comment('创建时间');
            $table->timestamp('updated_at')->comment('更新时间');
            $table->softDeletes($column = 'deleted_at');
            $table->comment('附件存储表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_file');
    }
};
