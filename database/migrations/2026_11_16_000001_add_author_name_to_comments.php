<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('author_name')->nullable();
        });
    }

    public function down(): void
    {
        // Admin comments have no customer account; remove them before restoring NOT NULL.
        DB::table('comments')->whereNull('user_id')->delete();
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('author_name');
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
