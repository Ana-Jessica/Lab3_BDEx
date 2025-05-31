<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('developers', function (Blueprint $table) {
            $table->id('id_desenvolvedor');
            $table->string('nome_desenvolvedor', 255);
            $table->string('telefone_desenvolvedor', 20);
            $table->string('email_desenvolvedor', 255)->unique();
            $table->string('cpf', 14)->unique();
            $table->text('linguagens_de_programacao')->nullable();
            $table->text('tecnologias')->nullable();
            $table->string('senha_desenvolvedor', 255);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('developers');
    }
};