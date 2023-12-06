@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Escorts Profile</h1>
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
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <div class="d-flex flex-wrap">
                        @if(isset($getUserDetails->escort_images) && count($getUserDetails->escort_images) > 0)
                            @foreach($getUserDetails->escort_images as $key => $value)
                                <div class="col-sm-3 image-preview">
                                    <img src="{{ $value->file_path }}" alt="Profile" class="rounded-circle" style="width: 100px; height: 100px;">
                                </div>
                            @endforeach
                        @else
                            <div>
                                <img src="{{ url('/').'/public/assets/img/profile-img.jpg' }}" alt="Profile" class="rounded-circle">
                            </div>
                        @endif
                        </div>
                        <h2>{{ $getUserDetails->name }}</h2>
                        <h3>{{ ucfirst($getUserDetails->user_role) }}</h3>
                        <!-- <div class="social-links mt-2">
                            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                        </div> -->
                        <div class="profile-overview w-100">
                            <h5 class="card-title">About</h5>
                            <p class="small fst-italic">
                                {{ $getUserDetails->description }}
                            </p>
                            <h5 class="card-title">Profile Details</h5>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 label ">Full Name:</div>
                                <div class="col-lg-8 col-md-8">{{ $getUserDetails->name }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 label">Role:</div>
                                <div class="col-lg-8 col-md-8">{{ ucfirst($getUserDetails->user_role) }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 label">Country:</div>
                                <div class="col-lg-8 col-md-8">{{ $getUserDetails->country }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 label">State:</div>
                                <div class="col-lg-8 col-md-8">{{ $getUserDetails->state }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 label">City:</div>
                                <div class="col-lg-8 col-md-8">{{ $getUserDetails->city }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 label">Address:</div>
                                <div class="col-lg-8 col-md-8">{{ $getUserDetails->address }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 label">Whatsapp No.:</div>
                                <div class="col-lg-8 col-md-8">{{ $getUserDetails->mobile_no }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 label">Line No.:</div>
                                <div class="col-lg-8 col-md-8">{{ $getUserDetails->line_number }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 label">Email:</div>
                                <div class="col-lg-8 col-md-8">{{ $getUserDetails->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit">Booking</button>
                            </li>
                            <!-- <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                            </li> -->
                        </ul>
                        <div class="tab-content pt-2">
                            <div class="tab-pane fade show active profile-edit pt-3" id="profile-edit">
                                <!-- Display Data -->
                                <div class="d-flex justify-content-end align-items-center">
                                    <div class="d-flex">
                                        <select class="form-control me-2" name="booking_type" id="booking_type">
                                            <option value="all">All</option>
                                            <option value="upcoming">Upcoming</option>
                                            <option value="past">Past</option>
                                        </select>
                                        <input name="search" id="search" class="form-control me-2" placeholder="Search Booking"/>
                                        <button name="clear-button" id="clear-button" class="btn btn-danger">Clear</button>
                                    </div>
                                    <div>
                                        
                                    </div>
                                </div>
                                <div class="tab-content pt-2 bookingDataList">

                                </div>
                                <!-- End Display Data -->
                            </div>
                            <div class="tab-pane fade pt-3" id="profile-settings">
                                <!-- Settings Form -->
                                
                                <!-- End settings Form -->
                            </div>
                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                
                                <!-- End Change Password Form -->
                            </div>
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
<script>
    var escort_id = "{{ $getUserDetails->id }}";
    $(document).ready(function () {
        $( document ).ready(function() 
        {
            bookingList();

            $('body').on('click', '.pagination a', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');
                getPerPageBookingList(url);
            });

            $('body').on('keyup', '#search', function (e) {
                bookingList();
            });
            
            $('body').on('change', '#booking_type', function (e) {
                bookingList();
            });

            $('body').on('click', '#clear-button', function(e) {
                $('#search').val('');
                $("#booking_type").val("all").change();
                bookingList();
            });
        });

        function bookingList()
        {
            var search = $('#search').val();
            var booking_type = $('#booking_type').val();
            $.ajax({
                type:'post',
                headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
                url:'{{ route("admin.escort.wise.booking.list") }}',
                data: { search: search, escort_id: escort_id, booking_type: booking_type },
                success:function(data)
                {
                    $('.bookingDataList').html(data);
                }
            });
        }

        function getPerPageBookingList(get_pagination_url) 
        {
            var search = $('#search').val();
            var booking_type = $('#booking_type').val();
            $.ajax({
                type:'post',
                headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
                url:get_pagination_url,
                data: { search: search, escort_id: escort_id, booking_type: booking_type },
                success:function(data)
                {
                    $('.bookingDataList').html(data);
                }
            });   
        }

        function deleteBooking(booking_id)
        {
            Swal.fire({
                title: 'Are you sure?',
                text: "Delete this booking.",
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
                        url:'{{ route("admin.booking.delete") }}',
                        data: { booking_id: booking_id },
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
                                    bookingList();
                                }
                            });
                        }
                    });
                }
            });
        }
    });
</script>
@include('admin.layout.end')