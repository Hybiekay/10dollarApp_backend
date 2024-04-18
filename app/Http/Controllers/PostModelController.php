<?php

namespace App\Http\Controllers;

use App\Models\PostModel;
use App\Http\Requests\StorePostModelRequest;
use App\Http\Requests\UpdatePostModelRequest;
use Bepsvpt\Blurhash\Facades\BlurHash;
use Illuminate\Support\Facades\Storage;
use Pawlox\VideoThumbnail\Facade\VideoThumbnail;

class PostModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = PostModel::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostModelRequest $request)
    {
        $request->validated();
        $userId =  auth()->user()->id;

        if($request["type"]== 'text' ) {

        $post = PostModel::create(
            array_merge($request->validated(), [
                "user_id" => $userId,
            ]
            ));

        $success['$success']= true;
        $success['message']= 'Post Created Succesfuly';
        $success ['post']= $post->fresh();
        return response()->json($success, 200);



        } elseif($request['type']== 'image') {

            if($request->hasFile('file')) {
                $file= $request->file('file');
                $ext = $file->getClientOriginalExtension();
                if(in_array($ext, ['jpg','png','jpeg', "gif"])) {
                    $post = PostModel::create(
                        array_merge($request->validated(), [

                            "user_id" => $userId,
                        ]
                        ));
                $imageName = $post->id.'.'.$ext;

                $path = $file->storeAs('post/images', $imageName, 'public');
              $hashburh = BlurHash::encode($file);

                $imageUrl =   Storage::disk('public')->url($path);
                $post->update(
                    [
                       "content" => $imageUrl,
                       "description"=> $request["content"],
                      "blur_hash" => $hashburh,
                    ]
                    );


                    $success['$success']= true;
                    $success['message']= 'Post Created Succesfuly';
                    $success ['post']= $post->fresh();
                    return response()->json($success, 200);


            }else{
                return response()->json(['error' => 'Invalid file extension. Only JPG, PNG, JPEG, and GIF are allowed. for images'], 400);
            }

        }


        } elseif($request['type'] == 'video' ){

            if($request->hasFile('file')) {
                $file= $request->file('file');
                $ext = $file->getClientOriginalExtension();
                if(in_array($ext, ["mp4","mov","webm","avi","mkv","wmv","flv"])) {
                    $post = PostModel::create(
                        array_merge($request->validated(),
                         ["user_id"=> $userId])
                    );
                    $videoName = $post->id.".".$ext;

                    $path = $file->storeAs('post/video', $videoName, 'public');

                    $videoUrl =   Storage::disk('public')->url($path);
                  //  $hashburh = BlurHash::encode($file);

                    VideoThumbnail::createThumbnail(
                        $videoUrl,
                        'post/thumbs/',
                        $videoName,
                        3,
                        640,
                        480
                    );
                    $post->update(
                       [ "content" => $videoUrl,
                        "description"=> $request->content,

                       ]

                    );

                    $success['$success']= true;
                    $success['message']= 'Post Created Succesfuly';
                    $success ['post']= $post->fresh();
                    return response()->json($success, 200);



                }else{
                    return response()->json(["error"=> "Invalid file extension. Only mp4, MOV, Webm, and Mkv are allowed. for video"], 400);
                }

            }

        } elseif($request['type']== 'link'){

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PostModel $postModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostModelRequest $request, PostModel $postModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostModel $postModel)
    {
        //
    }
}
