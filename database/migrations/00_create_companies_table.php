<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id('id_empresa');
            $table->string('nome_empresa', 255);
            $table->string('cnpj', 18)->unique();
            $table->string('endereco', 255);
            $table->string('email', 255)->unique();
            $table->integer('telefone_empresa');
            $table->string('senha_empresa', 255);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};