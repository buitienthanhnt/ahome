<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Helper\Nan::coreConfigTable(), function (Blueprint $table) {
            $table->id();
            $table->string(\App\Constant\AttributeInterface::ATTR_NAME);
            $table->string(\App\Constant\AttributeInterface::ATTR_VALUE)->nullable();
            $table->text(\App\Constant\AttributeInterface::ATTR_DESCRIPTION)->nullable(true);
            $table->text(\App\Constant\AttributeInterface::ATTR_TYPE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists(\App\Helper\Nan::coreConfigTable());
    }
}
