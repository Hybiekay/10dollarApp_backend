<?php

namespace App\Jobs;

use App\Models\VideoModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class CreateThumbnailFromVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $path;
    public $destination;

    public function __construct( $path, $destination )
    {
        $this->path =$path;
        $this->destination =$destination;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //$destination = "/video/thumbnail/". $this->videoName .".png";
        FFMpeg::fromDisk("public")
        ->open($this->path)
        ->getFrameFromSeconds(10)
        ->export()
        ->toDisk("public")
        ->save($this->destination);



    }
}
