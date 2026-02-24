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
        Schema::create('cabang_header', function (Blueprint $table) {
            $table->id('cabang_id');
            $table->string('nama_cabang', 100)->nullable();
            $table->timestamp('created_date')->useCurrent();
            $table->dateTime('updated_date')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->enum('deleted', ['1', '0'])->default('0');
            $table->bigInteger('lfk_last_modify_user_id')->nullable();
            $table->bigInteger('lfk_kategori_gereja_id')->nullable();
            $table->text('info_detail')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabang_header');
    }
};
