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
        // Schema::create('reactions', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('user_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('news_id')->constrained()->onDelete('cascade');
        //     $table->string('type'); // Example: like, love, haha, wow
        //     $table->timestamps();

        //     $table->unique(['user_id', 'news_id']); // One reaction per news per user
        // });


        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('reactable'); // reactable_id + reactable_type
            $table->string('type'); // like, love, haha, etc.
            $table->timestamps();

            $table->unique(['user_id', 'reactable_id', 'reactable_type']); // One reaction per user per item
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
