<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Ahomeglobal\Models\Types\OrderTimeInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(OrderTimeInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->date(OrderTimeInterface::DATE);
            $table->integer(OrderTimeInterface::HOME_ID);
            $table->json(OrderTimeInterface::ROOM_IDS)->nullable();
            $table->json(OrderTimeInterface::ORDER_IDS)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(OrderTimeInterface::TABLE_NAME);
    }
};
