<?php

namespace App\Console\Commands;

use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoEncode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video-encode:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Vedio Encoding......';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $low = (new X264("aac"))->setKiloBitrate(500);
    $high = (new X264("aac"))->setKiloBitrate(0);

    FFMpeg::fromDisk("public")
    ->open("post/video/sample.mp4")
    ->exportForHLS()
    ->addFormat($low, function($filters){
        $filters->resize(640, 480);
    })

    ->addFormat($high, function($filters){
        $filters->resize(1280, 720);
    })
    ->onProgress(function($progess){
        $this->info("Progess {$progess}");
    })
    ->toDisk('public')
    ->save("post/video/test/file.m3u8");

}
}
