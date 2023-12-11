<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Models\User;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use App\Models\ProfileImages;
use App\Models\EscortsBookings;
use App\Models\EscortsAvailability;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller as Controller;
use App\Services\ApiClientService;

use Hash;
use Session;
use Config;
 
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

            $url = config('constants.ADD_UPDATE_ESCORTS_PROFILE');
            
            $apiResponse = ApiClientService::apiCall($url, $input);

            if ($apiResponse && $apiResponse->success) {
                $getUserDetails = $apiResponse->data;

                if ($files = $request->file('image_upload')) {
            
                    foreach ($files as $file) {
                        $path = 'public/uploads/escorts_profile/';
                
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->move($path, $filename);
                
                        $img = 'public/uploads/escorts_profile/' . $filename;
                
                        $imgData = [];
                        $imgData['user_id'] = $getUserDetails->id;
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
                        $videoData['user_id'] = $getUserDetails->id;
                        $videoData['type'] = 'video';
                        $videoData['file_path'] = $img;
    
                        ProfileImages::create($videoData);
                    }
                }

                return redirect()->route('admin.show.escorts')->withSuccess($apiResponse->message);
            }
            else
            {
                return redirect()->back()->withError($apiResponse->message)->withInput($request->all());
            }

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
        $input = $request->all();

        $escortsList = [];

        $search = $input['search'];

        if(isset($search) && $search != '')
        {
            $escortsList = User::where('user_role', 'escorts')
                            ->where(function ($query) use ($search) {
                                $query->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('email', 'like', '%' . $search . '%');
                            })
                            ->paginate(15);
        }
        else
        {
            $escortsList = User::where('user_role', 'escorts')->paginate(15);
        }

        return view('admin.escorts.list', compact('escortsList'));
    }

    public function edit($userId)
    {
        $userId = Crypt::decryptString($userId);

        $url = config('constants.GET_ESCORTS_PROFILE');
        $parms = array('user_id' => $userId);
        
        $apiResponse = ApiClientService::apiCall($url, $parms);

        if ($apiResponse && $apiResponse->success) {
            $getUserDetails = $apiResponse->data;

            $countryData = Countries::get();
            return view('admin.escorts.add', compact('countryData', 'getUserDetails'));
        }
        else
        {
            return redirect()->back()->withError($apiResponse->message);
        }
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

        return response()->json(['success' => true, 'message' => 'Escorts profile deleted successfully.']);
    }

    public function changeStatus(Request $request)
    {
        $input = $request->all();

        $userId = Crypt::decryptString($input['user_id']);

        User::where('id', $userId)->update(['status' => $input['status']]);

        return response()->json(['success' => true, 'message' => 'Status change successfully.']);
    }

    public function calendarEvent($userId)
    {
        return view('admin.escorts.calendar', compact('userId'));
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

    public function availabilityAdd(Request $request)
    {
        $input = $request->all();

        $availableDate = date('Y-m-d', strtotime($input['date']));
        $fromDate = date('Y-m-d', strtotime($input['from_date']));
        $toDate = date('Y-m-d', strtotime($input['to_date']));
        $availableSlot = $input['available_slot'];
        $userId = Crypt::decryptString($input['user_id']);

        $allDates = $this->getDatesBetween($fromDate, $toDate);

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
            foreach ($allDates as $key => $dates) 
            {
                $availableArr = [];
                $availableArr['user_id'] = $userId;
                $availableArr['available_date'] = $dates;
                $availableArr['available_time'] = date('H:i:s', strtotime($explodeSlot[$i]));
                $availableArr['start_time'] = date('H:i:s', strtotime($explodeSlot[$i]));
                $availableArr['end_time'] = date('H:i:s', strtotime($explodeSlot[$i]) + 3600);
    
                $checkAvailable = EscortsAvailability::where('user_id', $userId)
                                                    ->where('available_date', $dates)
                                                    ->where('available_time', date('H:i:s', strtotime($explodeSlot[$i])))
                                                    ->first();
                
                if($checkAvailable)
                {
                    EscortsAvailability::where('user_id', $userId)
                                        ->where('available_date', $dates)
                                        ->where('available_time', date('H:i:s', strtotime($explodeSlot[$i])))
                                        ->update($availableArr);
                }
                else
                {
                    EscortsAvailability::create($availableArr);
                }                
            }
        }
        return response()->json(['success' => true, 'message' => 'Escorts availability created successfully.']);
    }

    public function availabilityList(Request $request)
    {
        $input = $request->all();

        $userId = Crypt::decryptString($input['user_id']);

        $availableList = EscortsAvailability::where('user_id', $userId)->where('available_date', '>=', date('Y-m-d'))->orderBy('available_time', 'asc')->get();

        $responseArr = [];
        foreach ($availableList as $key => $value) {
            $event = [
                'occasion' => '',
                'invited_count' => date('h:i A', strtotime($value['available_time'])),
                'year' => (int)date('Y', strtotime($value['available_date'])),
                'month' => (int)date('m', strtotime($value['available_date'])),
                'day' => (int)date('d', strtotime($value['available_date'])),
                'available_date' => date('Y-m-d', strtotime($value['available_date'])),
                'cancelled' => false,
            ];
        
            $responseArr['events'][] = $event;
        }

        return response()->json(['success' => true, 'data' => json_encode($responseArr), 'message' => 'Escorts availability list successfully.']);
    }

    public function viewProfile($userId)
    {
        $userId = Crypt::decryptString($userId);

        $url = config('constants.GET_ESCORTS_PROFILE');
        $parms = array('user_id' => $userId);
        
        $apiResponse = ApiClientService::apiCall($url, $parms);

        if ($apiResponse && $apiResponse->success) {
            $getUserDetails = $apiResponse->data;

            $countryData = Countries::get();
            return view('admin.escorts.view', compact('countryData', 'getUserDetails'));
        }
        else
        {
            return redirect()->back()->withError($apiResponse->message);
        }
    }

    public function bookingList(Request $request)
    {
        $input = $request->all();

        $getBookingList = [];

        $search = $input['search'];

        if(isset($search) && $search != '' || $input['booking_type'] != "")
        {
            $getBookingList = EscortsBookings::with(['bookingSlots', 'getusers', 'getescorts'])
                                            ->where(function ($query) use ($search) {
                                                // Search on the 'mobile_no' column of 'getusers'
                                                $query->whereHas('getusers', function ($subQuery) use ($search) {
                                                    $subQuery->where('mobile_no', 'like', '%' . $search . '%');
                                                })
                                                // Search on the 'name' column of 'getescorts'
                                                ->orWhereHas('getescorts', function ($subQuery) use ($search) {
                                                    $subQuery->where('name', 'like', '%' . $search . '%');
                                                });
                                            })
                                            ->where('escort_id', $input['escort_id'])
                                            ->where(function ($query) use ($input) {
                                                // Filter past bookings
                                                if ($input['booking_type'] == 'past') {
                                                    $query->whereHas('bookingSlots', function ($subQuery) {
                                                        $subQuery->where('booking_date', '<', date('Y-m-d'));
                                                    });
                                                }
                                                // Filter upcoming bookings
                                                if ($input['booking_type'] == 'upcoming') {
                                                    $query->whereHas('bookingSlots', function ($subQuery) {
                                                        $subQuery->where('booking_date', '>=', date('Y-m-d'));
                                                    });
                                                }
                                            })
                                            ->orderBy('id', 'desc')
                                            ->paginate(15);
        }
        else
        {
            $getBookingList = EscortsBookings::with(['bookingSlots', 'getusers', 'getescorts'])->orderBy('id', 'desc')->paginate(15);
        }

        return view('admin.escorts.booking_list', compact('getBookingList'));
    }
}

