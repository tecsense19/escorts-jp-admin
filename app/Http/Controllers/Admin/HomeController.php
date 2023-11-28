<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Models\User;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller as Controller;

use Hash;
use Session;
 
class HomeController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function index(): View
    {
        return view('admin.dashboard.dashboard');
    }

    public function profile(): View
    {
        return view('admin.profile.profile');
    }

    public function profileUpdate(Request $request)
    {
        try {
            $input = $request->all();

            $rules = [
                'full_name' => 'required|string',
                'mobile_no' => 'required|numeric|unique:users,mobile_no,' . $input['user_id'], // 'unique' rule with 'ignore' constraint
                'country' => 'required|string',
            ];
            
            $messages = [
                'mobile_no.numeric' => 'The mobile number must contain only numeric characters.',
                'mobile_no.unique' => 'The mobile number has already been taken.',
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                // Validation failed, handle errors as needed
                return redirect()->back()->withError($validator->errors()->first())->withInput();
            }

            $userData = [];
            $userData['name'] = isset($input['full_name']) ? $input['full_name'] : '';
            $userData['description'] = isset($input['about']) ? $input['about'] : '';
            $userData['country'] = isset($input['country']) ? $input['country'] : '';
            $userData['address'] = isset($input['address']) ? $input['address'] : '';
            $userData['mobile_no'] = isset($input['mobile_no']) ? $input['mobile_no'] : '';

            if($file = $request->file('profile_pic'))
            {
                $path = 'public/uploads/user/';

                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($path, $filename);

                $img = 'public/uploads/user/' . $filename;
                
                $userData['profile_pic'] = $img;

                if($input['user_id'])
                {
                    $getUserDetails = User::where('id', $input['user_id'])->first();
                    if ($getUserDetails) {
                        $proFilePath = $getUserDetails->profile_pic;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                }
            }

            User::where('id', $input['user_id'])->update($userData);

            return redirect()->back()->withSuccess('Profile updated successfully.');

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

    public function stateList(Request $request)
    {
        $input = $request->all();

        $getStateList = States::where('country_id', $input['country_id'])->get();

        $html = '<option value="">Select State</option>';
        foreach ($getStateList as $key => $value) 
        {
            $html .= '<option value="'.$value->id.'">'.$value->name.'</option>';
        }

        return response()->json(['success' => true, 'states' => $html]);
    }
    
    public function cityList(Request $request)
    {
        $input = $request->all();

        $getStateList = Cities::where('state_id', $input['state_id'])->get();

        $html = '<option value="">Select City</option>';
        foreach ($getStateList as $key => $value) 
        {
            $html .= '<option value="'.$value->id.'">'.$value->name.'</option>';
        }

        return response()->json(['success' => true, 'cities' => $html]);
    }
}