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
        Schema::create('user_role_header', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('nama_role', 20)->nullable();
            $table->timestamp('created_date')->useCurrent();
            $table->dateTime('updated_date')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->enum('deleted', ['1', '0'])->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_role_header');
    }
};
