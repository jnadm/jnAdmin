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
        Schema::create('sys_role_permission', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('role_id')->default(0)->comment('角色id');
            $table->unsignedInteger('permission_id')->default(0)->comment('权限id');
            $table->unsignedInteger('created_id')->default(0)->comment('创建人');
            $table->unsignedInteger('updated_id')->default(0)->comment('修改人');
            $table->timestamp('created_at')->comment('创建时间');
            $table->timestamp('updated_at')->comment('更新时间');
            $table->softDeletes($column = 'deleted_at');
            $table->comment('角色权限关联表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_role_permission');
    }
};
