<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('disc_tests', function (Blueprint $table) {
        $table->id();

        $table->string('name');
        $table->string('email')->nullable();

        // MOST
        $table->integer('most_d')->default(0);
        $table->integer('most_i')->default(0);
        $table->integer('most_s')->default(0);
        $table->integer('most_c')->default(0);
        $table->integer('most_star')->default(0);

        // LEAST
        $table->integer('least_d')->default(0);
        $table->integer('least_i')->default(0);
        $table->integer('least_s')->default(0);
        $table->integer('least_c')->default(0);
        $table->integer('least_star')->default(0);

        // SELF (Most - Least)
        $table->integer('self_d')->default(0);
        $table->integer('self_i')->default(0);
        $table->integer('self_s')->default(0);
        $table->integer('self_c')->default(0);
        $table->integer('self_star')->default(0);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disc_tests');
    }
};
