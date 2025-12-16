<?php

use App\Models\PaperContentInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaperContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(PaperContentInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(PaperContentInterface::ATTR_TYPE);
            $table->string(PaperContentInterface::ATTR_KEY);
            $table->longText(PaperContentInterface::ATTR_VALUE)->nullable();
            $table->string(PaperContentInterface::ATTR_DEPEND_VALUE)->nullable();
            $table->integer(PaperContentInterface::ATTR_PAPER_ID);
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
        Schema::dropIfExists(PaperContentInterface::TABLE_NAME);
    }
}
