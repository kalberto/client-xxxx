<?php
/**
 * Created by Yeshua Emanuel Braz
 * Date: 17/01/2018
 * Time: 14:21
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 31/01/2018
 * Time: 09:52
 */

namespace App\Http\Controllers\Cliente;

use App\Endereco;
use App\Helpers\MediaHelper;
use App\Http\Request\Cliente\EditUsuarioRequest;
use App\Http\Request\Cliente\SenhaUsuarioRequest;
use App\LogUsuario;
use App\Media;
use App\MediaRoot;
use App\Usuario;
use App\Helpers\OSSAPI;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class PerfilController extends AreaClienteController{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$this->beforeReturn();
		$usuario =  Auth::guard('cliente_api')->user();
		$usuario->load('media.media_root');
		$this->data['usuario']['sobrenome'] = $usuario->sobrenome;
		$this->data['usuario']['telefone'] = $usuario->telefone;
		$this->data['usuario']['email'] = $usuario->email;
		$this->data['usuario']['login'] = $usuario->login;
		$this->data['usuario']['media'] = $usuario->media;
		$this->data['usuario']['endereco'] = $usuario->getEnderecoOrCreate();
		$this->data['ufs'] = Endereco::getUfs();
		return view('site.clientes.perfil',$this->data);
	}

	/**
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function editar(Request $request)
	{
        $data = $request->all();
        $usuarioRequest = new EditUsuarioRequest();
        $data_validade =
            [
                'nome' => $data['nome'],
                'sobrenome' => $data['sobrenome'],
                'cep' => $data['endereco']['cep']
            ];
        $validate = $usuarioRequest->validar($data_validade);
        if(!$validate->fails()){
            if(!strlen($data_validade['cep']) == 8){
                $statusCode = 422;
                $response = [
                    'msg' => 'Ocorreu um erro ao editar o usuário',
                    'error_validate' => [
                        '0' => 'O Cep deve ter 8 números'
                    ],
                ];
            }else{
                $usuario = Auth::guard('cliente_api')->user();
                if($usuario->clientEdit($data, $request->ip()) && Endereco::edit($usuario->endereco_id,$data['endereco'])){
                    $statusCode = 200;
                    $response = [
                        'msg' => 'Usuário editado.'
                    ];
                }else{
                    $statusCode = 503;
                    $response = [
                        "msg" => "Database Unavailable"
                    ];
                }
            }
        }else{
            $statusCode = 422;
            $response = [
                'msg' => 'Ocorreu um erro ao editar o usuário',
                'error_validate' => $validate->errors()->all(),
            ];
        }
		return Response::json($response, $statusCode);
	}

	/**
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function senha(Request $request)
	{
        $data = $request->all();
        $senhaRequest = new SenhaUsuarioRequest();
        $validate = $senhaRequest->validar($data);
        if(!$validate->fails()){
            $usuario = Auth::guard('cliente_api')->user();
            $login =[
                'email' => $usuario->email,
                'password' => $data['password']
            ];
            if(Auth::guard('cliente_api')->once($login)){
                if($usuario->editPass($data, $request->ip())){
                    $statusCode = 200;
                    $response = [
                        'msg' => 'Senha editada.'
                    ];
                }else{
                    $statusCode = 503;
                    $response = [
                        "msg" => "Database Unavailable"
                    ];
                }
            }else{
                $statusCode = 422;
                $response = [
                    "error_validate" => [
                        "password" => ['Senha incorreta']
                    ],
                    "msg" => "Senha incorreta"
                ];
            }
        }else{
            $statusCode = 422;
            $response = [
                'msg' => 'Ocorreu um erro ao editar a senha',
                'error_validate' => $validate->errors(),
            ];
        }
		return Response::json($response, $statusCode);
	}

	/**
	 * Add media
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function createMedia(Request $request){
        if(isset($request->alias) && ($modulo  = MediaRoot::where( 'alias', $request->alias )->first()) != null) {
            if(isset($modulo))
                $resizes = $modulo->media_resizes();
            else
                $resizes = array();
            $data = $request->all();
            if(isset($data['temp']) && $data['temp'] == false)
                $path = '';
            else
                $path = 'temp/';
            $old_path = $modulo->path.$path;
            if(!isset($data['nome']))
                $data['nome'] = str_random(6);
            $data['nome'] = MediaHelper::upload( $data['file'], $old_path, $data['nome']);
            if ( isset( $request->resize ) ) {
                $options = array(
                    'action' => 'crop',
                    'width'  => isset( $data['width'] ) ? $data['width'] : 1280,
                    'height' => isset( $data['height'] ) ? $data['height'] : 720,
                    'x'      => isset( $data['x'] ) ? $data['x'] : 0,
                    'y'      => isset( $data['y'] ) ? $data['y'] : 0
                );
                MediaHelper::resize_image( $data['nome'], $old_path, $options );
            }
            foreach ( $resizes as $resize ) {
                $new_path = $modulo->path.$resize->path.$path;
                if ( ! file_exists( $new_path ) ) {
                    mkdir( $new_path, 0777,true );
                }
                if ( MediaHelper::copy_file( $old_path, $new_path, $data['nome'], $data['nome'] ) ) {
                    $options = array(
                        'width'  => $resize->width,
                        'height' => $resize->height
                    );
                    MediaHelper::resize_image( $data['nome'], $new_path, $options );
                }
            }
            $media = new Media();
            $media->fill( $data );
            $media->media_root()->associate( $modulo );
            if ( $media->save() ) {
                $data = array(
                    'usuario_id' => Auth::guard('cliente_api')->user()->id,
                    'achao' => 'upload de foto',
                    'area' => 'meu perfil',
                    'ip' => $request->ip()
                );
                LogUsuario::store($data);
                $statusCode = 201;
                $response = [
                    'msg' => 'Media salva com sucesso!',
                    'media_id' => $media->id
                ];
            } else {
                MediaHelper::delete_file( $old_path, $data['nome'] );
                foreach ( $resizes as $resize ) {
                    $new_path = $modulo->path . $resize->path.$path;
                    MediaHelper::delete_file( $new_path, $data['nome'] );
                }
                $statusCode = 500;
                $response = [
                    'msg' => 'Ocorreu um erro ao salvar a Media'
                ];
            }
        }
        else{
            $statusCode = 404;
            $response = [
                'msg' => 'Não foi possível encontrar esse alias!'
            ];
        }
		return Response::json($response, $statusCode);
	}

	/**
	 * Display the specified resource.
	 * @param  \App\Media-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function getMedia($id = null){
        $statusCode = 404;
        $response = [
            'msg' => 'Não foi possível encontrar essa media'
        ];
        if(isset($id) && is_numeric($id)){
            $media = Media::getMediaWithRoot($id);
            if(isset($media)){
                $statusCode = 200;
                $response = $media;
            }
        }
		return Response::json($response,$statusCode);
	}
}