@extends('layouts.layout')

@section('sidebar')
    @include('layouts.sidebar', ['sidebar'=> Menu::get('sidebar_admin')])
@endsection
@section('css')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script>
        window.temp_define = window['define'];
        window['define']  = undefined;
    </script>
            <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.32/moment-timezone.min.js" integrity="sha512-u3yRfU7FD5wGhxEMFZLZT/W/Y+C0vqUuQjPAhRWnQjBZ1LhUMnyTnZ6AfwxLSCxACT4eiyAnjFAMIt0qog67qg==" crossorigin="anonymous"></script>
    <script>
            window['define'] = window.temp_define;
    </script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    {{-- <link rel="stylesheet" href="{{mix('/css/package.css', 'vendor/processmaker/packages/adoa')}}"> --}}
    <style>
        [v-cloak] {
            display:none;
        }

        .dark-primary-color    { background: #411f91; }
        .default-primary-color { background: #673AB7; }
        .light-primary-color   { background: #e4d9f7; }
        .text-primary-color    { color: #FFFFFF; }
        .accent-color          { background: #FBBE02; }
        .primary-text-color    { color: #212121; }
        .secondary-text-color  { color: #757575; }
        .divider-color         { border-color: #BDBDBD; }

        #dataTable > tbody > tr > td > a > i {
            color: #673AB7 !important;
        }
        #dataTable > tbody > tr > td > a > i:hover {
            color:  #411f91 !important;
        }

        .dt-button  {
            border:0px !important;
            background-color: #FBBE02 !important;
            color: #fff !important;
            font-weight: bold;
            padding:0;
        }
        .dt-button:hover{
            background-color: #FBBE02 !important;
        }
        .dt-button > span{
            background-color: #f0cc60 !important;
            padding: 5px;
        }
        .dt-button > span:hover{
            border: 3px 0px 3px 0px solid #FBBE02 !important;
            background-color: #FBBE02 !important;
        }


        #dataTable > thead > tr {
            text-align: center;
            background-color: #411f91;
            color: #fff;

        }
        #dataTable > thead > tr > th {
            border-bottom: 3px solid  #FBBE02 !important;
            padding-top:10px;
            padding-bottom:10px;
        }
        #dataTable > thead > tr > th:hover {
            background-color: #FBBE02 !important;
            /* border-bottom: 3px solid #411f91 !important; */
        }
        #dataTable > tbody > tr:hover {
            background-color:#e4d9f7 !important;
        }
        td > a :hover{
            color:  #4c288c !important;
        }
        #dataTable_paginate > ul > li.paginate_button.page-item.active > a {
            background-color: #411f91 !important;
            color: #fff !important;
        }
        #dataTable_paginate > ul > li > a{
            color: #411f91 !important;
        }
        #dataTable_previous > a {
            color: #411f91 !important;
        }

        #app > div > div.card-deck > div:nth-child(1) {
            background-color: #411f91 !important;
        }
        #app > div > div.card-deck > div:nth-child(3) {
            background-color: #6c757d !important;
        }
        #dataTable_wrapper > div.dt-buttons.btn-group.flex-wrap > button.btn.btn-secondary:hover{
            background-color: #FBBE02 !important;
            border:1px solid #FBBE02 !important;
        }

        #broadcastTable > thead > tr {
            text-align: center;
            background-color: #411f91;
            color: #fff;
        }
        #broadcastTable > tbody > tr {
            text-align: center;
        }
        #broadcastTable > tbody > tr > td > a > i {
            color: #411f91;
        }
        .avatar-container{
            position: relative;
            height: 30px;
        }
        .zoom-avatar {
            position: absolute;
            display: none;
            top: -50px;
            left: 50px;
            width: 100px;
        }

        .avatar-container:hover .zoom-avatar{
            display: inherit !important;
        }

        .select2 > span, .select2-selection, .selection, .select2-selection__placeholder, .select2-selection__rendered {
            border-radius:0px !important;
        }
        #select2-adoaEmployee-results > .select2-results__option:hover {
            background-color: #71A2D4 !important;
            color:#fff;
        }

    </style>

@endsection

