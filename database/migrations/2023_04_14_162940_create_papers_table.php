<?php

use App\Models\PaperInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(PaperInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(PaperInterface::ATTR_TITLE)->nullable(false);
            $table->string(PaperInterface::ATTR_URL_ALIAS)->nullable(true)->unique("idx_".PaperInterface::ATTR_URL_ALIAS);
            $table->string(PaperInterface::ATTR_SHORT_CONTENT)->nullable(true);
            $table->string(PaperInterface::ATTR_IMAGE_PATH);
            $table->boolean(PaperInterface::ATTR_ACTIVE);
            $table->boolean(PaperInterface::ATTR_SHOW)->default(1);
            $table->time(PaperInterface::ATTR_SHOW_TIME)->nullable(true);
            $table->boolean(PaperInterface::ATTR_AUTO_HIDE)->default(0);
            $table->string(PaperInterface::ATTR_WRITER)->nullable(true);
            $table->boolean(PaperInterface::ATTR_SHOW_WRITER)->default(1);
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
        Schema::dropIfExists(PaperInterface::TABLE_NAME);
    }
}
