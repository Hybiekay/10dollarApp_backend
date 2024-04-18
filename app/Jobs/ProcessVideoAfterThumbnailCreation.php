<?php

namespace App\Jobs;

use App\Models\VideoModel;
use Bepsvpt\Blurhash\Facades\BlurHash;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessVideoAfterThumbnailCreation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

     protected $video;
     protected $videoUrl;
     protected $thumbnailDestination;
     protected $canPost;
     protected $userId;

     public function __construct(VideoModel $video, $videoUrl, $thumbnailDestination, $canPost, $userId)
     {
         $this->video = $video;
         $this->videoUrl = $videoUrl;
         $this->thumbnailDestination = $thumbnailDestination;
         $this->canPost = $canPost;
         $this->userId = $userId;


     }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
      //  $client = new Client();
        $thumbnailUrl = Storage::disk('public')->url($this->thumbnailDestination);;

    //     $response = $client->get($thumbnailUrl);
    // $imageContents = $response->getBody()->getContents();

    // Generate BlurHash for the image
   // $hash = Blurhash::encode($imageContents);
        $this->video->update([
            "video_url" => $this->videoUrl,
            "thumbnail" => $thumbnailUrl,
         //   "blur_hash" => $hash,
            // You can also update other fields here
        ]);

        // Create a post if necessary
        if (in_array($this->canPost, ["yes", "Yes", "YES", "y"])) {
            \App\Models\PostModel::create([
                "type" => "video",
                "id" => $this->video->id,
                "user_id" => $this->userId,
                "content" => $this->videoUrl,
                "description" => $this->video->caption,
                "thumbnail" => $thumbnailUrl,
              //  "blur_hash" => $hashburh,
                // You can also set other fields here
            ]);
        }
    }
}
