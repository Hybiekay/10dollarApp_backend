<?php

namespace App\Http\Controllers;

use App\Jobs\CreateThumbnailFromVideo;
use App\Jobs\ProcessVideoAfterThumbnailCreation;
use App\Models\PostModel;
use App\Models\VideoModel;
use App\Http\Requests\StoreVideoModelRequest;
use App\Http\Requests\UpdateVideoModelRequest;

use Bepsvpt\Blurhash\Facades\BlurHash;
use Illuminate\Support\Facades\Storage;

class VideoModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StoreVideoModelRequest $request)
    // {

    //     $validated = $request->validated();
    //     $userId = auth()->id();
    //     $video = VideoModel::create([
    //      "caption" => $validated['caption'],
    //      "user_id"=> $userId
    //     ]);


    //             if($request->hasFile('video')) {
    //              $videoFile = $request->file('video');
    //             $ext = $videoFile->getClientOriginalExtension();
    //             if(in_array($ext, ["mp4","mov","webm","avi","mkv","wmv","flv"])) {

    //                 $videoName = $video->id.".".$ext;

    //                 $path = $videoFile->storeAs('video', $videoName, 'public');

    //                 $videoUrl =   Storage::disk('public')->url($path);
    //                 $destination = "/video/thumbnail/". $video->id .".png";
    //               await  CreateThumbnailFromVideo::dispatch($path,$destination);
    //                 $thumbnail =   Storage::disk('public')->url($destination);
    //             // Generate BlurHash for the thumbnail
    //         $hashburh = BlurHash::encode(file_get_contents($thumbnail));


    //                 $video->update(
    //                    [
    //                     "video_url" => $videoUrl,
    //                     "thumbnail"=>$thumbnail,
    //                     "blur_hash"=> $hashburh
    //                    ]

    //                 );

    //                 if(in_array($request['can_post'], ["yes","Yes","YES","y"])){
    //                     $post = PostModel::create(
    //                     [
    //                     "type"=>"video",
    //                     "id"=> $video->id,
    //                      "user_id"=> $userId,
    //                      "content"=> $videoUrl,
    //                      "description"=> $video->caption,
    //                      "thumbnail"=>$thumbnail,
    //                      "blur_hash"=>$hashburh


    //                     ]
    //                 );

    //                 }


    //                 $success['$success']= true;
    //                 $success['message']= in_array($request['can_post'], ["yes","Yes","YES","y"]) ?  'Video and Post Created Succesfuly':'Video Created Succesfuly' ;
    //                 $success ['post']= $video->fresh();
    //                 return response()->json($success, 200);



    //             }else{
    //                 return response()->json(["error"=> "Invalid file extension. Only mp4, MOV, Webm, and Mkv are allowed. for video"], 400);
    //             }

    //         }else{
    //             return response()->json(["error"=> "Video is requied"], 400);
    //         }

    // }


    public function store(StoreVideoModelRequest $request)
    {
        $validated = $request->validated();
        $userId = auth()->id();
        $video = VideoModel::create([
            "caption" => $validated['caption'],
            "user_id" => $userId
        ]);

        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $ext = $videoFile->getClientOriginalExtension();
            if (in_array($ext, ["mp4", "mov", "webm", "avi", "mkv", "wmv", "flv"])) {
                $videoName = $video->id . "." . $ext;
                $path = $videoFile->storeAs('video', $videoName, 'public');
                $videoUrl = Storage::disk('public')->url($path);

                // Generate thumbnail path
                $thumbnailDestination = "/video/thumbnail/" . $video->id . ".png";

                // Dispatch job to create thumbnail and wait until it's done
                $thumbnailJob = (new CreateThumbnailFromVideo($path, $thumbnailDestination))->onQueue('thumbnails');
                 dispatch($thumbnailJob)->onQueue('thumbnails')->chain([
                    new ProcessVideoAfterThumbnailCreation($video, $videoUrl, $thumbnailDestination, $request['can_post'], $userId)
                ]);

                $success['$success'] = true;
                $success['message'] = 'Video and Thumbnail Creation Process Started Successfully';
                $success['video'] = $video->fresh();
                return response()->json($success, 200);
            } else {
                return response()->json(["error" => "Invalid file extension. Only mp4, MOV, Webm, and Mkv are allowed for video"], 400);
            }
        } else {
            return response()->json(["error" => "Video is required"], 400);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(VideoModel $videoModel)
    {
       return response()->json([
        "message"=> "Video Fetch Successfuly",
        "data"=> $videoModel::fresh
         ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVideoModelRequest $request, VideoModel $videoModel)

    {
        $request->validated();

        try {

         $video =   $videoModel->update([
                "content"=> $request["content"],
            ]);

            if($video ){
                return response()->json([
                    "message"=> "Video Updated Successfuly",
                    "data"=> $video::fresh()

                ], 200);
            }else{
                return response()->json([
                    "message"=> "Error occurred, the video might not be on the database ",
                ], 404);
            }
        }catch(e){
            return response()->json([
                "message"=> "Some Error occurred",


            ], 400);
        }


    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(VideoModel $videoModel)
    {
        $video = $videoModel->find($videoModel->id);
    }
}
