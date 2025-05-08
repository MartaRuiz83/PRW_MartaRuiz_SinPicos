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
    Schema::create('meals', function (Blueprint $table) {
        $table->id();
        $table->date('date');
        $table->time('time');
        $table->string('meal_type');
        $table->string('description');
        $table->timestamps();
    });

    // tabla pivot (meal_ingredient)
    Schema::create('ingredient_meal', function (Blueprint $table) {
        $table->id();
        $table->foreignId('meal_id')->constrained()->cascadeOnDelete();
        $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
        $table->decimal('quantity', 8, 2);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
