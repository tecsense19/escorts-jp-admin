<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\BookingSlot;
use App\Models\ProfileImages;
use App\Models\EscortsBookings;
use App\Models\EscortsAvailability;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Api\BaseController as BaseController;

use Hash;
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
}

