<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/*
 * The token should be on the header Like this:
 * Key: Authorization Value:Bearer 'Insert user token here'
 * */
Route::group(array('prefix' => 'v1/auth/'),function (){
	Auth::routes();
});
Route::group(['prefix'=>'v1','middleware'=>['auth:api']],function (){


	Route::get('/dashboard','Api\v1\DashboardController@index');

	//Leads
	Route::get('leads','Api\v1\LeadController@index');
	Route::delete('leads/{id}','Api\v1\LeadController@destroy');
	Route::get('leads/export','Api\v1\LeadController@export');

	//Configurações
	Route::get('/configuracoes','Api\v1\ConfiguracaoController@show');
	Route::put('configuracoes','Api\v1\ConfiguracaoController@update');

	// Modulos
	Route::resource('modulos-gerenciador','Api\v1\ModuloGerenciadorController');
	Route::resource('modulos-cliente','Api\v1\ModuloClienteController');

	//Empresas
	Route::get('/empresas','Api\v1\ClientesController@index');
	Route::get('/empresas/search','Api\v1\ClientesController@search');
	Route::get('empresas/{documento}', 'Api\v1\ClientesController@show')->where(['documento' => '[0-9]+']);

	//Organizacoes
	Route::get('/organizacoes','Api\v1\OrganizacaoController@index');
	Route::get('/organizacoes/search','Api\v1\OrganizacaoController@search');
	Route::get('/organizacoes/{slug}', 'Api\v1\OrganizacaoController@show');
	Route::post('/organizacoes','Api\v1\OrganizacaoController@store');
	Route::delete('/organizacoes/{slug}','Api\v1\OrganizacaoController@destroy');
	Route::put('/organizacoes/{slug}','Api\v1\OrganizacaoController@update');
	Route::get('/organizacoes/{slug}/documento', 'Api\v1\OrganizacaoController@documento');
	Route::get('/organizacoes/{slug}/add/{documento}', 'Api\v1\OrganizacaoController@addDocumento');
	Route::get('/organizacoes/{slug}/remove/{documento}', 'Api\v1\OrganizacaoController@removeDocumento');
	Route::get('/organizacao/{slug}/usuarios', 'Api\v1\OrganizacaoController@getUsuarios');


	//Serviços
	Route::get('/servicos','Api\v1\ServicoController@index');
	Route::get('/servicos/{documento}','Api\v1\ServicoController@getByDocumento');

	//Manuais
	Route::get('servicos/{url}/manuais','Api\v1\ManualController@getManuaisServico')->where(['url' => '[A-Za-z0-9]+']);
	Route::get('manuais/{id}','Api\v1\ManualController@show')->where(['id' => '[0-9]+']);
	Route::post('manuais','Api\v1\ManualController@store');
	Route::put('manuais/{id}','Api\v1\ManualController@update')->where(['id' => '[0-9]+']);
	Route::delete('manuais/{id}','Api\v1\ManualController@destroy')->where(['id' => '[0-9]+']);

	//FAQS
	Route::resource('faqs','Api\v1\FaqController');
	Route::get('servicos/{url}/faqs','Api\v1\FaqController@getFaqsServico')->where(['url' => '[A-Za-z0-9]+']);
	Route::get('faqs/search','Api\v1\FaqController@search');

	//Usuarios
	Route::resource('usuarios','Api\v1\UsuarioController');
	Route::put('usuarios/{id}/editpass','Api\v1\UsuarioController@updatePass');


	//Administrador
	Route::resource('administradores','Api\v1\AdministradorController');
	Route::put('administradores/{id}/editpass','Api\v1\AdministradorController@updatePass');
	Route::get('/auth/current-user','Api\v1\AdministradorController@currentUser');
	Route::get('/administrador/{id}/menu','Api\v1\AdministradorController@menus')->where(['id' => '[0-9]+']);
	Route::get('/administradores/search', 'Api\v1\AdministradorController@search');


	//MEDIAS
	Route::resource('medias','Api\v1\MediaController');
	Route::post('/medias/{id}','Api\v1\MediaController@update')->where(['id' => '[0-9]+']);

	Route::get('languages','Api\v1\LanguagesController@getLanguages');
	
	//Produtos Site
	Route::get('/produtos-site','Api\v1\ProdutosSiteController@index');
	Route::post('/produtos-site','Api\v1\ProdutosSiteController@store');
	Route::get('/produtos-site/{id}','Api\v1\ProdutosSiteController@show')->where(['id' => '[0-9]+']);
	Route::post('/produtos-site/{id}','Api\v1\ProdutosSiteController@update')->where(['id' => '[0-9]+']);
	Route::delete('/produtos-site/{id}','Api\v1\ProdutosSiteController@destroy')->where(['id' => '[0-9]+']);

	//Servicos Site
	Route::get('/servicos-site','Api\v1\ServicosSiteController@index');
	Route::post('/servicos-site','Api\v1\ServicosSiteController@store');
	Route::get('/servicos-site/{id}','Api\v1\ServicosSiteController@show')->where(['id' => '[0-9]+']);
	Route::post('/servicos-site/{id}','Api\v1\ServicosSiteController@update')->where(['id' => '[0-9]+']);
	Route::delete('/servicos-site/{id}','Api\v1\ServicosSiteController@destroy')->where(['id' => '[0-9]+']);
	Route::get('/servicos-site/{locale}','Api\v1\ServicosSiteController@getServicosByLocale');

	//Videos Site
	Route::get('/videos-site','Api\v1\VideosSiteController@index');
	Route::post('/videos-site','Api\v1\VideosSiteController@store');
	Route::get('/videos-site/{id}','Api\v1\VideosSiteController@show')->where(['id' => '[0-9]+']);
	Route::post('/videos-site/{id}','Api\v1\VideosSiteController@update')->where(['id' => '[0-9]+']);
	Route::delete('/videos-site/{id}','Api\v1\VideosSiteController@destroy')->where(['id' => '[0-9]+']);
	Route::get('/videos-site/{locale}','Api\v1\VideosSiteController@getVideosByLocale');

	//Paginas Site
	Route::get('/paginas-site','Api\v1\PaginasController@index');
	Route::post('/paginas-site','Api\v1\PaginasController@store');
	Route::get('/paginas-site/{id}','Api\v1\PaginasController@show')->where(['id' => '[0-9]+']);
	Route::post('/paginas-site/{id}','Api\v1\PaginasController@update')->where(['id' => '[0-9]+']);
	Route::delete('/paginas-site/{id}','Api\v1\PaginasController@destroy')->where(['id' => '[0-9]+']);

	//Leads
	Route::get('seja-parceiro','Api\v1\SejaParceiroController@index');
	Route::delete('seja-parceiro/{id}','Api\v1\SejaParceiroController@destroy');
	Route::get('seja-parceiro/export','Api\v1\SejaParceiroController@export');
});

Route::group([],function(){
	Route::get('produtos','Web\ProdutosController@produtos');
	Route::get('produtos/{slug}','Web\ProdutosController@produto');
	Route::get('servicos/{slug}','Web\ServicosController@servico');
	Route::get('paginas/{slug}','Web\PaginasController@pagina');
	Route::get('videos','Web\VideosController@videos');

	Route::get('contato/assuntos','Web\ContatoController@assuntos');
	Route::get('contato/estados','Web\ContatoController@estados');
	Route::post('contato','Web\ContatoController@contato');
	Route::post('seja-parceiro','Web\ContatoController@sejaUmParceiro');
	
	Route::get('seo/{slug}','Web\PaginasController@spaSeo');
});

Route::group(['prefix' => 'clientes/auth'], function () {
	Route::get('', 'Cliente\Auth\UsuarioAuthController@isUserAuthenticated');
	Route::post('login', 'Cliente\Auth\UsuarioAuthController@login');
	Route::post('password/email', 'Cliente\Auth\UsuarioForgotPassowordController@sendResetLinkEmail');
});