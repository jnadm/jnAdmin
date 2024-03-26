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
        Schema::create('sys_admin', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('username', 50)->comment('用户名');
            $table->char('password', 32)->comment('密码');
            $table->char('salt', 6)->comment('安全码');
            $table->char('mobile', 11)->comment('手机号');
            $table->unsignedInteger('role_id')->default(0)->comment('角色id');
            $table->string('nickname', 50)->comment('昵称');
            $table->string('real_name')->comment('真实姓名');
            $table->unsignedInteger('avatar')->default(0)->comment('头像(comm_upload表id)');
            $table->string('email', 100)->comment('email地址');
            $table->tinyInteger('status')->default(0)->comment('状态1启用0停用');
            $table->unsignedInteger('fail_nums')->default(0)->comment('登录失败次数');
            $table->unsignedInteger('login_time')->default(0)->comment('最后一次登录时间');
            $table->unsignedSmallInteger('login_ip')->default(0)->comment('登录ip');
            $table->unsignedInteger('created_id')->default(0)->comment('创建人');
            $table->unsignedInteger('updated_id')->default(0)->comment('修改人');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');
            $table->dateTime('deleted_at')->nullable()->comment('删除时间');
            $table->comment('管理员表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_admin');
    }
};
