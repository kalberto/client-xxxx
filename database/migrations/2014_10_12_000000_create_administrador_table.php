<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministradorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrador', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('nome', 255);
	        $table->string('sobrenome', 255)->nullable();
            $table->string('email');
            $table->string('password',60);
	        $table->string('api_token',60)->unique();
	        $table->string('telefone',15)->nullable();
	        $table->string('celular',15)->nullable();
	        $table->boolean('ativo')->default(false);
	        $table->timestamp('ultimo_acesso')->nullable();
            $table->rememberToken();
            $table->timestamps();
	        $table->softDeletes();
	        $table->unique(['email', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administrador');
    }
}
