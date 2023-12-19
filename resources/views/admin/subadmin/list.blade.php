<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Full Name.</th>
            <th scope="col">Email</th>
            <th scope="col">Access</th>
            <th scope="col">Created At</th>
            <th scope="col" class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($subAdminList) && count($subAdminList) > 0)
            @foreach($subAdminList as $key => $value)
                @php
                    $userId = Crypt::encryptString($value->id);
                @endphp
                <tr>
                    <th scope="row">{{ ($key + 1)  }}</th>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->email }}</td>
                    <td>{{ $value->email }}</td>
                    <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center">
                            <div class="me-2" role="button"><a href="{{ route('admin.subadmin.edit', ['user_id' => $userId]) }}" title="Edit"><i class="bi bi-pencil-square" style="color: #000000;"></i></a></div>
                            <div class="me-2" role="button" onclick="deleteSubAdmin('{{ $userId }}')" title="Delete"><i class="bi bi-trash"></i></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="text-center">
                <td colspan="10">Subadmin list not found.</td>
            </tr>
        @endif
    </tbody>
</table>
{!! $subAdminList->links('pagination') !!}