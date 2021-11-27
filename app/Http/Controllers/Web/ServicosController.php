<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;
use App\Helpers\SEO;

class ServicosController extends Controller
{

	public function servico(Request $request,$slug){
		$language = $this->getLanguage($request->all());
		$registro = DB::table('servicos_site')->select([
			'servicos_site.id','servicos_site.externo','servicos_site_translation.title','servicos_site_translation.url','servicos_site_translation.sub_title','servicos_site_translation.link',
			'servicos_site_translation.text_description_1','servicos_site_translation.text_description_2','servicos_site_translation.benefits','servicos_site_translation.differentials'])
		          ->join('servicos_site_translation',function(JoinClause $join) use ($language){
			          $join->on('servicos_site.id','=','servicos_site_translation.owner_id')
			               ->join('languages','servicos_site_translation.language_id','=','languages.id')
			               ->where('languages.id','=',$language->id);
		          })->where([['servicos_site_translation.url','=',$slug],['servicos_site.ativo','=',true]])->first();
		if(!isset($registro))
			return response()->json(['msg' => 'Serviço não encontrado'],404);
		$servicos = DB::table('servicos_site_has_servicos_site')->select(['servicos_site_translation.title','servicos_site_translation.url','media.nome','media_root.path'])
		                                      ->join('servicos_site','servicos_site_has_servicos_site.servico_relacionado_id','=','servicos_site.id')
		                                      ->join('servicos_site_translation',function(JoinClause $join) use ($language){
		                                      	$join->on('servicos_site.id','=','servicos_site_translation.owner_id')
		                                             ->join('languages','servicos_site_translation.language_id','=','languages.id')
			                                        ->where('languages.id','=',$language->id);
		                                      })->leftJoin('media','servicos_site.media_id','=','media.id')
		                                        ->leftJoin('media_root','media.media_root_id','=','media_root.id')
		                                        ->where([['servicos_site_has_servicos_site.servico_id','=',$registro->id],['servicos_site.ativo','=',true]])->get();
		$registro->servicos = [];
		if(isset($servicos)){
			foreach ($servicos as $servico){
				$registro->servicos[] = [
					'title' => $servico->title,
					'url' => $servico->url,
					'externo' => $registro->externo,
					'link' => $registro->link,
					'file' => isset($servico->path) && isset($servico->nome) ? url($servico->path.$servico->nome) : false
				];
			}
		}
		$videos = DB::table('servicos_has_videos_site')->select(['videos_site_translation.title','videos_site_translation.text_description',
			'media_thumb.nome as thumb_nome','media_root_thumb.path as thumb_path','media_video.nome as video_nome','media_root_video.path as video_path'])
		              ->join('videos_site','servicos_has_videos_site.video_id','=','videos_site.id')
		              ->join('videos_site_translation',function(JoinClause $join) use ($language){
			              $join->on('videos_site.id','=','videos_site_translation.owner_id')
			                   ->join('languages','videos_site_translation.language_id','=','languages.id')
			                   ->where('languages.id','=',$language->id);
		              })->leftJoin('media as media_thumb','videos_site.thumb_id','=','media_thumb.id')
		              ->leftJoin('media_root as media_root_thumb','media_thumb.media_root_id','=','media_root_thumb.id')
		              ->leftJoin('media as media_video','videos_site.video_id','=','media_video.id')
		              ->leftJoin('media_root as media_root_video','media_video.media_root_id','=','media_root_video.id')
		              ->where([['servicos_has_videos_site.servico_id','=',$registro->id],['videos_site.ativo','=',true]])->get();

		$registro->videos = [];
		if(isset($videos)){
			foreach ($videos as $video){
				$registro->videos[] = [
					'title' => $video->title,
					'text_description' => $video->text_description,
					'thumb' => isset($video->thumb_path) && isset($video->thumb_nome) ? url($video->thumb_path.$video->thumb_nome) : '',
					'video' => isset($video->video_path) && isset ($video->video_nome) ? url($video->video_path.$video->video_nome) : '',
				];
			}
		}

		$produto = DB::table('produtos_site')
			->select(['produtos_site.id','produtos_site_translation.title','produtos_site_translation.url'])
			->join('produtos_has_servicos_site','produtos_site.id','=','produtos_has_servicos_site.produto_id')
			->join('servicos_site','produtos_has_servicos_site.servico_id','=','servicos_site.id')
			->join('produtos_site_translation',function(JoinClause $join) use ($language){
				$join->on('produtos_site.id','=','produtos_site_translation.owner_id')
					->join('languages','produtos_site_translation.language_id','=','languages.id')
					->where('languages.id','=',$language->id);
			})
			->where('produtos_site_translation.url','=','dados')
			->where('produtos_has_servicos_site.servico_id', '=', $registro->id)->first();

		$registro->seo = SEO::get($registro->url);
		$registro->seo['description'] = $registro->sub_title;
		
		if (isset($produto)) {
			$registro->seo['canonical'] = url($produto->url . '/' . $registro->url);
			$registro->seo['title'] = $registro->seo['title'] . $produto->title . ' - ' . $registro->title;
		} else {
			$registro->seo['canonical'] = url($registro->url);
			$registro->seo['title'] = $registro->seo['title'] . $registro->title;
		}

		return response()->json($registro);
	}
}
