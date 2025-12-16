<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Models\OrderInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(\App\Models\OrderInterface::ATTR_STATUS)->default(\App\Models\OrderInterface::STATUS_PENDING);
            $table->string(\App\Models\OrderInterface::ATTR_NAME);
            $table->string(\App\Models\OrderInterface::ATTR_EMAIL);
            $table->string(\App\Models\OrderInterface::ATTR_PHONE);
            $table->string(\App\Models\OrderInterface::ATTR_TOTAL);
            $table->string(\App\Models\OrderInterface::ATTR_THANH_TOAN)->default('offline');
            $table->string(\App\Models\OrderInterface::ATTR_OMX)->nullable();
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
        Schema::dropIfExists(\App\Models\OrderInterface::TABLE_NAME);
    }
}
