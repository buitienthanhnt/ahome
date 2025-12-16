<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Models\OrderAddressInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->integer(\App\Models\OrderAddressInterface::ATTR_JOIN_ID);
            $table->boolean(\App\Models\OrderAddressInterface::ATTR_SHIP_HC)->nullable();
            $table->string(\App\Models\OrderAddressInterface::ATTR_ADDRESS_HC)->nullable();
            $table->boolean(\App\Models\OrderAddressInterface::ATTR_SHIP_NHC)->nullable();
            $table->string(\App\Models\OrderAddressInterface::ATTR_ADDRESS_NHC)->nullable();
            $table->boolean(\App\Models\OrderAddressInterface::ATTR_SHIP_STORE)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(\App\Models\OrderAddressInterface::TABLE_NAME);
    }
}
