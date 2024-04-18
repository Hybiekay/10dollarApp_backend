<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\ForgettenPasswordNotification;
use Exception;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use App\Models\User;
use \Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Image;
use PhpParser\Node\Stmt\TryCatch;
class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(RegisterRequest $registerRequest)
    {
        $registerRequest->validated();

        $value['email'] = $registerRequest['email'] ;
        $value['password'] = Hash::make( $registerRequest['password']);

    $user = User::create($value);


       $success["message"]= 'Verification OTP sent Succefully';
       $success["success"]= true;

     $user->notify(new EmailVerificationNotification);
return response()->json($success , 200);


    }


    public function  verify_otp(Request $request){
            $otp = new Otp;
            $request->validate(["otp"=>'required|max:6|min:6', "email"=>"required|email|exists:users"]);
    $newemail = $request['email'];
    $newotp =  $request["otp"];
    $otp =    $otp->validate($newemail,$newotp,);
    if(!$otp->status){
        return response()->json(["error" =>$otp]);
    }else{
    $user = User::where("email", $newemail)->first();

    if($user){
    $user->update(["email_verified"=>true, "email_verified_at"=>now()]);
    $success["token"]= $user->createToken("auth")->plainTextToken;
    $success["success"]=true;
    $success["message"]="Email verified Successfully";
    return response()->json($success);
    }else{
        return response()->json(["message"=>"users with the given email not found", ] ,404);
    }

    }
        }


public function resend_otp(Request $request){
    $request->validate([
        "email"=>"required|email|exists:users"
    ]);

    try{

        $email = $request["email"];
        $user = User::where("email", $email)->first();
        if($user){
        $user->notify(new EmailVerificationNotification);
        $success["status"]= true;
        $success['message']="Otp Resend Successfully";

        return response()->json($success, 200);
}
    }catch(e){
        return response()->json(['error' =>"Unable to send otp"], 400);
    }
}

    /**
     * Store a newly created resource in storage.
     */
    public function update_user(UpdateUserRequest $request)
    {
        // Validate the request data using the UpdateUserRequest class rules
        $validatedData = $request->validated();
     //   $image = new Image;
        try {
            // Retrieve the authenticated user
            $user = auth()->user();

            if($request->hasFile("profile_image")){
                if ($user->profile_image) {
                    // Delete the existing profile image
                    Storage::disk('public')->delete('profile_image/' . $user->profile_image);
                }
                $image = $request->file('profile_image');
                $imageName = $user->id.'.'.$image->getClientOriginalExtension();
               $path = $image->storeAs('profile_image', $imageName, 'public');
               $imageUrl =   Storage::disk('public')->url($path);
               $user->update(array_merge($validatedData, ["profile_image" => $imageUrl]));
              $user = $user->fresh();
            } else {
                $user->update($validatedData);

                $user = $user->fresh();
            }

            // Update the user's attributes with the validated data
          // Refresh the user model to reflect the changes

            // Prepare the success response
            $success = [
                "status" => true,
                "message" => "User details updated successfully",
                "user" => $user // Return the updated user details
            ];

            // Return a JSON response with the success data and HTTP status 200
            return response()->json($success, 200);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors, gracefully
            return response()->json(["error" => "Some Error Ocurured"], 400);
        }
    }


    /**
     // Optionally, if you want to return the updated user details in the response
     * Display the specified resource.
     */
    public function forget_password(Request $request)
    {
        $request->validate(['email'=>"required|email"]);
        try{
            $email = $request["email"];

            $user = User::where('email', $email)->first();
       if(!$user){
        $fail["status"]=false;
        $fail['message']="User With the given Email not Found Check the email and try later";

      return response()->json($fail, 404);
       }else{
        $user->notify(new ForgettenPasswordNotification);

        $success["status"]=true;
        $success['message']="Forgetten Password Email sent Successfully";

return response()->json($success, 200);
       }
    }catch(e ){
        return response()->json(['error'=> "some error occured"], 200);
    }


    }


    public function verify_forget_password(Request $request)
    {
        $otp1 = new Otp;
        $request->validate(['email'=>"required|email",
        'otp'=>"required"]);
        try{
            $email = $request["email"];
            $otp = $request["otp"];
          $otp1 =  $otp1->validate($email , $otp);

            if(!$otp1->status){
                $user = User::where('email', $email)->first();
       if(!$user){

    $fail['status']=false;
    $fail['message']= 'Users Does Not Exist Please Check Email And Try again';
    return response()->json($fail, 404);
}else{
                $success["token"]= $user->createToken("user")->plainTextToken;
                $success["success"]=true;
                $success["message"]="Email verified Successfully";

                return response()->json($success,200);}
            }else{
                return response()->json(['error'=>"some error occured" ],400);
            };



    }catch(e ){
        return response()->json(['error'=> "Unknown error occured"], 200);
    }


    }




    public function update_password(Request $request,)
    {
        $request->validate([
            "password"=> "required|min:6|confirmed"
        ]);

        try {
            $user = auth()->user();
            $user = $user->update([
                "password"=> Hash::make($request["password"])
            ]);

            $succes["status"] = true;
            $succes["message"]= "Password Updated Successfully";
            return response()->json($succes,200);
        } catch(e){
            return response()->json(["error"=> "Some error Occurier"], 404);
        }

    }

    public function login(Request $loginRequest)
    {
        $loginRequest->validate([
            "email"=>"required|email",
            "password"=>"required"
        ]);
        $email = $loginRequest['email'];
        $password = $loginRequest['password'];
        $user = User::where("email", $email)->first();
        if(!$user){
            return response()->json(["error"=>"User Not Found"], 404);
        }else if(!Hash::check($password, $user->password)){
           return response()->json(["error"=> "invalid Password"] ,400);
        }else {

            $user->tokens()->delete();
            $success["token"]= $user->createToken("auth")->plainTextToken;
            $success['success']= true;
            $success['message']= "Login Successfully";

            return response()->json($success, 200);
        }

    }


    /**
     * Remove the specified resource from storage.
     */
    public function log_out()
    {
        $user = auth()->user();
        $user->tokens()->delete();
        $succes["status"] = true;
        $succes["message"]= "You have successfully Log Out";
        return response()->json($succes,200);
    }
}
