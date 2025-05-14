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
    Schema::create('glucosa', function (Blueprint $table) {
        $table->id();
        $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
        $table->date('fecha');
        $table->time('hora');
        $table->enum('momento', ['ANTES', 'DESPUÉS'])->default('ANTES');
        $table->integer('nivel_glucosa'); // En mg/dL o mmol/L según lo que uses
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glucosa');
    }
};
