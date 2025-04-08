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
        Schema::table('news', function (Blueprint $table) {
            //
            // $table->foreignIdFor(App\Models\NewsCategory::class)->after('slug')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('news_category_id')->after('slug')->nullable()->constrained('news_categories')->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            //
            $table->dropForeign(['news_category_id']);
        });
    }
};
