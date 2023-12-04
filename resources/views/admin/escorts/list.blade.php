<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Mobile No.</th>
            <th scope="col">Age</th>
            <th scope="col">Status</th>
            <th scope="col">Created At</th>
            <th scope="col" class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($escortsList) > 0)
            @foreach($escortsList as $key => $user)
                @php
                    $userId = Crypt::encryptString($user->id);
                @endphp
                <tr>
                    <th scope="row">{{ ($key + 1)  }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->mobile_no }}</td>
                    <td>{{ $user->age }}</td>
                    <td>
                        <div>
                            <select class="form-control change-status" name="change_status" id="change_status" data-id="{{ $userId }}" data-status="{{ $user->status }}">
                                <option value="1" @if($user->status == '1') {{ 'selected' }} @endif>Active</option>
                                <option value="0" @if($user->status == '0') {{ 'selected' }} @endif>In-Active</option>
                            </select>
                        </div>
                    </td>
                    <td>{{ date('Y-m-d', strtotime($user->created_at)) }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center">
                            <div class="me-2" role="button"><a href="{{ route('admin.edit.escorts', ['user_id' => $userId]) }}" title="Edit"><i class="bi bi-pencil-square" style="color: #000000;"></i></a></div>
                            <div class="me-2" role="button" onclick="deleteEscortsProfile('{{ $userId }}')" title="Delete"><i class="bi bi-trash"></i></div>
                            <div class="me-2" role="button"><a href="{{ route('admin.calendar.event', ['user_id' => $userId]) }}" title="Availability"><i class="bi bi-calendar" style="color: #000000;"></i></a></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="text-center">
                <td colspan="10">Escorts list not found.</td>
            </tr>
        @endif
    </tbody>
</table>
{!! $escortsList->links('pagination') !!}