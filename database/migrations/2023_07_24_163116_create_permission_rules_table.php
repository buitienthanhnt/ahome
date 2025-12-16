<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Models\PermissionRulesInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->integer(\App\Models\PermissionRulesInterface::ATTR_PERMISSION_ID);
            $table->string(\App\Models\PermissionRulesInterface::ATTR_VALUE);
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
        Schema::dropIfExists(\App\Models\PermissionRulesInterface::TABLE_NAME);
    }
}
