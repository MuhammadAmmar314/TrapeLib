<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('childs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parents_id');
            $table->unsignedBigInteger('book_id');
            $table->integer('qty')->default(1);
            $table->foreign('parents_id')->on('parents')->references('id')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('book_id')->on('books')->references('id')->onUpdate('no action')->onDelete('restrict');
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
        Schema::dropIfExists('childs');
    }
};
