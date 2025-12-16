<?php

use App\Models\WriterInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWritersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(WriterInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(WriterInterface::ATTR_NAME)->nullable(false);
            $table->string(WriterInterface::ATTR_EMAIL)->unique(WriterInterface::ATTR_EMAIL)->nullable(false);
            $table->string(WriterInterface::ATTR_PHONE)->nullable();
            $table->string(WriterInterface::ATTR_ADDRESS)->nullable();
            $table->string(WriterInterface::ATTR_GROUP)->nullable();
            $table->string(WriterInterface::ATTR_IMAGE_PATH)->nullable();
            $table->string(WriterInterface::ATTR_NAME_ALIAS)->unique(WriterInterface::ATTR_NAME_ALIAS)->nullable();
            $table->boolean(WriterInterface::ATTR_ACTIVE)->default(false);
            $table->string(WriterInterface::ATTR_RATING)->nullable();
            $table->timestamp(WriterInterface::ATTR_DATE_OF_BIRTH)->nullable(false);
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
        Schema::dropIfExists(WriterInterface::TABLE_NAME);
    }
}
