<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 21/10/2020
 * Time: 11:18
 */
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected function getLanguage($request){
	    if (isset($request['language'])){
		    $language = DB::table('languages')->where('locale','=',$request['language'])->first();
	    }else{
		    $language = DB::table('languages')->where('default','=',true)->first();
	    }
		App::setLocale($language->locale);
	    return $language;
    }
}
