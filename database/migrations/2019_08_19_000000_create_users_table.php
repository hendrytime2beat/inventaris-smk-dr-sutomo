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
        Schema::create('m_user', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user_grup')->default(0)->nullable();
            $table->string('username', 250)->nullable();
            $table->string('password', 250)->nullable();
            $table->string('nama', 250)->nullable();
            $table->string('email', 250)->nullable();
            $table->string('no_hp', 250)->nullable();
            $table->text('foto_profil')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user');
    }
};
