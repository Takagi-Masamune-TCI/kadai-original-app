<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** record_items テーブルを作成するマイグレーション */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('record_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained()
                ->onDelete('cascade');
            $table->foreignId('prop_id')->constrained('prop_definitions')
                ->onDelete('cascade');
            $table->text('value');
            $table->timestamps();

            $table->unique(['record_id', 'prop_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_items');
    }
};
