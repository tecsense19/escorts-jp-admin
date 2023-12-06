<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\States;
use App\Models\Cities;
use App\Models\Countries;
use App\Models\BookingSlot;
use App\Models\ProfileImages;
use App\Models\EscortsBookings;
use App\Models\FavouriteEscorts;
use App\Models\EscortsAvailability;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController as BaseController;
use Validator;

class HomeController extends BaseController
{
    public function sentOtp(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'mobile_no' => 'required|numeric'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $userArr = [];
            $userArr['mobile_no'] = $input['mobile_no'];
            $userArr['user_role'] = 'client';
            $userArr['mobile_otp'] = 123456;

            $checkUser = User::where('mobile_no', $input['mobile_no'])->first();
            $lastId = $input['mobile_no'];
            if($checkUser)
            {
                User::where('mobile_no', $input['mobile_no'])->update($userArr);
            }
            else
            {
                User::create($userArr);
            }

            $userDetails = User::where('mobile_no', $input['mobile_no'])->first();

            return $this->sendResponse($userDetails, 'Escorts list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    
    public function verifyOtp(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'mobile_no' => 'required|string',
                'mobile_otp' => 'required|string'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $userDetails = User::where('mobile_no', $input['mobile_no'])->where('mobile_otp', $input['mobile_otp'])->first();
            if($userDetails)
            {
                User::where('id', $userDetails->id)->update(['mobile_otp' => '']);
                return $this->sendResponse($userDetails, 'Login successfully.');
            }
            else
            {
                return $this->sendError('Invalid otp or mobile number.');
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

            $getEscortsList = User::select('users.*')
                    ->selectRaw('(CASE WHEN favourite_escorts.escort_id IS NOT NULL THEN 1 ELSE 0 END) AS is_favourite')
                    ->leftJoin('favourite_escorts', function ($join) use ($userId) {
                        $join->on('users.id', '=', 'favourite_escorts.escort_id')
                            ->where('favourite_escorts.user_id', $userId);
                    })
                    ->with('escortImages', 'escortVideos')
                    ->when($filter == 'is_favourite', function ($query) use ($userId) {
                        $query->where('favourite_escorts.user_id', $userId);
                    })
                    ->where('users.user_role', 'escorts')
                    ->get();

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
                                                    ->whereNotIn('available_time', $getBookedSlot)
                                                    ->get()->toArray();

            $responseArr = [];
            if(isset($input['slot_duration']) && $input['slot_duration'] == 2)
            {
                for ($i=0; $i < count($getAvailability); $i += 2) 
                {
                    if(isset($getAvailability[$i+1]))
                    {
                        $newObj = new \StdClass();
                        $newObj->id = $getAvailability[$i]['id'];
                        $newObj->ids = (int)$getAvailability[$i]['id'] .','.(int)$getAvailability[$i+1]['id'];
                        $newObj->user_id = $getAvailability[$i]['user_id'];
                        $newObj->available_date = $getAvailability[$i]['available_date'];
                        $newObj->available_time = $getAvailability[$i]['available_time'];

                        $slotObj = new \StdClass();
                        $slotObj->from_time = date('H:i', strtotime($getAvailability[$i]['available_time']));
                        $slotObj->to_time = date('H:i', (strtotime($getAvailability[$i]['available_time']) + 3600));

                        $newObj->slot_arr[] = $slotObj;

                        $slotObj = new \StdClass();
                        $slotObj->from_time = date('H:i', strtotime($getAvailability[$i+1]['available_time']));
                        $slotObj->to_time = date('H:i', (strtotime($getAvailability[$i+1]['available_time']) + 3600));

                        $newObj->slot_arr[] = $slotObj;

                        $responseArr[] = $newObj;
                    }
                }
            }
            else
            {
                foreach ($getAvailability as $key => $value) 
                {
                    $newObj = new \StdClass();
                    $newObj->id = $value['id'];
                    $newObj->ids = $value['id'];
                    $newObj->user_id = $value['user_id'];
                    $newObj->available_date = $value['available_date'];
                    $newObj->available_time = $value['available_time'];

                    $slotObj = new \StdClass();
                    $slotObj->from_time = date('H:i', strtotime($value['available_time']));
                    $slotObj->to_time = date('H:i', (strtotime($value['available_time']) + 3600));

                    $newObj->slot_arr[] = $slotObj;

                    $responseArr[] = $newObj;
                }
            }
            
            return $this->sendResponse($responseArr, 'Escorts are available for the selected date.');

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
                'room_number' => 'required|string',
                'selected_word' => 'required|string',
                'booking_date' => 'required|date_format:Y-m-d',
                'slot_ids' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $checkEscorts = User::where('id', $input['escort_id'])->where('user_role', 'escorts')->first();
            if($checkEscorts)
            {
                $checkUser = User::where('id', $input['escort_id'])->where('user_role', 'escorts')->first();
                if($checkUser)
                {
                    $bookingArr = [];
                    $bookingArr['escort_id'] = $input['escort_id'];
                    $bookingArr['user_id'] = $input['user_id'];
                    $bookingArr['hotel_name'] = $input['hotel_name'];
                    $bookingArr['room_number'] = $input['room_number'];
                    $bookingArr['selected_word'] = $input['selected_word'];
                    $bookingArr['hourly_price'] = $checkEscorts->hourly_price;
                    $bookingArr['booking_price'] = ($checkEscorts->hourly_price * count(explode(',', $input['slot_ids'])));
        
                    $lastBooking = EscortsBookings::create($bookingArr);
        
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
}

