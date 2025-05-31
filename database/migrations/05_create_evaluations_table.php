<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_conexao')->constrained('connections', 'id_conexao')->onDelete('cascade');
            $table->string('tipo_avaliador'); // 'company' ou 'developer'
            $table->unsignedBigInteger('id_avaliador'); // id da empresa ou desenvolvedor
            $table->integer('nota'); // 1-5
            $table->text('comentario')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
};