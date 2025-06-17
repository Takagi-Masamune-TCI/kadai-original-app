<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** record_favorites テーブルを作成するマイグレーション */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('record_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrainted()
                ->onDelete('cascade');
            $table->foreignId('record_id')->constrained()
                ->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'record_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_favorites');
    }
};
