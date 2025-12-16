<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(RoomInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char(RoomInterface::TITLE);
            $table->string(RoomInterface::DESCRIPTION)->nullable();
             $table->char(RoomInterface::TYPE,)->default(RoomInterface::TYPE_VALUE[0]['value']);
            /**
             * enum type for value in list option
             */
            // $table->enum(RoomInterface::TYPE, RoomInterface::TYPE_VALUE)->default(RoomInterface::TYPE_VALUE[0]);
            $table->string(RoomInterface::IMAGE_PATH)->nullable();
            $table->integer(RoomInterface::HOME_ID);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(RoomInterface::TABLE_NAME);
    }
};
