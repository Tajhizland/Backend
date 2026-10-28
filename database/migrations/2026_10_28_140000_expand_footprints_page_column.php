<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('footprints', function (Blueprint $table) {
            $table->text('page')->change();
        });
    }

    public function down(): void
    {
        Schema::table('footprints', function (Blueprint $table) {
            $table->string('page')->change();
        });
    }
};
