<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->id('id_conexao');
            $table->foreignId('id_empresa')->constrained('companies', 'id_empresa')->onDelete('cascade');
            $table->foreignId('id_desenvolvedor')->constrained('developers', 'id_desenvolvedor')->onDelete('cascade');
            $table->dateTime('data_conexao');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('connections');
    }
};