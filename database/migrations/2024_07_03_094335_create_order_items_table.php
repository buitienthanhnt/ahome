<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Models\OrderItemInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->integer(\App\Models\OrderItemInterface::ATTR_ORDER_ID);
            $table->integer(\App\Models\OrderItemInterface::ATTR_PAPER_ID);
            $table->string(\App\Models\OrderItemInterface::ATTR_TITLE)->nullable();
            $table->string(\App\Models\OrderItemInterface::ATTR_PRICE);
            $table->integer(\App\Models\OrderItemInterface::ATTR_QTY);
            $table->string(\App\Models\OrderItemInterface::ATTR_URL)->nullable();
            $table->string(\App\Models\OrderItemInterface::ATTR_IMAGE_PATH)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists(\App\Models\OrderItemInterface::TABLE_NAME);
    }
}
