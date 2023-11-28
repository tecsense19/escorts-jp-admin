<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Mobile No.</th>
            <th scope="col">Age</th>
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
                    <td>{{ date('Y-m-d', strtotime($user->created_at)) }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center">
                            <div class="me-2" role="button"><i class="bi bi-pencil-square"></i></div>
                            <div class="me-2" role="button" onclick="deleteEscortsProfile('{{ $userId }}')"><i class="bi bi-trash"></i></div>
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