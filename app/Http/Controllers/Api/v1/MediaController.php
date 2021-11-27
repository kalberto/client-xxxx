<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 08/11/2017
 * Time: 18:18
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 13/11/2017
 * Time: *
 */

namespace App\Http\Controllers\Api\v1;

use App\Helpers\MediaHelper;
use App\LogAdministrador;
use App\Media;
use App\MediaRoot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Mockery\Exception;

class MediaController extends Controller
{
	/**
	 * Instantiate a new controller instance.
	 */
	public function __construct()
	{
		$this->middleware('role:4');
	}
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	    $statusCode = 200;
	    $response = [
		    'msg' => 'Método não implementado'
	    ];
	    return response()->json($response, $statusCode);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    $statusCode = 200;
	    $response = [
		    'msg' => 'Método não implementado'
	    ];
	    return response()->json($response, $statusCode);
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
    public function store(Request $request)
    {
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
                $data['nome'] = Str::random(6);
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
                    'administrador_id' => Auth::user()->id,
                    'registro_id' => $media->id,
                    'tabela' => 'media',
                    'tipo' => 'insert',
                    'ip' => $request->ip()
                );
                LogAdministrador::store($data);
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
	    return response()->json($response, $statusCode);
    }

	/**
	 * Display the specified resource.
	 * RF00
	 * @param  \App\Media-id $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id = null)
	{
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
		return response()->json($response, $statusCode);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function edit(Media $media)
    {
	    $statusCode = 200;
	    $response = [
		    'msg' => 'Método não implementado'
	    ];
	    return response()->json($response, $statusCode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = null)
    {
        if(isset($id) && is_numeric($id) && ($media = Media::find($id)) != null){
            $modulo = $media->media_root()->first();
            if(isset($modulo))
                $resizes = $modulo->media_resizes();
            else
                $resizes = array();
            $data = $request->all();

            if(isset($data['temp']) && $data['temp'] == false){
                $path = '';
                $temp = false;
            }
            else{
                $path = 'temp/';
                $temp = true;
            }
            $old_path = $modulo->path.$path;
            $nome = MediaHelper::upload( $data['file'], $old_path, str_random(6));

            if($media->temp == true){
                $data['nome'] = $nome;
                MediaHelper::delete_file($modulo->path.'temp/',$media->nome);
            }else{
                if($temp){
                    $data['nome'] = $media->nome;
                    $data['nome_temp'] = $nome;
                    $data['temp'] = false;
                }else
                    $data['nome'] = $nome;
            }
            if ( isset( $data['resize'] ) ) {
                $options = array(
                    'action' => 'crop',
                    'width'  => isset( $data['width'] ) ? $data['width'] : 1280,
                    'height' => isset( $data['height'] ) ? $data['height'] : 720,
                    'x'      => isset( $data['x'] ) ? $data['x'] : 0,
                    'y'      => isset( $data['y'] ) ? $data['y'] : 0
                );
                MediaHelper::resize_image( $nome, $old_path, $options );
            }
            foreach ( $resizes as $resize ) {
                $new_path = $modulo->path . $resize->path.$path;
                if ( ! file_exists( $new_path ) ) {
                    mkdir( $new_path, 0777,true );
                }
                if ( MediaHelper::copy_file( $old_path, $new_path, $nome, $nome ) ) {
                    $options = array(
                        'width'  => $resize->width,
                        'height' => $resize->height
                    );
                    MediaHelper::resize_image( $nome, $new_path, $options );
                }
            }
            $media->fill( $data );
            if ( $media->save() ) {
                $data = array(
                    'administrador_id' => Auth::user()->id,
                    'registro_id' => $media->id,
                    'tabela' => 'media',
                    'tipo' => 'update',
                    'ip' => $request->ip()
                );
                LogAdministrador::store($data);
                $statusCode = 201;
                $response = [
                    'msg' => 'Media salva com sucesso!',
                    'media_id' => $media->id
                ];
            } else {
                MediaHelper::delete_file( $old_path,$nome );
                foreach ( $resizes as $resize ) {
                    $new_path = $modulo->path . $resize->path.$path;
                    MediaHelper::delete_file( $new_path, $nome );
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
                'msg' => 'Não foi possível encontrar essa Media.'
            ];
        }
	    return response()->json($response, $statusCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id = null)
    {
	    if(isset($id) && is_numeric($id) && ($media = Media::find($id)) != null){
		    if($media->delete()){
			    $data = array(
				    'administrador_id' => Auth::user()->id,
				    'registro_id' => $id,
				    'tabela' => 'media',
				    'tipo' => 'delete',
				    'ip' => $request->ip()
			    );
			    LogAdministrador::store($data);
			    $statusCode = 200;
			    $response = [
				    'msg' => 'Media deletado com sucesso!'
			    ];
		    }else{
			    $statusCode = 500;
			    $response = [
				    'msg' => 'Erro ao deletar a Media!'
			    ];
		    }
	    }
	    else{
		    $statusCode = 404;
		    $response = [
			    'msg' => 'Não foi possível encontrar essa Media.'
		    ];
	    }
	    return response()->json($response, $statusCode);
    }
}
