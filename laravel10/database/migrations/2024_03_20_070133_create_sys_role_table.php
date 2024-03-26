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
        Schema::create('sys_role', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('name', 50)->comment('角色名称');
            $table->tinyInteger('status')->default(1)->comment('状态：1启用0停用');
            $table->tinyInteger('sort')->default(0)->comment('序号');
            $table->unsignedInteger('created_id')->default(0)->comment('创建人');
            $table->unsignedInteger('updated_id')->default(0)->comment('修改人');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');
            $table->dateTime('deleted_at')->nullable()->comment('删除时间');
            $table->comment('角色表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_role');
    }
};
