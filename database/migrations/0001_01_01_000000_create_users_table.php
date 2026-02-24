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
        Schema::create('user_header', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('email', 50)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('profile_pic', 255)->nullable();
            $table->string('nama', 100)->nullable();
            $table->string('gender', 15)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->bigInteger('lfk_role_id')->nullable();
            $table->bigInteger('lfk_cabang_id')->nullable();
            $table->bigInteger('lfk_bahan_id')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->enum('deleted', ['1', '0'])->default('0');
            $table->timestamp('last_login')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamp('created_date')->useCurrent();
            $table->dateTime('updated_date')->nullable();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
