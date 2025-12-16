<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ConfigCategory;

class CreateConfigCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(ConfigCategory::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(ConfigCategory::ATTR_PATH)->nullable(false)->unique("path_value");
            $table->text(ConfigCategory::ATTR_VALUE)->nullable();
            $table->text(ConfigCategory::ATTR_DESCRIPTION)->nullable(true);
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
        Schema::dropIfExists(ConfigCategory::TABLE_NAME);
    }
}
