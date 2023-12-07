<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">User Mobile No.</th>
            <th scope="col">Booking</th>
            <th scope="col">Created At</th>
            <th scope="col" class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($clientsList) && count($clientsList) > 0)
            @foreach($clientsList as $key => $client)
                @php
                    $clientId = Crypt::encryptString($client->id);
                @endphp
                <tr>
                    <th scope="row">{{ ($key + 1)  }}</th>
                    <td>{{ $client->mobile_no }}</td>
                    <td>
                        <div><span class="badge border-danger border-1 text-danger">Past Booking: </span><span class="badge bg-danger">{{ $client->past_booking }}</span></div>
                        <div><span class="badge border-success border-1 text-success">UpComing Booking: </span><span class="badge bg-success">{{ $client->upcoming_booking }}</span></div>
                    </td>
                    <td>{{ date('d-m-Y', strtotime($client->created_at)) }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center">
                            <div class="me-2" role="button" onclick="deleteClient('{{ $clientId }}')" title="Delete"><i class="bi bi-trash"></i></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="text-center">
                <td colspan="10">Clients list not found.</td>
            </tr>
        @endif
    </tbody>
</table>
{!! $clientsList->links('pagination') !!}