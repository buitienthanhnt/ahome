<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PapertagBasevalue extends Migration
{
    /**
     * Run the migrations.
     * add new colum to table
     * @return void
     */
    public function up()
    {
        Schema::table(\App\Models\PaperTagInterface::TABLE_NAME, function (Blueprint $table){
           $table->string("base_value")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(\App\Models\PaperTagInterface::TABLE_NAME, function (Blueprint $table){
            $table->dropColumn("string");
        });
    }
}
