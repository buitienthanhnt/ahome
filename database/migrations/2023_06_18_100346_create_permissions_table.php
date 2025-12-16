<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Models\PermissionInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->text(\App\Models\PermissionInterface::ATTR_LABEL);
            $table->string(\App\Models\PermissionInterface::ATTR_KEY)->unique()->default(Str::random(5));
            $table->boolean(\App\Models\PermissionInterface::ATTR_ACTIVE)->default(true);
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
        Schema::dropIfExists(\App\Models\PermissionInterface::TABLE_NAME);
    }
}
