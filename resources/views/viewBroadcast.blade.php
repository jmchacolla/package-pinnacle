@extends('layouts.layout')

@section('sidebar')
    @include('layouts.sidebar', ['sidebar'=> Menu::get('sidebar_admin')])
@endsection
@section('css')

    <style>
        [v-cloak] {
            display:none;
        }
    </style>

@endsection

@section('content')
    <div id="app">

        <div class="container" style="background-color: #fff; width:100%; padding:20px;" v-cloak>
            <div class="row">
                <div class="col-12">
                    <h5 style="font-weight: bold;">
                        @{{ slipTitle }}, Broadcast:  @{{ broadcastTitle }}
                    </h5>
                    <hr style="border-top:1px solid #efefef;">
                </div>
            </div>
            <div class="">
                <div class="form-group row">
                    <label for="broadcastTitle" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Broadcast Title</label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <small id="passwordHelpBlock" class="form-text text-info">
                            This will be the Subject of the Email. It will not be seen in the SMS message.
                        </small>
                        <input type="text" class="form-control" placeholder="Broadcast Title" id="broadcastTitle" :disabled="!editMode" v-model="broadcastTitle">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="slipTitle" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Created from</label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <input type="text" class="form-control" placeholder="Slip Title" id="slipTitle" :disabled="!editMode" v-model="slipTitle">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="sentForStatus" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Send for</label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <select id="sentForStatus" name="sentForStatus" class="form-control col-12" :disabled="!editMode" v-model="sentForStatus">
                            <option value="ALL" selected="">All Students</option>
                            <option value="RESPONDED" >Have Responded</option>
                            <option value="POSITIVE">Only Positive Responses</option>
                            <option value="NEGATIVE">Not Positive Responses</option>
                            <option value="NO_RESPONDED">Have not Responded</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="">
                    <label for="responderId" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Send Broadcast to?&nbsp;</label>
                    <div class="col-md-3">
                        <select id="responderId" name="responderId" class="form-control" tabindex="2" :disabled="!editMode" v-model="responderId">
                            <option value="PARENTS">Parents</option>
                            <option value="STUDENTS">Students</option>
                        </select>
                    </div>
                    <label for="ccNotifications" class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="text-align: right;">CC To&nbsp;</label>
                    <div class="col-md-3">
                        <select id="ccNotifications" name="ccNotifications" class="form-control col-12" :disabled="!editMode" v-model="ccNotifications">
                            <option value="NONE">None</option>
                            <option value="PARENTS">Parents</option>
                            <option value="STUDENTS">Students</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="">
                    <label for="dateToSend" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Broadcast Date & Time to Send&nbsp;</label>
                    <div class="col-md-3">
                        <input type="date" id="dateToSend" name="dateToSend" class="form-control"  tabindex="" :disabled="!editMode" v-model="dateToSend">
                    </div>
                    <label for="timeToSend" class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="text-align: right;">Time&nbsp;</label>
                    <div class="col-md-3">
                        <date-picker class="form-control col-12" v-model="timeToSend" :config="options" value="00:00"  :disabled="!editMode" ></date-picker>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="sendEmail" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Send by Email</label>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="text-align: center;">
                        <input type="checkbox" id="sendEmail" name="sendEmail" class="form-check-input" :disabled="!editMode" v-model="sendEmail">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="sendSms" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Send by SMS</label>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="text-align: center;">
                        <input type="checkbox" id="sendSms" name="sendSms" class="form-check-input" :disabled="!editMode" v-model="sendSms">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="copyUserGroup" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Send copy to User/User Groups</label>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <small id="passwordHelpBlock" class="form-text text-info">
                            Type name of User, or a User Group.
                        </small>
                        <select multiple="multiple" name="copyUserGroup" id="copyUserGroup" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" :disabled="!editMode">
                            <option value=""> -Select- </option>
                        </select>
                    </div>
                </div>


                <div class="sms-form-group" style="">
                    <br>
                    <h5 id="section1Name" style="font-weight: bold;">SMS Text Message</h5>
                    <hr style="border-top:1px solid #efefef;">
                    <div class="form-group">
                        <div class="" style="background-color:#fff;">
                            <div class="iphone-shell" deluminate_imagetype="png" style="background: url({{asset('/vendor/processmaker/packages/package-pinnacle/images/pinnacle_mobile_background.png')}})  no-repeat center rgba(0, 0, 0, 0);width: 240px;height: 400px;margin: auto;">
                                <div class="iphone-shell-content" style="font-size:12px;padding: 100px 45px 0;">
                                    @{{ smsContent }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="email-form-group" style="background-color: #fff; min-height: 400px;"  v-if="sendEmail">
                    <br>
                    <h5 id="section1Name" style="font-weight: bold;">Email</h5>
                    <hr style="border-top:1px solid #efefef;">
                    <div class="form-group">
                        <div class="col-md-12" id="email-iframe">
                            {{-- <iframe id="preview-email-template" width="100%" height="500"></iframe> --}}
                            <div class="container-fluid">
                                <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0" style="background: #ececec;" bgcolor="#ececec">
                                    <tbody style="background-color: #fff;">
                                        <tr>
                                            <td height="20"> </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <table style="font-family:Arial,Verdana,Tahoma; border: 1px solid #eaeff2; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; background-color:#ffffff;" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" align="center" width="620">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <table style="max-width: 700px; font-family:Arial,Verdana,Tahoma; padding: 0;margin-bottom:20px;;" cellpadding="0" cellspacing="0" border="0" align="center" width="620" bgcolor="#ffffff">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td height="20" colspan="4" bgcolor="#141c57"> </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30"> </td>
                                                                            <td valign="top" colspan="2">
                                                                                <p style="margin-top: 30px; font-size: 11pt; line-height: 26px; color: #323b43;">
                                                                                </p>
                                                                                <p align="center"><img src="{{asset('/vendor/processmaker/packages/package-pinnacle/images/pinnacle_logo.jpg')}}"></p>
                                                                                <p align="left" style="font-size: 18px;line-height: 24px;font-weight: bold;margin-top: 0px;margin-bottom: 18px;font-family: Arial, Helvetica, Geneva, sans-serif;">Community Hub </p>
                                                                                <div align="left" class="article-content">
                                                                                <p>Dear [Name],</p>
                                                                                <p v-html="emailContent">@{{ emailContent }}</p>

                                                                            </div>
                                                                            </td>
                                                                            <td width="30"> </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table id="footerTable" bgcolor="#141c57" style="margin-bottom: 10px; font-family:Arial,Verdana,Tahoma; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;border-radius: 0px 0px 6px 6px;-moz-border-radius: 0px 0px 6px 6px;-webkit-border-radius: 0px 0px 6px 6px;-webkit-font-smoothing: antialiased;" cellpadding="0" cellspacing="0" border="0" align="center" width="620">
                                                    <tbody>
                                                            <tr>
                                                                <td width="30"></td>
                                                                <td height="20"><p align="left" style="font-size: 12px;line-height: 15px;color: #ffffff;margin-top: 20px;margin-bottom: 20px;">
                                                                    This email was sent to
                                                                    <a style="color:#ffffff" href="mailto:[Email]">pinnacle.college@pinnacle.sa.edu.au</a> by Pinnacle College using ProcessMaker. Please contact the school if you have any questions or concerns.
                                                                    </p>
                                                                </td>
                                                                <td width="30"></td>
                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="50"> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="files-list-view" style="display:none;">
                    <div class="col-md-12">
                        <h4>File to include on the Broadcast</h4>
                        <table class="table table-striped" role="presentation" id="files-list">
                            <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <h5 style="font-weight: bold;">File to include on the Broadcast</h5>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">
                    <div class="form-group">
                        <div>
                            <small class="text-info">
                                The file will be displayed on a web page displayed when the link in the Email or SMS message is clicked. Maximum size 3Mb, must be PDF.
                            </small><br>
                            <label class="btn btn-primary btm-sm" for="uploadFile" hidden><i class="fas fa-arrow-circle-up">&nbsp;</i> Choose file</label>
                            <input type="file" class="custom-file-input"  id="uploadFile" @change="uploadBroadcastFile();" ref="uploadFile" aria-describedby="uploadFileAddon" hidden>
                        </div>
                        <div class="table-responsive" >
                            <table class="table table-striped table-hover" style="width: 100%;" >
                                <tbody>
                                    <tr v-for="file in filesList" >
                                        <td style="width: 80%;" class=""><small> @{{ file.file_name }} </small></td>
                                        <td class="" style="width: 20%;text-align: center;">
                                            <span><a href="" @click="downloadFile(file.id);"><i class="fas fa-download"></i></a></span>
                                            {{-- <span><a href=""><i class="fas fa-trash-alt"></i></a></span> --}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12" style="text-align: center;">
                        <a class="btn btn-danger btn-sm" :href="'{{url('/')}}/pinnacle/pinnacle-request/' + requestId" id="cancelForm" ><i class="fas fa-times-circle"> </i> Close</a>
                        <a class="btn btn-primary btn-sm" :href="'{{url('/')}}/pinnacle/broadcast/' + broadcastId" id="editBroadcast" ><i class="fas fa-edit"> </i> Edit</a>

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

    {{-- {{ $varsJs}} --}}
    @include('package-pinnacle::layouts.LayoutJsVariables')

    @section('js')
        <script>
            window.temp_define = window['define'];
            window['define']  = undefined;
        </script>
            <!-- Date-picker -->
            <script src="https://cdn.jsdelivr.net/npm/pc-bootstrap4-datetimepicker@4.17/build/js/bootstrap-datetimepicker.min.js"></script>
            <link href="https://cdn.jsdelivr.net/npm/pc-bootstrap4-datetimepicker@4.17/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/vue-bootstrap-datetimepicker@5"></script>

            {{-- Select2 --}}
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" ></script>
            <link href="https://rawgit.com/select2/select2/master/dist/css/select2.min.css" rel="stylesheet"/>
        <script>
            window['define'] = window.temp_define;
        </script>

        <script>
            Vue.component('date-picker', VueBootstrapDatetimePicker);

            var app = new Vue({

                el: '#app',

                data(){
                    return{
                        broadcastId     : '',
                        broadcastTitle  : '',
                        ccNotifications : '',
                        copyUserGroup : '',
                        copyUserGroupConfig: [],
                        dateToSend    : '',
                        emailContent  : '',
                        requestId     : '',
                        responderId   : '',
                        sendEmail     : '',
                        sendSms       : '',
                        sentForStatus : '',
                        slipTitle     : '',
                        smsContent    : '',
                        status        : '',
                        timeToSend    : '',
                        staffList     : '',
                        files         : [],
                        filesList     : [],
                        options: {
                            format: 'h:mm:A',
                            useCurrent: false,
                            stepping:15,
                            showClear: true,
                            showClose: true,
                        },
                        loading       : false,
                        editMode      : false
                    }
                },
                methods: {
                    populateEmployeeList(){
                        let data = $.map(this.staffList, function (obj) {
                            obj.id   = obj.id || obj.employeeNo;
                            obj.text = obj.text || obj.fullName || obj.name;
                            return obj;
                        });
                        $('#copyUserGroup')
                        .select2({
                            "placeholder": 'Select an option',
                            "data"       : data
                        })
                    },
                    editBroaddcast(){
                        this.editMode = true;
                    },
                    saveBroadcast() {
                    this.loading = true;
                    ProcessMaker.apiClient
                    .post(
                        "pinnacle/broadcast", {
                            data: {
                                "request_id"       : this.requestId,
                                "broadcast_title"  : this.broadcastTitle,
                                "slip_title"       : this.slipTitle,
                                "sent_for_status"  : this.typeBroadcast,
                                "responder_id"     : this.responderId,
                                "cc_notifications" : this.ccNotifications,
                                "date_to_send"     : this.dateToSend,
                                "time_to_send"     : this.timeToSend,
                                "send_email"       : this.sendEmail,
                                "send_sms"         : this.sendSms,
                                "copy_user_group"  : this.copyUserGroup,
                                "sms_content"      : this.smsContent,
                                "email_content"    : this.emailContent,
                                "files"            : JSON.stringify(this.files),
                                "copy_user_group"  : JSON.stringify($("#copyUserGroup").val()),
                                "copy_user_group_config" : $("#copyUserGroup").select2('data')
                            }
                        }
                    )
                    .then(response => {
                        window.location.href = this.getUrlReturnSlip();
                    })
                    .catch(response=> {
                        this.loading = false;
                        return false;
                    });
                },

                },
                mounted(){
                    this.$nextTick(function () {
                        this.broadcastId     = pinnacleBroadcast.broadcast_id;
                        this.broadcastTitle  = pinnacleBroadcast.broadcast_title;
                        this.ccNotifications = pinnacleBroadcast.cc_notifications;
                        this.copyUserGroup   = JSON.parse(pinnacleBroadcast.copy_user_group);
                        this.copyUserGroupConfig = pinnacleBroadcast.copy_user_group_config;
                        this.dateToSend    = pinnacleBroadcast.date_to_send;
                        this.emailContent  = pinnacleBroadcast.email_content;
                        this.requestId     = pinnacleBroadcast.request_id;
                        this.responderId   = pinnacleBroadcast.responder_id;
                        this.sendEmail     = pinnacleBroadcast.send_email;
                        this.sendSms       = pinnacleBroadcast.send_sms;
                        this.sentForStatus = pinnacleBroadcast.sent_for_status;
                        this.slipTitle     = pinnacleBroadcast.slip_title;
                        this.smsContent    = pinnacleBroadcast.sms_content;
                        this.status        = pinnacleBroadcast.status;
                        this.timeToSend    = pinnacleBroadcast.time_to_send;
                        this.staffList     = staff_list;
                        this.files         = pinnacleBroadcast.files == null ? [] : pinnacleBroadcast.files ;
                        this.filesList     = (files != undefined && files.length > 0) ? files : [];
                        this.populateEmployeeList();
                        $("#copyUserGroup").val(this.copyUserGroup).trigger('change')
                    });

                },
                computed: {

                },
                watch: {

                }
            });

        </script>

    @endsection
@endsection