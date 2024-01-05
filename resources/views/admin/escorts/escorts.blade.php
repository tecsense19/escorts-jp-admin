@php
    $getPermission = Auth::user()->user_permissions ? explode(",", Auth::user()->user_permissions) : [];
@endphp
@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Escorts</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Escorts</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    @include('flash-message')
    <section class="section profile">
        {!! csrf_field() !!}
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            @if(!in_array('escort', $getPermission))
                                <div class="d-flex">
                                    <input name="search" id="search" class="form-control me-2" placeholder="Search Profile"/>
                                    <button name="clear-button" id="clear-button" class="btn btn-danger">Clear</button>
                                </div>
                                <div>
                                    <a href="{{ route('admin.create.escorts') }}"><button name="clear-button" id="clear-button" class="btn btn-primary">Create New</button></a>
                                </div>
                            @endif
                        </div>
                        <div class="tab-content pt-2 table-responsive escortsDataList">

                        </div>
                        <!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- End #main -->
@include('admin.layout.footer')
<script type="text/javascript">
    $( document ).ready(function() 
    {
        escortsProfileList();

        $('body').on('click', '.pagination a', function(e) 
        {
            e.preventDefault();

            var url = $(this).attr('href');
            getPerPageEscortsProfileList(url);
        });

        $('body').on('change', '.change-status', function(e) {
            var userId = $(this).data('id');
            var currentVal = $(this).val();
            var message = $(this).data('status') ? 'In-Active' : 'Active';
            
            Swal.fire({
                title: 'Are you sure?',
                text: message + " this profile.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                confirmButtonColor: '#fe7d22',
                cancelButtonText: 'No',
                cancelButtonColor: '#d33',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type:'post',
                        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
                        url:'{{ route("admin.escorts.change.status") }}',
                        data: { user_id: userId, status: currentVal },
                        success:function(response)
                        {
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#fe7d22',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    escortsProfileList();
                                }
                            });
                        }
                    });
                }
                else
                {
                    escortsProfileList();
                }
            });
        });
    });

    function escortsProfileList()
    {
        var search = $('#search').val();
        $.ajax({
            type:'post',
            headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
            url:'{{ route("admin.escorts.list") }}',
            data: { search: search ? search : '' },
            success:function(data)
            {
                $('.escortsDataList').html(data);
            }
        });
    }

    function getPerPageEscortsProfileList(get_pagination_url) 
    {
        var search = $('#search').val();
        $.ajax({
            type:'post',
            headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
            url:get_pagination_url,
            data: { search: search ? search : '' },
            success:function(data)
            {
                $('.escortsDataList').html(data);
            }
        });   
    }

    function deleteEscortsProfile(user_id)
    {
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete this escorts profile.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            confirmButtonColor: '#fe7d22',
            cancelButtonText: 'No',
            cancelButtonColor: '#d33',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type:'post',
                    headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
                    url:'{{ route("admin.escorts.delete") }}',
                    data: { user_id: user_id },
                    success:function(response)
                    {
                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#fe7d22',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                escortsProfileList();
                            }
                        });
                    }
                });
            }
        });
    }

    $('body').on('keyup', '#search', function (e) 
    {
        escortsProfileList();
    });

    $('body').on('click', '#clear-button', function(e) {
        $('#search').val('');
        escortsProfileList();
    });
</script>
@include('admin.layout.end')