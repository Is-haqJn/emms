<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Hash;
// use App\QrGenerate;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\UpdatePasswordRequest;
use App\Http\Requests\Api\ProfilePicRequest;

class AuthController extends Controller
{
    public function loginUser(LoginRequest $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $user->token = $user->createToken("API TOKEN")->plainTextToken;
                $data['user'] = $user;
                $data['user']['image'] = asset('uploads/user_img/' . $user->image);
                return responseData('1', 'User Logged In Successfully.',$data, 200);
            }

            return responseData('0', 'Email & Password do not match with our record.', '', 401);
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $response = Password::sendResetLink($request->only('email'));
            return responseData(
                $response == Password::RESET_LINK_SENT ? '1' : '0',
                $response == Password::RESET_LINK_SENT ? 'Please, check your Emails for Login Credentials.' : 'Could not Send Verification Email. Please, Try again Later!',
                '',
                $response == Password::RESET_LINK_SENT ? 200 : 401
            );
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = auth('sanctum')->user();
            // dd($user);
            if (is_null($user)) {
                return responseData('0', 'User Does Not Exist', '', 401);
            }

            if (Hash::check($request->password, $user->password)) {
                return responseData('0', 'Old And New Password Is Same.', '', 401);
            }

            $user->password = Hash::make($request->password);
            $user->save();
            $data['user'] = $user;

            return responseData('1', 'Password Changed Successfully.', $data, 200);
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }
    public function profile_pic_update(ProfilePicRequest $req){
        try {
            $user = auth('sanctum')->user();
            if ($req->hasFile('image')) {
                if($user->image!=null){
                    unlink('uploads/user_img/' . $user->image);
                }
                $filename = $req->file('image')->getClientOriginalName(); // get the file name
                $getfilenamewitoutext = pathinfo($filename, PATHINFO_FILENAME); // get the file name without extension
                $getfileExtension = $req->file('image')->getClientOriginalExtension(); // get the file extension
                $createnewFileName = time() . '_' . str_replace(' ', '_', $getfilenamewitoutext) . '.' . $getfileExtension; // create new random file name
                $img_path = $req->file('image')->move('uploads/user_img', $createnewFileName);
                // $img_path = $req->file('image')->storeAs('public/user_img', $createnewFileName); // get the image path
                $user->image = $createnewFileName;
                $user->save();
             }
            $data['user'] = $user;
            $data['user']['image'] = asset('uploads/user_img/' . $user->image);
            return responseData('1', 'Profile Pic Changed Successfully.', $data, 200);
        } catch (\Throwable $th) {
            return handleException($th);
        }

    }
    
    // Common utility method for error response
   
}
