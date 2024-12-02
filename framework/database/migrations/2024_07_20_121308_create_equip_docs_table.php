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
        Schema::create('equip_docs', function (Blueprint $table) {
            $table->id();
            $table->integer('equip_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('document_name')->nullable();
            $table->string('document_file')->nulable();
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
        Schema::dropIfExists('equip_docs');
    }
};
