<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Models\NotificationInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(\App\Models\NotificationInterface::ATTR_TYPE)->nullable();
            $table->text(\App\Models\NotificationInterface::ATTR_FCM_TOKEN);
            $table->string(\App\Models\NotificationInterface::ATTR_DEVICE_ID)->nullable();
            $table->boolean(\App\Models\NotificationInterface::ATTR_ACTIVE);
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
        Schema::dropIfExists(\App\Models\NotificationInterface::TABLE_NAME);
    }
}
