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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('slug');
            $table->text('question');
            $table->integer('grade_id');
            $table->integer('subject_id');
            $table->integer('vote')->default(0);
            $table->enum('have_recommendations', ['Y', 'N'])->default('N');
            $table->timestamp('recommendation_time')->nullable();
            $table->enum('index_requested', ['Y', 'N'])->default('N');
            $table->text('answers_cached')->nullable(); // [1]
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
        Schema::dropIfExists('questions');
    }
};
