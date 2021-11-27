<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('log_administrador',function (Blueprint $table){
		    $table->increments('id');
		    $table->integer('administrador_id')->unsigned()->nullable();
		    $table->foreign('administrador_id')->references('id')->on('administrador');
		    $table->ipAddress('ip')->nullable();
		    $table->string('tipo',40)->nullable();
		    $table->string('tabela',50)->nullable();
		    $table->integer('registro_id')->nullable();
		    $table->timestamps();
		    $table->softDeletes();
	    });
	    Schema::create('acesso_administrador',function (Blueprint $table){
		    $table->increments('id');
		    $table->integer('administrador_id')->unsigned()->nullable();
		    $table->foreign('administrador_id')->references('id')->on('administrador');
		    $table->timestamp('data')->nullable();
		    $table->ipAddress('ip')->nullable();
		    $table->softDeletes();
	    });
	    Schema::create('modulo_gerenciador',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('nome',255);
		    $table->string('icone',40)->nullable();
		    $table->string('modulo_list',45);
		    $table->string('modulo_url',45);
		    $table->integer('order')->nullable();
		    $table->softDeletes();
	    });
	    Schema::create('administrador_has_modulo_gerenciador',function (Blueprint $table){
		    $table->integer('administrador_id')->unsigned();
		    $table->foreign('administrador_id')->references('id')->on('administrador');
		    $table->integer('modulo_gerenciador_id')->unsigned();
		    $table->foreign('modulo_gerenciador_id')->references('id')->on('modulo_gerenciador');
		    $table->softDeletes();

	    });
	    Schema::create('organizacao',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('nome',150);
		    $table->string('slug',150);
		    $table->timestamps();
		    $table->softDeletes();
		    $table->unique(['slug', 'deleted_at']);
	    });
	    Schema::create('cnpj',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('cnpj',50);
		    $table->integer('organizacao_id')->unsigned()->nullable();
		    $table->foreign('organizacao_id')->references('id')->on('organizacao');
		    $table->timestamps();
	    });
	    Schema::create('configuracao',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('nome_app',150);
		    $table->string('seo_sufix',255)->nullable();
		    $table->string('tag_manager_id',30)->nullable();
		    $table->string('email_remetente',255)->nullable();
		    $table->string('email_destinatario',255)->nullable();
		    $table->string('telefone',60)->nullable();
		    $table->string('whatsapp',60)->nullable();
		    $table->text('social_facebook')->nullable();
		    $table->text('social_twitter')->nullable();
		    $table->text('social_instagram')->nullable();
		    $table->text('social_youtube')->nullable();
		    $table->string('local_latitude',40)->nullable();
		    $table->string('local_longitude',40)->nullable();
		    $table->string('local_cep',25)->nullable();
		    $table->string('local_endereco',255)->nullable();
		    $table->string('local_numero',10)->nullable();
		    $table->string('local_complemento',25)->nullable();
		    $table->string('local_bairro',80)->nullable();
		    $table->string('local_cidade',80)->nullable();
		    $table->string('local_estado',40)->nullable();
		    $table->string('local_pais',40)->nullable();
		    $table->softDeletes();
	    });
	    Schema::create('faq',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('pergunta',255)->nullable();
		    $table->longText('resposta')->nullable();
		    $table->boolean('ativo')->default(false);
		    $table->string('tipo_servico');
		    $table->string('tipo_servico_url');
		    $table->timestamps();
		    $table->softDeletes();
	    });
	    Schema::create('uf',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('uf', 2);
	    });
	    Schema::create('endereco',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('cep',8)->nullable();
		    $table->integer('uf_id')->unsigned();
		    $table->foreign('uf_id')->references('id')->on('uf');
		    $table->string('cidade', 100)->nullable();
		    $table->string('endereco',120)->nullable();
		    $table->integer('numero')->nullable();
		    $table->string('complemento',100)->nullable();
		    $table->string('bairro',100)->nullable();
		    $table->softDeletes();
	    });
	    Schema::create('usuario',function (Blueprint $table){
		    $table->increments('id');
		    $table->integer('organizacao_id')->unsigned()->nullable();
		    $table->foreign('organizacao_id')->references('id')->on('organizacao');
		    $table->string('nome', 255);
		    $table->string('sobrenome', 255)->nullable();
		    $table->string('email',70);
		    $table->string('login',40);
		    $table->string('password',60);
		    $table->string('api_token',60)->unique();
		    $table->string('telefone',15)->nullable();
		    $table->string('celular',15)->nullable();
		    $table->integer('endereco_id')->unsigned()->nullable();
		    $table->foreign('endereco_id')->references('id')->on('endereco');
		    $table->boolean('ativo')->default(false);
		    $table->timestamp('ultimo_acesso')->nullable();
		    $table->rememberToken();
		    $table->timestamps();
		    $table->softDeletes();
		    $table->unique(['login', 'deleted_at']);
	    });
	    Schema::create('modulo_cliente',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('nome',100);
		    $table->string('url',100);
		    $table->boolean('menu');
		    $table->integer('order')->nullable();
		    $table->integer('parent_id')->unsigned()->nullable();
		    $table->foreign('parent_id')->references('id')->on('modulo_cliente');
		    $table->softDeletes();
	    });
	    Schema::create('usuario_has_modulo_cliente',function (Blueprint $table){
		    $table->integer('usuario_id')->unsigned();
		    $table->foreign('usuario_id')->references('id')->on('usuario');
		    $table->integer('modulo_cliente_id')->unsigned();
		    $table->foreign('modulo_cliente_id')->references('id')->on('modulo_cliente');
		    $table->softDeletes();
	    });
	    Schema::create('acesso_cliente',function (Blueprint $table){
		    $table->increments('id');
		    $table->integer('usuario_id')->unsigned()->nullable();
		    $table->foreign('usuario_id')->references('id')->on('usuario');
		    $table->timestamp('data')->nullable();
		    $table->ipAddress('ip')->nullable();
		    $table->softDeletes();
	    });
	    Schema::create('log_usuario',function (Blueprint $table){
		    $table->increments('id');
		    $table->integer('usuario_id')->unsigned()->nullable();
		    $table->foreign('usuario_id')->references('id')->on('usuario');
		    $table->ipAddress('ip')->nullable();
		    $table->string('acao',80)->nullable();
		    $table->string('area',80)->nullable();
		    $table->timestamps();
		    $table->softDeletes();
	    });
	    Schema::create('lead',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('empresa',100);
		    $table->string('contato',100);
		    $table->string('email',100);
		    $table->string('telefone',170)->nullable();
		    $table->string('assunto',255)->nullable();
		    $table->longText('mensagem')->nullable();
		    $table->string('origem',40)->nullable();
		    $table->string('form_origem',40)->nullable();
		    $table->timestamps();
	    });
	    Schema::create('media_root',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('alias',45);
		    $table->string('path',255);
		    $table->softDeletes();
	    });
	    Schema::create('media_resize',function (Blueprint $table){
		    $table->increments('id');
		    $table->integer('width');
		    $table->integer('height');
		    $table->string('path',255);
		    $table->string('action',40);
		    $table->integer('media_root_id')->unsigned();
		    $table->foreign('media_root_id')->references('id')->on('media_root');
		    $table->softDeletes();
	    });
	    Schema::create('media',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('viewport',5)->nullable();
		    $table->string('nome',255);
		    $table->string('nome_temp',255)->nullable();
		    $table->text('legenda')->nullable();
		    $table->boolean('temp')->default(true);
		    $table->integer('media_root_id')->unsigned();
		    $table->foreign('media_root_id')->references('id')->on('media_root');
		    $table->timestamps();
	    });
	    Schema::create('manual',function (Blueprint $table){
	    	$table->increments('id');
	    	$table->string('nome');
	    	$table->string('tipo_servico');
		    $table->string('tipo_servico_url');
		    $table->boolean('ativo')->default(false);
		    $table->integer('media_id')->unsigned()->nullable();
		    $table->foreign('media_id')->references('id')->on('media');
		    $table->timestamps();
	    });
	    Schema::table('administrador', function (Blueprint $table) {
		    $table->integer('media_id')->unsigned()->nullable();
		    $table->foreign('media_id')->references('id')->on('media');
	    });
	    Schema::table('usuario', function (Blueprint $table) {
		    $table->integer('media_id')->unsigned()->nullable();
		    $table->foreign('media_id')->references('id')->on('media');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('usuario', function (Blueprint $table) {
		    $table->dropColumn('media_id');
	    });
	    Schema::table('administrador', function (Blueprint $table) {
		    $table->dropColumn('media_id');
	    });
	    Schema::dropIfExists('manual');
	    Schema::dropIfExists('media');
	    Schema::dropIfExists('media_resize');
	    Schema::dropIfExists('media_root');
	    Schema::dropIfExists('lead');
	    Schema::dropIfExists('log_usuario');
	    Schema::dropIfExists('acesso_cliente');
	    Schema::dropIfExists('usuario_has_modulo_cliente');
	    Schema::dropIfExists('modulo_cliente');
	    Schema::dropIfExists('usuario');
	    Schema::dropIfExists('endereco');
	    Schema::dropIfExists('uf');
	    Schema::dropIfExists('faq');
	    Schema::dropIfExists('configuracao');
	    Schema::dropIfExists('cnpj');
	    Schema::dropIfExists('organizacao');
	    Schema::dropIfExists('administrador_has_modulo_gerenciador');
	    Schema::dropIfExists('modulo_gerenciador');
	    Schema::dropIfExists('acesso_administrador');
	    Schema::dropIfExists('log_administrador');
    }
}
