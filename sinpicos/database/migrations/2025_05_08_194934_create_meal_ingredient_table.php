<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealIngredientTable extends Migration
{
    public function up()
    {
        Schema::create('meal_ingredient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('ingredient_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meal_ingredient');
    }
}
