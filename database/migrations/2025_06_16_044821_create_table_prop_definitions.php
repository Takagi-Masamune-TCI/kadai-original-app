<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** prop_definitions テーブルを作成するマイグレーション */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prop_definitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()
                ->onDelete('cascade');
            $table->string('name');
            $table->unsignedInteger('index');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prop_definitions');
    }
};
