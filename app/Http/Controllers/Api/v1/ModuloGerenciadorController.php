<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 30/10/2017
 * Time: 18:20
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 30/10/2017
 * Time: 18:20
 */

namespace App\Http\Controllers\Api\v1;

use App\ModuloGerenciador;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Mockery\Exception;

class ModuloGerenciadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ModuloGerenciador::getAll();
        $statusCode = 200;
        $response = $data;
	    Return Response::json($response, $statusCode);
    }
}
