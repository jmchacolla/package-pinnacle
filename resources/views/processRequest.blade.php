@extends('layouts.layout')

@section('sidebar')
    @include('layouts.sidebar', ['sidebar'=> Menu::get('sidebar_admin')])
@endsection
@section('css')
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

        #processRequest > tbody > tr > td > a > i {
            color: #673AB7 !important;
        }
        #processRequest > tbody > tr > td > a > i:hover {
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

        #processRequest > thead > tr {
            text-align: center;
            background-color: #411f91;
            color: #fff;
        }
        #processRequest > thead > tr > th {
            border-bottom: 3px solid  #FBBE02 !important;
            padding-top:10px;
            padding-bottom:10px;
        }
        #processRequest > thead > tr > th:hover {
            background-color: #FBBE02 !important;
        }
        #processRequest > tbody > tr:hover {
            background-color:#e4d9f7 !important;
        }
        td > a :hover{
            color:  #4c288c !important;
        }
        #processRequest_paginate > ul > li.paginate_button.page-item.active > a {
            background-color: #411f91 !important;
            color: #fff !important;
        }
        #processRequest_paginate > ul > li > a{
            color: #411f91 !important;
        }
        #processRequest_previous > a {
            color: #411f91 !important;
        }

        #app > div > div.card-deck > div:nth-child(1) {
            background-color: #411f91 !important;
        }
        #app > div > div.card-deck > div:nth-child(3) {
            background-color: #6c757d !important;
        }
        #processRequest_wrapper > div.dt-buttons.btn-group.flex-wrap > button.btn.btn-secondary:hover{
            background-color: #FBBE02 !important;
            border:1px solid #FBBE02 !important;
        }
        #processRequest_filter {
            border: 1px solid #BDBDBD;
            padding: 10px;
        }
        #processRequest_wrapper{
            min-height: 600px;
        }
        .dropdown-item:hover {
            cursor: pointer;
        }

    </style>
@endsection


