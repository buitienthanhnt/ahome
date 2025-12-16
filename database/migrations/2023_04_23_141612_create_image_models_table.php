<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_models', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable(true);
            $table->string("file_name")->nullable(false);
            $table->text("file_path")->nullable(true);
            $table->text("file_url")->nullable(true);
            $table->text("resize_name")->nullable();
            $table->text("resize_path")->nullable();
            $table->text("resize_url")->nullable();
            $table->text("real_path")->nullable(true);
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
        Schema::dropIfExists('image_models');
    }
}
