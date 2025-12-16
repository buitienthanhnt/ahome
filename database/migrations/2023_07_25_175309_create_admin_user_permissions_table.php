<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Helper\Nan::userPermissionTable(), function (Blueprint $table) {
            $table->id();
            $table->integer(\App\Models\AdminUserInterface::PRIMARY_ALIAS);
            $table->integer(\App\Models\PermissionInterface::PRIMARY_ALIAS);
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
        Schema::dropIfExists(\App\Helper\Nan::userPermissionTable());
    }
}
