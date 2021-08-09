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

    <div class="container needs-validation" style="background-color: #fff; width:100%; padding:20px;" v-cloak>
        <div class="row">
            <div class="col-12">
                <h5 style="font-weight: bold;">
                    Broadcast
                </h5>
                <hr style="border-top:1px solid #efefef;">
            </div>
        </div>
        <div class="">
            <div class="form-group row">
                <label for="broadcastTitle" class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >Broadcast Title<span style="color: red;">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <small id="passwordHelpBlock" class="form-text text-info">
                        This will be the Subject of the Email. It will not be seen in the SMS message.
                    </small>
                    <input type="text" class="form-control" placeholder="Broadcast Title" id="broadcastTitle" v-model="broadcastTitle" required>
                    <div class="invalid-feedback">
                        This field is Required.
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="slipTitle" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Created from<span style="color: red;">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <input type="text" class="form-control" placeholder="Slip Title" id="slipTitle" disabled v-model="slipTitle" required>
                    <div class="invalid-feedback">
                        This field is Required.
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="sentForStatus" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Send for<span style="color: red;">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <select id="sentForStatus" name="sentForStatus" class="form-control col-12" v-model="sentForStatus" required>
                        <option value="ALL" selected="">All Students</option>
                        <option value="RESPONDED" >Have Responded</option>
                        <option value="NO_RESPONDED">Have not Responded</option>
                        <option value="POSITIVE">Only Positive Responses</option>
                        <option value="NEGATIVE">Not Positive Responses</option>
                    </select>
                    <div class="invalid-feedback">
                        This field is Required.
                    </div>
                </div>
            </div>
            <div class="form-group row" style="">
                <label for="responderId" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Send Broadcast to?&nbsp;<span style="color: red;">*</span></label>
                <div class="col-md-3">
                    <select id="responderId" name="responderId" class="form-control" tabindex="2" v-model="responderId" required>
                        <option value="" selected>- Select -</option>
                        <option value="PARENTS">Parents</option>
                        <option value="STUDENTS">Students</option>
                    </select>
                    <div class="invalid-feedback">
                        This field is Required.
                    </div>
                </div>
                <label for="ccNotifications" class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="text-align: right;">CC To&nbsp;</label>
                <div class="col-md-3">
                    <select id="ccNotifications" name="ccNotifications" class="form-control col-12" v-model="ccNotifications">
                        <option value="" selected>- Select -</option>
                        <option value="NONE">None</option>
                        <option value="PARENTS">Parents</option>
                        <option value="STUDENTS">Students</option>
                    </select>
                </div>
            </div>
            <div class="form-group row" style="">
                <label for="dateToSend" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Broadcast Date & Time to Send<span style="color: red;">*</span></label>
                <div class="col-md-3">
                    <input type="date" id="dateToSend" name="dateToSend" class="form-control"  tabindex="" v-model="dateToSend" required>
                    <div class="invalid-feedback">
                        This field is Required.
                    </div>
                </div>
                <label for="timeToSend" class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="text-align: right;">Time<span style="color: red;">*</span></label>
                <div class="col-md-3">
                    <date-picker class="form-control col-12" v-model="timeToSend" :config="options" value="00:00" required></date-picker>
                    <div class="invalid-feedback">
                        This field is Required.
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="sendEmail" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Send by Email</label>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="text-align: center;">
                    <input type="checkbox" id="sendEmail" name="sendEmail" class="form-check-input" v-model="sendEmail">
                </div>
            </div>
            <div class="form-group row">
                <label for="sendSms" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Send by SMS</label>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="text-align: center;">
                    <input type="checkbox" id="sendSms" name="sendSms" class="form-check-input" v-model="sendSms">
                </div>
            </div>

            <div class="form-group row">
                <label for="copyUserGroup" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">Send copy to User/User Groups</label>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <small id="passwordHelpBlock" class="form-text text-info">
                        Type name of User, or a User Group.
                    </small>
                    {{-- <input type="text-area" id="copyUserGroup" class="form-control" placeholder="Send copy to User/User Groups" v-model="copyUserGroup"> --}}
                    <select multiple="multiple" name="copyUserGroup" id="copyUserGroup" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
                        <option value=""> -Select- </option>
                    </select>
                </div>
            </div>
            <div class="row" v-if="sendSms">
                <div class="col-12">
                    <h5>SMS Text Message</h5>
                </div>
            </div>
            <div class="form-group row" v-if="sendSms">
                <small class="col-lg-8 col-md-8 col-sm-12 col-xs-12 text-info">
                    The number of available characters is dependent on whether we need to include a file link. Click Preview to check.
                </small>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="text-align: right !important;">
                    <button type="button" class="btn btn-primary btn-sm"  id="btnPreviewSms"  data-toggle="modal" data-target="#mobilePreviewModal">
                        <i class="fas fa-eye"> </i> Preview
                    </button>
                </div>
            </div>
            <div class="form-group row" v-if="sendSms">
                <label for="smsContent" class="col-lg-8 col-md-8 col-sm-12 col-xs-12">@{{ chartCount}} characters of @{{ smsMax }}</label>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <textarea type="text-area" class="form-control" placeholder="Broadcast Title" id="smsContent" v-model="smsContent"  :maxlength="smsMax">
                    </textarea>
                </div>
            </div>
            <div class="row" v-if="sendEmail">
                <div class="col-12">
                    <h5>Email</h5>
                </div>
            </div>
            <div class="form-group row"  v-if="sendEmail">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <editor id="emailContent" v-model="emailContent" @change="onChange"  theme="snow"></editor>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="text-align: right !important;">
                    <button type="button" class="btn btn-primary btn-sm"  id="btnPreviewEmail" data-toggle="modal" data-target="#emailPreview">
                        <i class="fas fa-eye"> </i> Preview
                    </button>
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
                        <label class="btn btn-primary btm-sm" for="uploadFile"><i class="fas fa-arrow-circle-up">&nbsp;</i> Choose file</label>
                        <input type="file" class="custom-file-input"  id="uploadFile" @change="uploadBroadcastFile();" ref="uploadFile" aria-describedby="uploadFileAddon" hidden>
                    </div>
                    <div class="table-responsive" >
                        <table class="table table-striped table-hover" style="width: 100%;" >
                            <tbody>
                                <tr v-for="file in filesList" >
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

            <div class="row">
                <div class="col-12" style="text-align: center;">
                    <button class="btn btn-danger btn-sm" id="cancelForm" data-toggle="modal" data-target="#modalCancelForm" ><i class="fas fa-times-circle"> </i> Cancel</button>
                    <button class="btn btn-primary btn-sm" id="submitForm" @click="finishEdit();"><i class="fas fa-paper-plane"> </i> Submit</button>
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

    {{-- Modal Email Preview --}}
    <div class="modal fade" id="emailPreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Email Preview</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0" style="background: #ececec;" bgcolor="#ececec">
                            <tbody>
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
                                                            <a style="color:#ffffff" href="mailto:[Email]">[Email]</a> by Pinnacle College using ProcessMaker. Please contact the school if you have any questions or concerns.
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
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    {{-- Modal SMS Preview --}}
    <div class="modal fade" id="mobilePreviewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="iphone-shell" deluminate_imagetype="png" style="background: url({{asset('/vendor/processmaker/packages/package-pinnacle/images/pinnacle_mobile_background.png')}}) no-repeat center;width:240px !important; height: 400px !important;">
                        <div class="iphone-shell-content" style="padding: 100px 45px 0;font-size: 12px;">
                            @{{ smsContent }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Cancel confirmation-->
    <div class="modal fade" id="modalCancelForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirm Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure to cancel this Broadcast?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                <a class="btn btn-primary" @click="cancelBroadcast()"><i class="fas fa-check-circle"> </i> Confirm</a>
            </div>
        </div>
        </div>
    </div>


</div>
    @include('package-pinnacle::layouts.LayoutJsVariables')
    @section('js')
        <script>
            window.temp_define = window['define'];
            window['define']  = undefined;
        </script>
            <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
            <script src="https://unpkg.com/@mycure/vue-wysiwyg/dist/mc-wysiwyg.js"></script>

            <link href="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.min.js" type="text/javascript"></script>

            <link href="https://unpkg.com/@morioh/v-quill-editor/dist/editor.css" rel="stylesheet">
            <script src="https://unpkg.com/@morioh/v-quill-editor/dist/editor.min.js" type="text/javascript"></script>
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

            Vue.use(McWysiwyg.default);

            var app = new Vue({
                el: '#app',
                data(){
                    return{
                        broadcastId : '',
                        broadcastTitle : '',
                        requestId : '',
                        slipTitle : '',
                        sentForStatus: '',
                        responderId : '',
                        ccNotifications: 'NONE',
                        dateToSend : '',
                        timeToSend : '00:00',
                        options: {
                            format: 'H:mm',
                            useCurrent: false,
                            stepping:15,
                            showClear: true,
                            showClose: true,
                        },
                        sendEmail       : '',
                        sendSms         : '',
                        copyUserGroup   : '',
                        smsContent      : '',
                        smsMax          :150,
                        emailContent    : '',
                        status          : '',
                        chartCount      : 0,
                        loading         : false,
                        staffList       : '',
                        apiToken        : '',
                        uploadFile      : '',
                        files           : [],
                        filesList       : [],
                    }
                },
                methods: {

                    val() {
                        this.value = "This's new value";
                    },
                    onChange(html, text) {
                    },
                    cancelBroadcast() {
                        this.loading = true;
                        ProcessMaker.apiClient
                        .post(
                            "pinnacle/broadcast", {
                                data: {
                                    "broadcast_id"     : this.broadcastId,
                                    "status"           : 'CANCELED'
                                }
                            }
                        )
                        .then(response => {
                            window.open("{{url('/')}}" +"/pinnacle/pinnacle-request/" +  this.requestId);
                            this.loading = false;
                        })
                        .catch(response=> {
                            this.loading = false;
                            return false;
                        });
                    },
                    saveBroadcast() {
                        this.loading = true;
                        ProcessMaker.apiClient
                        .post(
                            "pinnacle/update-broadcast/", {
                                data: {
                                    "broadcast_id"     : this.broadcastId,
                                    "request_id"       : this.requestId,
                                    "broadcast_title"  : this.broadcastTitle,
                                    "slip_title"       : this.slipTitle,
                                    "sent_for_status"  : this.sentForStatus,
                                    "responder_id"     : this.responderId,
                                    "cc_notifications" : this.ccNotifications,
                                    "date_to_send"     : this.dateToSend,
                                    "time_to_send"     : this.timeToSend,
                                    "send_email"       : this.sendEmail,
                                    "send_sms"         : this.sendSms,
                                    "copy_user_group"  : this.copyUserGroup,
                                    "sms_content"      : this.smsContent,
                                    "email_content"    : this.emailContent,
                                    "status"           : this.status,
                                    "copy_user_group"  : JSON.stringify($("#copyUserGroup").val()),
                                    "copy_user_group_config" : $("#copyUserGroup").select2('data'),
                                    "files" : JSON.stringify(this.files)
                                }
                            }
                        )
                        .then(response => {
                            this.loading = false;
                        })
                        .catch(response=> {
                            this.loading = false;
                            return false;
                        });
                    },

                    log: function (val) {
                        this.time = val
                    },
                    finishEdit() {
                        try {
                            this.status = 'QUEUED';
                            if (this.validateForm()) {
                                this.saveBroadcast();
                                window.open("{{url('/')}}" +"/pinnacle/pinnacle-request/" +  this.requestId, "_self");
                            } else {
                                var forms = document.getElementsByClassName('needs-validation');
                                forms[0].className += " was-validated";
                                return false;
                            }
                        } catch (error) {
                            top.ProcessMaker.alert("There was an error To save form: " + error.message, "danger");
                            return false;
                        }
                    },
                    populateEmployeeList () {
                        let data = $.map(this.staffList, function (obj) {
                            obj.id   = obj.id || obj.employeeNo;
                            obj.text = obj.text || obj.fullName;
                            return obj;
                        });

                        $('#copyUserGroup')
                        .select2({
                            "placeholder": 'Select an option',
                            "data"       : data
                        })
                    },
                    uploadBroadcastFile () {

                        this.uploadFile = this.$refs.uploadFile.files[0];

                        let formData = new FormData();
                        formData.append("file", this.uploadFile);
                        ProcessMaker.apiClient
                        .post('/requests/' + this.requestId +  '/files',
                        formData,{
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function (response) {
                            app.files.push(response.data.fileUploadId);
                            app.saveBroadcast();
                            app.getBroadcastFiles();
                        });
                    },
                    downloadFile(fileId) {
                        window.open( "{{ url('request')}}/" + this.requestId + "/files/" + fileId, "_blank");
                    },
                    getBroadcastFiles() {
                        ProcessMaker.apiClient
                        .get('/pinnacle/broadcast/files/' + this.broadcastId)
                        .then(response => {
                            console.log(response);
                            this.filesList = response.data;
                        }).catch(response => {
                            top.ProcessMaker.alert("There was an error on getSlipFiles function: " + error.message, "danger");
                        });
                    },
                    validateForm () {
                        let status = true;

                        if (this.broadcastTitle == '' || this.broadcastTitle == null
                            || this.slipTitle == '' || this.slipTitle == null
                            || this.sentForStatus == '' || this.sentForStatus == null
                            || this.responderId == '' || this.responderId == null
                            || this.dateToSend == '' || this.dateToSend == null
                            || this.timeToSend == ''  || this.timeToSend == null) {
                            status = false;
                        }

                        return status;
                    }

                },
                mounted(){
                    this.$nextTick(function () {
                        this.broadcastId   = broadcast["broadcast_id"];
                        this.requestId     = broadcast["request_id"];
                        this.broadcastTitle = broadcast['broadcast_title'],
                        this.slipTitle     = broadcast["slip_title"];
                        this.sentForStatus = broadcast["sent_for_status"];
                        this.files         = broadcast["files"] == null ? [] : broadcast["files"];
                        this.filesList     = files;
                        this.staffList     = staff_list;
                        this.apiToken      = apiToken;
                        this.responderId   = broadcast['responder_id'];
                        this.ccNotifications     = broadcast['cc_notifications'];
                        this.dateToSend          = broadcast['date_to_send'];
                        this.timeToSend          = broadcast['time_to_send'];
                        this.sendEmail           = broadcast['send_email'];
                        this.sendSms             = broadcast['send_sms'];
                        this.copyUserGroupConfig = broadcast['copy_user_group_config'];
                        this.smsContent          = broadcast['sms_content'];
                        this.emailContent        = broadcast['email_content'];
                        this.status              = broadcast['status'];

                        this.populateEmployeeList();
                        $("#copyUserGroup").val(broadcast['copy_user_group']);
                        $("#copyUserGroup").trigger("change");
                    });
                },
                computed: {
                    studentfullName: function(){
                        return this.student.firstname + ' ' + this.student.lastname;
                    },

                    createBroadcastData: function () {
                        return 'pinnacle/broadcast?type=' + this.createBroadcastData;
                    }
                },
                watch: {
                    smsContent: function (val) {
                        if (val != null && val.length > 0) {
                            this.chartCount = isNaN(this.smsMax - val.length) ? 0 : this.smsMax - val.length;
                        }
                    }
                },
                created () {
                }
            });

        </script>

    @endsection
@endsection