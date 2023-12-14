@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<html lang="en">
<style>
/* Overall body and content */
/* body {
        height: 100%;
        width: 100%;
        margin: 0;
        background: #005C97;
        background: -webkit-linear-gradient(left, #363795, #005C97);
        background: -moz-linear-gradient(left, #363795, #005C97);
        background: -o-linear-gradient(left, #363795, #005C97);
        background: linear-gradient(to right, #363795, #005C97);
        font-family: Helvetica;
    } */

.content {
    overflow: none;
    max-width: 790px;
    padding: 0px 0;
    height: 500px;
    position: relative;
    margin: 20px auto;
    /* background: #52A0FD; */
    /* background: -moz-linear-gradient(right, #52A0FD 0%, #00C9FB 80%, #00C9FB 100%); */
    /* background: -webkit-linear-gradient(right, #52A0FD 0%, #00C9FB 80%, #00C9FB 100%); */
    /* background: linear-gradient(to left, #52A0FD 0%, #00C9FB 80%, #00C9FB 100%); */
    border-radius: 3px;
    box-shadow: 3px 8px 16px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
    -moz-box-shadow: 3px 8px 16px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
    -webkit-box-shadow: 3px 8px 16px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
}

/*  Events display */
.events-container {
    overflow-y: scroll;
    height: 100%;
    float: right;
    margin: 0px auto;
    display: inline-block;
    padding: 0 10px;
    border-bottom-right-radius: 3px;
    border-top-right-radius: 3px;
}

.events-container:after {
    clear: both;
}

.event-card {
    padding: 20px 0;
    width: 350px;
    margin: 20px auto;
    display: block;
    background: #fff;
    border-left: 10px solid #52A0FD;
    border-radius: 3px;
    box-shadow: 3px 8px 16px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
    -moz-box-shadow: 3px 8px 16px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
    -webkit-box-shadow: 3px 8px 16px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
}

.event-count,
.event-name,
.event-cancelled {
    display: inline;
    padding: 0 10px;
    font-size: 1rem;
}

.event-count {
    color: #52A0FD;
    text-align: right;
}

.event-name {
    padding-right: 0;
    text-align: left;
}

.event-cancelled {
    color: #FF1744;
    text-align: right;
}

/*  Calendar wrapper */
.calendar-container {
    float: left;
    position: relative;
    margin: 0px auto;
    height: 100%;
    background: #fff;
    display: inline-block;
    border-bottom-left-radius: 3px;
    border-top-left-radius: 3px;
}

.calendar-container:after {
    clear: both;
}

.calendar {
    display: table;
}

