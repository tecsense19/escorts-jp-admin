<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\BookingSlot;
use App\Models\ProfileImages;
use App\Models\EscortsBookings;
use App\Models\FavouriteEscorts;
use App\Models\EscortsAvailability;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Http\Controllers\Api\BaseController as BaseController;

use Hash;
use Mail;
use Validator;

class EscortsController extends BaseController
{
    public function register(Request $request)
    {
        try {

            $input = $request->all();

            $validator = Validator::make($input, [
                'full_name' => 'required',
                'about' => 'required',
                'age' => 'required',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'hourly_price' => 'required',
                'line_number' => 'required',
                'mobile_no' => isset($input['user_id']) && $input['user_id'] != ''
                    ? 'required|numeric|unique:users,mobile_no,' . $input['user_id']
                    : 'required|numeric|unique:users,mobile_no',
                'email' => isset($input['user_id']) && $input['user_id'] != ''
                    ? 'required|unique:users,email,' . $input['user_id']
                    : 'required|unique:users,email',
                'password' => isset($input['user_id']) && $input['user_id'] == ''
                    ? 'required'
                    : '',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $userData = [];
            $userData['name'] = isset($input['full_name']) ? $input['full_name'] : '';
            $userData['description'] = isset($input['about']) ? $input['about'] : '';
            $userData['age'] = isset($input['age']) ? $input['age'] : '';
            $userData['country'] = isset($input['country']) ? $input['country'] : '';
            $userData['state'] = isset($input['state']) ? $input['state'] : '';
            $userData['city'] = isset($input['city']) ? $input['city'] : '';
            $userData['mobile_no'] = isset($input['mobile_no']) ? $input['mobile_no'] : '';
            $userData['line_number'] = isset($input['line_number']) ? $input['line_number'] : '';
            $userData['email'] = isset($input['email']) ? $input['email'] : '';
            $userData['hourly_price'] = isset($input['hourly_price']) ? $input['hourly_price'] : '';

            $userId = '';
            $message = '';
            if($input['user_id'])
            {
                User::where('id', $input['user_id'])->update($userData);
                $userId = $input['user_id'];
                $message = 'Profile updated successfully.';
            }
            else
            {
                $userData['password'] = Hash::make($input['password']);
                $userData['user_role'] = 'escorts';
                
                $lastUser = User::create($userData);

                $userId = $lastUser->id;

                $message = 'Escort register successfully.';
            }

            if(isset($input['remove_img_ids']) && $input['remove_img_ids'] != '')
            {
                $getImages = ProfileImages::whereIn('id', explode(',', $input['remove_img_ids']))->where('type', 'image')->get();
                foreach ($getImages as $key => $value) 
                {
                    $proFilePath = $value->file_path;
                    $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                    if (file_exists(public_path($proPath))) {
                        \File::delete(public_path($proPath));
                    }
                }

                ProfileImages::whereIn('id', explode(',', $input['remove_img_ids']))->where('type', 'image')->delete();   
            }
            if(isset($input['remove_video_ids']) && $input['remove_video_ids'] != '')
            {
                $getVideos = ProfileImages::whereIn('id', explode(',', $input['remove_video_ids']))->where('type', 'video')->get();
                foreach ($getVideos as $key => $value) 
                {
                    $proFilePath = $value->file_path;
                    $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                    if (file_exists(public_path($proPath))) {
                        \File::delete(public_path($proPath));
                    }
                }

                ProfileImages::whereIn('id', explode(',', $input['remove_video_ids']))->where('type', 'video')->delete();
            }

            if ($files = $request->file('image_upload')) {
            
                foreach ($files as $file) {
                    $path = 'public/uploads/escorts_profile/';
            
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move($path, $filename);
            
                    $img = 'public/uploads/escorts_profile/' . $filename;
            
                    $imgData = [];
                    $imgData['user_id'] = $userId;
                    $imgData['type'] = 'image';
                    $imgData['file_path'] = $img;

                    ProfileImages::create($imgData);
                }
            }

            if ($files = $request->file('video_upload')) {
            
                foreach ($files as $file) {
                    $path = 'public/uploads/escorts_video/';
            
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move($path, $filename);
            
                    $img = 'public/uploads/escorts_video/' . $filename;
            
                    $videoData = [];
                    $videoData['user_id'] = $userId;
                    $videoData['type'] = 'video';
                    $videoData['file_path'] = $img;

                    ProfileImages::create($videoData);
                }
            }

            $userDetails = User::where('id', $userId)->first();

            return $this->sendResponse($userDetails, $message);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {

            $input = $request->all();

            $validator = Validator::make($input, [
                'email' => 'required',
                'password' => 'required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $getUser = User::where('email', $input['email'])->where('user_role', 'escorts')->first();

            if($getUser)
            {
                if(Hash::check($input['password'], $getUser->password))
                {
                    return $this->sendResponse($getUser, 'Login Successfully.');
                }
                else
                {
                    return $this->sendError('Invalid password! please try again.');
                }
            }
            else
            {
                return $this->sendError('Invalid email address! please try again.');
            }

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getEscortProfile(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $getUserDetails = User::where('id', $input['user_id'])->where('user_role', 'escorts')->with('escortImages')->with('escortVideos')->first();

            return $this->sendResponse($getUserDetails, 'Profile get successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    
    public function escortAvailabilityAdd(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required',
                'available_date' => 'required',
                'available_slot' => 'required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $availableDate = date('Y-m-d', strtotime($input['available_date']));
            $availableSlot = $input['available_slot'];
            $userId = $input['user_id'];

            $explodeSlot = [];
            if($availableSlot)
            {
                $explodeSlot = explode(',', $availableSlot);
            }

            $timeArr = [];
            for ($i=0; $i < count($explodeSlot); $i++) 
            {
                $timeArr[] = date('H:i:s', strtotime($explodeSlot[$i]));
            }

            EscortsAvailability::where('user_id', $userId)
                                ->where('available_date', $availableDate)
                                ->whereNotIn('available_time', $timeArr)
                                ->delete();

            for ($i=0; $i < count($explodeSlot); $i++) 
            {
                $availableArr = [];
                $availableArr['user_id'] = $userId;
                $availableArr['available_date'] = $availableDate;
                $availableArr['available_time'] = date('H:i', strtotime($explodeSlot[$i]));

                $checkAvailable = EscortsAvailability::where('user_id', $userId)
                                                    ->where('available_date', $availableDate)
                                                    ->where('available_time', date('H:i:s', strtotime($explodeSlot[$i])))
                                                    ->first();
                
                if($checkAvailable)
                {
                    EscortsAvailability::where('user_id', $userId)
                                        ->where('available_date', $availableDate)
                                        ->where('available_time', date('H:i:s', strtotime($explodeSlot[$i])))
                                        ->update($availableArr);
                }
                else
                {
                    EscortsAvailability::create($availableArr);
                }
            }

            $getAvailability = EscortsAvailability::where('user_id', $userId)->where('available_date', $availableDate)->get();

            return $this->sendResponse($getAvailability, 'Availability added successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required',
                'old_password' => 'required',
                'new_password' => 'required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $getUserDetails = User::where('id', $input['user_id'])->where('user_role', 'escorts')->first();

            if($getUserDetails)
            {
                if(Hash::check($input['old_password'], $getUserDetails->password))
                {
                    $userData = [];
                    $userData['password'] = isset($input['new_password']) ? Hash::make($input['new_password']) : '';

                    User::where('id', $input['user_id'])->update($userData);

                    $profileDetails = User::where('id', $input['user_id'])->first();

                    return $this->sendResponse($profileDetails, 'Password change successfully.');
                }
                else
                {
                    return $this->sendError('Current password are not match.');
                }
            }
            else
            {
                return $this->sendError('Invalid user.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'email' => 'required'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $checkEmail = User::where('email', $input['email'])->where('user_role', 'escorts')->first();

            if($checkEmail)
            {
                $redToken = Str::random(8);

                $dataArr = [];
                $dataArr['forgot_pass_date'] = date('Y-m-d H:i:s');
                $dataArr['forgot_pass_token'] = $redToken;
                $dataArr['forgot_pass'] = 0;

                User::where('email', $input['email'])->update($dataArr);

                $respoArr['full_name'] = $checkEmail->fname . ' ' . $checkEmail->lname;
                $respoArr['pass_link'] = url('/').'/'.'forgot/password/view/'.Crypt::encryptString($checkEmail->rid).'/'.Crypt::encryptString($redToken);
                $respoArr['logo_link'] = url('/').'/'.'public/assets/img/site-logo.png';

                Mail::send('email/forgot_pass_mail', ['user' => $respoArr], function ($m) use ($respoArr, $input) {
                    $m->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

                    $m->to( $input['email'] )->subject('Forgot Password');
                });

                return $this->sendResponse($input['email'], 'A password reset link has been sent to your email address. Please check your email for further instructions.');
            }
            else
            {
                return $this->sendError('Invalid user.');
            }

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function favouriteEscorts(Request $request)
    {
        try {

            $input = $request->all();

            $validator = Validator::make($input, [
                'escort_ids' => 'required',
                'user_id' => 'required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $explodeIds = isset($input['escort_ids']) ? explode(',', $input['escort_ids']) : [];

            FavouriteEscorts::whereIn('escort_id', $explodeIds)->where('user_id', $input['user_id'])->delete();

            $message = '';
            if($input['is_favourite'] == '1')
            {
                for ($i=0; $i < count($explodeIds); $i++) 
                { 
                    $favouriteArr = [];
                    $favouriteArr['escort_id'] = $explodeIds[$i];
                    $favouriteArr['user_id'] = $input['user_id'];
                    $favouriteArr['is_favourite'] = $input['is_favourite'];
                    
                    FavouriteEscorts::create($favouriteArr);
                    $message = 'Escort favourite Successfully.';
                }
            }
            else
            {
                $message = 'Escort unfavourite Successfully.';
            }

            return $this->sendResponse($input['is_favourite'], $message);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}

