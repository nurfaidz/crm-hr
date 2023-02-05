<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">

<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <title>Workspace</title>
    <link rel="apple-touch-icon" href="{{ url('app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('app-assets/images/ico/favicon.ico') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/forms/wizard/bs-stepper.min.css">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/bootstrap-extended.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/colors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/components.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/themes/dark-layout.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/themes/bordered-layout.min.css') }}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/pages/app-calendar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/pages/page-bank.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/pages/page-employee.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/pages/page-dashboard.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/pages/page-holidays.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/pages/page-leave-application.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/pages/page-leave-approval.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/pages/page-medical-reimbursement.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/pages/page-self-leave-application.css') }}">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/forms/form-wizard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/style.css">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/plugins/forms/form-file-uploader.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/font/css/fontawesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/font/css/brands.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/font/css/solid.css') }}">

    <!-- END: Custom CSS-->

    @yield("page_style")
    @yield("meta_header")

    <style>
        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }

        .popover-header{
            /* background-color: red; */
        }
    </style>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static   menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col="1-column">