@section('content')
    <div class="card" id="app">
        <div class="card-body" style="border: 1px solid #eee;" v-cloak>
            <div id="slipTitle" style="margin: 20px;">
                <h3>@{{ appData.slipTitle }},  Slip No. @{{ caseId }}</h3>
                <span class="badge badge-primary">Requestor: @{{ appData.requestorName }}</span>
            </div>

            <div class="card-deck" style="margin-top: 20px;margin-bottom:20px;">

                <div class="card bg-success d-flex flex-row card-border border-0 text-white">
                    <div class="card-header card-size-header px-4 px-xl-5 d-flex d-md-none d-lg-flex align-items-center justify-content-center border-0">

                        <i class="fas fa-id-badge fa-2x"></i>
                    </div>
                    <div class="card-body">
                        <span href="" class="card-link text-light">
                            <h1 class="m-0 font-weight-bold"> @{{ studentsAmount }}</h1>
                            <h6 class="card-text">Students</h6>
                        </span>
                    </div>
                </div>

                <div class="card bg-warning d-flex flex-row card-border border-0 text-white">
                    <div class="card-header card-size-header px-4 px-xl-5 d-flex d-md-none d-lg-flex align-items-center justify-content-center border-0">
                        <i class="fas fa-clipboard-check fa-2x"></i>
                    </div>
                    <div class="card-body">
                        <span  class="card-link text-light">
                            <h1 class="m-0 font-weight-bold"> @{{ caseResponse }}</h1>
                            <h6 class="card-text">Responses</h6>
                        </span>
                    </div>
                </div>

                <div class="card bg-warning d-flex flex-row  card-border border-0 text-white">
                    <div class="card-header card-size-header px-4 px-xl-5 d-flex d-md-none d-lg-flex align-items-center justify-content-center border-0">
                        <i class="fas fa-chart-bar fa-2x"></i>
                    </div>
                    <div class="card-body">
                        <span class="card-link text-light">
                            <h1 class="m-0 font-weight-bold"> @{{ porcentageResponse() }}%</h1>
                            <h6 class="card-text">Response Rate</h6>
                        </span>
                    </div>
                </div>
            </div>

            {{-- NavBar Actions --}}
            <nav class="navbar navbar-expand-lg navbar-dark " id="actionsMenu" style="padding-left: 0px;padding-right: 0px;">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse bg-secondary" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto  bg-secondary">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dataTablePrint" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dataTablePrint">
                                <a class="dropdown-item" href="#" id="sendEmailMarkAttendance" data-toggle="modal" data-target="#modalsendEmailMarkAttendance"><i class="fas fa-envelope"> </i> Email Mark Attendance link</a>
                                <a class="dropdown-item" href="#" id="cloneRequest" data-toggle="modal" data-target="#modalCloneRequest"><i class="fas fa-copy text-success"> </i> Clone Slip</a>
                                <a class="dropdown-item" href="#" id="deleteRequest" data-toggle="modal" data-target="#modalDeleteRequest"  data-toggle="modal" data-target="#modalDeleteRequest"><i class="fas fa-trash text-danger"> </i> Delete Slip</a>
                                <a class="dropdown-item" href="#" id="updateDueDate" data-toggle="modal" data-target="#modalUpdateDueDate"><i class="fas fa-calendar text-info"> </i> Update Due Date</a>
                                <a class="dropdown-item" href="#" id="setUpClosingDate" data-toggle="modal" data-target="#modalSetUpClosingDate"><i class="fas fa-calendar-alt text-info"> </i> Set/Up Closing Date</a>
                                {{-- <a class="dropdown-item" href="#" id="setUpCalendarDates" data-toggle="modal" data-target="#modalSetUpCalendarDates"><i class="fas fa-calendar-alt text-info"> </i> Set/Up Calendar Dates</a> --}}
                                <a class="dropdown-item" href="#" id="addRemoveStudent" data-toggle="modal" data-target="#modalAddRemoveStudent"><i class="fas fa-user text-warning"> </i> Add/RemoveStudent</a>
                                <a class="dropdown-item" href="#" id="updateLimitResponses" data-toggle="modal" data-target="#modalUpdateLimitResponses"><i class="fas fa-users"> </i> Set/Update Limit Responses</a>
                                <a class="dropdown-item" href="#" id="copySlipToStaff" data-toggle="modal" data-target="#modalCopySlipToStaff"><i class="fas fa-user-graduate text-success"> </i> Copy Slip to Staff</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="true">Slip</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="responses-tab" data-toggle="tab" href="#responses" role="tab" aria-controls="responses" aria-selected="false">Responses ( @{{ caseResponse }} )</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="broadcast-tab" data-toggle="tab" href="#broadcast" role="tab" aria-controls="broadcast" aria-selected="false">Broadcast</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent" style="width: 100%;">
                    <div class="tab-pane fade show active" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                        <div style="border: 1px solid #eee; padding:30px;">
                            <div style="border: 1px solid #eee; padding:10px;margin:20px;" class="table-responsive-sm">
                                <table class=" table-sm" style="width: 100%;">
                                    <tr>
                                        <td style="width:25% !important;font-weight: bold;">Status</td>
                                        <td v-if="appData.status == 'SENT'"><span class="badge badge-success">SENT</span></td>
                                        <td v-if="appData.status == 'QUEUED'"><span class="badge badge-primary">QUEUED</span></td>
                                        <td v-if="appData.status == 'PENDING_APPROVAL'"><span class="badge badge-info">PENDING APPROVAL</span></td>
                                        <td v-if="appData.status == 'DRAFT'"><span class="badge badge-secondary">DRAFT</span></td>
                                        <td v-if="appData.status == 'REJECTED'"><span class="badge badge-danger">REJECTED</span></td>
                                        <td rowspan="7" style="text-align: center;">
                                            <img src="{{asset('/vendor/processmaker/packages/package-pinnacle/images/pinnacle_logo.jpg')}}" alt="Pinnacle College"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">Student List(s)</td>
                                        <td> <span v-for="(student, index) in studentList"> @{{ student.firstName }} @{{ student.lastName }}<span v-if="typeof studentList !== 'undefined' && index+1 < studentList.length">, </span> </span> </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">Who completes this Slip</td>
                                        <td><i class="fas fa-user"> </i> @{{ appData.slipCompletedBy }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">Slip Due Date</td>
                                        <td>@{{ formatDate(appData.slipDueDate) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">Slip Date Time to Send</td>
                                        <td>@{{ formatDate(appData.slipDateToSend) }} @ @{{ appData.timeToSend }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">Close Responses after Date</td>
                                        <td v-if="appData.closingDate!= null && typeof appData.closingDate !== 'undefined' && (appData.closingDate).length > 0"> @{{ formatDate(appData.closingDate) }} </td>
                                        <td v-if="appData.closingDate!= null && typeof appData.closingDate !== 'undefined' && (appData.closingDate).length == 0"> No Closing Date </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">Limit Responses</td>
                                        <td v-if="appData.limitResponses == 'ALL' "> Accept all Responses </td>
                                        <td v-if="appData.limitResponses == 'FIRST' "> First @{{ appData.limitFirst }} positive responses</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="">
                                <div class="" style="padding: 20px;"><h5 class="title">Activity Information</h5></div>
                                <div class="alert alert-primary" role="alert" style="margin: 20px;">
                                    Enter information about the activity, this will be displayed to the parents when they view the slip.
                                </div>
                                <div style="border: 1px solid #eee; padding:10px;margin:20px;">
                                    <table class=" table-sm" style="width: 100%;">
                                        <tr>
                                            <td style="width:40% !important;font-weight: bold;">Date of Activity</td>
                                            <td>@{{ formatDate(appData.activityDate) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Venue of Activity</td>
                                            <td>@{{ appData.activityVenue }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Address of Venue</td>
                                            <td>@{{ appData.venueAddress }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Time of Bus Departing School</td>
                                            <td>@{{ appData.departingSchoolTime }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Time of Bus Returning to School</td>
                                            <td>@{{ appData.returningSchoolTime }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Cost</td>
                                            <td>@{{ appData.excursionCost }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Uniform Requirements</td>
                                            <td>@{{ appData.uniformRequirements }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Activity Information</td>
                                            <td v-html="appData.activityInformation"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="" style="padding: 20px;"><h5 class="title">Parent/Student Information</h5></div>
                                <div class="alert alert-primary" role="alert" style="margin: 20px;">
                                    These fields are for the parents/students to complete when making their response.
                                </div>
                                <div style="border: 1px solid #eee; padding:10px;margin:20px;">
                                    <table class=" table-sm" style="width: 100%;">
                                        <tr>
                                            <td style="width:40% !important;font-weight: bold;">I give permission for my child to attend the above activity</td>
                                            <td>
                                                <select class="form-control" placeholder="Parent to complete">
                                                    <option>Parent to complete</option>
                                                    <option value="YES">Permission Given</option>
                                                    <option value="NO">Permission NOT Given</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Emergency Contact Name</td>
                                            <td><input type="text" class="form-control" placeholder="Parent to complete"/></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Emergency Contact Number</td>
                                            <td><input type="text" class="form-control" placeholder="Parent to complete"/></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Please enter any Medical Conditions for your child</td>
                                            <td><input type="text" class="form-control" placeholder="Parent to complete" /></td>
                                        </tr>

                                    </table>
                                </div>
                                <div class="" style="padding: 20px;"><h5 class="title">Files to include on the Slip</h5></div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">
                                    <div class="form-group">
                                        <div class="table-responsive" >
                                            <table class="table table-striped table-hover" style="width: 100%;" >
                                                <tbody>
                                                    <tr v-for="file in files" >
                                                        <td style="width: 80%;" class=""><small> @{{ file.file_name }} </small></td>
                                                        <td class="" style="width: 20%;text-align: center;">
                                                            <span><a href="" @click="downloadFile(file.id);"><i class="fas fa-download"></i></a></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Tab Responses --}}
                    <div class="tab-pane fade" id="responses" role="tabpanel" aria-labelledby="responses-tab">
                        <div class="table-responsive" style="width: 100%;padding-top:20px;">
                            <table id="dataTable" class="table table-striped table-sm" style="width: 100%; font-size: 14px;">
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="broadcast" role="tabpanel" aria-labelledby="broadcast-tab" style="padding: 20px;">
                        <div class="row" style="padding: 20px;">
                            <div class="col-md-12">
                                <div class="btn-group hideButtonsForPrint  float-right">
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createObjectFromSlipModal"><i class="fas fa-plus"> </i> Create Broadcast</button>
                                </div>
                            </div>

                        </div>
                        <div class="row" style="padding: 20px;">
                            <div class="table-responsive">
                                <table id="broadcastTable" class="table table-striped table-hover" style="width: 80%;border:1px solid #efefef;margin: auto;">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Date Time to Send</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="broadcast in broadcastList" v-if="broadcastList">
                                            <td>@{{ broadcast.broadcast_title }}</td>
                                            <td>@{{ broadcast.date_to_send }} &commat; @{{ broadcast.time_to_send }}</td>
                                            <td>
                                                <span class="badge badge-danger" v-if=" broadcast.status=='CANCELED'">CANCELED</span>
                                                <span class="badge badge-info" v-if=" broadcast.status=='QUEUED'">QUEUED</span>
                                                <span class="badge badge-success" v-if=" broadcast.status=='SENT'">SENT</span>
                                            </td>
                                            <td>
                                                <span><a :href="'{{url('/')}}/pinnacle/broadcast/'+ broadcast.broadcast_id"><i class="fas fa-edit"></i></a></span>
                                                <span><a :href="'{{url('/')}}/pinnacle/broadcast/view/' + broadcast.broadcast_id"><i class="fas fa-external-link-square-alt"> </i></a></span>
                                            </td>
                                        </tr>
                                        <tr v-else><td colspan="4"><span class="alert alert-primary" role="alert" style="width: 80%;">No records to show.</span></td></tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Modal Show Summary-->
        <div class="modal fade" id="studentDetailSlip" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 style="margin: auto;" class="text-primary">Parent Approval Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body needs-validation" style="padding: 25px !important;">

                        <div class="col col-12" style="margin: 20px 0px;">
                            <h4 style="text-align:left;" class="text-primary">@{{ appData.slipTitle }}</h4>
                            <hr class="row col-12">
                        </div>

                        <div class="form-group row col-lg-12">
                            <label for="studentName" class="col-lg-3 col-sm-12 col-12 col-form-label">Student name</label>
                            <div class="col-lg-6 col-sm-12 col-12">
                            <input type="text" class="form-control" id="studentName" placeholder="Student Name" disabled v-model="studentfullName">
                            </div>
                        </div>

                        <div class="form-group row col-lg-12">
                            <label for="studentName" class="col-lg-3 col-sm-12 col-12 col-form-label">Date of Activity</label>
                            <div class="col-lg-6 col-sm-12 col-12">
                            <input type="date" class="form-control" disabled v-model="caseData.activityDate">
                            </div>
                        </div>


                        <div class="row col-lg-12 col-md-12 col-sm-12 col-12">
                            <h5 class="text-primary">Information about the activity</h5>
                        </div>

                        <div class="row">
                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12" style="">
                                <label for="">Address of Venue</label>
                                <input type="text" class="form-control" disabled v-model="caseData.venueAddress">
                            </div>
                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12" style="">
                                <label for="">Time of Bus Departing School</label>
                                <input type="text" class="form-control" disabled v-model="caseData.departingSchoolTime">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12" style="">
                                <label for="">Time of Bus Returning to School</label>
                                <input type="text" class="form-control" disabled v-model="caseData.returningSchoolTime">
                            </div>
                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12" style="">
                                <label for="">Cost of Excursion</label>
                                {{-- <currency-input  class="form-control" style="text-align: right;" disabled
                                    v-model="caseData.excursionCost"
                                    currency="USD"
                                    locale="en"
                                /> --}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12" style="">
                                <label for="">Uniform Requirements</label>
                                <input type="text" class="form-control" disabled v-model="caseData.uniformRequirements">
                            </div>
                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12" style="">
                                <label for="">Activity Information</label>
                                <div  class="" style="border: 1px solid #b8b8b8; border-radius: 2px;background-color:#e9ecef;padding:2px 10px 2px 10px;" v-html="appData.activityInformation" >@{{ appData.activityInformation }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12" style="margin-top: 20px;">
                                <h5 class="" style="font-weight: bold;"> Please complete the following fields. </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12" v-if="caseData.parentComplete == 1">
                                <label for="">I give permission for my child <span style="color: red;">*</span></label>
                                <select class="form-control" v-model="givePermissionForChild" placeholder="Please select an option" required  :disabled="!editMode">
                                    <option value="">Please select an option</option>
                                    <option value="YES">Yes</option>
                                    <option value="NO">No</option>
                                </select>
                                <div class="invalid-feedback">
                                    This field is Required.
                                </div>
                            </div>
                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12" v-if="caseData.contactInfo == 1">
                                <label for="">Emergency Contact Name <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" v-model="emergencyContactName" required :disabled="!editMode">
                                <div class="invalid-feedback">
                                    This field is Required.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12" v-if="caseData.contactInfo == 1">
                                <label for="">Emergency Contact Number <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" v-model="emergencyContactNumber" pattern="[0-9]{8}" required :disabled="!editMode">
                                <div class="invalid-feedback">
                                    This field is mandatory and must have an extension equal to 8 characters.
                                </div>
                            </div>
                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12" v-if="caseData.medicalInfo == 1">
                                <label for="">Please enter any Medical Conditions for your child <span style="color: red;">*</span></label>
                                <textarea rows="2" class="form-control" v-model="medicalConditions" required :disabled="!editMode"></textarea>
                                <div class="invalid-feedback">
                                    This field is Required.
                                </div>
                            </div>
                        </div>

                       <div class="row">
                            <div class="col-12" style="margin-top: 20px;" v-show="caseData.paymentRequired == 'YES' || (caseData.paymentRequired == 'ONLY' && givePermissionForChild=='YES')">
                                <h5 class="" style="font-weight: bold;"> Payment Data </h5>
                            </div>
                       </div>

                        <div class="row" v-show="caseData.paymentRequired == 'YES' || (caseData.paymentRequired == 'ONLY' && givePermissionForChild=='YES')">
                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12" >
                                <div class="col col-12 col-sm-12 col-md-12 col-lg-12 form-group">
                                    <label for="">Payment Requestes</label>
                                    <input type="text" class="form-control" v-model="caseData.paymentRequested" disabled>
                                </div>
                                <div class="col col-12 col-sm-12 col-md-12 col-lg-12 form-group">
                                    <label for="">Payment Required</label>
                                    <input type="text" class="form-control" v-model="caseData.paymentRequired" disabled>
                                </div>
                                <div class="col col-12 col-sm-12 col-md-12 col-lg-12 form-group">
                                    <label for="">Payment Amount</label>
                                    {{-- <currency-input id="paymentAmount" class="form-control" style="text-align: right;"
                                        :disabled="caseData.paymentAmountEdit != 'YES' || !editMode"
                                        v-model="caseData.paymentAmount"
                                        currency="USD"
                                        locale="en"
                                    /> --}}
                                </div>
                                <div class="col col-12 col-sm-12 col-md-12 col-lg-12 form-group">
                                    <label for="">Payment Reference</label>
                                    <input type="text" class="form-control" v-model="caseData.paymentReference" disabled>
                                </div>
                                <div class="col col-12 col-sm-12 col-md-12 col-lg-12 form-group">
                                    <label for="">Allow edit of amount?</label>
                                    <input type="text" class="form-control" v-model="caseData.paymentAmountEdit" disabled>
                                </div>

                            </div>

                            <div class="col col-lg-6 col-md-12 col-sm-12 col-12"
                                v-show="caseData.paymentRequired == 'YES' || (caseData.paymentRequired == 'ONLY' && givePermissionForChild=='YES')" required>
                                <div class="col col-xlg-12 col-lg-12 col-md-12 col-sm-12 col-12" style="text-align: center; overflow:auto;" v-show="payStatus!='approved'">
                                    <div id="payway-credit-card" style="margin:auto;"></div>
                                    <input type="hidden" id="singleUseTokenId" v-model="singleUseTokenId"/>
                                    <div class="col col-xlg-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        {{-- <button id="pay" @click="makePayment();" :disabled="buttonDisabled || caseData.paymentAmount <= 0 " class="btn btn-primary btn-block">Pay</button> --}}
                                        <button id="pay" :disabled="buttonDisabled || caseData.paymentAmount <= 0 " class="btn btn-primary btn-block"><i class="fas fa-credit-card"> </i> Pay</button>
                                        <small v-show="caseData.paymentAmount <= 0" class="text-danger">The amount to be canceled must be greater than zero</small>
                                    </div>
                                    <div class="col  col-12 col-sm-12 col-md-12 col-lg-12" style="text-align:left;" v-show="(caseData.paymentRequired == 'YES' || (this.caseData.paymentRequired == 'ONLY' && this.givePermissionForChild == 'YES')) && payStatus != 'approved' ">
                                    <small> Payment is required.<span style="color: red;">*</span></small>
                                    </div>
                                </div>

                                {{-- payment Status --}}

                                <div class="col  col-12 col-sm-12 col-md-12 col-lg-12 form-group" v-if="payStatus == 'approved'">
                                    <label for="">Transaction ID</label>
                                    <input type="text" class="form-control" v-model="transactionId" disabled>
                                </div>
                                <div class="col  col-12 col-sm-12 col-md-12 col-lg-12 form-group" v-if="payStatus == 'approved'">
                                    <label for="">Credit Card Number</label>
                                    <input type="text" class="form-control" v-model="creditCardNumber" disabled>
                                </div>
                                <div class="col  col-12 col-sm-12 col-md-12 col-lg-12 form-group" v-if="payStatus == 'approved'">
                                    <label for="">Amount</label>
                                    <input type="text" class="form-control" v-model="principalAmount" disabled>
                                </div>
                                <div class="col  col-12 col-sm-12 col-md-12 col-lg-12 form-group" v-if="payStatus == 'approved'">
                                    <label for="">Status</label>
                                    <input type="text" class="form-control" v-model="payStatus" disabled>
                                </div>

                                <br>
                                <div class="col  col-12 col-sm-12 col-md-12 col-lg-12 alert alert-success" role="alert" v-if="payStatus == 'approved'">
                                    <i class="fas fa-check text-success" style="font-size: 25px;"></i> &nbsp; The payment has been registered correctly.
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                        <button type="button" class="btn btn-primary btn-sm" :disabled="!editMode" @click="updateResponse()"><i class="far fa-save"> </i> Save changes</button>
                    </div>
                </div>
            </div>

            {{-- NavBar --}}
            <nav class="navbar navbar-expand-lg navbar-dark" id="dataTableSeach" style="padding-left: 0px;padding-right: 0px;">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto  bg-secondary">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dataTablePrint" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Export / Print
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dataTablePrint">
                                <a class="dropdown-item" href="#" id="printList"><i class="fas fa-print"> </i> Print List</a>
                                <a class="dropdown-item" :href="'{{url('/')}}/pinnacle/print-form/' + caseId + '?status=SUBMITTED'"><i class="fas fa-print"> </i> Print Submitted Forms</a>
                                <a class="dropdown-item" :href="'{{url('/')}}/pinnacle/print-form/' + caseId + '?status=NO_SUBMITTED'"><i class="fas fa-print"> </i> Print Not submitted Forms</a>
                                <a class="dropdown-item" :href="'{{url('/')}}/pinnacle/print-blank-form/' + caseId"><i class="fas fa-print"> </i>  Print Blank Form</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dataTableFilter" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Filter
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dataTableFilter">
                                <a href="#" class="dropdown-item response-status" dataTableFilter = "ALL"><i class="fas fa-list-alt" > </i> Show All</a>
                                <a href="#" class="dropdown-item response-status" dataTableFilter = "YES"><i class="fas fa-comment-check text-success"> </i> Only Positive Responses</a>
                                <a href="#" class="dropdown-item response-status" dataTableFilter = "NO"><i class="fas fa-comment-times text-warning"> </i> Not Positive Responses</a>
                                <a href="#" class="dropdown-item response-status" dataTableFilter = "ATTENDED"><i class="fas fa-check text-success"> </i> Attended</a>
                                <a href="#" class="dropdown-item response-status" dataTableFilter = "NO_ATTENDED"><i class="far fa-clock"> </i> No Attended</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dataTableGradeLavel" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Filter by Grade
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dataTableGrade" id="dataTableGrade">
                                <a href="#" class="dropdown-item response-grade" dataTableGrade = ""><i class="fas fa-list-alt" > </i> Show All</a>
                            </div>
                        </li>
                    </ul>
                    <form class="input-group navbar-right col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <input type="text" class="form-control form-control-sm" placeholder="Search" id="searchText">
                    </form>
                </div>
            </nav>
        </div>
        <!-- Modal Resent Message -->
        <div class="modal fade" id="resentMessage" tabindex="-1" role="dialog" aria-labelledby="resentMessageLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="resentMessageLabel">Reopen Slip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    Reopen the Slip for this Student and send a new notification?
                    <div v-if="typeof sms !== 'undefined' && sms.length > 0" v-for="message in sms">
                        <div class="form-group font-weight-bold">
                            <label for="smsNumber" >Phone Number</label>
                            <input type="text" class="form-control" id="smsNumber" v-model="message.number" disabled>
                        </div>
                        <div class="form-group font-weight-bold">
                            <label for="smsStatus" >Status</label>
                            <input type="text" class="form-control" id="smsStatus" v-model="message.status"  disabled>
                        </div>
                        <div class="form-group font-weight-bold" v-if="message.status != 'OK'">
                            <label for="smsErrorMessage" >Error</label>
                            <input type="text" class="form-control" id="smsErrorMessage" v-model="message.errorMessage"  disabled>
                        </div>
                        <br>
                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="sendNotification()"><i class="fas fa-paper-plane"> </i> Send Now</button>
                </div>
            </div>
            </div>
        </div>
        <!-- Modal sendEmailMarkAttendance-->
        <div class="modal fade" id="modalsendEmailMarkAttendance" tabindex="-1" role="dialog" aria-labelledby="modalsendEmailMarkAttendanceLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalsendEmailMarkAttendanceLabel">Email Mark Attendance Link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div>
                        <br>
                        <div class="form-group font-weight-bold">
                            <label for="usersMarkAttendance" >
                                Send the link to Mark Attendance to yourself, or another User.
                            </label>
                        </div>
                        <div class="form-group font-weight-bold">
                            <label for="usersMarkAttendance" >Select Recipient</label>
                            <select class="form-control" id="usersMarkAttendance" style="width:90%;" v-model="actionUsersMarkAttendance">
                                <option value="">-Select-</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                    <button type="button" class="btn btn-primary btn-sm" :disabled="typeof actionUsersMarkAttendance !== 'undefined' && actionUsersMarkAttendance.length <= 0" @click="sendAttendanceEmail()"> <i class="fas fa-paper-plane"> </i> Confirm</button>
                </div>
            </div>
            </div>
        </div>
        <!-- Modal Clone Case-->
        <div class="modal fade" id="modalCloneRequest" tabindex="-1" role="dialog" aria-labelledby="modalCloneRequestLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalCloneRequestLabel">Clone Slip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    Are you sure to clone this Slip?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="cloneRequest()" data-dismiss="modal"><i class="fas fa-check-circle"> </i> Confirm</button>
                </div>
            </div>
            </div>
        </div>
        <!-- Modal Delete Case-->
        <div class="modal fade" id="modalDeleteRequest" tabindex="-1" role="dialog" aria-labelledby="modalDeleteRequestLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteRequestLabel">Delete Slip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    Are you sure to Delete this Slip?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="deleteRequest()"><i class="fas fa-check-circle"> </i> Confirm</button>
                </div>
            </div>
            </div>
        </div>
        <!-- Modal UpdateDueDate Case-->
        <div class="modal fade" id="modalUpdateDueDate" tabindex="-1" role="dialog" aria-labelledby="modalUpdateDueDateLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalUpdateDueDateLabel">Set/Update Closing Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <br>
                    <div class="form-group font-weight-bold">
                        <label for="actionUpdateDueDate" >Slip Due Date</label>
                        <input type="date" class="form-control" id="actionUpdateDueDate" style="width:90%;" v-model="actionUpdateDueDate">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="updateDueDate()"><i class="fas fa-check-circle"> </i> Update</button>
                </div>
            </div>
            </div>
        </div>
        <!-- Modal UpdateClosing Date -->
        <div class="modal fade" id="modalSetUpClosingDate" tabindex="-1" role="dialog" aria-labelledby="modalSetUpClosingDateLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalSetUpClosingDateLabel">Set/Update Closing Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <br>
                    <div class="form-group font-weight-bold">
                        <input type="checkbox"  class="col-1" id="actionCheckClosingDate" v-model="actionCheckClosingDate">
                        <label for="actionClosingDate" class="col-9">Close Responses after this Date</label>
                        <input type="date" class="input-sm col-12" id="actionClosingDate" :disabled="!actionCheckClosingDate" v-model="actionClosingDate">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="setUpClosingDate()"><i class="fas fa-check-circle"> </i> Confirm</button>
                </div>
            </div>
            </div>
        </div>
        <!-- Modal UpdateCalendar Case-->
        <div class="modal fade" id="modalSetUpCalendarDates" tabindex="-1" role="dialog" aria-labelledby="modalSetUpCalendarDatesLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalSetUpCalendarDatesLabel">Set/Update Closing Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <br>
                    <div class="form-group font-weight-bold">
                        <label for="actionDateFrom" class="col-3">Date From</label>
                        <input type="date" class="input-sm col-4" id="actionDateFrom" style="" v-model="actionDateFrom">
                        <input type="time" class="input-sm col-4" id="actionTimeFrom" style="" v-model="actionTimeFrom">
                    </div>
                    <div class="form-group font-weight-bold">
                        <label for="actionDateTo" class="col-3">Date To</label>
                        <input type="date" class="input-sm col-4" id="actionDateTo" style="" v-model="actionDateTo">
                        <input type="time" class="input-sm col-4" id="actionTimeTo" style="" v-model="actionTimeTo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="setUpCalendarDates()"><i class="fas fa-check-circle"> </i> Confirm</button>
                </div>
            </div>
            </div>
        </div>
        <!-- Modal Add remove Student -->
        <div class="modal fade" id="modalAddRemoveStudent" tabindex="-1" role="dialog" aria-labelledby="modalAddRemoveStudentLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalAddRemoveStudentLabel">Add/Remove Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div>
                        <br>
                        <div class="form-group font-weight-bold">
                            <label for="actionAddRemoveUserOption" >Add or Remove?</label>
                            <select class="form-control" name="actionAddRemoveUserOption" id="actionAddRemoveUserOption" v-model="actionAddRemoveUserOption">
                                <option value="">--Select action --</option>
                                <option value="ADD">Add</option>
                                <option value="REMOVE">Remove</option>
                            </select>
                        </div>
                        <div class="form-group font-weight-bold">
                            <label for="actionAddUsersList" >Select one or more Students </label>
                            <span v-show="actionAddRemoveUserOption == 'ADD'">
                                <select multiple="multiple"
                                    class="form-control"
                                    id="actionAddUsersList"
                                    style="width:100%;"
                                    v-model="actionAddUsersList">
                                </select>
                            </span>
                            <span  v-show="actionAddRemoveUserOption == 'REMOVE'">
                                <select multiple="multiple"
                                    class="form-control"
                                    id="actionRemoveUsersList"
                                    style="width:100%;"
                                    v-model="actionRemoveUsersList">
                                </select>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                    <button type="button" :disabled="typeof actionAddUsersList !== 'undefined' && typeof actionRemoveUsersList !== 'undefined' && actionAddUsersList.length + actionRemoveUsersList.length <= 0" class="btn btn-primary btn-sm" @click="addRemoveStudent()">
                        <i class="fas fa-check-circle"> </i>
                         Update
                    </button>
                </div>
            </div>
            </div>
        </div>
        <!-- Modal Update Limit Responses -->
        <div class="modal fade" id="modalUpdateLimitResponses" tabindex="-1" role="dialog" aria-labelledby="modalUpdateLimitResponsesLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalUpdateLimitResponsesLabel">Set/Update Limit Responses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <br>
                    <div class="form-group font-weight-bold">
                        <label for="actionClosingDate" class="col-5"> Limit Responses </label>
                        <div class="col-12">
                            <small id="passwordHelpBlock" class="form-text text-info">
                                Only 'positive' Responses.
                            </small>
                            <select id="actionLimitResponse" class="form-control" v-model="actionLimitResponse">
                                <option value="ALL" selected> All </option>
                                <option value="FIRST"> First </option>
                            </select>
                            <br>
                            <input v-if="actionLimitResponse == 'FIRST' " type="number" class="input-sm col-12" id="actionLimitFirst" style="" v-model="actionLimitFirst">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="updateLimitResponses()"><i class="fas fa-check-circle"> </i> Confirm</button>
                </div>
            </div>
            </div>
        </div>
        <!-- Modal Copy Slip to Staff-->
        <div class="modal fade" id="modalCopySlipToStaff" tabindex="-1" role="dialog" aria-labelledby="modalCopySlipToStaffLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalCopySlipToStaffLabel">Copy Slip to Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div>
                        <br>
                        <div class="form-group font-weight-bold">
                            <small id="actionCopySlipToStaffLabel" class="form-text text-info">
                                Type name of User, or a User Group
                            </small>
                            <select multiple="multiple" class="form-control" id="actionCopySlipToStaff" style="width:90%;" v-model="actionCopySlipToStaff"></select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="copySlipToStaff()"><i class="fas fa-check-circle"> </i> Confirm</button>
                </div>
            </div>
            </div>
        </div>

        {{-- Modal create Broadcast --}}
        <div class="modal fade" id="createObjectFromSlipModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create New Broadcast</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="createBroadcast" class="control-label">Select Recipients</label>
                                </div>
                                <div class="col-md-8">
                                    <select id="createBroadcast" name="createBroadcast" class="form-control" v-model="createBroadcastType">
                                        <option value="ALL" >All Students</option>
                                        <option value="RESPONDED" >Have Responded</option>
                                        <option value="NO_RESPONDED">Have not Responded</option>
                                        <option value="POSITIVE">Only Positive Responses</option>
                                        <option value="NEGATIVE">Not Positive Responses</option>
                                    </select>
                                </div>
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                        <a class="btn btn-primary btn-sm" id="btnModalCreateObjectFromSlip" @click="saveBroadcast()"><i class="fas fa-plus"> </i> Create Broadcast</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Loading Modal--}}
        <div style="background-color: rgba(0,0,0,.7);position:fixed;top:0;right:0;bottom:0;left:0;z-index:9999;" v-if="loading">
            <div class="row" style="width:50%;padding: 10px 20px;margin: 15% auto;position: relative;vertical-align: middle;background-color: #fff;">
                <span class="" style="text-align: left;vertical-align: text-top;width: 60%;">
                    <img src="{{asset('/vendor/processmaker/packages/package-pinnacle/images/pinnacle_logo.jpg')}}" alt="Pinnacle College" style="max-width: 300px;"/>
                </span>
                <span style="padding:20px;width: 40%;">
                    <span style="text-align: left;font-size: 24px;"> Loading ... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <span class="spinner-border" role="status" style="vertical-align: middle;text-align: right;">
                        <span class="sr-only">Loading...</span>
                    </span>
                </span>
            </div>
        </div>

    </div>

    @include('package-pinnacle::layouts.LayoutJsVariables')

    @section('js')
    <script>
        window.temp_define = window['define'];
        window['define']  = undefined;
    </script>
            {{-- Jquery Datatable --}}
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.bootstrap4.min.js"></script>

            <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
            {{-- Buttons funcionality --}}
            {{-- <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> --}}
            <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
            {{-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script> --}}
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
            {{-- Currency --}}
            <script src="https://unpkg.com/vue"></script>
            <script src="https://unpkg.com/vue-currency-input"></script>
            {{-- Select 2 --}}
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            {{-- PayWay --}}
            <script src="https://api.payway.com.au/rest/v1/payway.js"></script>
            {{-- Axios --}}
            <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <script>
            window['define'] = window.temp_define;
    </script>



    <script>

        var app = new Vue({

            el: '#app',

            data(){
                return{
                    dataTable : '',
                    editMode  : false,
                    pinnaclePaymentRecord : pinnaclePaymentRecord,
                    appData     : [],
                    caseId      : 0,
                    broadcastList : null,
                    caseData : {
                        slipTitle: '',
                    },
                    studentsAmount : '',
                    caseResponse : '',
                    formStatus : '',
                    permission: '',
                    emergencyContactName : '',
                    emergencyContactNumber : '',
                    medicalConditions : '',

                    student: {
                        firstName: "",
                        lastName: ""
                    },
                    studentList :[],
                    usersToNotify : '',
                    sms : [],
                    createBroadcastType : 'ALL',
                    actionAddRemoveUserOption:'ADD',
                    actionAddUsersList: [],
                    actionRemoveUsersList: [],
                    allStudentsList2:[],
                    studentListToRemove : [],
                    usersMarkAttendance:[],
                    actionUsersMarkAttendance:[],
                    scriptSenEmailAttendanceId:'',
                    scriptSentResentReopen:'',
                    actionDateFrom: '',
                    actionTimeFrom: '',
                    actionDateTo: '',
                    actionTimeTo: '',
                    actionUpdateDueDate: '',
                    actionCheckClosingDate: '',
                    actionClosingDate: '',
                    actionLimitResponse : 'ALL',
                    actionLimitFirst : '',
                    files: [],
                    actionCopySlipToStaff : [],
                    apiToken : '',
                    payStatus : '',
                    singleUseTokenId : '',
                    publishableApiKey : '',
                    buttonDisabled : '',
                    givePermissionForChild : '',
                    transactionId : '',
                    creditCardNumber : '',
                    principalAmount : '',
                    emergencyContactName : '',
                    emergencyContactNumber : '',
                    medicalConditions : '',
                    userId :'',
                    loading: false
                }
            },
            methods: {
                addEmployee(){
                    this.dataTable.row.add([
                        this.name,
                        this.position,
                        this.office,
                        this.age,
                        this.startDate,
                        this.salary,
                    ]).draw(false);
                },
                formatDate(date) {
                    return (date == '' || typeof date !== 'undefined' || date == null || date.length <= 0) ? '' : moment(new Date(date)).format('D MMM YYYY @ HH:mm:ss');
                },
                updateResponse () {

                    if(this.validateForm()) {
                        ProcessMaker.apiClient
                        .put(
                            "pinnacle/update-response/" + this.formStatus.id, {
                                data: {
                                    "permission"               : this.givePermissionForChild,
                                    "emergency_contact_name"   : this.emergencyContactName,
                                    "emergency_contact_number" : this.emergencyContactNumber,
                                    "medical_conditions"       : this.medicalConditions,
                                    "response_date"            : moment().format("Y-MM-DD HH:mm:ss"),
                                    "response_status"          : "DONE"
                                }
                            }
                        )
                        .then(response => {
                            location.reload();
                            $('#studentDetailSlip').modal('toggle')
                        })
                        .catch(response=> {
                            console.log(response);
                        });

                    } else {
                        let forms           = document.getElementsByClassName('needs-validation');
                        forms[0].className += " was-validated";
                        this.loading        = false;
                        return false;
                    }
                },
                currentDateTime () {
                    let current = new Date();
                    let date = current.getFullYear()+'-'+(current.getMonth()+1)+'-'+current.getDate();
                    let time = current.getHours() + ":" + current.getMinutes() + ":" + current.getSeconds();
                    let dateTime = date + ' ' + time;
                    return dateTime;
                },
                sendNotification () {

                    this.sms     = [];
                    this.loading = true;
                    let message  = 'You have a pending permit application, please complete the following form ';
                    dataCase= {
                                "requestId" : this.caseId,
                                "studentNo" : this.usersToNotify
                    };
                    ProcessMaker.apiClient
                    .post(
                        "scripts/execute/" + this.scriptSentResentReopen, {
                            data : JSON.stringify(dataCase),
                            headers:{
                                "Authorization" : "Bearer " + this.apiToken,
                                'Content-Type'  : 'application/json'
                            }
                        }
                    )
                    .then(response => {
                        try {
                            let newData = response.data.response;
                            let responseSplit = newData;
                            $("#resentMessage").modal("hide");
                            setTimeout(() => {
                                location.reload();
                            }, 5000);

                        } catch (error) {
                            console.log(error);
                        }
                    })
                    .catch(response => {
                        this.loading = false;
                        console.log(Response);
                    });
                },
                saveBroadcast() {
                    this.loading = true;
                    ProcessMaker.apiClient
                    .post(
                        "pinnacle/broadcast", {
                            data: {
                                "request_id"       : this.caseId,
                                "slip_title"       : this.appData.slipTitle,
                                "sent_for_status"  : this.createBroadcastType,
                            }
                        }
                    )
                    .then(response => {
                        window.open("{{url('/')}}" +"/pinnacle/broadcast/" + response.data.id, "_self");
                        this.loading = false;
                    })
                    .catch(response=> {
                        this.loading = false;
                        return false;
                    });
                },
                cloneRequest (){
                    this.loading = true;

                    ProcessMaker.apiClient
                    .get(
                        // '{{url("api/1.0/pinnacle/clone-request")}}' + '/' + this.caseId
                        'pinnacle/clone-request/' + this.caseId
                    )
                    .then(response => {
                        this.loading = false;
                        window.open(response.data.data);
                    })
                    .catch(response => {
                        this.loading = false;
                        console.log(response);
                    });
                },
                addRemoveStudent () {
                    if (this.actionAddRemoveUserOption == 'ADD') {
                        ProcessMaker.apiClient
                        .post(
                            'pinnacle/payment-record-massive/' + this.caseId, {
                                data: {
                                    "studentList" : this.actionAddUsersList
                                }
                            }
                        )
                        .then(response => {
                            this.loading = false;
                            $("#modalAddRemoveStudent").modal("hide");
                            location.reload();
                        })
                        .catch(response => {
                            this.loading = false;
                        });

                    } else {

                        ProcessMaker.apiClient
                        .post(
                            'pinnacle/payment-record-delete-massive/' + this.caseId, {
                                data: {
                                    "studentList" : this.actionRemoveUsersList
                                }
                            }
                        )
                        .then(response => {
                            this.loading = false;
                            $("#modalAddRemoveStudent").modal("hide");
                            location.reload();
                        })
                        .catch(response => {
                            this.loading = false;
                            console.log(response);
                        });
                    }
                },
                updateDueDate () {
                    this.loading = true;
                    ProcessMaker.apiClient
                    .put(
                        'pinnacle/process-request-data/' + this.caseId, {
                            data: {
                                "slipDueDate" : this.actionUpdateDueDate
                            }
                        }
                    )
                    .then(response => {

                        $("#modalUpdateDueDate").modal("hide");
                        if(!response.data.error){
                            this.appData = response.data.data;
                        } else {
                            top.ProcessMaker.alert(response.data.message, "danger");
                        }
                        this.loading = false;

                    })
                    .catch(error => {
                        this.loading = false;
                        top.ProcessMaker.alert(error, "danger");
                        console.log(error);
                    });
                },
                setUpClosingDate () {
                    ProcessMaker.apiClient
                    .put(
                        'pinnacle/process-request-data/' + this.caseId, {
                            data: {
                                "closingDate"   : this.actionClosingDate,
                                "closeResponse" : this.actionCheckClosingDate
                            }
                        }
                    )
                    .then(response => {

                        $("#modalSetUpClosingDate").modal("hide");
                        if(!response.data.error){
                            this.appData = response.data.data;
                        } else {
                            top.ProcessMaker.alert(response.data.message, "danger");
                        }
                        this.loading = false;

                    })
                    .catch(error => {
                        this.loading = false;
                        top.ProcessMaker.alert(error, "danger");
                        console.log(error);
                    });
                },
                updateLimitResponses () {
                    if(this.actionLimitResponse == 'ALL') {
                        this.actionLimitFirst = '';
                    }

                    ProcessMaker.apiClient
                    .put(
                        'pinnacle/process-request-data/' + this.caseId, {
                            data: {
                                "limitResponses" : this.actionLimitResponse,
                                "limitFirst"     : this.actionLimitFirst
                            }
                        }
                    )
                    .then(response => {

                        $("#modalUpdateLimitResponses").modal("hide");
                        if(!response.data.error){
                            this.appData = response.data.data;
                        } else {
                            top.ProcessMaker.alert(response.data.message, "danger");
                        }
                        this.loading = false;

                    })
                    .catch(error => {
                        this.loading = false;
                        top.ProcessMaker.alert(error, "danger");
                        console.log(error);
                    });
                },
                deleteRequest () {
                    ProcessMaker.apiClient
                    .delete('pinnacle/process-request/' + this.caseId)
                    .then(response => {
                        $("#modalDeleteRequest").modal("hide");
                        location.href = "{{ url('/pinnacle/request')}}";
                    })
                    .catch(error => {
                        this.loading = false;
                        top.ProcessMaker.alert(error, "danger");
                        console.log(response);
                    });
                },
                sendAttendanceEmail () {
                    let dataCase= {
                                "requestId" : this.caseId,
                                "staffList" : [this.actionUsersMarkAttendance],
                                'slipTitle' : this.appData.slipTitle
                            }
                    ProcessMaker.apiClient
                    .post(
                        'scripts/execute/' + this.scriptSenEmailAttendanceId, {
                            data : JSON.stringify(dataCase),
                            headers:{
                                "Authorization" : "Bearer " + this.apiToken,
                                'Content-Type': 'application/json'
                            }
                        }
                    )
                    .then(response => {
                        this.loading = false;
                        $("#modalsendEmailMarkAttendance").modal("hide");
                    })
                    .catch(response => {
                        this.loading = false;
                        console.log(response);
                    });
                },
                copySlipToStaff () {
                    let dataCase={
                                "requestId" : this.caseId,
                                "staffList" : this.actionCopySlipToStaff,
                                'slipTitle' : this.appData.slipTitle
                            };
                    ProcessMaker.apiClient
                    .post(
                        'scripts/execute/' + this.scriptSenEmailAttendanceId  , {
                            data : JSON.stringify(dataCase),
                            headers:{
                                "Authorization" : "Bearer " + this.apiToken,
                                'Content-Type': 'application/json'
                            }
                        }
                    )
                    .then(response => {
                        this.loading = false;
                        $("#modalCopySlipToStaff").modal("hide");
                    })
                    .catch(response => {
                        this.loading = false;
                        console.log(response);
                    });
                },
                downloadFile(fileId) {
                    window.open( "{{ url('request')}}/" + this.requestId + "/files/" + fileId, "_blank");
                },
                porcentageResponse() {
                    if (this.studentsAmount == undefined || this.studentsAmount == '' || this.studentsAmount == 0) {
                        return '0.00';
                    } else {
                        let floatValue= (parseFloat(this.caseResponse)*100)/parseFloat(this.studentsAmount);
                        return floatValue.toFixed(2);
                    }
                },
                formatDate(date) {
                    return moment(date).format('DD MMM Y');
                },
                validateForm() {
                    if (this.caseData.parentComplete == 1 && (this.givePermissionForChild == '' || this.givePermissionForChild == null) ) {
                        return false;
                    }

                    if (this.caseData.contactInfo == 1
                        && (this.emergencyContactName == '' || this.emergencyContactName == null)
                        && (this.emergencyContactNumber == '' || this.emergencyContactNumber == null)) {
                        return false;
                    }

                    if (this.caseData.medicalInfo == 1 && (this.medicalConditions == '' || this.medicalConditions == null)) {
                        return false;
                    }
                    if (((this.caseData.paymentRequired == 'ONLY' && this.givePermissionForChild == 'YES')
                            || this.caseData.paymentRequired == 'YES')
                        && this.payStatus != 'approved') {
                        return false;
                    }
                    return true;
                }
            },
            mounted(){
                this.apiToken                   = apiToken;
                this.caseId                     = caseId;
                this.appData                    = appData;
                this.broadcastList              = broadcastList;
                this.studentListToRemove        = studentListToRemove;
                this.scriptSenEmailAttendanceId = scriptSenEmailAttendanceId;
                this.scriptSentResentReopen     = scriptSentResentReopen;
                this.files                      = files.data;
                this.caseResponse               = caseResponse;
                this.studentList                = studentList;
                this.studentsAmount             = studentsAmount;
                this.publishableApiKey          = publishableApiKey;
                this.actionUpdateDueDate        = this.appData.slipDueDate;
                this.actionCheckClosingDate     = this.appData.closeResponse;
                this.actionClosingDate          = this.appData.closingDate;
                this.actionLimitResponse        = this.appData.limitResponses;
                this.actionLimitFirst           = this.appData.limitFirst;

                this.allStudentsList = $.map(allStudentsList, function (obj) {
                    obj.id   = obj.id   || obj.studentNo;
                    obj.text = obj.text || obj.fullName;
                    return obj;
                });
                this.usersMarkAttendance = $.map(allStaffList, function (obj) {
                    obj.id   = obj.id   || obj.employeeNo;
                    obj.text = obj.text || obj.fullName;
                    return obj;
                });

                ////---- PAYWAY Settings

                const payButton = document.getElementById( "pay" );
                let creditCardFrame = null;

                const tokenCallback = function( err, data ) {
                    if ( err ) {
                        console.error( "Error getting token: " + err.message );
                        app.loading = false;
                        document.getElementById('payway-credit-card-iframe0').contentWindow.location.reload(true);
                    } else {
                        // TODO: send token to server with ajax
                        // this.singleUseTokenId = data.singleUseTokenId;
                        document.getElementById("singleUseTokenId").value = data.singleUseTokenId;
                        document.getElementById("pay").style.display = "none";

                        axios.post('/api/1.0/pinnacle/payway/transaction', {
                            data: {
                                "singleUseTokenId" : document.getElementById("singleUseTokenId").value,
                                "amount" : document.getElementById("paymentAmount").value,
                                "case_id" : app.caseId,
                                "user_id" : app.userId,
                            }
                        })
                        .then(function (response) {
                            app.loading = false;
                            app.payStatus = response.data.status;

                            if(response.data.status == "approved") {
                                app.payStatus        = response.data.status;
                                app.transactionId    = response.data.transactionId;
                                app.creditCardNumber = response.data.creditCard.cardNumber;
                                app.principalAmount  = response.data.principalAmount;

                            } else {
                                document.getElementById('payway-credit-card-iframe0')
                                .contentWindow.location.reload(true);
                            }
                        })
                        .catch(function (error) {
                            console.log(error.response);
                            app.loading = false;
                            document.getElementById('payway-credit-card-iframe0')
                            .contentWindow.location.reload(true);
                        });


                    }
                    creditCardFrame.destroy();
                    creditCardFrame = null;
                };

                payButton.onclick = function() {
                    payButton.disabled = true;
                    app.loading        = true;
                    creditCardFrame.getToken( tokenCallback );
                };

                const createdCallback = function( err, frame ) {
                    if ( err ) {
                        console.error( "Error creating frame: " + err.message );
                    } else {
                        // Save the created frame for when we get the token
                        creditCardFrame = frame;
                    }
                };

                const options = {
                    // TODO: Replace {publishableApiKey} with your key
                    'publishableApiKey': publishableApiKey,
                    'tokenMode'        : "callback",
                    'onValid'          : function() { payButton.disabled = false; },
                    'onInvalid'        : function() { payButton.disabled = true; }
                };

                // payway.createCreditCardFrame( options, createdCallback );

                ////---- DataTable Settings
                // console.log(app.pinnaclePaymentRecord,'---', typeof app.pinnaclePaymentRecord);
                this.dataTable = $('#dataTable').DataTable({
                    "responsive" : true,
                    "processing" : true,
                    "data"       : this.pinnaclePaymentRecord,
                    "order"      : [[ 2, "asc" ]],

                    "columns": [
                        /*0*/   { "title": "Id", "data": "id","visible": false, "defaultContent": ""},
                        /*1*/   { "title": "User Id", "data": "user_id","visible": false, "defaultContent": ""},
                        /*2*/   {
                                    "title": "No","data": null,"sortable": true,
                                    "className": "text-center",
                                    "defaultContent": "",
                                    "render": function (data, type, row, meta) {

                                        return meta.row + meta.settings._iDisplayStart + 1;
                                    }
                                },
                        /*3*/   { "title": "Permission 1", "data": "permission", "defaultContent": "NO_ATTENDED", "visible":false},
                        /*4*/   { "title": "Permission", "data": "permission", "defaultContent": "No Attended",
                                    "render" : function (data, type, row) {
                                        let status = 'No Attended';
                                        switch (row.permission) {
                                            case "YES":
                                                status = "Permission Given";
                                                break;
                                            case "NO":
                                                status = "Permission NOT Given";
                                                break;
                                            default:
                                                status = 'No Attended'
                                                break;
                                        }
                                        return '<span>' + status + '</span>';
                                    }
                                },
                        /*5*/   { "title": "" , "data": "firstName","sortable": false, "className": "text-center", "defaultContent": "",
                                    "render": function ( data, type, row ) {
                                        let html   = '';
                                        let srcImg = '';

                                        if (row.picture != null && row.picture != '') {
                                            srcImg += ' data:image/jpeg;base64, ' + row.picture ;
                                        } else {
                                            srcImg += "{{asset('/vendor/processmaker/packages/package-pinnacle/images/pinnacle_user_avatar_default.png')}}";
                                        }
                                        html += '<div class="avatar-container">';
                                        html += '<div>';
                                        html += '<figure class="figure">';
                                        html += '<img class="figure-img img-fluid rounded" src="'+ srcImg + '" alt="' + row.firstName + ' ' + row.lastName + '" width="30" height="auto"/>';
                                        html += '</figure>';
                                        html += '</div>';
                                        html += '<div class="zoom-avatar">';
                                        html += '<img class="img-thumbnail" src="'+ srcImg + '" alt="' + row.firstName + ' ' + row.lastName + '" width="100" height="auto"/>';
                                        html += '</div>';
                                        html += '</div>';
                                        return html;
                                    }
                                },
                        /*6*/   { "title": "Student Name" , "data": "firstName", "defaultContent": "",
                                    "render": function ( data, type, row ) {
                                        return '<a href="{{url('/')}}/pinnacle/person/view/' + row.user_id + '">' + row.firstName + ' ' + row.lastName + '</a>';
                                    }
                                },
                        /*7*/   { "title": "Grade", "data": "gradegroup", "className": "text-center", "defaultContent": "" },
                        /*8*/   {
                                    "title": "Response Date", "data": "response_date", "defaultContent": "", "className": "text-center",
                                    "render": function ( data, type, row ) {
                                        if (typeof row.response_date === 'undefined'
                                            || row.response_date == null
                                            || row.response_date == ''
                                            || row.response_date.length <= 0) {
                                            return '';
                                        }
                                        let newDate = moment(row.response_date).format('DD MMM Y @ hh:m A');
                                        let html = '<span>' + newDate + '</span>';
                                        return html;
                                    }
                                },
                        /*9*/   { "title": "Contact Name", "data": "emergency_contact_name", "defaultContent": "" },
                        /*10*/  {
                                    "name": "control",
                                    "searchable": false,
                                    "title": "View",
                                    "defaultContent": "",
                                    "className": "text-center",
                                    "orderable": false,
                                    "render": function ( data, type, row ) {
                                        let html = '<a title="View Slip" class="text-primary" ';
                                        html += 'href="#" style="font-weight: bold;font-size:18px;" ';
                                        html += 'data-toggle="modal" data-target="#studentDetailSlip">';
                                        html += '<i class="fas fa-eye"></i></a>';
                                        return html;
                                    },
                                    "createdCell": function(cell, cellData, rowData, rowIndex, colIndex) {
                                        $(cell).on("click", "a", rowData, viewModal);
                                    }
                                },
                        /*11*/  {
                                    "name": "control",
                                    "searchable": false,
                                    "title": "Edit",
                                    "defaultContent": "",
                                    "className": "text-center",
                                    "orderable": false,
                                    "render": function ( data, type, row ) {
                                        let html = '<a title="Edit Slip" class="text-primary" ';
                                        html += 'href="#" style="font-weight: bold;font-size:18px;" ';
                                        html += 'data-toggle="modal" data-target="#studentDetailSlip">';
                                        html += '<i class="fas fa-edit"></i></a>';
                                        return html;
                                    },
                                    "createdCell": function(cell, cellData, rowData, rowIndex, colIndex) {
                                        $(cell).on("click", "a", rowData, editModal);
                                    }
                                },
                        /*12*/  {
                                    "title": "Resent/Reopen",
                                    "className": "text-center",
                                    "defaultContent": "",
                                    "data": null,
                                    "orderable": false,
                                    "render": function ( data, type, row ) {
                                        let html = '<a title="View Slip" class="text-primary" href="#" ';
                                        html += 'style="font-weight: bold;font-size:18px;" ';
                                        html += 'data-toggle="modal" data-target="#resentMessage">';
                                        html += '<i class="far fa-folder-open"></a>';
                                        return html;
                                    },
                                    "createdCell": function(cell, cellData, rowData, rowIndex, colIndex) {
                                        $(cell).on("click", "a", rowData, toResentMessage);
                                    }
                                },
                        /*13*/  {
                                    "title": "Attended",
                                    "className": "text-center",
                                    "defaultContent": "",
                                    "data": "attended",
                                    "orderable": false,
                                    "render": function ( data, type, row ) {
                                        let options = [
                                            {"value": "", "text": "N/A", "selected_id" : ""},
                                            {"value": "YES", "text": "Yes", "selected_id" : ""},
                                            {"value": "NO", "text": "No", "selected_id" : ""},
                                        ];
                                        let html = '<select class="form-control form-control-sm select-attended">';
                                        $.each(options, function (key, value) {
                                            let selected = value.value == row.attended ? "selected" : "";
                                            html += '<option value ="' + value.value + '" ' + selected + ' >' + value.text + '</option>';
                                        });
                                        html += '</select>';
                                        return  html;
                                    },
                                    "createdCell": function(cell, cellData, rowData, rowIndex, colIndex) {
                                        $(cell).on("change", "select", rowData, updateAttented);
                                    }
                                }
                    ],
                    "dom": '<"top"><"bottom"><"clear"><"toolbar">Brtip',
                    "buttons": [
                        {
                            "extend": "print",
                            "exportOptions": {
                                "columns" : [ 4, 6, 7, 8, 9, 13 ],
                                "format"  : {
                                    body: function( data, row, col, node ) {
                                        let dataTable = $('#dataTable').DataTable();
                                        if (col == 0) {
                                            return dataTable
                                            .cell( {row: row, column: col} )
                                            .nodes()
                                            .to$()
                                            .find(':selected')
                                            .text()
                                        } else {
                                            return data;
                                        }
                                    }
                                }
                            },
                            "title" : $("#slipTitle h3").text()
                        },
                        'colvis'
                    ],
                    "initComplete": function () {
                        this.api().columns(7).every( function () {
                            var column = this;
                            column.data().unique().sort().each( function ( d, j ) {
                                let select = $("#dataTableGrade");
                                select.append('<a href="#" class="dropdown-item response-grade" dataTableGrade = "'+d+'"><i class="fas fa-list-alt" > </i> ' + d + '</a>' )
                            });
                        });

                        $('#dataTable tbody').on('change', 'select.select-attended', function () {

                            let table = $("#dataTable").DataTable();
                            //get selected value
                            var changed = $(this).find(":selected").val();
                            var row = table.row ($(this).closest('tr'));
                            var data = row.data();
                            ProcessMaker.apiClient
                            .put(
                                "pinnacle/payment-record/" + data.id, {
                                    data : {
                                        "attended" : changed
                                    }
                                }
                            )
                            .then(response => {
                                data.attended = changed;
                                row.invalidate().draw(false);
                            })
                            .catch(response => {
                                row.invalidate().draw(false);
                            });
                        });
                    },
                    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                        let limitDate      = appData.slipDateToSend + ' ' + appData.timeToSend;
                        let newLimitDate   = moment(limitDate).format('Y-MM-D HH:mm:ss');
                        let respDate       = moment(aData['response_date']).format('Y-MM-D HH:mm:ss');

                        if(moment(respDate).isAfter(newLimitDate)) {
                            $('td', nRow).css('background-color', '#f3ebbf');
                        }
                    }
                });

                this.$nextTick(function () {


                    $("#actionAddUsersList").select2({
                        data: this.allStudentsList
                    })
                    .on('change', function () {
                        app.actionAddUsersList = $(this).select2("val");
                    });

                    $("#actionRemoveUsersList").select2({
                        data: this.studentListToRemove
                    })
                    .on('change', function () {
                        app.actionRemoveUsersList = $(this).select2("val");
                    });

                    $("#usersMarkAttendance").select2({
                        data: this.usersMarkAttendance
                    })
                    .on('change', function () {
                        app.actionUsersMarkAttendance = $(this).select2("val");
                    });

                    $("#actionCopySlipToStaff").select2({
                        data: this.usersMarkAttendance
                    })
                    .on('change', function () {
                        app.actionCopySlipToStaff = $(this).select2("val");
                    });
                });

                $("#dataTable_wrapper > div.dt-buttons.btn-group.flex-wrap").hide();

                $('#searchText').on( 'keyup', function () {
                    let dataTable = $('#dataTable').DataTable();
                    dataTable.search( this.value ).draw();
                });

                $(".response-status").on("click", function () {
                    let dataTable = $("#dataTable").DataTable();

                    let search = "";
                    switch ($(this).attr("dataTableFilter")) {
                        case "ALL":
                        default:
                            search = "";
                            dataTable.column(3).search( search ).draw();
                            break;
                        case "YES":
                            search = "YES";
                            dataTable.column(3).search( "YES" ).draw();
                            break;
                        case "NO":
                            search = "NO";
                            dataTable.column(3).search( search ? '^'+search+'$' : '', true, false ).draw();
                            break;
                        case "ATTENDED":
                            search = "NO_ATTENDED";
                            dataTable.column(3).search('^((?!' + search + ').)*$', true, false ).draw();
                            break;
                        case "NO_ATTENDED":
                            search = "NO_ATTENDED";
                            dataTable.column(3).search(search ? '^'+search+'$' : '', true, false ).draw();
                            break;
                    }
                });
                $(".response-grade").on("click", function () {
                    let dataTable = $("#dataTable").DataTable();
                    let search    = $(this).attr("dataTableGrade");
                    dataTable.column(7).search( search ).draw();
                });

                /* custom button event print */
                $(document).on('click', '#printList', function(){
                    $(".buttons-print")[0].click(); //trigger the click event
                });

                $("div.toolbar").append($("#dataTableSeach"));

                function viewModal(event) {
                    app.userId = event.data.user_id;
                    if(creditCardFrame != null) {
                        creditCardFrame.destroy();
                        creditCardFrame = null;
                    }
                    payway.createCreditCardFrame( options, createdCallback );

                    ProcessMaker.apiClient
                    .get(
                        "pinnacle/student-slip-detail/" + event.data.user_id + "/" + app.caseId
                    )
                    .then(response => {
                        app.caseData               = response.data.caseData;
                        app.formStatus             = response.data.formStatus;
                        app.payStatus              = response.data.formStatus.transaction_status;
                        app.student                = response.data.student;
                        app.givePermissionForChild = response.data.formStatus.permission;
                        app.transactionId          = response.data.formStatus.transaction_id;
                        app.creditCardNumber       = response.data.formStatus.credit_card_number;
                        app.principalAmount        = response.data.formStatus.amount_paid;
                        app.emergencyContactName   = response.data.formStatus.emergency_contact_name;
                        app.emergencyContactNumber = response.data.formStatus.emergency_contact_number;
                        app.medicalConditions      = response.data.formStatus.medical_conditions;

                        app.editMode   = false;
                    });
                }
                function editModal(event) {
                    app.userId = event.data.user_id;
                    if(creditCardFrame != null) {
                        creditCardFrame.destroy();
                        creditCardFrame = null;
                    }
                    payway.createCreditCardFrame( options, createdCallback );

                    ProcessMaker.apiClient
                    .get(
                        "pinnacle/student-slip-detail/" + event.data.user_id + "/" + app.caseId
                    )
                    .then(response => {
                        app.caseData               = response.data.caseData;
                        app.formStatus             = response.data.formStatus;
                        app.payStatus              = response.data.formStatus.transaction_status;
                        app.student                = response.data.student;
                        app.givePermissionForChild = response.data.formStatus.permission;
                        app.transactionId          = response.data.formStatus.transaction_id;
                        app.creditCardNumber       = response.data.formStatus.credit_card_number;
                        app.principalAmount        = response.data.formStatus.amount_paid;
                        app.emergencyContactName   = response.data.formStatus.emergency_contact_name;
                        app.emergencyContactNumber = response.data.formStatus.emergency_contact_number;
                        app.medicalConditions      = response.data.formStatus.medical_conditions;

                        app.editMode   = true;
                    });



                }

                function toResentMessage(event) {
                    app.sms           = [];
                    app.usersToNotify = event.data.user_id;
                }
                function updateAttented() {
                    return true;
                }



            },
            computed: {
                studentfullName: function(){
                    return this.student.firstName + ' ' + this.student.lastName;
                },

                createBroadcastData: function () {
                    return 'pinnacle/broadcast?type=' + this.createBroadcastData;
                }
            }

        });

    </script>

    @endsection
@endsection



