<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;

return new class extends Migration
{
    /**
     * make migration: php artisan make:migration create_homes_table --path=packages/thanhnt/ahomeglobal/src/Database/Migrations
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(HomeInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char(HomeInterface::NAME);
            $table->string(HomeInterface::DESCRIPTION)->nullable();
            $table->string(HomeInterface::DISTRICT)->nullable();
            $table->string(HomeInterface::IMAGE_PATH)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(HomeInterface::TABLE_NAME);
    }
};
