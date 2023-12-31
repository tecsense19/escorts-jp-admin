@php
    $user_id = isset($getUserDetails) ? $getUserDetails->id : '';
    $full_name = isset($getUserDetails) ? $getUserDetails->name : old('full_name');
    $about = isset($getUserDetails) ? $getUserDetails->description : old('about');
    $age = isset($getUserDetails) ? $getUserDetails->age : old('age');
    $country = isset($getUserDetails) ? $getUserDetails->country : old('country');
    $state = isset($getUserDetails) ? $getUserDetails->state : old('state');
    $city = isset($getUserDetails) ? $getUserDetails->city : old('city');
    $address = isset($getUserDetails) ? $getUserDetails->address : old('address');
    $mobile_no = isset($getUserDetails) ? $getUserDetails->mobile_no : old('mobile_no');
    $line_number = isset($getUserDetails) ? $getUserDetails->line_number : old('line_number');
    $email = isset($getUserDetails) ? $getUserDetails->email : old('email');
    $hourly_price = isset($getUserDetails) ? $getUserDetails->hourly_price : old('hourly_price');
    $ward = isset($getUserDetails) ? $getUserDetails->ward : old('ward');
    $phone_code = isset($getUserDetails) ? $getUserDetails->phone_code : old('phone_code');
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
                            <form class="row g-3" method="POST" action="{{ route('admin.save.escorts') }}" id="profileForm" enctype='multipart/form-data'>
                                {!! csrf_field() !!}
                                <div class="row mb-3">
                                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Images</label>
                                    <div class="col-md-8 col-lg-9">
                                        <div id="image-preview-container">
                                            <label for="image-upload" id="image-label">Choose Images</label>
                                            <input type="file" name="image_upload[]" id="image-upload" accept="image/*" multiple style="display: none;">
                                            <input type="hidden" name="remove_img_ids" id="remove_img_ids" />
                                        </div>

                                        <div class="row" id="preview-container">
                                            @if(isset($getUserDetails->escort_images))
                                                @foreach($getUserDetails->escort_images as $key => $value)
                                                    <div class="col-sm-3 image-preview">
                                                        <img src="{{ $value->file_path }}" alt="Image Preview" style="width: 100%;">
                                                        <button class="remove-image" data-index="{{ $key }}" data-id="{{ $value->id }}"><i class="bi bi-trash"></i></button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row mb-3">
                                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Videos</label>
                                    <div class="col-md-8 col-lg-9">
                                        <div id="video-preview-container">
                                            <label for="video-upload" id="video-label">Choose Videos</label>
                                            <input type="file" name="video_upload[]" id="video-upload" accept="video/*" multiple style="display: none;">
                                            <input type="hidden" name="remove_video_ids" id="remove_video_ids" />
                                        </div>

                                        <div class="row" id="vid-preview-container">
                                            @if(isset($getUserDetails->escortVideos))
                                                @foreach($getUserDetails->escortVideos as $key => $value)
                                                    <div class="col-sm-3 video-preview">
                                                        <video width="100%" height="100%" controls>
                                                        <source src="{{ $value->file_path }}" type="">Your browser does not support the video tag.</video>
                                                        <button class="remove-video" data-index="{{ $key }}" data-id="{{ $value->id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div> -->
                                <div class="row mb-3">
                                    <label for="full_name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="full_name" type="text" class="form-control" id="full_name" value="{{ $full_name }}" placeholder="Enter Full Name">
                                        <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                                    <div class="col-md-8 col-lg-9">
                                        <textarea name="about" class="form-control" id="about" style="height: 100px" placeholder="Enter Description">{{ $about }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="age" class="col-md-4 col-lg-3 col-form-label">Age</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="age" type="number" class="form-control" id="age" value="{{ $age }}" placeholder="Enter Age">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="ward" class="col-md-4 col-lg-3 col-form-label">Ward</label>
                                    <div class="col-md-8 col-lg-9">
                                        <select name="ward" id="ward" class="form-control">
                                            <option value="">Select ward</option>
                                            <option value="Minato">Minato</option>
                                            <option value="Chiyoda">Chiyoda</option>
                                            <option value="Chūō">Chūō</option>
                                            <option value="Shinjuku">Shinjuku</option>
                                            <option value="Bunkyō">Bunkyō</option>
                                            <option value="Taitō">Taitō</option>
                                            <option value="Sumida">Sumida</option>
                                            <option value="Kōtō">Kōtō</option>
                                            <option value="Shinagawa">Shinagawa</option>
                                            <option value="Meguro">Meguro</option>
                                            <option value="Ōta">Ōta</option>
                                            <option value="Setagaya">Setagaya</option>
                                            <option value="Shibuya">Shibuya</option>
                                            <option value="Nakano">Nakano</option>
                                            <option value="Suginami">Suginami</option>
                                            <option value="Toshima">Toshima</option>
                                            <option value="Kita">Kita</option>
                                            <option value="Arakawa">Arakawa</option>
                                            <option value="Itabashi">Itabashi</option>
                                            <option value="Nerima">Nerima</option>
                                            <option value="Adachi">Adachi</option>
                                            <option value="Katsushika">Katsushika</option>
                                            <option value="Edogawa">Edogawa</option>
                                        </select>
                                        <label id="ward-error" class="error" for="ward"></label>
                                    </div>
                                </div>
                                <!-- <div class="row mb-3">
                                    <label for="country" class="col-md-4 col-lg-3 col-form-label">Country</label>
                                    <div class="col-md-8 col-lg-9">
                                        <select name="country" id="country" class="form-control">
                                            <option value="">Select Country</option>
                                            @foreach($countryData as $cou)
                                                <option value="{{ $cou->id }}" @if($cou->id == $country) {{ 'selected' }} @endif >{{ $cou->name }}</option>            
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
                                </div> -->
                                <!-- <div class="row mb-3">
                                    <label for="address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="address" type="text" class="form-control" id="address" value="{{ $address }}">
                                    </div>
                                </div> -->
                                <div class="row mb-3">
                                    <label for="phone_code" class="col-md-4 col-lg-3 col-form-label">Phone Code</label>
                                    <div class="col-md-8 col-lg-9">
                                        <select name="phone_code" id="phone_code" class="form-control">
                                            <option data-countryCode="GB" value="+44" Selected>UK (+44)</option>
                                            <option data-countryCode="US" value="+1">USA (+1)</option>
                                            <optgroup label="Other countries">
                                                <option data-countryCode="DZ" value="+213">Algeria (+213)</option>
                                                <option data-countryCode="AD" value="+376">Andorra (+376)</option>
                                                <option data-countryCode="AO" value="+244">Angola (+244)</option>
                                                <option data-countryCode="AI" value="+1264">Anguilla (+1264)</option>
                                                <option data-countryCode="AG" value="+1268">Antigua &amp; Barbuda (+1268)</option>
                                                <option data-countryCode="AR" value="+54">Argentina (+54)</option>
                                                <option data-countryCode="AM" value="+374">Armenia (+374)</option>
                                                <option data-countryCode="AW" value="+297">Aruba (+297)</option>
                                                <option data-countryCode="AU" value="+61">Australia (+61)</option>
                                                <option data-countryCode="AT" value="+43">Austria (+43)</option>
                                                <option data-countryCode="AZ" value="+994">Azerbaijan (+994)</option>
                                                <option data-countryCode="BS" value="+1242">Bahamas (+1242)</option>
                                                <option data-countryCode="BH" value="+973">Bahrain (+973)</option>
                                                <option data-countryCode="BD" value="+880">Bangladesh (+880)</option>
                                                <option data-countryCode="BB" value="+1246">Barbados (+1246)</option>
                                                <option data-countryCode="BY" value="+375">Belarus (+375)</option>
                                                <option data-countryCode="BE" value="+32">Belgium (+32)</option>
                                                <option data-countryCode="BZ" value="+501">Belize (+501)</option>
                                                <option data-countryCode="BJ" value="+229">Benin (+229)</option>
                                                <option data-countryCode="BM" value="+1441">Bermuda (+1441)</option>
                                                <option data-countryCode="BT" value="+975">Bhutan (+975)</option>
                                                <option data-countryCode="BO" value="+591">Bolivia (+591)</option>
                                                <option data-countryCode="BA" value="+387">Bosnia Herzegovina (+387)</option>
                                                <option data-countryCode="BW" value="+267">Botswana (+267)</option>
                                                <option data-countryCode="BR" value="+55">Brazil (+55)</option>
                                                <option data-countryCode="BN" value="+673">Brunei (+673)</option>
                                                <option data-countryCode="BG" value="+359">Bulgaria (+359)</option>
                                                <option data-countryCode="BF" value="+226">Burkina Faso (+226)</option>
                                                <option data-countryCode="BI" value="+257">Burundi (+257)</option>
                                                <option data-countryCode="KH" value="+855">Cambodia (+855)</option>
                                                <option data-countryCode="CM" value="+237">Cameroon (+237)</option>
                                                <option data-countryCode="CA" value="+1">Canada (+1)</option>
                                                <option data-countryCode="CV" value="+238">Cape Verde Islands (+238)</option>
                                                <option data-countryCode="KY" value="+1345">Cayman Islands (+1345)</option>
                                                <option data-countryCode="CF" value="+236">Central African Republic (+236)</option>
                                                <option data-countryCode="CL" value="+56">Chile (+56)</option>
                                                <option data-countryCode="CN" value="+86">China (+86)</option>
                                                <option data-countryCode="CO" value="+57">Colombia (+57)</option>
                                                <option data-countryCode="KM" value="+269">Comoros (+269)</option>
                                                <option data-countryCode="CG" value="+242">Congo (+242)</option>
                                                <option data-countryCode="CK" value="+682">Cook Islands (+682)</option>
                                                <option data-countryCode="CR" value="+506">Costa Rica (+506)</option>
                                                <option data-countryCode="HR" value="+385">Croatia (+385)</option>
                                                <option data-countryCode="CU" value="+53">Cuba (+53)</option>
                                                <option data-countryCode="CY" value="+90392">Cyprus North (+90392)</option>
                                                <option data-countryCode="CY" value="+357">Cyprus South (+357)</option>
                                                <option data-countryCode="CZ" value="+42">Czech Republic (+42)</option>
                                                <option data-countryCode="DK" value="+45">Denmark (+45)</option>
                                                <option data-countryCode="DJ" value="+253">Djibouti (+253)</option>
                                                <option data-countryCode="DM" value="+1809">Dominica (+1809)</option>
                                                <option data-countryCode="DO" value="+1809">Dominican Republic (+1809)</option>
                                                <option data-countryCode="EC" value="+593">Ecuador (+593)</option>
                                                <option data-countryCode="EG" value="+20">Egypt (+20)</option>
                                                <option data-countryCode="SV" value="+503">El Salvador (+503)</option>
                                                <option data-countryCode="GQ" value="+240">Equatorial Guinea (+240)</option>
                                                <option data-countryCode="ER" value="+291">Eritrea (+291)</option>
                                                <option data-countryCode="EE" value="+372">Estonia (+372)</option>
                                                <option data-countryCode="ET" value="+251">Ethiopia (+251)</option>
                                                <option data-countryCode="FK" value="+500">Falkland Islands (+500)</option>
                                                <option data-countryCode="FO" value="+298">Faroe Islands (+298)</option>
                                                <option data-countryCode="FJ" value="+679">Fiji (+679)</option>
                                                <option data-countryCode="FI" value="+358">Finland (+358)</option>
                                                <option data-countryCode="FR" value="+33">France (+33)</option>
                                                <option data-countryCode="GF" value="+594">French Guiana (+594)</option>
                                                <option data-countryCode="PF" value="+689">French Polynesia (+689)</option>
                                                <option data-countryCode="GA" value="+241">Gabon (+241)</option>
                                                <option data-countryCode="GM" value="+220">Gambia (+220)</option>
                                                <option data-countryCode="GE" value="+7880">Georgia (+7880)</option>
                                                <option data-countryCode="DE" value="+49">Germany (+49)</option>
                                                <option data-countryCode="GH" value="+233">Ghana (+233)</option>
                                                <option data-countryCode="GI" value="+350">Gibraltar (+350)</option>
                                                <option data-countryCode="GR" value="+30">Greece (+30)</option>
                                                <option data-countryCode="GL" value="+299">Greenland (+299)</option>
                                                <option data-countryCode="GD" value="+1473">Grenada (+1473)</option>
                                                <option data-countryCode="GP" value="+590">Guadeloupe (+590)</option>
                                                <option data-countryCode="GU" value="+671">Guam (+671)</option>
                                                <option data-countryCode="GT" value="+502">Guatemala (+502)</option>
                                                <option data-countryCode="GN" value="+224">Guinea (+224)</option>
                                                <option data-countryCode="GW" value="+245">Guinea - Bissau (+245)</option>
                                                <option data-countryCode="GY" value="+592">Guyana (+592)</option>
                                                <option data-countryCode="HT" value="+509">Haiti (+509)</option>
                                                <option data-countryCode="HN" value="+504">Honduras (+504)</option>
                                                <option data-countryCode="HK" value="+852">Hong Kong (+852)</option>
                                                <option data-countryCode="HU" value="+36">Hungary (+36)</option>
                                                <option data-countryCode="IS" value="+354">Iceland (+354)</option>
                                                <option data-countryCode="IN" value="+91">India (+91)</option>
                                                <option data-countryCode="ID" value="+62">Indonesia (+62)</option>
                                                <option data-countryCode="IR" value="+98">Iran (+98)</option>
                                                <option data-countryCode="IQ" value="+964">Iraq (+964)</option>
                                                <option data-countryCode="IE" value="+353">Ireland (+353)</option>
                                                <option data-countryCode="IL" value="+972">Israel (+972)</option>
                                                <option data-countryCode="IT" value="+39">Italy (+39)</option>
                                                <option data-countryCode="JM" value="+1876">Jamaica (+1876)</option>
                                                <option data-countryCode="JP" value="+81">Japan (+81)</option>
                                                <option data-countryCode="JO" value="+962">Jordan (+962)</option>
                                                <option data-countryCode="KZ" value="+7">Kazakhstan (+7)</option>
                                                <option data-countryCode="KE" value="+254">Kenya (+254)</option>
                                                <option data-countryCode="KI" value="+686">Kiribati (+686)</option>
                                                <option data-countryCode="KP" value="+850">Korea North (+850)</option>
                                                <option data-countryCode="KR" value="+82">Korea South (+82)</option>
                                                <option data-countryCode="KW" value="+965">Kuwait (+965)</option>
                                                <option data-countryCode="KG" value="+996">Kyrgyzstan (+996)</option>
                                                <option data-countryCode="LA" value="+856">Laos (+856)</option>
                                                <option data-countryCode="LV" value="+371">Latvia (+371)</option>
                                                <option data-countryCode="LB" value="+961">Lebanon (+961)</option>
                                                <option data-countryCode="LS" value="+266">Lesotho (+266)</option>
                                                <option data-countryCode="LR" value="+231">Liberia (+231)</option>
                                                <option data-countryCode="LY" value="+218">Libya (+218)</option>
                                                <option data-countryCode="LI" value="+417">Liechtenstein (+417)</option>
                                                <option data-countryCode="LT" value="+370">Lithuania (+370)</option>
                                                <option data-countryCode="LU" value="+352">Luxembourg (+352)</option>
                                                <option data-countryCode="MO" value="+853">Macao (+853)</option>
                                                <option data-countryCode="MK" value="+389">Macedonia (+389)</option>
                                                <option data-countryCode="MG" value="+261">Madagascar (+261)</option>
                                                <option data-countryCode="MW" value="+265">Malawi (+265)</option>
                                                <option data-countryCode="MY" value="+60">Malaysia (+60)</option>
                                                <option data-countryCode="MV" value="+960">Maldives (+960)</option>
                                                <option data-countryCode="ML" value="+223">Mali (+223)</option>
                                                <option data-countryCode="MT" value="+356">Malta (+356)</option>
                                                <option data-countryCode="MH" value="+692">Marshall Islands (+692)</option>
                                                <option data-countryCode="MQ" value="+596">Martinique (+596)</option>
                                                <option data-countryCode="MR" value="+222">Mauritania (+222)</option>
                                                <option data-countryCode="YT" value="+269">Mayotte (+269)</option>
                                                <option data-countryCode="MX" value="+52">Mexico (+52)</option>
                                                <option data-countryCode="FM" value="+691">Micronesia (+691)</option>
                                                <option data-countryCode="MD" value="+373">Moldova (+373)</option>
                                                <option data-countryCode="MC" value="+377">Monaco (+377)</option>
                                                <option data-countryCode="MN" value="+976">Mongolia (+976)</option>
                                                <option data-countryCode="MS" value="+1664">Montserrat (+1664)</option>
                                                <option data-countryCode="MA" value="+212">Morocco (+212)</option>
                                                <option data-countryCode="MZ" value="+258">Mozambique (+258)</option>
                                                <option data-countryCode="MN" value="+95">Myanmar (+95)</option>
                                                <option data-countryCode="NA" value="+264">Namibia (+264)</option>
                                                <option data-countryCode="NR" value="+674">Nauru (+674)</option>
                                                <option data-countryCode="NP" value="+977">Nepal (+977)</option>
                                                <option data-countryCode="NL" value="+31">Netherlands (+31)</option>
                                                <option data-countryCode="NC" value="+687">New Caledonia (+687)</option>
                                                <option data-countryCode="NZ" value="+64">New Zealand (+64)</option>
                                                <option data-countryCode="NI" value="+505">Nicaragua (+505)</option>
                                                <option data-countryCode="NE" value="+227">Niger (+227)</option>
                                                <option data-countryCode="NG" value="+234">Nigeria (+234)</option>
                                                <option data-countryCode="NU" value="+683">Niue (+683)</option>
                                                <option data-countryCode="NF" value="+672">Norfolk Islands (+672)</option>
                                                <option data-countryCode="NP" value="+670">Northern Marianas (+670)</option>
                                                <option data-countryCode="NO" value="+47">Norway (+47)</option>
                                                <option data-countryCode="OM" value="+968">Oman (+968)</option>
                                                <option data-countryCode="PW" value="+680">Palau (+680)</option>
                                                <option data-countryCode="PA" value="+507">Panama (+507)</option>
                                                <option data-countryCode="PG" value="+675">Papua New Guinea (+675)</option>
                                                <option data-countryCode="PY" value="+595">Paraguay (+595)</option>
                                                <option data-countryCode="PE" value="+51">Peru (+51)</option>
                                                <option data-countryCode="PH" value="+63">Philippines (+63)</option>
                                                <option data-countryCode="PL" value="+48">Poland (+48)</option>
                                                <option data-countryCode="PT" value="+351">Portugal (+351)</option>
                                                <option data-countryCode="PR" value="+1787">Puerto Rico (+1787)</option>
                                                <option data-countryCode="QA" value="+974">Qatar (+974)</option>
                                                <option data-countryCode="RE" value="+262">Reunion (+262)</option>
                                                <option data-countryCode="RO" value="+40">Romania (+40)</option>
                                                <option data-countryCode="RU" value="+7">Russia (+7)</option>
                                                <option data-countryCode="RW" value="+250">Rwanda (+250)</option>
                                                <option data-countryCode="SM" value="+378">San Marino (+378)</option>
                                                <option data-countryCode="ST" value="+239">Sao Tome &amp; Principe (+239)</option>
                                                <option data-countryCode="SA" value="+966">Saudi Arabia (+966)</option>
                                                <option data-countryCode="SN" value="+221">Senegal (+221)</option>
                                                <option data-countryCode="CS" value="+381">Serbia (+381)</option>
                                                <option data-countryCode="SC" value="+248">Seychelles (+248)</option>
                                                <option data-countryCode="SL" value="+232">Sierra Leone (+232)</option>
                                                <option data-countryCode="SG" value="+65">Singapore (+65)</option>
                                                <option data-countryCode="SK" value="+421">Slovak Republic (+421)</option>
                                                <option data-countryCode="SI" value="+386">Slovenia (+386)</option>
                                                <option data-countryCode="SB" value="+677">Solomon Islands (+677)</option>
                                                <option data-countryCode="SO" value="+252">Somalia (+252)</option>
                                                <option data-countryCode="ZA" value="+27">South Africa (+27)</option>
                                                <option data-countryCode="ES" value="+34">Spain (+34)</option>
                                                <option data-countryCode="LK" value="+94">Sri Lanka (+94)</option>
                                                <option data-countryCode="SH" value="+290">St. Helena (+290)</option>
                                                <option data-countryCode="KN" value="+1869">St. Kitts (+1869)</option>
                                                <option data-countryCode="SC" value="+1758">St. Lucia (+1758)</option>
                                                <option data-countryCode="SD" value="+249">Sudan (+249)</option>
                                                <option data-countryCode="SR" value="+597">Suriname (+597)</option>
                                                <option data-countryCode="SZ" value="+268">Swaziland (+268)</option>
                                                <option data-countryCode="SE" value="+46">Sweden (+46)</option>
                                                <option data-countryCode="CH" value="+41">Switzerland (+41)</option>
                                                <option data-countryCode="SI" value="+963">Syria (+963)</option>
                                                <option data-countryCode="TW" value="+886">Taiwan (+886)</option>
                                                <option data-countryCode="TJ" value="+7">Tajikstan (+7)</option>
                                                <option data-countryCode="TH" value="+66">Thailand (+66)</option>
                                                <option data-countryCode="TG" value="+228">Togo (+228)</option>
                                                <option data-countryCode="TO" value="+676">Tonga (+676)</option>
                                                <option data-countryCode="TT" value="+1868">Trinidad &amp; Tobago (+1868)</option>
                                                <option data-countryCode="TN" value="+216">Tunisia (+216)</option>
                                                <option data-countryCode="TR" value="+90">Turkey (+90)</option>
                                                <option data-countryCode="TM" value="+7">Turkmenistan (+7)</option>
                                                <option data-countryCode="TM" value="+993">Turkmenistan (+993)</option>
                                                <option data-countryCode="TC" value="+1649">Turks &amp; Caicos Islands (+1649)</option>
                                                <option data-countryCode="TV" value="+688">Tuvalu (+688)</option>
                                                <option data-countryCode="UG" value="+256">Uganda (+256)</option>
                                                <!-- <option data-countryCode="GB" value="+44">UK (+44)</option> -->
                                                <option data-countryCode="UA" value="+380">Ukraine (+380)</option>
                                                <option data-countryCode="AE" value="+971">United Arab Emirates (+971)</option>
                                                <option data-countryCode="UY" value="+598">Uruguay (+598)</option>
                                                <!-- <option data-countryCode="US" value="+1">USA (+1)</option> -->
                                                <option data-countryCode="UZ" value="+7">Uzbekistan (+7)</option>
                                                <option data-countryCode="VU" value="+678">Vanuatu (+678)</option>
                                                <option data-countryCode="VA" value="+379">Vatican City (+379)</option>
                                                <option data-countryCode="VE" value="+58">Venezuela (+58)</option>
                                                <option data-countryCode="VN" value="+84">Vietnam (+84)</option>
                                                <option data-countryCode="VG" value="+84">Virgin Islands - British (+1284)</option>
                                                <option data-countryCode="VI" value="+84">Virgin Islands - US (+1340)</option>
                                                <option data-countryCode="WF" value="+681">Wallis &amp; Futuna (+681)</option>
                                                <option data-countryCode="YE" value="+969">Yemen (North)(+969)</option>
                                                <option data-countryCode="YE" value="+967">Yemen (South)(+967)</option>
                                                <option data-countryCode="ZM" value="+260">Zambia (+260)</option>
                                                <option data-countryCode="ZW" value="+263">Zimbabwe (+263)</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="mobile_no" class="col-md-4 col-lg-3 col-form-label">Whatsapp Number</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="mobile_no" type="number" class="form-control" id="mobile_no" value="{{ $mobile_no }}" placeholder="Enter Whatsapp Number">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="line_number" class="col-md-4 col-lg-3 col-form-label">Line Number</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="line_number" type="number" class="form-control" id="line_number" value="{{ $line_number }}" placeholder="Enter Line Number">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="email" type="email" class="form-control" id="email" value="{{ $email }}" placeholder="Enter Email">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="password" class="col-md-4 col-lg-3 col-form-label">Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="password" type="password" class="form-control" id="password" value="{{ old('password') }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="hourly_price" class="col-md-4 col-lg-3 col-form-label">Hourly Price</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="hourly_price" type="text" class="form-control" id="hourly_price" value="{{ $hourly_price }}" placeholder="Enter Hourly Price">
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
                full_name: {
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
                phone_code : {
                    required: true,
                },
                mobile_no : {
                    required: true,
                    digits: true,
                    // minlength: 10,
                    // maxlength: 10
                },
                line_number : {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                hourly_price: {
                    required: true,
                },
                password: {
                    required: function(element) {
                        // Check if the 'id' field is blank
                        return $('#user_id').val() === '';
                    }
                }
            },
            messages: {
                full_name: {
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
                phone_code : {
                    required: 'Phone code is required!',
                },
                mobile_no : {
                    required: 'Mobile no is required!',
                    digits: "Mobile no must contain only digits",
                    // minlength: "Mobile no must be exactly 10 digits",
                    // maxlength: "Mobile no must be exactly 10 digits"
                },
                line_number : {
                    required: 'Line no is required!',
                },
                email : {
                    required: 'Email is required!',
                    email: 'Please enter valid email address.'
                },
                hourly_price: {
                    required: 'Hourly price is required!',
                },
                password: {
                    required: 'Password is required!',
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        var countryId = '{{ $country }}';
        var stateId = '{{ $state }}';
        var cityId = '{{ $city }}';
        var ward = '{{ $ward }}';
        var phoneCode = '{{ $phone_code }}';
        if(countryId)
        {
            changeCountry(countryId, stateId)
        }
        
        if(stateId)
        {
            changeState(stateId, cityId)
        }

        if(ward)
        {
            $('#ward option[value="'+ward+'"]').attr('selected', 'selected');
        }
        
        if(ward)
        {
            $('#phone_code option[value="'+phoneCode+'"]').attr('selected', 'selected');
        }

        $('body').on('change', '#country', function(e) {            
            var selectedCountry = $(this).val();
            changeCountry(selectedCountry, '')
        });

        $('body').on('change', '#state', function(e) {            
            var selectedState = $(this).val();
            changeState(selectedState, '')
        });

        // $('#country, #state, #city').select2();
        $('#ward').select2({
            placeholder: "Select ward"
        });

        $('#phone_code').select2({
            placeholder: "Select Phone Code"
        });

        $('#ward').on('change', function(e) {
            $('#ward-error').text('')
        });
        
        $('#phone_code').on('change', function(e) {
            $('#phone_code-error').text('')
        });

        $('#image-upload').on('change', function (e) {
            var files = e.target.files;

            // $('#preview-container').empty();

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                if (!file.type.startsWith('image/')) {
                    continue;
                }

                var reader = new FileReader();
                reader.onload = function (e) {
                    var preview = '<div class="col-sm-3 image-preview"><img src="' + e.target.result + '" alt="Image Preview" style="width: 100%;">';
                    preview += '<button class="remove-image" data-index="' + i + '" data-id=""><i class="bi bi-trash"></i></button></div>';
                    $('#preview-container').append(preview);
                };

                reader.readAsDataURL(file);
            }
        });

        // Remove image on click
        $('#preview-container').on('click', '.remove-image', function () {
            var indexToRemove = $(this).data('index');
            var idToRemove = $(this).data('id');
            if(idToRemove)
            {
                var oldVal = $('#remove_img_ids').val();
                oldVal = oldVal ? oldVal + ',' + idToRemove : idToRemove;
                $('#remove_img_ids').val(oldVal);
            }

            $(this).closest('.image-preview').remove();
            $('#image-upload')[0].files[indexToRemove] = null;
        });

        $('#video-upload').on('change', function (e) {
            var files = e.target.files;

            // $('#vid-preview-container').empty();

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
                    preview += '<button class="remove-video" data-index="' + i + '" data-id=""><i class="bi bi-trash"></i></button></div>';
                    $('#vid-preview-container').append(preview);
                };

                reader.readAsDataURL(file);
            }
        });

        // Remove video on click
        $('#vid-preview-container').on('click', '.remove-video', function () {
            var indexToRemove = $(this).data('index');
            var idToRemove = $(this).data('id');
            if(idToRemove)
            {
                var oldVal = $('#remove_video_ids').val();
                oldVal = oldVal ? oldVal + ',' + idToRemove : idToRemove;
                $('#remove_video_ids').val(oldVal);
            }
            $(this).closest('.video-preview').remove();
            $('#video-upload')[0].files[indexToRemove] = null;
        });
    });

    function changeCountry(selectedCountry, stateId)
    {
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

                    if(stateId)
                    {
                        $('#state option[value="'+stateId+'"]').attr('selected', 'selected');
                    }
                    // $('#state').val(stateId).trigger('change');
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
    }

    function changeState(selectedState, cityId)
    {
        $.ajax({
            type:'post',
            headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
            url:'{{ route("admin.city.list") }}',
            data: { state_id: selectedState },
            success:function(response)
            {
                if(response.success)
                {
                    $('#city').html(response.cities);

                    if(cityId)
                    {
                        $('#city option[value="'+cityId+'"]').attr('selected', 'selected');
                    }
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
    }
</script>
@include('admin.layout.end')