/* Calendar Header */
.year-header {
    background: #52A0FD;
    background: -moz-linear-gradient(left, #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
    background: -webkit-linear-gradient(left, #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
    background: linear-gradient(to right, #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
    font-family: Helvetica;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
    -moz-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
    -webkit-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
    height: 40px;
    text-align: center;
    position: relative;
    color: #fff;
    border-top-left-radius: 3px;
}

.year-header span {
    display: inline-block;
    font-size: 20px;
    line-height: 40px;
}

.left-button,
.right-button {
    cursor: pointer;
    width: 28px;
    text-align: center;
    position: absolute;
}

.left-button {
    left: 0;
    -webkit-border-top-left-radius: 5px;
    -moz-border-radius-topleft: 5px;
    border-top-left-radius: 5px;
}

.right-button {
    right: 0;
    top: 0;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topright: 5px;
    border-top-right-radius: 5px;
}

.left-button:hover {
    background: #3FADFF;
}

.right-button:hover {
    background: #00C1FF;
}

/* Buttons */
.button {
    cursor: pointer;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    outline: none;
    font-size: 1rem;
    border-radius: 25px;
    padding: 0.65rem 1.9rem;
    transition: .2s ease all;
    color: white;
    border: none;
    box-shadow: -1px 10px 20px #9BC6FD;
    background: #52A0FD;
    background: -moz-linear-gradient(left, #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
    background: -webkit-linear-gradient(left, #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
    background: linear-gradient(to right, #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
}

#cancel-button {
    box-shadow: -1px 10px 20px #FF7DAE;
    background: #FF1744;
    background: -moz-linear-gradient(left, #FF1744 0%, #FF5D95 80%, #FF5D95 100%);
    background: -webkit-linear-gradient(left, #FF1744 0%, #FF5D95 80%, #FF5D95 100%);
    background: linear-gradient(to right, #FF1744 0%, #FF5D95 80%, #FF5D95 100%);
}

#add-button {
    display: block;
    position: absolute;
    right: 20px;
    bottom: 20px;
}

#add-button:hover,
#ok-button:hover,
#cancel-button:hover {
    transform: scale(1.03);
}

#add-button:active,
#ok-button:active,
#cancel-button:active {
    transform: translateY(3px) scale(.97);
}

/* Days/months tables */
.days-table,
.dates-table,
.months-table {
    border-collapse: separate;
    text-align: center;
    width: 100%;
}

.day {
    height: 26px;
    width: 26px;
    padding: 0 10px;
    line-height: 26px;
    border: 2px solid transparent;
    text-transform: uppercase;
    font-size: 90%;
    color: #9e9e9e;
}

.month {
    cursor: default;
    height: 26px;
    width: 26px;
    padding: 0 2px;
    padding-top: 10px;
    line-height: 26px;
    text-transform: uppercase;
    font-size: 11px;
    color: #9e9e9e;
    transition: all 250ms;
}

.active-month {
    font-weight: bold;
    font-size: 14px;
    color: #FF1744;
    text-shadow: 0 1px 4px RGBA(255, 50, 120, .8);
}

.month:hover {
    color: #FF1744;
    text-shadow: 0 1px 4px RGBA(255, 50, 120, .8);
}

/*  Dates table */
.table-date {
    cursor: default;
    color: #2b2b2b;
    height: 26px;
    width: 26px;
    font-size: 15px;
    padding: 10px;
    line-height: 26px;
    text-align: center;
    border-radius: 50%;
    border: 2px solid transparent;
    transition: all 250ms;
}

.table-date:not(.nil):hover {
    border-color: #FF1744;
    box-shadow: 0 2px 6px RGBA(255, 50, 120, .9);
}

.event-date {
    border-color: #52A0FD;
    box-shadow: 0 2px 8px RGBA(130, 180, 255, .9);
}

.active-date {
    background: #FF1744;
    box-shadow: 0 2px 8px RGBA(255, 50, 120, .9);
    color: #fff;
}

.event-date.active-date {
    background: #52A0FD;
    box-shadow: 0 2px 8px RGBA(130, 180, 255, .9);
}

/* input dialog */
.dialog {
    z-index: 5;
    background: #fff;
    position: absolute;
    width: 415px;
    height: 500px;
    left: 387px;
    border-top-right-radius: 3px;
    border-bottom-right-radius: 3px;
    display: none;
    border-left: 1px #aaa solid;
}

.dialog-header {
    margin: 20px;
    color: #333;
    text-align: center;
}

/* .form-container {
        margin-top: 25%;
    } */

.form-label {
    color: #333;
}

.input {
    border: none;
    background: none;
    border-bottom: 1px #aaa solid;
    display: block;
    margin-bottom: 50px;
    width: 200px;
    height: 20px;
    text-align: center;
    transition: border-color 250ms;
}

.input:focus {
    outline: none;
    border-color: #00C9FB;
}

.error-input {
    border-color: #FF1744;
}

/* Tablets and smaller */
@media only screen and (max-width: 780px) {
    .content {
        overflow: visible;
        position: relative;
        max-width: 100%;
        width: 370px;
        height: 100%;
        background: #52A0FD;
        background: -moz-linear-gradient(left, #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
        background: -webkit-linear-gradient(left, #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
        background: linear-gradient(to right, #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
    }

    .dialog {
        width: 370px;
        height: 450px;
        border-radius: 3px;
        top: 0;
        left: 0;
    }

    .events-container {
        float: none;
        overflow: visible;
        margin: 0 auto;
        padding: 0;
        display: block;
        left: 0;
        border-radius: 3px;
    }

    .calendar-container {
        float: none;
        padding: 0;
        margin: 0 auto;
        margin-right: 0;
        display: block;
        left: 0;
        border-radius: 3px;
        box-shadow: 3px 8px 16px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        -moz-box-shadow: 3px 8px 16px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        -webkit-box-shadow: 3px 8px 16px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
    }
}

/* Small phone screens */
@media only screen and (max-width: 400px) {

    .content,
    .events-container,
    .year-header,
    .calendar-container {
        width: 320px;
    }

    .dialog {
        width: 320px;
    }

    .months-table {
        display: block;
        margin: 0 auto;
        width: 320px;
    }

    .event-card {
        width: 300px;
    }

    .day {
        padding: 0 7px;
    }

    .month {
        display: inline-block;
        padding: 10px 10px;
        font-size: .8rem;
    }

    .table-date {
        width: 20px;
        height: 20px;
        line-height: 20px;
    }

    .event-name,
    .event-count,
    .event-cancelled {
        font-size: .8rem;
    }

    .add-button {
        bottom: 10px;
        right: 10px;
        padding: 0.5rem 1.5rem;
    }
}

input[type=checkbox] {
    position: relative;
    border: 2px solid #000;
    border-radius: 2px;
    background: none;
    cursor: pointer;
    line-height: 0;
    margin: 0 .6em 0 0;
    outline: 0;
    padding: 0 !important;
    vertical-align: text-top;
    height: 20px;
    width: 20px;
    -webkit-appearance: none;
    opacity: .5;
}

input[type=checkbox]:hover {
    opacity: 1;
}

input[type=checkbox]:checked {
    background-color: #000;
    opacity: 1;
}

input[type=checkbox]:before {
    content: '';
    position: absolute;
    right: 50%;
    top: 50%;
    width: 4px;
    height: 10px;
    border: solid #FFF;
    border-width: 0 2px 2px 0;
    margin: -1px -1px 0 -1px;
    transform: rotate(45deg) translate(-50%, -50%);
    z-index: 2;
}

.app-check {
    display: flex;
    border: 1px solid gray;
    padding: 6px;
    border-radius: 10px;
    /* width: 150px; */
    /* margin: 5px; */
    justify-content: center;
    align-items: center;
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
        {!! csrf_field() !!}
        <input type="hidden" name="user_id" id="user_id" value="{{ $userId }}" />
        <div id='calendar'></div>

        <div class="modal fade" id="largeModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form class="form" id="myForm" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title">Available Time Slot</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-container" align="center">
                                <div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6" style="position: relative;">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="" />
                                        </div>
                                        <div class="col-sm-6" style="position: relative;">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3 p-2">
                                            <div class="app-check">
                                                <input type="checkbox" class="option-input radio" name="check_all" id="check_all" value="" />
                                                <div class="app-border">
                                                    <label for="check_all" class="app-label">Check All</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3 p-2">
                                            <div class="app-check">
                                                <input type="checkbox" class="option-input radio available_slot" name="available_slot" id="slot_1" value="10:00 AM" />
                                                <div class="app-border">
                                                    <label for="slot_1" class="app-label">10:00 AM</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 p-2">
                                            <div class="app-check">
                                                <input type="checkbox" class="option-input radio available_slot" name="available_slot" id="slot_2" value="11:00 AM" />
                                                <div class="app-border">
                                                    <label for="slot_2" class="app-label">11:00 AM</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 p-2">
                                            <div class="app-check">
                                                <input type="checkbox" class="option-input radio available_slot" name="available_slot" id="slot_3" value="12:00 PM" />
                                                <div class="app-border">
                                                    <label for="slot_3" class="app-label">12:00 PM</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 p-2">
                                            <div class="app-check">
                                                <input type="checkbox" class="option-input radio available_slot" name="available_slot" id="slot_4" value="01:00 PM" />
                                                <div class="app-border">
                                                    <label for="slot_4" class="app-label">1:00 PM</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 p-2">
                                            <div class="app-check">
                                                <input type="checkbox" class="option-input radio available_slot" name="available_slot" id="slot_5" value="02:00 PM" />
                                                <div class="app-border">
                                                    <label for="slot_5" class="app-label">2:00 PM</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 p-2">
                                            <div class="app-check">
                                                <input type="checkbox" class="option-input radio available_slot" name="available_slot" id="slot_6" value="03:00 PM" />
                                                <div class="app-border">
                                                    <label for="slot_6" class="app-label">3:00 PM</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 p-2">
                                            <div class="app-check">
                                                <input type="checkbox" class="option-input radio available_slot" name="available_slot" id="slot_7" value="04:00 PM" />
                                                <div class="app-border">
                                                    <label for="slot_7" class="app-label">4:00 PM</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 p-2">
                                            <div class="app-check">
                                                <input type="checkbox" class="option-input radio available_slot" name="available_slot" id="slot_8" value="05:00 PM" />
                                                <div class="app-border">
                                                    <label for="slot_8" class="app-label">5:00 PM</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 p-2">
                                            <div class="app-check">
                                                <input type="checkbox" class="option-input radio available_slot" name="available_slot" id="slot_9" value="06:00 PM" />
                                                <div class="app-border">
                                                    <label for="slot_9" class="app-label">6:00 PM</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 p-2">
                                            <div class="app-check">
                                                <input type="checkbox" class="option-input radio available_slot" name="available_slot" id="slot_10" value="07:00 PM" />
                                                <div class="app-border">
                                                    <label for="slot_10" class="app-label">7:00 PM</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary save-change">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@include('admin.layout.footer')
<!-- Dialog Box-->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css"/> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script> -->
<!-- <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" integrity="sha512-liDnOrsa/NzR+4VyWQ3fBzsDBzal338A1VfUpQvAcdt+eL88ePCOd3n9VQpdA0Yxi4yglmLy/AmH+Lrzmn0eMQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js" integrity="sha512-iusSCweltSRVrjOz+4nxOL9OXh2UA0m8KdjsX8/KUUiJz+TCNzalwE0WE6dYTfHDkXuGuHq3W9YIhDLN7UNB0w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

<link rel="stylesheet" href="{{ URL::to('public/assets/fullcalendar/fullcalendar.min.css') }}" />
<script src="{{ URL::to('public/assets/fullcalendar/lib/jquery.min.js') }}"></script>
<script src="{{ URL::to('public/assets/fullcalendar/lib/moment.min.js') }}"></script>
<script src="{{ URL::to('public/assets/fullcalendar/fullcalendar.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>


<script>
var userId = $('#user_id').val();

$(document).ready(function() {

    let event_data = {"events":[]};

    $('#from_date').datepicker({
        dateFormat: 'dd-mm-yy'
    });
    $('#to_date').datepicker({
        dateFormat: 'dd-mm-yy'
    });

    $('.available_slot').change(function () {
        var allChecked = $('.available_slot:checked').length === $('.available_slot').length;
        $('#check_all').prop('checked', allChecked);
    });

    // Check or uncheck all available_slot checkboxes when "Check All" is clicked
    $('#check_all').change(function () {
        $('.available_slot').prop('checked', $(this).prop('checked'));
    });

    getAvailabilityData();

    var calendar = $('#calendar').fullCalendar({
        editable: true,
        events: "fetch-event.php",
        displayEventTime: false,
        eventRender: function(event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        selectable: true,
        selectHelper: true,
        select: function(start, end, allDay) {
            // var title = prompt('Event Title:');

            $("#largeModal").modal('show');

            // if (title) {
                var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                var end = $.fullCalendar.formatDate(end, "Y-MM-DD");

                // Assuming 'end' is your date variable
                // var start = moment(start, "Y-MM-DD");
                var end = moment(end, "Y-MM-DD");
                // Subtract one day from the date
                var newEndDate = end.subtract(1, 'days');

                $('#from_date').datepicker('setDate', new Date(start));
                $('#to_date').datepicker('setDate', new Date(newEndDate));

                $("input[name='available_slot']").each(function() {
                    $(this).prop("checked", false);
                });

                for (var i = 0; i < event_data['events'].length; i++) {    
                    var event = event_data["events"][i];
                    $("input[name='available_slot']").each(function() {
                        if (event['available_date'] === start) {
                            if ($(this).val() === event['invited_count']) {
                                $(this).prop("checked", true);
                            }
                        }
                    });
                }

                // $.ajax({
                //     url: 'add-event.php',
                //     data: 'title=' + title + '&start=' + start + '&end=' + end,
                //     type: "POST",
                //     success: function(data) {
                //         displayMessage("Added Successfully");
                //     }
                // });
                // calendar.fullCalendar('renderEvent', {
                //         title: title,
                //         start: start,
                //         end: end,
                //         allDay: allDay
                //     },
                //     true
                // );
            // }
            calendar.fullCalendar('unselect');
        },

        editable: true,
        eventDrop: function(event, delta) {
            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
            $.ajax({
                url: 'edit-event.php',
                data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' +
                    event.id,
                type: "POST",
                success: function(response) {
                    displayMessage("Updated Successfully");
                }
            });
        },
        eventClick: function(event) {
            var deleteMsg = confirm("Do you really want to delete?");
            if (deleteMsg) {
                $.ajax({
                    type: "POST",
                    url: "delete-event.php",
                    data: "&id=" + event.id,
                    success: function(response) {
                        if (parseInt(response) > 0) {
                            $('#calendar').fullCalendar('removeEvents', event.id);
                            displayMessage("Deleted Successfully");
                        }
                    }
                });
            }
        }
    });

    $("#myForm").submit(function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();

        var checkedValues = $("input[name='available_slot']:checked").map(function() {
                                return this.value;
                            }).get();

        var availableSlot = '';
        if (checkedValues.length > 0) {
            availableSlot = checkedValues.join(",");
        }

        if(availableSlot)
        {
            var userId = $('#user_id').val();

            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();

            $.ajax({
                type:'post',
                headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
                url:'{{ route("admin.escorts.availability.add") }}',
                data: { available_slot: availableSlot, date: fromDate, user_id: userId, from_date: fromDate, to_date: toDate },
                success:function(response)
                {
                    if(response.success)
                    {
                        Swal.fire({
                            title: 'Success',
                            icon: 'success',
                            html: response.message,
                            showCloseButton: true,
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#largeModal").modal('hide');
                            }
                        });
                    }
                    // new_event_json('', availableSlot, date, day);
                    // date.setDate(day);
                    // init_calendar(date);
                    getAvailabilityData()
                }
            });
        }
    });
    
    function getAvailabilityData()
    {
        $.ajax({
            type:'post',
            headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
            url:'{{ route("admin.escorts.availability.list") }}',
            data: { user_id: userId },
            success:function(response)
            {
                if(response.success)
                {                    
                    eventData = JSON.parse(response.data);
                    
                    if(eventData.length == undefined && eventData.events.length > 0)
                    {
                        event_data = JSON.parse(response.data);
                    }
                    else
                    {
                        event_data = {"events":[]};
                    }
    
                    $("input[name='available_slot']").each(function() {
                        $(this).prop("checked", false);
                    });

                    $('#check_all').prop("checked", false)

                    calendar.fullCalendar('removeEvents');
    
                    for (var i = 0; i < event_data['events'].length; i++) {
    
                        var event = event_data["events"][i];
    
                        var fullDate = moment(event["year"] + '-' + event["month"] + '-' + event["day"], "Y-MM-DD HH:mm:ss");

                        var start = $.fullCalendar.formatDate(fullDate, "Y-MM-DD HH:mm:ss");
                        var end = $.fullCalendar.formatDate(fullDate, "Y-MM-DD HH:mm:ss");
    
                        calendar.fullCalendar('renderEvent', {
                                title: event['invited_count'],
                                start: start,
                                end: end,
                                allDay: true
                            }
                        );
                    }
                }
            }
        });
    }
});


function displayMessage(message) {
    $(".response").html("<div class='success'>" + message + "</div>");
    setInterval(function() {
        $(".success").fadeOut();
    }, 1000);
}
</script>
@include('admin.layout.end')