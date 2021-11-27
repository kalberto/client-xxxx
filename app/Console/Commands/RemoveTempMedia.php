<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 23/02/2018
 * Time: 14:37
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 23/02/2018
 * Time: 14:37
 */


namespace App\Console\Commands;

use App\Media;
use Illuminate\Console\Command;

class RemoveTempMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:remove_temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all the temporary media saved on the server - executed daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $medias = Media::where('temp','=',true)->get();
        $int = 0;
        foreach ($medias as $media){
        	if($media->manuais()->first() == null && $media->administradores()->first() == null && $media->usuarios()->first() == null){
        		$media->delete();
        		$int++;
	        }
        }
        $this->info($int.' medias have been deleted');
    }
}
