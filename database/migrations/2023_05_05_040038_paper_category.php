<?php

use App\Models\PaperCategoryInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaperCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(PaperCategoryInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->integer(\App\Models\PaperInterface::PRIMARY_ALIAS);
            $table->integer(\App\Models\CategoryInterface::PRIMARY_ALIAS);
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
        Schema::dropIfExists(PaperCategoryInterface::TABLE_NAME);
    }
}
