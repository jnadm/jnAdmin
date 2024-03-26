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
        Schema::create('sys_menu', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('name', 50)->comment('菜单名称');
            $table->char('code', 8)->comment('菜单标识');
            $table->char('parent_code', 8)->default('')->comment('菜单父级id');
            $table->json('all_parent_codes')->comment('所有上级');
            $table->json('all_child_codes')->comment('所有下级');
            $table->string('path', 255)->comment('后端路由');
            $table->string('component', 255)->comment('前端路由');
            $table->tinyInteger('type')->default(1)->comment('类型：1菜单，2按钮');
            $table->tinyInteger('is_show')->default(0)->comment('是否显示：1是0否');
            $table->integer('sort')->default(0)->comment('排序号');
            $table->unsignedInteger('created_id')->default(0)->comment('创建人');
            $table->unsignedInteger('updated_id')->default(0)->comment('修改人');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');
            $table->dateTime('deleted_at')->nullable()->comment('删除时间');
            $table->comment('菜单表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_menu');
    }
};
