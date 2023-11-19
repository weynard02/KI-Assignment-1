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
        Schema::create('aes', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('id_card');
            $table->string('document');
            $table->string('video');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->constrained();
            $table->string('fullname_key');
            $table->string('fullname_iv');
            $table->string('id_card_key');
            $table->string('id_card_iv');
            $table->string('document_key');
            $table->string('document_iv');
            $table->string('video_key');
            $table->string('video_iv');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aes');
    }
};
