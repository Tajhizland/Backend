<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('run_concept_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->foreignId('parent_question')->nullable();
            $table->foreignId('parent_answer')->nullable();
            $table->integer('status');
            $table->integer('level');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('run_concept_questions');
    }
};
