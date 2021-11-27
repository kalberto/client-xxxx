<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;
use App\Helpers\SEO;

class PaginasController extends Controller
{
	public function pagina(Request $request,$slug){
		$language = $this->getLanguage($request->all());
		$registro = DB::table('paginas')->select([
			'paginas.id','paginas_translation.title','paginas_translation.url','paginas_translation.sub_title','paginas_translation.text_1',
			'paginas_translation.text_2','paginas.video_id'])
		          ->join('paginas_translation',function(JoinClause $join) use ($language){
			          $join->on('paginas.id','=','paginas_translation.owner_id')
			               ->join('languages','paginas_translation.language_id','=','languages.id')
			               ->where('languages.id','=',$language->id);
		          })->where('paginas_translation.url','=',$slug)->first();
		if(!isset($registro))
			return response()->json(['msg' => 'Página não encontrado'],404);
		if(isset($registro->video_id)){
			$video = DB::table('videos_site')->select(['videos_site_translation.title','videos_site_translation.text_description','media_thumb.nome as thumb_nome',
				'media_root_thumb.path as thumb_path','media_video.nome as video_nome','media_root_video.path as video_path'])
			           ->join('videos_site_translation',function(JoinClause $join) use ($language){
				           $join->on('videos_site.id','=','videos_site_translation.owner_id')
				                ->join('languages','videos_site_translation.language_id','=','languages.id')
				                ->where('languages.id','=',$language->id);
			           })->leftJoin('media as media_thumb','videos_site.thumb_id','=','media_thumb.id')
			           ->leftJoin('media_root as media_root_thumb','media_thumb.media_root_id','=','media_root_thumb.id')
			           ->leftJoin('media as media_video','videos_site.video_id','=','media_video.id')
			           ->leftJoin('media_root as media_root_video','media_video.media_root_id','=','media_root_video.id')
			           ->where('videos_site.id','=',$registro->video_id)->first();
			$registro->video = [
				'title' => $video->title,
				'text_description' => $video->text_description,
				'video_thumb' => url($video->thumb_path.$video->thumb_nome),
				'video' => url($video->video_path.$video->video_nome)
			];
			unset($registro->video_id);
		}
		$videos = DB::table('paginas_has_videos_site')->select(['videos_site_translation.title','videos_site_translation.text_description',
			'media_thumb.nome as thumb_nome','media_root_thumb.path as thumb_path','media_video.nome as video_nome','media_root_video.path as video_path'])
		              ->join('videos_site','paginas_has_videos_site.video_id','=','videos_site.id')
		              ->join('videos_site_translation',function(JoinClause $join) use ($language){
			              $join->on('videos_site.id','=','videos_site_translation.owner_id')
			                   ->join('languages','videos_site_translation.language_id','=','languages.id')
			                   ->where('languages.id','=',$language->id);
		              })->leftJoin('media as media_thumb','videos_site.thumb_id','=','media_thumb.id')
		              ->leftJoin('media_root as media_root_thumb','media_thumb.media_root_id','=','media_root_thumb.id')
		              ->leftJoin('media as media_video','videos_site.video_id','=','media_video.id')
		              ->leftJoin('media_root as media_root_video','media_video.media_root_id','=','media_root_video.id')
		              ->where([['paginas_has_videos_site.pagina_id','=',$registro->id],['videos_site.ativo','=',true]])->get();

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
		unset($registro->id);

		$registro->seo = SEO::get($registro->url);

		return response()->json($registro);
	}

	public function spa(Request $request, $slug = null, $other_slug = null) {
		return view('spa.web', SEO::get($slug, $other_slug), ['language' => 'pt-br']);
	}

	public function spaSeo(Request $request, $slug = null, $other_slug = null) {
		$registro = [
			'seo' => SEO::get($slug, $other_slug),
			'language' => 'pt-br'
		];
		return response()->json($registro);
	}
}
