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
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();

            // Data responden
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();

            // Jawaban survey. Ganti/ tambah kolom di bawah ini sesuai pertanyaan
            // yang sudah fix, atau simpan di kolom JSON `answers` bila pertanyaan
            // masih sering berubah.
            $table->string('question_1')->nullable();
            $table->string('question_2')->nullable();
            $table->text('question_3')->nullable();
            $table->json('answers')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
