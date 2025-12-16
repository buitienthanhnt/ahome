<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Models\ViewSourceInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(\App\Models\ViewSourceInterface::ATTR_TYPE)->nullable();
            $table->integer(\App\Models\ViewSourceInterface::ATTR_SOURCE_ID);
            $table->integer(\App\Models\ViewSourceInterface::ATTR_VALUE)->nullable();
            $table->integer(\App\Models\ViewSourceInterface::ATTR_LIKE)->default(0);
            $table->integer(\App\Models\ViewSourceInterface::ATTR_HEART)->default(0);
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
        Schema::dropIfExists(\App\Models\ViewSourceInterface::TABLE_NAME);
    }
}
