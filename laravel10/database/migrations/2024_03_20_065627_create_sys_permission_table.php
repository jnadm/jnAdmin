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
        Schema::create('sys_permission', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->json('menu_codes')->comment('权限内容');
            $table->unsignedInteger('created_id')->default(0)->comment('创建人');
            $table->unsignedInteger('updated_id')->default(0)->comment('修改人');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');
            $table->dateTime('deleted_at')->nullable()->comment('删除时间');
            $table->comment('权限表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_permission');
    }
};
