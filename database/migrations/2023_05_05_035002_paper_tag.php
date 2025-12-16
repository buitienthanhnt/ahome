<?php

use App\Models\PaperTagInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaperTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(PaperTagInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(PaperTagInterface::ATTR_VALUE)->index("idx_".PaperTagInterface::ATTR_VALUE);
            $table->integer(PaperTagInterface::ATTR_ENTITY_ID);
            $table->string(PaperTagInterface::ATTR_TYPE);
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
        //
        Schema::dropIfExists(PaperTagInterface::TABLE_NAME);
    }
}