@section('content')

        <div class="card" id="app">
            <div class="card-body" style="padding: 20px;" v-cloak>
                <h5 class="card-title">Parent / Student Slip</h5>
                <div class="card-deck" style="margin-top: 20px;margin-bottom:20px;">

                    <div class="card bg-info d-flex flex-row card-border border-0 text-white">
                        <div class="card-header card-size-header px-4 px-xl-5 d-flex d-md-none d-lg-flex align-items-center justify-content-center border-0">
                            <i class="fas fa-paper-plane fa-2x"></i></i>
                        </div>
                        <div class="card-body">
                            <span class="card-link text-light">
                                <h1 class="m-0 font-weight-bold">{{ $countRequest }}</h1>
                                <h6 class="card-text">Slip Sent</h6>
                            </span>
                        </div>
                    </div>

                    <div class="card bg-warning d-flex flex-row  card-border border-0 text-white">
                        <div class="card-header card-size-header px-4 px-xl-5 d-flex d-md-none d-lg-flex align-items-center justify-content-center border-0">

                            <i class="fas fa-id-badge fa-2x"></i>
                        </div>
                        <div class="card-body">
                            <span class="card-link text-light">
                                <h1 class="m-0 font-weight-bold">345</h1>
                                <h6 class="card-text">Students</h6>
                            </span>
                        </div>
                    </div>

                    <div class="card bg-info d-flex flex-row card-border border-0 text-white">
                        <div class="card-header card-size-header px-4 px-xl-5 d-flex d-md-none d-lg-flex align-items-center justify-content-center border-0">
                            <i class="fas fa-clipboard-check fa-2x"></i>
                        </div>
                        <div class="card-body">
                            <span class="card-link text-light">
                                <h1 class="m-0 font-weight-bold">{{ $slipResponse }}</h1>
                                <h6 class="card-text">Responses</h6>
                            </span>
                        </div>
                    </div>

                    <div class="card bg-warning d-flex flex-row  card-border border-0 text-white">
                        <div class="card-header card-size-header px-4 px-xl-5 d-flex d-md-none d-lg-flex align-items-center justify-content-center border-0">

                            <i class="fas fa-chart-bar fa-2x"></i>
                        </div>
                        <div class="card-body">
                            <span  class="card-link text-light">
                                <h1 class="m-0 font-weight-bold">{{ empty($slipResponse) || $countRequest == 0 ? 0 : round(($slipResponse * 100)/$countRequest, 2)}}%</h1>
                                <h6 class="card-text">Response Rate</h6>
                            </span>
                        </div>
                    </div>
                </div>
                {{-- Data Table --}}
                <div class="container-fluid" style="padding: 20px;">
                    <div class="table-responsive">

                        <table id="processRequest"
                        class="table table-striped table-sm"
                        style="width: 100%; font-size: 14px;">
                        </table>
                    </div>
                </div>
                {{-- NavBar --}}
                <nav class="navbar navbar-expand-lg navbar-dark" id="dataTableSeach" style="padding-left: 0px;padding-right: 0px;">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent" style="z-index: 999; overflow-y: visible;">
                        <ul class="navbar-nav mr-auto  bg-secondary">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dataTableFilter" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Filter by Status
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dataTableFilter">
                                    <a class="dropdown-item response-status" dataTableFilter = ""><i class="fas fa-list-alt" > </i> All Statutes</a>
                                    <a class="dropdown-item response-status" dataTableFilter = "DRAFT"><i class="fas fa-eraser"> </i> Draft</a>
                                    <a class="dropdown-item response-status" dataTableFilter = "QUEUED"><i class="fab fa-stack-overflow"> </i> Queued</a>
                                    <a class="dropdown-item response-status" dataTableFilter = "SENT"><i class="fas fa-paper-plane"> </i> Sent</a>
                                    <a class="dropdown-item response-status" dataTableFilter = "PENDING_APPROVAL"><i class="far fa-clock"> </i> Pending Approval</a>
                                    <a class="dropdown-item response-status" dataTableFilter = "REJECTED"><i class="fas fa-exclamation-triangle"> </i> Rejected</a>
                                    <a class="dropdown-item response-status" dataTableFilter = "APPROVED"><i class="far fa-calendar-check"> </i> Approved</a>
                                </div>
                            </li>

                            <li class="nav-item">
                                <input type="date" id="minDate" class="form-control form-control-sm" placeholder="Select from date" style="margin-top: 5px;">
                            </li>
                            <li class="nav-item">
                                <input type="date" id="maxDate" class="form-control form-control-sm" placeholder="Select to date" style="margin-left: 5px;margin-top: 5px;">
                            </li>
                            <li class="nav-item">
                                <button id="searchDate" class="btn btn-outline-secondary btn-sm" type="button" style="color: #fff !important;margin: 5px;margin-left: 10px; border:1px solid #fff !important;">
                                    <i class="fas fa-search" style="color: #fff !important;"></i>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button id="cleanDate" class="btn btn-outline-secondary btn-sm" type="button" style="color: #fff !important;margin: 5px;margin-left: 10px; border:1px solid #fff !important;">
                                    {{-- <i class="fas fa-search" style="color: #fff !important;"></i> --}}
                                     Clean
                                </button>
                            </li>
                        </ul>

                        <div class="input-group navbar-right col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <input type="text" class="form-control form-control-sm" placeholder="Search" id="searchText">
                        </div>
                    </div>
                </nav>
            </div>
        </div>

    @section('js')
        <script>
            window.temp_define = window['define'];
            window['define']  = undefined;
        </script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.32/moment-timezone.min.js" integrity="sha512-u3yRfU7FD5wGhxEMFZLZT/W/Y+C0vqUuQjPAhRWnQjBZ1LhUMnyTnZ6AfwxLSCxACT4eiyAnjFAMIt0qog67qg==" crossorigin="anonymous"></script>
            <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
            <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
            {{-- Jquery Datatable --}}
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.bootstrap4.min.js"></script>

            <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
            {{-- Buttons funcionality --}}
            <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>

        <script>
            window['define'] = window.temp_define;
        </script>

        <script>

            var app = new Vue({

                el: '#app',
                data(){
                    return{
                        dataTable : '',
                        requestData : {!! json_encode($processRequest) !!}
                    }
                },
                methods: {
                    formatDate(date) {
                        return (date ==null || date.lenght<=0) ? '' : moment(new Date(date)).format('D MMM YYYY @ HH:mm:ss');
                    }
                },

                mounted(){
                    this.$nextTick(function () {
                        document.title = "Request Report";
                        this.dataTable = $('#processRequest').DataTable({
                            "responsive": true,
                            "processing": true,
                            "lengthChange": true,
                            "data" : this.requestData,
                            "order": [[ 0, "desc" ]],
                            "columns": [
                                /*0*/   { "title": "No", "data": "id","visible": true, "className": "text-center", "defaultContent": ""},
                                /*1*/   {
                                            "title": "No","data": "","orderable": false, "visible": false,
                                            "className": "text-center",
                                            "defaultContent": "",
                                            "render": function (data, type, row, meta) {
                                                return meta.row + meta.settings._iDisplayStart + 1;
                                            }
                                        },
                                /*2*/   { "title": "Date Added", "data": "initiated_at", "defaultContent": "","className": "text-center"},
                                /*3*/   { "title": "Requestor", "data": "data.requestorName", "defaultContent": "","className": "text-center"},

                                /*4*/   { "title": "Slip Title" , "data": "", "className": "text-center",
                                            "render": function ( data, type, row ) {
                                                if ( row.data != undefined && row.data != null) {
                                                    let appData = row.data;
                                                    return appData.slipTitle;
                                                }
                                                return "";
                                            },
                                            "defaultContent": ""
                                        },
                                /*5*/   { "title": "Slip Date Time to Send" , "data": "slipDateToSend", "className": "text-center",
                                            "render": function ( data, type, row ) {
                                                if ( row.data != undefined && row.data != null ) {
                                                        let appData = row.data;
                                                        let dateToSend = (appData.slipDateToSend == undefined || appData.slipDateToSend == '' || appData.timeToSend == undefined || appData.timeToSend == '') ? '' : appData.slipDateToSend + ' ' +appData.timeToSend;
                                                        return (dateToSend == '' ) ? '' : moment(new Date(dateToSend)).format('D MMM YYYY @ HH:mm');
                                                }
                                                return "";
                                            },
                                            "defaultContent": ""
                                        },
                                /*6*/   { "title": "Due Date" , "data": "", "className": "text-center", "type": "date",
                                            "render": function ( data, type, row ) {
                                                if ( row.data != undefined && row.data != null) {
                                                        let appData = row.data;
                                                        return (appData.slipDueDate ==null || appData.slipDueDate.lenght<=0) ? "" : moment(new Date(appData.slipDueDate)).format('D MMM YYYY');
                                                }
                                                return "";
                                            },
                                            "defaultContent": ""
                                        },
                                /*7*/   { "title": "Status", "data": "status", "defaultContent": "","className": "text-center",
                                            "render": function ( data, type, row ) {
                                                let appData = '';
                                                if ( row.data != undefined && row.data != null) {
                                                    newData= row.data;
                                                    appData = newData;
                                                }
                                                let html = '';
                                                switch (appData.status) {
                                                    case 'PENDING_APPROVAL':
                                                        html = '<span class="badge badge-info">PENDING APPROVAL</span>';
                                                        break;
                                                    case 'DRAFT':
                                                    default:
                                                        html = '<span class="badge badge-secondary">DRAFT</span>';
                                                        break;
                                                    case 'SENT':
                                                        html = '<span class="badge badge-success">SENT</span>';
                                                        break;
                                                    case 'QUEUED':
                                                        html = '<span class="badge badge-primary">QUEUED</span>';
                                                        break;
                                                    case 'REJECTED':
                                                        html = '<span class="badge badge-danger">REJECTED</span>';
                                                        break;
                                                    case 'APPROVED':
                                                        html = '<span class="badge badge-warning">APPROVED</span>';
                                                        break;
                                                }
                                                return html;
                                            }
                                        },
                                /*8*/   { "title": "#Sent", "data": "notification_sent", "defaultContent": "","className": "text-center"},
                                /*9*/   { "title": "#Responses", "data": "responses_done", "defaultContent": "", "className": "text-center"},
                                /*10*/   { "title": "% Rate", "data": "", "defaultContent": "",
                                            "className": "text-right",
                                            "render": function ( data, type, row ) {
                                                if (row.notification_sent == 0 ) {
                                                    return '0.00 %'
                                                }
                                                let rate = (row.responses_done * 100) / row.notification_sent;
                                                return isNaN(rate) ? '0.00 %' : Number.parseFloat(rate).toFixed(2)+ '%';
                                            }
                                        },
                                /*11*/  {
                                            "title": "View",
                                            "data": "",
                                            "orderable": false,
                                            "className": "text-center",
                                            "render": function ( data, type, row ) {
                                                let html = '<a title="View Slip" class="text-primary" href="pinnacle-request/' ;
                                                html += row.id + '" style="font-weight: bold;font-size:18px;">';
                                                html += '<i class="fas fa-external-link-square"></i></a>';

                                                return html;
                                            },
                                            "defaultContent": ""
                                        }
                            ],
                            "dom": '<"top"><"bottom"><"clear"><"toolbar">Brtip',
                            "buttons": [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ],
                            "initComplete": function () {
                                $("#searchByStatus").append($("#dateSearch"));
                                $.fn.dataTable.ext.search.push(
                                    function (settings, data, dataIndex) {
                                        var valid = true;
                                        var min = moment($("#minDate").val());
                                        if (!min.isValid()) {
                                            min = null;
                                        }

                                        var max = moment($("#maxDate").val());
                                        if (!max.isValid()) {
                                            max = null;
                                        }

                                        if (min === null && max === null) {
                                            // no filter applied or no date columns
                                            valid = true;
                                        }
                                        else {
                                            $.each(settings.aoColumns, function (i, col) {
                                                if (col.type == "date") {
                                                    var cDate = moment(data[i],'D MMM YYYY');
                                                    cDate = moment(cDate).format('YYYY-MM-DD');
                                                    cDate = moment(cDate, 'YYYY-MM-DD');

                                                    if (cDate.isValid()) {
                                                        if (max !== null && max.isBefore(cDate)) {
                                                            valid = false;
                                                        }
                                                        if (min !== null && cDate.isBefore(min)) {
                                                            valid = false;
                                                        }
                                                    }
                                                    else {
                                                        valid = false;
                                                    }
                                                }
                                            });
                                        }
                                        return valid;
                                    }
                                );
                                $("#searchDate").click(function () {
                                    $('#processRequest').DataTable().draw();
                                });

                                $("#cleanDate").click(function () {
                                    var min = $("#minDate").val("");
                                    var max = $("#maxDate").val("");
                                    $('#processRequest').DataTable().draw();
                                });

                                $(".response-status").on("click", function () {
                                    let dataTable = $("#processRequest").DataTable();
                                    if($(this).attr("dataTableFilter") == 'PENDING_APPROVAL') {
                                        dataTable.column(7).search("PENDING APPROVAL").draw();
                                    } else {
                                        dataTable.column(7).search( $(this).attr("dataTableFilter") ).draw();
                                    }
                                });
                            }
                        });
                        $("#processRequest_wrapper > div.dt-buttons.btn-group.flex-wrap").hide();

                        $("div.toolbar").append($("#dataTableSeach"));

                        $('#searchText').on( 'keyup', function (e) {
                            let dataTable = $('#processRequest').DataTable();
                            dataTable.search( this.value ).draw();
                        });

                    });
                }
            });
        </script>

    @endsection
@endsection



