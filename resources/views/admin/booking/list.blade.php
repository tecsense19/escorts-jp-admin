<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">User Mobile No.</th>
            <th scope="col">Escort Name</th>
            <th scope="col">Hotel Details</th>
            <th scope="col">Word</th>
            <th scope="col">Booking Date</th>
            <th scope="col">Booking Time</th>
            <th scope="col">Booking Price</th>
            <th scope="col">Created At</th>
            <th scope="col" class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($getBookingList) > 0)
            @foreach($getBookingList as $key => $booking)
                @php
                    $bookingId = Crypt::encryptString($booking->id);
                @endphp
                <tr>
                    <th scope="row">{{ ($key + 1)  }}</th>
                    <td>{{ $booking->getusers->mobile_no }}</td>
                    <td>{{ $booking->getEscorts->name }}</td>
                    <td>
                        <div class="">
                            <div><b>Hotel Name:</b> {{ $booking->hotel_name }}</div>
                            <div><b>Room No.:</b> {{ $booking->room_number }}</div>
                        </div>
                    </td>
                    <td>{{ $booking->selected_word }}</td>
                    <td>{{ $booking->bookingSlots[0]->booking_date }}</td>
                    <td>
                        <div>
                            @if(count($booking->bookingSlots) > 0)
                                @foreach($booking->bookingSlots as $slot)
                                    <div>{{ date('H:i', strtotime($slot->booking_time)) }} - {{ date('H:i A', strtotime($slot->booking_time . ' +1 hour')) }}</div>
                                @endforeach
                            @endif
                        </div>
                    </td>
                    <td>{{ $booking->booking_price }}</td>
                    <td>{{ date('Y-m-d', strtotime($booking->created_at)) }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center">
                            <div class="me-2" role="button" onclick="deleteBooking('{{ $bookingId }}')"><i class="bi bi-trash"></i></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="text-center">
                <td colspan="10">Booking list not found.</td>
            </tr>
        @endif
    </tbody>
</table>
{!! $getBookingList->links('pagination') !!}