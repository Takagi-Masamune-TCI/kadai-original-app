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
        Schema::create('user_group_store_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_group_id")->constrained()
                ->onDelete("cascade");
            $table->foreignId("store_id")->constrained()
                ->onDelete("cascade");
            $table->timestamps();

            $table->unique(['user_group_id', 'store_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_group_store_permissions');
    }
};
