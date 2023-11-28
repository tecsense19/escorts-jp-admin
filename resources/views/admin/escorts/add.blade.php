@php
    $profilePic = Auth::user()->profile_pic ? url('/').'/'.Auth::user()->profile_pic : url('/').'/public/assets/img/profile-img.jpg';
    $full_name = old('full_name');
    $about = old('about');
    $user_role = old('user_role');
    $age = old('age');
    $country = old('country');
    $state = old('state');
    $city = old('city');
    $address = old('address');
    $mobile_no = old('mobile_no');
    $email = old('email');
    $hourly_price = old('hourly_price');
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
        right: 5px;
        cursor: pointer;
        background-color: #e74c3c;
        color: #fff;
        border: none;
        padding: 5px;
        border-radius: 50%;
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
        margin: 10px;
        position: relative;
    }

    .remove-video {
        position: absolute;
        top: 5px;
        right: 22px;
        cursor: pointer;
        background-color: #e74c3c;
        color: #fff;
        border: none;
        padding: 5px;
        border-radius: 50%;
        font-size: 12px;
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
                            <form class="row g-3" method="POST" action="{{ route('admin.save.escorts') }}" id="profileForm" enctype='multipart/form-data'>
                                {!! csrf_field() !!}
                                <div class="row mb-3">
                                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Images</label>
                                    <div class="col-md-8 col-lg-9">
                                        <div id="image-preview-container">
                                            <label for="image-upload" id="image-label">Choose Images</label>
                                            <input type="file" name="image_upload[]" id="image-upload" accept="image/*" multiple style="display: none;">
                                        </div>

                                        <div class="row" id="preview-container"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Videos</label>
                                    <div class="col-md-8 col-lg-9">
                                        <div id="video-preview-container">
                                            <label for="video-upload" id="video-label">Choose Videos</label>
                                            <input type="file" name="video_upload[]" id="video-upload" accept="video/*" multiple style="display: none;">
                                        </div>

                                        <div class="row" id="vid-preview-container"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="full_name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="full_name" type="text" class="form-control" id="full_name" value="{{ $full_name }}" required>
                                        <input type="hidden" name="user_id" id="user_id" value=""/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                                    <div class="col-md-8 col-lg-9">
                                        <textarea name="about" class="form-control" id="about" style="height: 100px">{{ $about }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="age" class="col-md-4 col-lg-3 col-form-label">Age</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="age" type="number" class="form-control" id="age" value="{{ $age }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="country" class="col-md-4 col-lg-3 col-form-label">Country</label>
                                    <div class="col-md-8 col-lg-9">
                                        <select name="country" id="country" class="form-control">
                                            <option value="">Select Country</option>
                                            @foreach($countryData as $cou)
                                                <option value="{{ $cou->id }}">{{ $cou->name }}</option>            
                                            @endforeach
                                        </select>
                                        <label id="country-error" class="error" for="country"></label>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="state" class="col-md-4 col-lg-3 col-form-label">State</label>
                                    <div class="col-md-8 col-lg-9">
                                        <select name="state" id="state" class="form-control">
                                            <option value="">Select State</option>
                                        </select>
                                        <label id="state-error" class="error" for="state"></label>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="city" class="col-md-4 col-lg-3 col-form-label">City</label>
                                    <div class="col-md-8 col-lg-9">
                                        <select name="city" id="city" class="form-control">
                                            <option value="">Select City</option>
                                        </select>
                                        <label id="city-error" class="error" for="city"></label>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="address" type="text" class="form-control" id="address" value="{{ $address }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="mobile_no" class="col-md-4 col-lg-3 col-form-label">Mobile</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="mobile_no" type="number" class="form-control" id="mobile_no" value="{{ $mobile_no }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="email" type="email" class="form-control" id="email" value="{{ $email }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="hourly_price" class="col-md-4 col-lg-3 col-form-label">Hourly Price</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="hourly_price" type="text" class="form-control" id="hourly_price" value="{{ $hourly_price }}">
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
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
                country : {
                    required: true,
                },
                state : {
                    required: true,
                },
                city : {
                    required: true,
                },
                address : {
                    required: true,
                },
                mobile_no : {
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
                country : {
                    required: 'Country is required!',
                },
                state : {
                    required: 'State is required!',
                },
                city : {
                    required: 'City is required!',
                },
                address : {
                    required: 'Address is required!',
                },
                mobile_no : {
                    required: 'Mobile no is required!',
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

        $('body').on('change', '#country', function(e) {            
            var selectedCountry = $(this).val();
            $.ajax({
                type:'post',
                headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
                url:'{{ route("admin.state.list") }}',
                data: { country_id: selectedCountry },
                success:function(response)
                {
                    if(response.success)
                    {
                        $('#state').html(response.states);
                    }
                    else
                    {
                        Swal.fire('Error!', 'Something went wrong.', 'error');    
                    }
                },
                error: function(error) {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                }
            });
        });

        $('body').on('change', '#state', function(e) {            
            var selectedCountry = $(this).val();
            $.ajax({
                type:'post',
                headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
                url:'{{ route("admin.city.list") }}',
                data: { state_id: selectedCountry },
                success:function(response)
                {
                    if(response.success)
                    {
                        $('#city').html(response.cities);
                    }
                    else
                    {
                        Swal.fire('Error!', 'Something went wrong.', 'error');    
                    }
                },
                error: function(error) {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                }
            });
        });

        $('#country, #state, #city').select2();

        $('#image-upload').on('change', function (e) {
            var files = e.target.files;

            $('#preview-container').empty();

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                if (!file.type.startsWith('image/')) {
                    continue;
                }

                var reader = new FileReader();
                reader.onload = function (e) {
                    var preview = '<div class="col-sm-3 image-preview"><img src="' + e.target.result + '" alt="Image Preview" style="">';
                    preview += '<button class="remove-image" data-index="' + i + '">Remove</button></div>';
                    $('#preview-container').append(preview);
                };

                reader.readAsDataURL(file);
            }
        });

        // Remove image on click
        $('#preview-container').on('click', '.remove-image', function () {
            var indexToRemove = $(this).data('index');
            $(this).closest('.image-preview').remove();
            $('#image-upload')[0].files[indexToRemove] = null;
        });

        $('#video-upload').on('change', function (e) {
            var files = e.target.files;

            $('#vid-preview-container').empty();

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                if (!file.type.startsWith('video/')) {
                    continue;
                }

                var reader = new FileReader();
                reader.onload = function (e) {
                    var preview = '<div class="col-sm-3 video-preview"><video width="100%" height="100%" controls>';
                    preview += '<source src="' + e.target.result + '" type="' + file.type + '">';
                    preview += 'Your browser does not support the video tag.</video>';
                    preview += '<button class="remove-video" data-index="' + i + '">Remove</button></div>';
                    $('#vid-preview-container').append(preview);
                };

                reader.readAsDataURL(file);
            }
        });

        // Remove video on click
        $('#vid-preview-container').on('click', '.remove-video', function () {
            var indexToRemove = $(this).data('index');
            $(this).closest('.video-preview').remove();
            $('#video-upload')[0].files[indexToRemove] = null;
        });
    });
</script>
@include('admin.layout.end')