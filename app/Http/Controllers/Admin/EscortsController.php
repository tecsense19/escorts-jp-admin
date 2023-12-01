<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Models\User;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use App\Models\ProfileImages;
use App\Models\EscortsAvailability;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller as Controller;

use Hash;
use Session;
 
class EscortsController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function index(): View
    {
        return view('admin.dashboard.dashboard');
    }

    public function create(): View
    {
        $countryData = Countries::get();
        return view('admin.escorts.add', compact('countryData'));
    }

    public function saveEscorts(Request $request)
    {
        try {
            $input = $request->all();

            if($input['user_id'])
            {
                $rules = [
                    'full_name' => 'required|string',
                    'email' => 'required|unique:users,email,' . $input['user_id'],
                    'mobile_no' => 'required|numeric|unique:users,mobile_no,' . $input['user_id'], // 'unique' rule with 'ignore' constraint
                ];
                
                $messages = [
                    'mobile_no.numeric' => 'The mobile number must contain only numeric characters.',
                    'mobile_no.unique' => 'The mobile number has already been taken.',
                ];
    
                $validator = Validator::make($input, $rules, $messages);
    
                if ($validator->fails()) {
                    // Validation failed, handle errors as needed
                    return redirect()->back()->withError($validator->errors()->first())->withInput($request->all());
                }
            }
            else
            {
                $rules = [
                    'full_name' => 'required|string',
                    'email' => 'required|unique:users,email',
                    'mobile_no' => 'required|numeric|unique:users,mobile_no', // 'unique' rule with 'ignore' constraint
                ];
                
                $messages = [
                    'mobile_no.numeric' => 'The mobile number must contain only numeric characters.',
                    'mobile_no.unique' => 'The mobile number has already been taken.',
                ];
    
                $validator = Validator::make($input, $rules, $messages);
    
                if ($validator->fails()) {
                    // Validation failed, handle errors as needed
                    return redirect()->back()->withError($validator->errors()->first())->withInput($request->all());
                }
            }

            if(isset($input['remove_img_ids']))
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
            if(isset($input['remove_video_ids']))
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

            $userData = [];
            $userData['name'] = isset($input['full_name']) ? $input['full_name'] : '';
            $userData['description'] = isset($input['about']) ? $input['about'] : '';
            $userData['age'] = isset($input['age']) ? $input['age'] : '';
            $userData['country'] = isset($input['country']) ? $input['country'] : '';
            $userData['state'] = isset($input['state']) ? $input['state'] : '';
            $userData['city'] = isset($input['city']) ? $input['city'] : '';
            $userData['address'] = isset($input['address']) ? $input['address'] : '';
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
                $userData['password'] = Hash::make(Str::random(8));
                $userData['user_role'] = 'escorts';
                
                $lastUser = User::create($userData);

                $userId = $lastUser->id;

                $message = 'Profile created successfully.';
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

            return redirect()->route('admin.show.escorts')->withSuccess($message);

        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $input = $request->all();

            $getUserDetails = User::where('id', $input['user_id'])->first();

            if($getUserDetails)
            {
                if(Hash::check($input['old_password'], $getUserDetails->password))
                {
                    $userData = [];
                    $userData['password'] = isset($input['new_password']) ? Hash::make($input['new_password']) : '';

                    User::where('id', $input['user_id'])->update($userData);

                    return redirect()->back()->withSuccess('Password updated successfully.');
                }
                else
                {
                    return redirect()->back()->withError('Current password are not match.');
                }
            }
            else
            {
                return redirect()->back()->withError('Invalid user.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function showEscorts(): View
    {
        return view('admin.escorts.escorts');
    }

    public function listEscorts(Request $request)
    {
        $escortsList = User::where('user_role', 'escorts')->paginate(15);

        return view('admin.escorts.list', compact('escortsList'));
    }

    public function edit($userId)
    {
        $userId = Crypt::decryptString($userId);
        
        $getUserDetails = User::where('id', $userId)->with('escortImages')->with('escortVideos')->first();

        $countryData = Countries::get();
        return view('admin.escorts.add', compact('countryData', 'getUserDetails'));
    }

    public function deleteEscorts(Request $request)
    {
        $input = $request->all();

        $userId = Crypt::decryptString($input['user_id']);

        $getUserDetails = User::where('id', $userId)->first();
        if ($getUserDetails) {
            $proFilePath = $getUserDetails->profile_pic;
            $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

            if (file_exists(public_path($proPath))) {
                \File::delete(public_path($proPath));
            }
        }

        $allImages = ProfileImages::where('user_id', $userId)->where('type', 'image')->get();
        foreach ($allImages as $key => $value) 
        {
            $proFilePath = $value->file_path;
            $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

            if (file_exists(public_path($proPath))) {
                \File::delete(public_path($proPath));
            }
        }

        ProfileImages::where('user_id', $userId)->where('type', 'image')->delete();

        $allVideos = ProfileImages::where('user_id', $userId)->where('type', 'video')->get();
        foreach ($allVideos as $key => $value) 
        {
            $proFilePath = $value->file_path;
            $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

            if (file_exists(public_path($proPath))) {
                \File::delete(public_path($proPath));
            }
        }

        ProfileImages::where('user_id', $userId)->where('type', 'video')->delete();

        User::where('id', $userId)->delete();

        return response()->json(['success' => true, 'message' => 'Escorts profile delete successfully.']);
    }

    public function calendarEvent($userId)
    {
        // $userId = Crypt::decryptString($userId);

        // $availableList = EscortsAvailability::where('user_id', $userId)->get();

        // $responseArr = [];
        // foreach ($availableList as $key => $value) {
        //     $event = [
        //         'occasion' => '',
        //         'invited_count' => date('h:i A', strtotime($value['available_time'])),
        //         'year' => (int)date('Y', strtotime($value['available_date'])),
        //         'month' => (int)date('m', strtotime($value['available_date'])),
        //         'day' => (int)date('d', strtotime($value['available_date'])),
        //         'cancelled' => false,
        //     ];
        
        //     $responseArr['events'][] = $event;
        // }

        // $availabilityArr = count($responseArr) > 0 ? $responseArr : new \StdClass();

        return view('admin.escorts.calendar', compact('userId'));
    }

    public function availabilityAdd(Request $request)
    {
        $input = $request->all();

        $availableDate = date('Y-m-d', strtotime($input['date']));
        $availableSlot = $input['available_slot'];
        $userId = Crypt::decryptString($input['user_id']);

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
        return response()->json(['success' => true, 'message' => 'Escorts availability created successfully.']);
    }

    public function availabilityList(Request $request)
    {
        $input = $request->all();

        $userId = Crypt::decryptString($input['user_id']);

        $availableList = EscortsAvailability::where('user_id', $userId)->where('available_date', '>=', date('Y-m-d'))->get();

        $responseArr = [];
        foreach ($availableList as $key => $value) {
            $event = [
                'occasion' => '',
                'invited_count' => date('h:i A', strtotime($value['available_time'])),
                'year' => (int)date('Y', strtotime($value['available_date'])),
                'month' => (int)date('m', strtotime($value['available_date'])),
                'day' => (int)date('d', strtotime($value['available_date'])),
                'cancelled' => false,
            ];
        
            $responseArr['events'][] = $event;
        }

        return response()->json(['success' => true, 'data' => json_encode($responseArr), 'message' => 'Escorts availability list successfully.']);
    }
}