<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_inboxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_user_id')->references('id')->on('users');
            $table->foreignId('client_user_id')->references('id')->on('users');
            $table->boolean('is_acc')->default(false);
            $table->string('type');
            $table->string('sym_key')->nullable();
            $table->string('iv')->nullable();
            $table->string('encrypted_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_inboxes');
    }
};
