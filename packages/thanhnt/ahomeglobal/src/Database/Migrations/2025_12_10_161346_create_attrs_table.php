<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(AttrInterface::TABLE_NAME, function (Blueprint $table) {
            $table->char(AttrInterface::TYPE);
            $table->integer(AttrInterface::SOURCE_ID);
            $table->char(AttrInterface::KEY);
            $table->text(AttrInterface::VALUE);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(AttrInterface::TABLE_NAME);
    }
};
