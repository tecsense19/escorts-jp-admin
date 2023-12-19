<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Models\User;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use App\Models\EscortsBookings;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller as Controller;

use Hash;
use Session;
 
class SubAdminController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function index(): View
    {
        return view('admin.subadmin.subadmin');
    }

    public function create(): View
    {
        return view('admin.subadmin.addedit');
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $rules = [
                'full_name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $input['user_id'], // 'unique' rule with 'ignore' constraint
            ];
            
            $messages = [
                'email.email' => 'Please enter valid email address.',
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                // Validation failed, handle errors as needed
                return redirect()->back()->withError($validator->errors()->first())->withInput();
            }
            
            $permissionArr = $input['user_permissions'];
            
            $userData = [];
            $userData['name'] = isset($input['full_name']) ? $input['full_name'] : '';
            $userData['email'] = isset($input['email']) ? $input['email'] : '';
            $userData['user_permissions'] = isset($input['user_permissions']) ? implode(",", $permissionArr) : '';
            $userData['user_role'] = 'subadmin';
            
            $message = '';
            
            if(isset($input['user_id']) && $input['user_id'] != "")
            {
                User::where('id', $input['user_id'])->update($userData);
                
                $message = 'Subadmin updated successfully.';
            }
            else
            {
                $userData['password'] = Hash::make($input['password']);
                User::create($userData);

                $message = 'Subadmin created successfully.';
            }

            return redirect()->route('admin.subadmin')->withSuccess($message);

        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function list(Request $request)
    {
        $input = $request->all();

        $subAdminList = [];

        $search = $input['search'];

        if(isset($search) && $search != '')
        {
            $subAdminList = User::where('user_role', 'subadmin')
                            ->where(function ($query) use ($search) {
                                $query->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('email', 'like', '%' . $search . '%');
                            })
                            ->paginate(15);
        }
        else
        {
            $subAdminList = User::where('user_role', 'subadmin')->paginate(15);
        }

        return view('admin.subadmin.list', compact('subAdminList'));
    }

    public function edit($userId)
    {
        $userId = Crypt::decryptString($userId);

        $getUserDetails = User::where('id', $userId)->first();
        
        return view('admin.subadmin.addedit', compact('getUserDetails'));
    }

    public function delete(Request $request)
    {
        $input = $request->all();

        $userId = Crypt::decryptString($input['user_id']);

        User::where('id', $userId)->delete();

        return response()->json(['success' => true, 'message' => 'Subadmin deleted successfully.']);
    }
}


