<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 31/10/2017
 * Time: 12:01
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 31/10/2017
 * Time: 12:01
 */

namespace App\Http\Controllers\Api\v1;

use App\ModuloCliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Mockery\Exception;

class ModuloClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ModuloCliente::orderBy('order')->get()->makeVisible('id');
        $statusCode = 200;
        $response = $data;
	    Return Response::json($response, $statusCode);
    }
}
