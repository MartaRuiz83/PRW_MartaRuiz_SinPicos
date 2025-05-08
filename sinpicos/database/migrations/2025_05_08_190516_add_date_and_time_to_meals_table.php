<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->date('date')->after('id');
            $table->time('time')->after('date');
            $table->string('meal_type')->after('time');
            $table->string('description')->after('meal_type');
            $table->decimal('carbohydrates', 8, 1)->nullable()->after('description');
            $table->decimal('proteins',      8, 1)->nullable()->after('carbohydrates');
            $table->decimal('fats',          8, 1)->nullable()->after('proteins');
            $table->integer('calories')->nullable()->after('fats');
        });
    }

    public function down()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropColumn(['date','time','meal_type','description','carbohydrates','proteins','fats','calories']);
        });
    }
};
