<form class="row g-3" method="POST" action="{{ route('admin.save.string') }}" id="profileForm" enctype='multipart/form-data'>
    {!! csrf_field() !!}
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" style="width: 30%">Key</th>
                <th scope="col" style="width: 60%">Value</th>
                <th scope="col" style="width: 60%">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row"></th>
                <td>
                    <textarea class="form-control" name="lang_value[]" id="lang_value"></textarea>
                    <input type="hidden" name="lang_id[]" id="lang_id" value=""/>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center">
                        <button name="clear-button" id="save-button" class="btn btn-primary">Update</button>
                    </div>
                </td>
            </tr>
            @if(count($languageList) > 0)
                @foreach($languageList as $key => $lang)
                    @php
                        $langId = Crypt::encryptString($lang->id);
                    @endphp
                    <tr>
                        <td>{{ $lang->lang_key }}</td>
                        <td>
                            <textarea class="form-control" name="lang_value[]" id="lang_value">{{ $lang->lang_value }}</textarea>
                            <input type="hidden" name="lang_id[]" id="lang_id" value="{{ $lang->id }}"/>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center">
                                <div class="me-2" role="button" onclick="deleteLanguage('{{ $langId }}')" title="Delete"><i class="bi bi-trash"></i></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</form>
{!! $languageList->links('pagination') !!}