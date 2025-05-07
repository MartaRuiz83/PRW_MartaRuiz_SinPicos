<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetDefaultRolOnUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Cambiamos la columna rol para que por defecto sea 'Usuario'
            $table->string('rol')->default('Usuario')->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Si haces rollback, quitas el default
            $table->string('rol')->default(null)->change();
        });
    }
}
