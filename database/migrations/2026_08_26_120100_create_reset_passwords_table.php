<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Predates the migration set; this only records the existing structure.
     */
    public function up(): void
    {
        Schema::create('reset_passwords', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('username', 256);
            $table->string('code', 256);
            $table->timestamp('expire_at');
            $table->integer('status');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reset_passwords');
    }
};
