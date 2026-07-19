<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * برای پشتیبانی از ورود/ثبت‌نام با کد یکبار مصرف، ستون رمز عبور باید nullable باشد
     * (کاربران قدیمی که رمز دارند بدون تغییر باقی می‌مانند)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
        });
    }
};
