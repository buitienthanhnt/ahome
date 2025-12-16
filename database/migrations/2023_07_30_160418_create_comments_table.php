<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Models\CommentInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(\App\Models\CommentInterface::ATTR_EMAIL);
            $table->string(\App\Models\CommentInterface::ATTR_NAME);
            $table->string(\App\Models\CommentInterface::ATTR_SUBJECT)->nullable();
            $table->text(\App\Models\CommentInterface::ATTR_CONTENT);
            $table->boolean(\App\Models\CommentInterface::ATTR_SHOW)->default(false);
            $table->integer(\App\Models\CommentInterface::ATTR_LIKE)->nullable();
            $table->integer(\App\Models\CommentInterface::ATTR_PARENT_ID)->nullable();
            $table->integer(\App\Models\CommentInterface::ATTR_PAPER_ID);
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
        Schema::dropIfExists(\App\Models\CommentInterface::TABLE_NAME);
    }
}
