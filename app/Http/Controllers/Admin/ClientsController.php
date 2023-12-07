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
 
class ClientsController extends Controller
{
    public function index(): View
    {
        return view('admin.clients.clients');
    }

    public function listClients(Request $request)
    {
        $input = $request->all();

        $clientsList = [];

        $search = $input['search'];

        $clientsList = User::where('user_role', 'client')
                        ->when(isset($search) && $search != '', function ($query) use ($search) {
                            $query->where('mobile_no', 'like', '%' . $search . '%');
                        })
                        ->orderBy('id', 'desc')
                        ->paginate(15);

        foreach ($clientsList as $key => $value) 
        {
            $getPastBooking = EscortsBookings::with(['bookingSlots', 'getusers', 'getescorts'])
                                        ->where(function ($query) {
                                            $query->whereHas('bookingSlots', function ($subQuery) {
                                                $subQuery->where('booking_date', '<', date('Y-m-d'));
                                            });
                                        })
                                        ->where('user_id', $value->id)
                                        ->count();
            $getUpcomingBooking = EscortsBookings::with(['bookingSlots', 'getusers', 'getescorts'])
                                        ->where(function ($query) {
                                            $query->whereHas('bookingSlots', function ($subQuery) {
                                                $subQuery->where('booking_date', '>=', date('Y-m-d'));
                                            });
                                        })
                                        ->where('user_id', $value->id)
                                        ->count();

            $value->past_booking = $getPastBooking;
            $value->upcoming_booking = $getUpcomingBooking;
        }

        return view('admin.clients.list', compact('clientsList'));
    }

    public function deleteClient(Request $request)
    {
        $input = $request->all();

        $userId = Crypt::decryptString($input['user_id']);

        User::where('id', $userId)->delete();

        return response()->json(['success' => true, 'message' => 'Client deleted successfully.']);
    }
}