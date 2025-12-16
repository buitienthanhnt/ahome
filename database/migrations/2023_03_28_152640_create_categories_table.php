<?php

use App\Models\CategoryInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CategoryInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(CategoryInterface::ATTR_NAME);
            $table->string(CategoryInterface::ATTR_URL_ALIAS)->index("idx_".CategoryInterface::ATTR_URL_ALIAS);
            $table->boolean(CategoryInterface::ATTR_ACTIVE)->default(true);
            $table->string(CategoryInterface::ATTR_TYPE)->default(CategoryInterface::TYPE_DEFAULT);
            $table->integer(CategoryInterface::ATTR_PARENT_ID)->default(0);
            $table->string(CategoryInterface::ATTR_IMAGE_PATH)->nullable();
            $table->string(CategoryInterface::ATTR_DESCRIPTION)->nullable();
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
        Schema::dropIfExists(CategoryInterface::TABLE_NAME);
    }
}
