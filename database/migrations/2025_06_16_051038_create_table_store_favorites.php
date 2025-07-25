<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** store_favorites テーブルを作成するマイグレーション */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrainted()
                ->onDelete('cascade');
            $table->foreignId('store_id')->constrained()
                ->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'store_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_favorites');
    }
};
