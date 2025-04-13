<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('central_app_managers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link');
            $table->string('group');
            $table->string('logo');
            $table->foreignIdFor(User::class)->comment('Created By');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_app_managers');
    }
};
