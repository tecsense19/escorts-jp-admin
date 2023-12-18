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
    private $globalIdsArray = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24'];
    private $globalTimesArray = ['01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00','00:00'];

    public function register(Request $request)
    {
        try {

            $input = $request->all();

            $validator = Validator::make($input, [
                'full_name' => 'required',
                'about' => 'required',
                'age' => 'required',
                'hourly_price' => 'required',
                'line_number' => 'required',
                'mobile_no' => isset($input['user_id']) && $input['user_id'] != ''
                    ? 'required|numeric|unique:users,mobile_no,' . $input['user_id']
                    : 'required|numeric|unique:users,mobile_no',
                'email' => isset($input['user_id']) && $input['user_id'] != ''
                    ? 'required|unique:users,email,' . $input['user_id']
                    : 'required|unique:users,email',
                // 'password' => isset($input['user_id']) && $input['user_id'] == ''
                //     ? 'required'
                //     : '',
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
            $userData['phone_code'] = isset($input['phone_code']) ? $input['phone_code'] : '';
            $userData['mobile_no'] = isset($input['mobile_no']) ? $input['mobile_no'] : '';
            $userData['line_number'] = isset($input['line_number']) ? $input['line_number'] : '';
            $userData['email'] = isset($input['email']) ? $input['email'] : '';
            $userData['hourly_price'] = isset($input['hourly_price']) ? $input['hourly_price'] : '';
            $userData['ward'] = isset($input['ward']) ? $input['ward'] : '';

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
                // $userData['password'] = Hash::make($input['password']);
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

            $getUserDetails = User::select('users.*', 'coun.name as country_name', 's.name as state_name', 'c.name as city_name')->where('users.id', $input['user_id'])
                                    ->with('escortImages')
                                    ->with('escortVideos')
                                    ->leftJoin('countries as coun', 'coun.id', 'users.country')
                                    ->leftJoin('states as s', 's.id', 'users.state')
                                    ->leftJoin('cities as c', 'c.id', 'users.city')
                                    ->first();

            return $this->sendResponse($getUserDetails, 'Profile get successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    function getDatesBetween($startDate, $endDate) {
        $dates = array();
    
        $currentDate = strtotime($startDate);
        $endDate = strtotime($endDate);
    
        while ($currentDate <= $endDate) {
            $dates[] = date('Y-m-d', $currentDate);
            $currentDate = strtotime('+1 day', $currentDate);
        }
    
        return $dates;
    }
    
    public function escortAvailabilityAdd(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $input = $request->all();

            $availableDate = date('Y-m-d', strtotime($input['from_date']));
            $fromDate = date('Y-m-d', strtotime($input['from_date']));
            $toDate = date('Y-m-d', strtotime($input['to_date']));
            // $availableSlot = $input['available_slot'];
            $userId = $input['user_id'];
            $timeIds = $input['time_ids'] ? explode(',', $input['time_ids']) : [];

            $timeArr = [];

            foreach ($timeIds as $key => $id) {
                $timeArr[] = $this->globalTimesArray[((int)$id-1)];
            }

            $allDates = $this->getDatesBetween($fromDate, $toDate);

            // $explodeSlot = [];
            // if($availableSlot)
            // {
            //     $explodeSlot = explode(',', $availableSlot);
            // }

            // $timeArr = [];
            // for ($i=0; $i < count($explodeSlot); $i++) 
            // {
            //     $timeArr[] = date('H:i:s', strtotime($explodeSlot[$i]));
            // }

            EscortsAvailability::where('user_id', $userId)
                                ->whereIn('available_date', $allDates)
                                ->whereNotIn('available_time', $timeArr)
                                ->delete();

            for ($i=0; $i < count($timeArr); $i++) 
            {
                foreach ($allDates as $key => $dates) 
                {
                    $availableArr = [];
                    $availableArr['user_id'] = $userId;
                    $availableArr['available_date'] = $dates;
                    $availableArr['available_time'] = date('H:i:s', strtotime($timeArr[$i]));
                    $availableArr['start_time'] = date('H:i:s', strtotime($timeArr[$i]));
                    $availableArr['end_time'] = date('H:i:s', strtotime($timeArr[$i]) + 3600);
        
                    $checkAvailable = EscortsAvailability::where('user_id', $userId)
                                                        ->where('available_date', $dates)
                                                        ->where('available_time', date('H:i:s', strtotime($timeArr[$i])))
                                                        ->first();
                    
                    if($checkAvailable)
                    {
                        EscortsAvailability::where('user_id', $userId)
                                            ->where('available_date', $dates)
                                            ->where('available_time', date('H:i:s', strtotime($timeArr[$i])))
                                            ->update($availableArr);
                    }
                    else
                    {
                        EscortsAvailability::create($availableArr);
                    }                
                }
            }

            $getAvailability = EscortsAvailability::where('user_id', $userId)->where('available_date', $availableDate)->get();

            return $this->sendResponse($getAvailability, 'Availability added successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function escortAvailabilityList(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $userId = $input['user_id'];

            $getAllDate = EscortsAvailability::where('user_id', $userId)
                                                ->where('available_date', '>=', date('Y-m-d'))
                                                ->orderBy('available_date', 'asc')
                                                ->groupBy('available_date')
                                                ->pluck('available_date');

            $responseArr = [];
            foreach ($getAllDate as $key => $value) 
            {
                $availableList = EscortsAvailability::where('user_id', $userId)
                                                ->where('available_date', $value)
                                                ->orderBy('available_time', 'asc')
                                                ->get();

                $getAllTime =  EscortsAvailability::selectRaw("TIME_FORMAT(available_time, '%H:%i') as formatted_time")
                                                ->where('user_id', $userId)
                                                ->where('available_date', $value)
                                                ->orderBy('available_time', 'asc')
                                                ->pluck('formatted_time');

                $findTime = [];
                foreach ($getAllTime as $timeKey => $values) 
                {
                    foreach ($this->globalIdsArray as $keys => $id) {
                        if($values == $this->globalTimesArray[$keys])
                        {
                            $findTime[] = [
                                'id' => $id,
                                'time' => $this->globalTimesArray[$keys],
                                'from_time' => $this->globalTimesArray[$keys],
                                'to_time' => date('H:i', strtotime($this->globalTimesArray[$keys]) + 3600),
                            ];
                        }
                    }
                }

                usort($findTime, function($a, $b) {
                    return strcmp($a['id'], $b['id']);
                });

                $newObj = new \StdClass();
                $newObj->date = $value;
                $newObj->slots = $findTime;

                $responseArr[] = $newObj;
            }

            // $availableList = EscortsAvailability::where('user_id', $userId)
            //                                     ->where('available_date', '>=', date('Y-m-d'))
            //                                     ->orderBy('available_date', 'asc')
            //                                     ->orderBy('available_time', 'asc')
            //                                     ->get();
            

            return $this->sendResponse($responseArr, 'Availability list get successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function availabilityDelete(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required',
                'available_date' => 'required'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            EscortsAvailability::where('user_id', $input['user_id'])->where('available_date', date('Y-m-d', strtotime($input['available_date'])))->delete();

            return $this->sendResponse($input['user_id'], 'Availability delete successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
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

    public function getTiming(Request $request)
    {
        try {

            $input = $request->all();

            $combinedArray = [];

            foreach ($this->globalIdsArray as $key => $id) {
                $combinedArray[] = [
                    'id' => $id,
                    'time' => $this->globalTimesArray[$key],
                    'from_time' => $this->globalTimesArray[$key],
                    'to_time' => date('H:i', strtotime($this->globalTimesArray[$key]) + 3600),
                ];
            }

            return $this->sendResponse($combinedArray, 'Get timing successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}

