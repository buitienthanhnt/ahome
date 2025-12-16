<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Acarglobal\Models\Types\CarInterface;

return new class extends Migration
{
    /**
     * Make migration: php artisan make:migration create_cars_table --path=packages/thanhnt/acarglobal/src/database/migrations
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(CarInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char('key');                // bien so
            $table->char('suspension');         // hang xe
            $table->char('type');               // dong xe

            $table->bigInteger('km')->nullable();
            $table->dateTime('year')->nullable();
            $table->char('vin')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(CarInterface::TABLE_NAME);
    }
};
