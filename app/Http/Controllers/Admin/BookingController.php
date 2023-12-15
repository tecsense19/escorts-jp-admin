<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Models\User;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use App\Models\ProfileImages;
use App\Models\EscortsAvailability;
use App\Models\EscortsBookings;
use App\Models\BookingSlot;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller as Controller;

use Hash;
use Session;
 
class BookingController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function index(): View
    {
        return view('admin.booking.booking');
    }

    public function bookingList(Request $request)
    {
        $input = $request->all();

        $getBookingList = [];

        $search = $input['search'];

        if(isset($search) && $search != '')
        {
            $getBookingList = EscortsBookings::join('users', 'escorts_bookings.user_id', '=', 'users.id')
                                            ->with(['bookingSlots', 'getusers', 'getescorts'])
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
                                            ->orderBy('escorts_bookings.id', 'desc')
                                            ->paginate(15);
        }
        else
        {
            $getBookingList = EscortsBookings::join('users', 'escorts_bookings.user_id', '=', 'users.id')
                                            ->with(['bookingSlots', 'getusers', 'getescorts'])
                                            ->orderBy('escorts_bookings.id', 'desc')->paginate(15);
        }

        return view('admin.booking.list', compact('getBookingList'));
    }

    public function bookingDelete(Request $request)
    {
        $input = $request->all();

        $bookingId = Crypt::decryptString($input['booking_id']);
        EscortsBookings::where('id', $bookingId)->delete();

        return response()->json(['success' => true, 'message' => 'Booking deleted successfully.']);
    }
}

