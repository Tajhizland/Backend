<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Legacy table. Kept on MyISAM/utf8mb3 to match production exactly;
     * converting it to InnoDB/utf8mb4 deserves its own migration.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->text('content')->nullable();
            $table->string('img')->nullable();
            $table->boolean('published')->nullable();
            $table->boolean('static')->nullable();
            $table->integer('author')->nullable();
            $table->integer('category_id')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->engine = 'MyISAM';
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_general_ci';
            $table->index('category_id', 'category');
            $table->index('published', 'published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
