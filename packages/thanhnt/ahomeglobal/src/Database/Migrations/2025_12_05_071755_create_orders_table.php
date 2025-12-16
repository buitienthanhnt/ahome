<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(OrderInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->integer(OrderInterface::HOME_ID);
            $table->integer(OrderInterface::ROOM_ID);
            $table->char(OrderInterface::STATUS)->default('complete');
            $table->date(OrderInterface::DATE_FROM);
            $table->date(OrderInterface::DATE_TO)->nullable();
            $table->json(OrderInterface::SELECTED_TIME);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(OrderInterface::TABLE_NAME);
    }
};
