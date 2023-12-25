<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\States;
use App\Models\Cities;
use App\Models\Countries;
use App\Models\BookingSlot;
use App\Models\ProfileImages;
use App\Models\LanguageString;
use App\Models\EscortsBookings;
use App\Models\FavouriteEscorts;
use App\Models\EscortsAvailability;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController as BaseController;
use DB;
use Validator;

use Twilio\Rest\Client;

class HomeController extends BaseController
{
    private $token;
    private $twilio_sid;
    private $twilio_verify_sid;
    private $twilio;

    /**
     * Constructor method
     */
    public function __construct()
    {
        // Initialize private variable in the constructor
        $this->token = getenv("TWILIO_AUTH_TOKEN");
        $this->twilio_sid = getenv("TWILIO_SID");
        $this->twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $this->twilio = new Client($this->twilio_sid, $this->token);
    }

    public function sentOtp(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'phone_code' => 'required|string',
                'mobile_no' => 'required|numeric'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $verification = $this->twilio->verify->v2->services($this->twilio_verify_sid)->verifications->create($input['phone_code'] . $input['mobile_no'], "sms");

            if($verification->status == 'pending')
            {
                $userArr = [];
                $userArr['phone_code'] = $input['phone_code'];
                $userArr['mobile_no'] = $input['mobile_no'];
                $userArr['user_role'] = 'client';
                $userArr['mobile_otp'] = 123456;

                $checkUser = User::where('mobile_no', $input['mobile_no'])->first();
                $lastId = $input['mobile_no'];
                if($checkUser)
                {
                    $userArr['user_role'] = $checkUser ? $checkUser->user_role : 'client' ;
                    User::where('mobile_no', $input['mobile_no'])->update($userArr);
                }
                else
                {
                    User::create($userArr);
                }
                
                $userDetails = User::where('mobile_no', $input['mobile_no'])->first();
                
                return $this->sendResponse($userDetails, 'OTP sent successfully.');
            }
            else
            {
                return $this->sendError($verification->message);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    
    public function verifyOtp(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'phone_code' => 'required|string',
                'mobile_no' => 'required|string',
                'mobile_otp' => 'required|string'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $verification_check = $this->twilio->verify->v2->services($this->twilio_verify_sid)->verificationChecks->create(["to" => $input['phone_code'] . $input['mobile_no'],"code" => $input['mobile_otp']]);
            
            if($verification_check->status == 'approved')
            {
                $userDetails = User::where('mobile_no', $input['mobile_no'])->first();
                if($userDetails)
                {
                    $updateData = [];
                    
                    if(isset($input['latitude']) && isset($input['longitude']))
                    {
                        $updateData['latitude'] = $input['latitude'];
                        $updateData['longitude'] = $input['longitude'];
                    }

                    $updateData['device_token'] = isset($input['device_token']) ? $input['device_token'] : '';
                    $updateData['mobile_otp'] = '';

                    User::where('id', $userDetails->id)->update($updateData);
                    return $this->sendResponse($userDetails, 'Login successfully.');
                }
                else
                {
                    return $this->sendError('Invalid OTP.');
                }
            }
            else
            {
                return $this->sendError('Invalid OTP.');
            }

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function escortsList(Request $request)
    {
        try {
            $input = $request->all();

            $userId = isset($input['user_id']) ? $input['user_id'] : '';
            $filter = isset($input['filter']) ? $input['filter'] : '';

            $getEscortsList = User::select('users.*', 'coun.name as country_name', 's.name as state_name', 'c.name as city_name')
                    ->selectRaw('(CASE WHEN favourite_escorts.escort_id IS NOT NULL THEN 1 ELSE 0 END) AS is_favourite')
                    ->leftJoin('favourite_escorts', function ($join) use ($userId) {
                        $join->on('users.id', '=', 'favourite_escorts.escort_id')
                            ->where('favourite_escorts.user_id', $userId);
                    })
                    ->leftJoin('countries as coun', 'coun.id', 'users.country')
                    ->leftJoin('states as s', 's.id', 'users.state')
                    ->leftJoin('cities as c', 'c.id', 'users.city')
                    ->with('escortImages', 'escortVideos')
                    ->when($filter == 'is_favourite', function ($query) use ($userId) {
                        $query->where('favourite_escorts.user_id', $userId);
                    })
                    ->where('users.user_role', 'escorts')
                    ->where('users.status', 1)
                    ->get();

            $responseArr = [];

            foreach ($getEscortsList as $key => $value) 
            {
                $checkAvailable = EscortsAvailability::where('user_id', $value->id)
                                                    ->where('available_date', date('Y-m-d'))
                                                    ->where('start_time', '>=', date('H:i:s'))
                                                    ->first();
                $value->is_available = $checkAvailable ? 1 : 0;
            }

            return $this->sendResponse($getEscortsList, 'Escorts list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getDateWiseAvailability(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'escort_id' => 'required',
                'selected_date' => 'required|date_format:Y-m-d',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $escort_id = $input['escort_id'];
            $selected_date = date('Y-m-d', strtotime($input['selected_date']));

            $getBookedSlot = BookingSlot::where('escort_id', $escort_id)
                                        ->where('booking_date', date('Y-m-d', strtotime($input['selected_date'])))
                                        ->pluck('booking_time')
                                        ->toArray();

            $getAvailability = EscortsAvailability::where('user_id', $escort_id)
                                                    ->where('available_date', $selected_date)
                                                    // ->where('start_time', '>=', date('H:i:s'))
                                                    ->whereNotIn('available_time', $getBookedSlot)
                                                    ->orderBy('available_time', 'asc')
                                                    ->get()->toArray();

            // $responseArr = [];
            // if(isset($input['slot_duration']) && $input['slot_duration'] == 2)
            // {
            //     for ($i=0; $i < count($getAvailability); $i += 2) 
            //     {
            //         if(isset($getAvailability[$i+1]))
            //         {
            //             if(date('H:i:s', (strtotime($getAvailability[$i]['available_time']) + 3600)) == date('H:i:s', strtotime($getAvailability[$i+1]['available_time'])))
            //             {
            //                 $newObj = new \StdClass();
            //                 $newObj->id = $getAvailability[$i]['id'];
            //                 $newObj->ids = (int)$getAvailability[$i]['id'] .','.(int)$getAvailability[$i+1]['id'];
            //                 $newObj->user_id = $getAvailability[$i]['user_id'];
            //                 $newObj->available_date = $getAvailability[$i]['available_date'];
            //                 $newObj->available_time = $getAvailability[$i]['available_time'];

            //                 $slotObj = new \StdClass();
            //                 $slotObj->from_time = date('H:i', strtotime($getAvailability[$i]['available_time']));
            //                 $slotObj->to_time = date('H:i', (strtotime($getAvailability[$i]['available_time']) + 3600));

            //                 $newObj->slot_arr[] = $slotObj;

            //                 $slotObj = new \StdClass();
            //                 $slotObj->from_time = date('H:i', strtotime($getAvailability[$i+1]['available_time']));
            //                 $slotObj->to_time = date('H:i', (strtotime($getAvailability[$i+1]['available_time']) + 3600));

            //                 $newObj->slot_arr[] = $slotObj;

            //                 $responseArr[] = $newObj;
            //             }
            //         }
            //     }
            // }
            // else
            // {
            //     foreach ($getAvailability as $key => $value) 
            //     {
            //         $newObj = new \StdClass();
            //         $newObj->id = $value['id'];
            //         $newObj->ids = $value['id'];
            //         $newObj->user_id = $value['user_id'];
            //         $newObj->available_date = $value['available_date'];
            //         $newObj->available_time = $value['available_time'];

            //         $slotObj = new \StdClass();
            //         $slotObj->from_time = date('H:i', strtotime($value['available_time']));
            //         $slotObj->to_time = date('H:i', (strtotime($value['available_time']) + 3600));

            //         $newObj->slot_arr[] = $slotObj;

            //         $responseArr[] = $newObj;
            //     }
            // }
            $slotDuration = $input['slot_duration'];

            $filteredSlots = collect();

            if($slotDuration == "2")
            {
                $twoHourSlots = EscortsAvailability::select(
                                    'ea1.id',
                                    'ea1.start_time',
                                    'ea2.end_time',
                                    'ea1.available_date',
                                    'ea1.available_time',
                                    'ea1.user_id'
                                )
                                ->from('escorts_availability as ea1')
                                ->join('escorts_availability as ea2', 'ea2.start_time', '=', 'ea1.end_time')
                                ->where('ea1.user_id', $escort_id)
                                ->where('ea1.available_date', $selected_date)
                                // ->where('ea1.start_time', '>=', date('H:i:s'))
                                ->whereRaw("TIMEDIFF(ea2.end_time, ea1.start_time) = '02:00:00'")
                                ->whereNotIn('ea1.start_time', $getBookedSlot)
                                ->whereNotIn('ea2.end_time', $getBookedSlot)
                                ->orderBy('ea1.start_time', 'asc')
                                ->get();

                foreach ($twoHourSlots as $index => $slot) {
                    // If it's the first slot or there's no overlap with the previous slot, add it to the filtered collection
                    if ($index === 0 || $slot->start_time >= $filteredSlots->last()['end_time']) {
                        $filteredSlots->push([
                            'id' => $slot->id,
                            'user_id' => $slot->user_id,
                            'available_date' => $slot->available_date,
                            'available_time' => $slot->available_time,
                            'start_time' => date('H:i', strtotime($slot->start_time)),
                            'end_time' => date('H:i', strtotime($slot->end_time)),
                        ]);
                    }
                }
            }
            else
            {
                foreach ($getAvailability as $key => $value) 
                {
                    $filteredSlots->push([
                        'id' => $value['id'],
                        'user_id' => $value['user_id'],
                        'available_date' => $value['available_date'],
                        'available_time' => $value['available_time'],
                        'start_time' => date('H:i', strtotime($value['start_time'])),
                        'end_time' => date('H:i', strtotime($value['end_time'])),
                    ]);
                }
            }

            return $this->sendResponse($filteredSlots, 'Escorts are available for the selected date.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function escortsBooking(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'escort_id' => 'required|numeric',
                'user_id' => 'required|numeric',
                'hotel_name' => 'required|string',
                'selected_word' => 'required|string',
                'booking_date' => 'required|date_format:Y-m-d',
                'slot_ids' => 'required|string',
                'slot_duration' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $checkEscorts = User::where('id', $input['escort_id'])->where('user_role', 'escorts')->first();
            if($checkEscorts)
            {
                $checkUser = User::where('id', $input['user_id'])->where('user_role', 'client')->first();
                if($checkUser)
                {
                    $bookingArr = [];
                    $bookingArr['escort_id'] = $input['escort_id'];
                    $bookingArr['user_id'] = $input['user_id'];
                    $bookingArr['hotel_name'] = $input['hotel_name'];
                    $bookingArr['room_number'] = '';
                    $bookingArr['selected_word'] = $input['selected_word'];
                    $bookingArr['hourly_price'] = $checkEscorts->hourly_price;
                    $bookingArr['booking_price'] = ($checkEscorts->hourly_price * count(explode(',', $input['slot_ids'])));
        
                    $lastBooking = EscortsBookings::create($bookingArr);

                    if($input['slot_duration'] == '2')
                    {
                        $firstSlot = EscortsAvailability::where('id', $input['slot_ids'])->first();
                        if($firstSlot)
                        {
                            $secondSlot = EscortsAvailability::where('available_time', date('H:i:s', (strtotime($firstSlot->available_time) + 3600)))
                                                                ->where('user_id', $input['escort_id'])
                                                                ->where('available_date', $input['booking_date'])
                                                                ->first();
                            if($secondSlot)
                            {
                                $slotIds = [$firstSlot->id, $secondSlot->id];
                                
                                $getSlot = EscortsAvailability::where('user_id', $input['escort_id'])
                                                    ->where('available_date', $input['booking_date'])
                                                    ->whereIn('id', $slotIds)
                                                    ->get();

                                foreach ($getSlot as $key => $value) 
                                {
                                    $bookSlot = [];
                                    $bookSlot['escort_id'] = $input['escort_id'];
                                    $bookSlot['booking_id'] = $lastBooking->id;
                                    $bookSlot['booking_date'] = $input['booking_date'];
                                    $bookSlot['booking_time'] = date('H:i:s', strtotime($value['available_time']));
                    
                                    BookingSlot::create($bookSlot);
                                }
                            }
                        }
                    }
                    else
                    {
                        $getSlot = EscortsAvailability::where('user_id', $input['escort_id'])
                                                    ->where('available_date', $input['booking_date'])
                                                    ->whereIn('id', explode(',', $input['slot_ids']))
                                                    ->get();

                        foreach ($getSlot as $key => $value) 
                        {
                            $bookSlot = [];
                            $bookSlot['escort_id'] = $input['escort_id'];
                            $bookSlot['booking_id'] = $lastBooking->id;
                            $bookSlot['booking_date'] = $input['booking_date'];
                            $bookSlot['booking_time'] = date('H:i:s', strtotime($value['available_time']));
            
                            BookingSlot::create($bookSlot);
                        }
                    }
        
                    return $this->sendResponse($lastBooking, 'Escorts booking confirmed successfully.');
                }
                else
                {
                    return $this->sendError('Invalid user id.');
                }
            }
            else
            {
                return $this->sendError('Invalid escort id.');
            }

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function countryList(Request $request)
    {
        try {

            $getCountryList = Countries::get();

            foreach ($getCountryList as $key => $value) 
            {
                $value->flage = url('/').'/public/flags/'.strtolower($value->code).'.png';
            }

            return $this->sendResponse($getCountryList, 'Country list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function stateList(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'country_id' => 'required'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $getStateList = States::where('country_id', $input['country_id'])->get();

            return $this->sendResponse($getStateList, 'State list get successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function cityList(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'state_id' => 'required'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $getCityList = Cities::where('state_id', $input['state_id'])->get();

            return $this->sendResponse($getCityList, 'City list get successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function bookingList(Request $request)
    {
        try {
            $input = $request->all();

            $getBookingList = EscortsBookings::with(['bookingSlots', 'getusers', 'getescorts'])
                                            ->where(function ($query) use ($input) {
                                                if (isset($input['escort_id']) && $input['escort_id'] != '') {
                                                    $query->where('escort_id', $input['escort_id']);
                                                }

                                                if (isset($input['user_id']) && $input['user_id'] != '') {
                                                    $query->where('user_id', $input['user_id']);
                                                }
                                            })
                                            ->where(function ($query) use ($input) {
                                                if ($input['filter'] == 'past') {
                                                    $query->whereHas('bookingSlots', function ($subQuery) {
                                                        $subQuery->where('booking_date', '<', date('Y-m-d'));
                                                    });
                                                }

                                                if ($input['filter'] == 'upcoming') {
                                                    $query->whereHas('bookingSlots', function ($subQuery) {
                                                        $subQuery->where('booking_date', '>=', date('Y-m-d'));
                                                    });
                                                }
                                            })
                                            ->orderBy('id', 'desc')
                                            ->get();
            foreach ($getBookingList as $key => $value) 
            {
                foreach ($value->bookingSlots as $key => $slot) 
                {
                    $slot->from_time = date('H:i', strtotime($slot->booking_time));
                    $slot->to_time = date('H:i', (strtotime($slot->booking_time) + 3600));
                }
            }

            return $this->sendResponse($getBookingList, 'Booking list get successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getAllString(Request $request)
    {
        try {
            $input = $request->all();

            $getStringList = LanguageString::get();

            // Transform the data
            $result = [];
            foreach ($getStringList as $item) {
                $result[$item['lang_key']] = $item['lang_value'];
            }

            return $this->sendResponse($result, 'All string get successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    function terms_privacypolicy(Request $request)
    {
        $result = [];
        $result['terms_condition_link'] = url('/').'/'.'termscondition/';
        $result['privacy_policy_link'] = url('/').'/'.'privacypolicy/';
        return $this->sendResponse($result, ' list get successfully.');
    }

    function updateProfile(Request $request)
    {
        try {

            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required',
                'phone_code' => 'required',
                'old_mobile_no' => 'required',
                'mobile_no' => 'required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $checkUser = User::where('id', $input['user_id'])->where('mobile_no', $input['old_mobile_no'])->first();

            if($checkUser)
            {
                $checkNewNumber = User::where('id', '!=', $input['user_id'])->where('mobile_no', $input['mobile_no'])->first();
                if(!$checkNewNumber)
                {
                    $updateArr = [];
                    $updateArr['phone_code'] = $input['phone_code'];
                    $updateArr['mobile_no'] = $input['mobile_no'];

                    User::where('id', $input['user_id'])->update($updateArr);

                    $userDetails = User::where('id', $input['user_id'])->first();

                    return $this->sendResponse($userDetails, 'Profile update successfully.');
                }
                else
                {
                    return $this->sendError('Mobile number already exists. Please enter another mobile number.');
                }
            }
            else
            {
                return $this->sendError('Invalid user old mobile no.');
            }

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    function updateLocation(Request $request)
    {
        try {

            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $updateLocationArr = [];
            $updateLocationArr['latitude'] = $input['latitude'];
            $updateLocationArr['longitude'] = $input['longitude'];

            User::where('id', $input['user_id'])->update($updateLocationArr);
            
            return $this->sendResponse($input['user_id'], 'Location updated successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}

