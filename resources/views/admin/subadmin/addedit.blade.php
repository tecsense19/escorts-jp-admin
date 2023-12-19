@php
    $user_id = isset($getUserDetails) ? $getUserDetails->id : '';
    $full_name = isset($getUserDetails) ? $getUserDetails->name : old('full_name');
    $email = isset($getUserDetails) ? $getUserDetails->email : old('email');
    $user_permissions = isset($getUserDetails) ? explode(",", $getUserDetails->user_permissions) : '';
@endphp
@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<style>
    #image-preview-container {
        text-align: left;
    }

    #image-label {
        cursor: pointer;
        display: inline-block;
        padding: 10px;
        background-color: #3498db;
        color: #fff;
        border-radius: 5px;
        font-size: 14px;
        /* margin-top: 20px; */
    }

    #preview-container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .image-preview {
        /* margin: 10px; */
        position: relative;
        font-size: 10px;
    }

    .remove-image {
        position: absolute;
        top: 5px;
        right: 20px;
        cursor: pointer;
        background-color: #e74c3c;
        color: #fff;
        border: none;
        padding: 5px;
        border-radius: 50%;
        color: white;
        width: 27px;
    }

    #video-preview-container {
        text-align: left;
    }

    #video-label {
        cursor: pointer;
        display: inline-block;
        padding: 10px;
        background-color: #3498db;
        color: #fff;
        border-radius: 5px;
        font-size: 14px;
        margin-top: 20px;
    }

    #preview-container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .video-preview {
        /* margin: 10px; */
        position: relative;
        margin-top: 10px;
    }

    .remove-video {
        position: absolute;
        top: 5px;
        right: 20px;
        cursor: pointer;
        background-color: #e74c3c;
        color: #fff;
        border: none;
        padding: 5px;
        border-radius: 50%;
        color: white;
        width: 27px;
        font-size: 11px;
    }
</style>
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
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <div class="tab-content pt-2">
                            <!-- Profile Edit Form -->
                            <form class="row g-3" method="POST" action="{{ route('admin.subadmin.store') }}" id="profileForm" enctype='multipart/form-data'>
                                {!! csrf_field() !!}
                                <div class="row mb-3">
                                    <label for="full_name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="full_name" type="text" class="form-control" id="full_name" value="{{ $full_name }}" required>
                                        <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="email" type="email" class="form-control" id="email" value="{{ $email }}">
                                    </div>
                                </div>
                                @if($user_id == '')
                                    <div class="row mb-3">
                                        <label for="password" class="col-md-4 col-lg-3 col-form-label">Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password" type="password" class="form-control" id="password" value="{{ old('password') }}">
                                        </div>
                                    </div>
                                @endif
                                <div class="row mb-3">
                                    <label for="user_permissions" class="col-md-4 col-lg-3 col-form-label">Permissions</label>
                                    <div class="col-md-8 col-lg-9">
                                        <select name="user_permissions[]" id="user_permissions" class="form-control" multiple>
                                            <option value="">Select Permissions</option>
                                            <option value="escorts"  @if(in_array('escorts', $user_permissions)) {{ 'selected' }} @endif>Escorts</option>
                                            <option value="booking" @if(in_array('booking', $user_permissions)) {{ 'selected' }} @endif>Booking</option>
                                            <option value="client" @if(in_array('client', $user_permissions)) {{ 'selected' }} @endif>Client</option>
                                            <option value="allstring" @if(in_array('allstring', $user_permissions)) {{ 'selected' }} @endif>All String</option>
                                            <option value="setting" @if(in_array('setting', $user_permissions)) {{ 'selected' }} @endif>Settings</option>
                                            <option value="subadmin" @if(in_array('subadmin', $user_permissions)) {{ 'selected' }} @endif>Subadmin</option>
                                        </select>
                                        <label id="ward-error" class="error" for="ward"></label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">{{ $user_id ? 'Update' : 'Create' }}</button>
                                </div>
                            </form>
                            <!-- End Profile Edit Form -->
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
    $(document).ready(function () {

        $("#profileForm").validate({
            rules: {
                fullName: {
                    required: true,
                },
                about: {
                    required: true,
                },
                age: {
                    required: true,
                },
                ward: {
                    required: true,
                },
                // country : {
                //     required: true,
                // },
                // state : {
                //     required: true,
                // },
                // city : {
                //     required: true,
                // },
                mobile_no : {
                    required: true,
                },
                line_number : {
                    required: true,
                },
                email: {
                    required: true,
                },
                hourly_price: {
                    required: true,
                }
            },
            messages: {
                fullName: {
                    required: "Full name is required!",
                },
                about: {
                    required: "About is required!",
                },
                age: {
                    required: "Age is required!",
                },
                ward: {
                    required: "Ward is required!",
                },
                // country : {
                //     required: 'Country is required!',
                // },
                // state : {
                //     required: 'State is required!',
                // },
                // city : {
                //     required: 'City is required!',
                // },
                mobile_no : {
                    required: 'Mobile no is required!',
                },
                line_number : {
                    required: 'Line no is required!',
                },
                email : {
                    required: 'Email is required!',
                },
                hourly_price: {
                    required: 'Hourly price is required!',
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        $('#user_permissions').select2();
    });
</script>
@include('admin.layout.end')