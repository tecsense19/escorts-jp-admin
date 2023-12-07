@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>All String</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">All String</li>
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
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="d-flex">
                                <input name="search" id="search" class="form-control me-2" placeholder="Search String"/>
                                <button name="clear-button" id="clear-button" class="btn btn-danger">Clear</button>
                            </div>
                            <div>
                                
                            </div>
                        </div>
                        <div class="tab-content pt-2 languageDataList">
                            
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
        languageList();

        $('body').on('click', '.pagination a', function(e) 
        {
            e.preventDefault();

            var url = $(this).attr('href');
            getPerPageLanguageList(url);
        });
    });

    function languageList()
    {
        var search = $('#search').val();
        $.ajax({
            type:'post',
            headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
            url:'{{ route("admin.language.list") }}',
            data: { search: search },
            success:function(data)
            {
                $('.languageDataList').html(data);
            }
        });
    }

    function getPerPageLanguageList(get_pagination_url) 
    {
        var search = $('#search').val();
        $.ajax({
            type:'post',
            headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
            url:get_pagination_url,
            data: { search: search },
            success:function(data)
            {
                $('.languageDataList').html(data);
            }
        });   
    }

    function deleteLanguage(id)
    {
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete this string.",
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
                    url:'{{ route("admin.language.delete") }}',
                    data: { id: id },
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
                                languageList();
                            }
                        });
                    }
                });
            }
        });
    }

    $('body').on('keyup', '#search', function (e) 
    {
        languageList();
    });

    $('body').on('click', '#clear-button', function(e) {
        $('#search').val('');
        languageList();
    });
</script>
@include('admin.layout.end')