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
        Schema::create('indexers', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->enum('is_checked', ['Y', 'N'])->default('N');
            $table->enum('is_last_used', ['Y', 'N'])->default('N');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indexers');
    }
};
