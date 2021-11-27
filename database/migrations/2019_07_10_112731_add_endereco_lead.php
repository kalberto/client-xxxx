<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnderecoLead extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lead',function (Blueprint $table){
			$table->string('cep')->unsigned()->nullable()->change();
			$table->string('endereco',200)->nullable();
			$table->string('numero',10)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lead',function (Blueprint $table){
			$table->dropColumn('endereco');
			$table->dropColumn('numero');
		});
	}
}
