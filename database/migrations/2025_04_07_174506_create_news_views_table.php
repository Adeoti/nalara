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
        Schema::create('news_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // For guests, keep it nullable
            $table->ipAddress('ip_address')->nullable(); // Optional
            $table->timestamps();

            $table->unique(['news_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_views');
    }
};
