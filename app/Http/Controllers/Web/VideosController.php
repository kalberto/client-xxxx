<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class VideosController extends Controller
{

	public function videos(Request $request) {
		$language  = $this->getLanguage( $request->all() );
		$db_videos = DB::table( 'videos_site' )->select( [
			'videos_site_translation.title',
			'videos_site_translation.text_description',
			'media_thumb.nome as thumb_nome',
			'media_root_thumb.path as thumb_path',
			'media_video.nome as video_nome',
			'media_root_video.path as video_path'
		] )
		               ->join( 'videos_site_translation', function ( JoinClause $join ) use ( $language ) {
			               $join->on( 'videos_site.id', '=', 'videos_site_translation.owner_id' )
			                    ->join( 'languages', 'videos_site_translation.language_id', '=', 'languages.id' )
			                    ->where( 'languages.id', '=', $language->id );
		               } )->leftJoin( 'media as media_thumb', 'videos_site.thumb_id', '=', 'media_thumb.id' )
		               ->leftJoin( 'media_root as media_root_thumb', 'media_thumb.media_root_id', '=', 'media_root_thumb.id' )
		               ->leftJoin( 'media as media_video', 'videos_site.video_id', '=', 'media_video.id' )
		               ->leftJoin( 'media_root as media_root_video', 'media_video.media_root_id', '=', 'media_root_video.id' )
		               ->where( 'videos_site.ativo', '=', true )->get();

		$videos = [];
		if ( isset( $db_videos ) ) {
			foreach ( $db_videos as $video ) {
				$videos[] = [
					'title'            => $video->title,
					'text_description' => $video->text_description,
					'thumb'            => isset( $video->thumb_path ) && isset( $video->thumb_nome ) ? url( $video->thumb_path . $video->thumb_nome ) : '',
					'video'            => isset( $video->video_path ) && isset ( $video->video_nome ) ? url( $video->video_path . $video->video_nome ) : '',
				];
			}
		}

		return response()->json( $videos );
	}
}
