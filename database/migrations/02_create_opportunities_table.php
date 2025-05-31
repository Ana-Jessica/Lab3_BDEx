<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id('id_vaga');
            $table->foreignId('id_empresa')->constrained('companies', 'id_empresa')->onDelete('cascade');
            $table->string('titulo_vaga', 255);
            $table->text('descricao_vaga');
            $table->float('valor_oferta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('opportunities');
    }
};