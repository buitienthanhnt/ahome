<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Models\AdminUserInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(\App\Models\AdminUserInterface::ATTR_NAME)->unique();
            $table->string(\App\Models\AdminUserInterface::ATTR_EMAIL)->unique();
            $table->string(\App\Models\AdminUserInterface::ATTR_PASSWORD);
            $table->boolean(\App\Models\AdminUserInterface::ATTR_ACTIVE);
            $table->date(\App\Models\AdminUserInterface::ATTR_LOG_DATE)->nullable();
            $table->integer(\App\Models\AdminUserInterface::ATTR_LOG_ERROR_NUM)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(\App\Models\AdminUserInterface::TABLE_NAME);
    }
}
