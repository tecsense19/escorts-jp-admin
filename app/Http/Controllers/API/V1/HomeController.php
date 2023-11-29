<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Models\ProfileImages;
use Illuminate\Http\Request;
use App\Models\DedicatedExperts;
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
                'mobile_no' => 'required|numeric|unique:users,mobile_no'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $userArr = [];
            $userArr['mobile_no'] = $input['mobile_no'];
            $userArr['user_role'] = 'client';
            $userArr['mobile_otp'] = 123456;

            $userDetails = User::create($userArr);

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

            $getEscortsList = User::with('escortImages')->with('escortVideos')->where('user_role', 'escorts')->get();

            return $this->sendResponse($getEscortsList, 'Escorts list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}