<?php

use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    DB::table('administrador')->insert([
		    'nome' => 'Desenvolvimento',
		    'sobrenome' => 'Etools',
		    'email' => '',
		    'password' => bcrypt(''),
		    'api_token' => '',
		    'telefone' => '4199999999',
		    'celular' => '',
		    'ativo' => 1,
		    'ultimo_acesso' => null
	    ]);
	    DB::table('modulo_gerenciador')->insert([
		    'nome'=>'Dashboard',
		    'icone'=>'dashboard',
		    'modulo_list'=>'painel.dashboard',
		    'modulo_url'=>'painel.dashboard',
		    'order'=>'0'
	    ]);
	    DB::table('modulo_gerenciador')->insert([
		    'nome'=>'Administradores',
		    'icone'=>'person',
		    'modulo_list'=>'painel.administradores.listar',
		    'modulo_url'=>'painel.administradores',
		    'order'=>'1'
	    ]);
	    DB::table('modulo_gerenciador')->insert([
		    'nome'=>'Usuários',
		    'icone'=>'person',
		    'modulo_list'=>'painel.users.listar',
		    'modulo_url'=>'painel.users',
		    'order'=>'2'
	    ]);
	    DB::table('modulo_gerenciador')->insert([
		    'nome'=>'Organizações',
		    'icone'=>'group',
		    'modulo_list'=>'painel.organizacoes.listar',
		    'modulo_url'=>'painel.organizacoes',
		    'order'=>'3'
	    ]);
	    DB::table('modulo_gerenciador')->insert([
		    'nome'=>'Serviços',
		    'icone'=>'web',
		    'modulo_list'=>'painel.servicos.listar',
		    'modulo_url'=>'painel.servicos',
		    'order'=>'4'
	    ]);
	    DB::table('modulo_gerenciador')->insert([
		    'nome'=>'Leads',
		    'icone'=>'mail_outline',
		    'modulo_list'=>'painel.leads.listar',
		    'modulo_url'=>'painel.leads',
		    'order'=>'5'
	    ]);
	    DB::table('administrador_has_modulo_gerenciador')->insert([
		    'administrador_id'=>1,
		    'modulo_gerenciador_id'=>1
	    ]);
	    DB::table('administrador_has_modulo_gerenciador')->insert([
		    'administrador_id'=>1,
		    'modulo_gerenciador_id'=>2
	    ]);
	    DB::table('administrador_has_modulo_gerenciador')->insert([
		    'administrador_id'=>1,
		    'modulo_gerenciador_id'=>3
	    ]);
	    DB::table('administrador_has_modulo_gerenciador')->insert([
		    'administrador_id'=>1,
		    'modulo_gerenciador_id'=>4
	    ]);
	    DB::table('administrador_has_modulo_gerenciador')->insert([
		    'administrador_id'=>1,
		    'modulo_gerenciador_id'=>5
	    ]);
	    DB::table('administrador_has_modulo_gerenciador')->insert([
		    'administrador_id'=>1,
		    'modulo_gerenciador_id'=>6
	    ]);
	    DB::table('media_root')->insert([
		    'alias'=>'manuais',
		    'path'=>'upload/media/manuais/'
	    ]);
	    DB::table('media_root')->insert([
		    'alias'=>'administradores',
		    'path'=>'upload/media/administradores/'
	    ]);
	    DB::table('media_root')->insert([
		    'alias'=>'usuarios',
		    'path'=>'upload/media/usuarios/'
	    ]);
	    DB::table('organizacao')->insert([
		    'nome' => 'Empresa teste',
		    'slug' => str_slug('Empresa teste')
	    ]);
	    DB::table('documento')->insert([
		    'documento' => 19968745000177,
		    'organizacao_id' => 1
	    ]);
	    DB::table('modulo_cliente')->insert([
		    'nome' => 'Início',
		    'url' => '/cliente',
		    'menu' => true,
		    'order' => 1,
		    'parent_id' => null
	    ]);
	    DB::table('modulo_cliente')->insert([
		    'nome' => 'Meus Serviços',
		    'url' => '/cliente/servicos',
		    'menu' => true,
		    'order' => 2,
		    'parent_id' => null
	    ]);
	    DB::table('modulo_cliente')->insert([
		    'nome' => 'Chamados',
		    'url' => '/cliente/chamados',
		    'menu' => true,
		    'order' => 3,
		    'parent_id' => null
	    ]);
	    DB::table('modulo_cliente')->insert([
		    'nome' => 'Faturas',
		    'url' => '/cliente/faturas',
		    'menu' => true,
		    'order' => 4,
		    'parent_id' => null
	    ]);
	    DB::table('modulo_cliente')->insert([
		    'nome' => 'Meus Contratos',
		    'url' => '/cliente/contratos',
		    'menu' => true,
		    'order' => 5,
		    'parent_id' => null
	    ]);
	    DB::table('modulo_cliente')->insert([
		    'nome' => 'Contato',
		    'url' => '/cliente/contato',
		    'menu' => true,
		    'order' => 6,
		    'parent_id' => null
	    ]);
	    DB::table('modulo_cliente')->insert([
		    'nome' => 'Gráficos de Internet',
		    'url' => '/cliente/servicos',
		    'menu' => false,
		    'order' => 7,
		    'parent_id' => 2
	    ]);
	    DB::table('modulo_cliente')->insert([
		    'nome' => 'Gráficos de Voz',
		    'url' => '/cliente/servicos',
		    'menu' => false,
		    'order' => 8,
		    'parent_id' => 2
	    ]);

	    DB::table('uf')->insert([
		    'id' => 1,
		    'uf' => 'AC'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 2,
		    'uf' => 'AL'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 3,
		    'uf' => 'AP'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 4,
		    'uf' => 'BA'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 5,
		    'uf' => 'CE'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 6,
		    'uf' => 'DF'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 7,
		    'uf' => 'ES'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 8,
		    'uf' => 'GO'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 9,
		    'uf' => 'MA'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 10,
		    'uf' => 'MG'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 11,
		    'uf' => 'MS'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 12,
		    'uf' => 'MT'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 13,
		    'uf' => 'PA'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 14,
		    'uf' => 'PB'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 15,
		    'uf' => 'PE'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 16,
		    'uf' => 'PI'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 17,
		    'uf' => 'PR'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 18,
		    'uf' => 'RJ'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 19,
		    'uf' => 'RN'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 20,
		    'uf' => 'RO'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 21,
		    'uf' => 'RR'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 22,
		    'uf' => 'RS'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 23,
		    'uf' => 'SC'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 24,
		    'uf' => 'SE'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 25,
		    'uf' => 'SP'
	    ]);
	    DB::table('uf')->insert([
		    'id' => 26,
		    'uf' => 'TO'
	    ]);
	    DB::table('configuracao')->insert([
		    'nome_app' => 'xxxx - Área do Cliente',
	    ]);

	    // $this->call(UsersTableSeeder::class);
    }
}
