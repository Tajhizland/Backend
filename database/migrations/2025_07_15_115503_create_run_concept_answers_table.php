<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('run_concept_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('run_concept_question_id');
            $table->string('answer');
            $table->integer('status');
            $table->integer('price');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('run_concept_answers');
    }
};
