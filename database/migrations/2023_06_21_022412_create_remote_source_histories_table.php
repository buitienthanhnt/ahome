<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemoteSourceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Models\RemoteSourceHistoryInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->text(\App\Models\RemoteSourceHistoryInterface::ATTR_URL_VALUE);
            $table->string(\App\Models\RemoteSourceHistoryInterface::ATTR_TYPE)->nullable();
            $table->integer(\App\Models\RemoteSourceHistoryInterface::ATTR_PAPER_ID)->nullable();
            $table->boolean(\App\Models\RemoteSourceHistoryInterface::ATTR_ACTIVE)->nullable();
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
        Schema::dropIfExists(\App\Models\RemoteSourceHistoryInterface::TABLE_NAME);
    }
}
