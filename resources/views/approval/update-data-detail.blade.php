@extends('partials.template')
@section('meta_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <p class="d-flex align-items-center">
                    <img src="{{ url('./img/icons/dashboard.svg') }}" alt="Dashboard">
                    <a href="{{ url('dashboard') }}" class="dashboard-link">Dashboard</a>
                    <a href="{{ $employee->id }}" class="leave-application-link">/ Approval Queue /
                        Updating Data / Details Updating Data</a>
                </p>
                <section class="horizontal-wizard">
                    <div class="bs-stepper horizontal-wizard-example">
                        <div class="bs-stepper-content">
                            <h3>Details Updating Data</h3>
                        </div>
                        <div class="container border-bottom-cust">
                            <div class="stepper">
                                <div class="bs-stepper-header">
                                    <div class="step" data-target="#account-details">
                                        <button type="button" class="step-trigger first-st done-step-trigger">
                                            <span class="bs-stepper-box first-bsn done-bs-stepper-box">
                                                <span class="bs-stepper-number first-bsn done-bs-stepper-number"><i
                                                        class="fa-solid fa-check"></i></span>
                                            </span>
                                            <span class="bs-stepper-label">
                                                <span class="bs-stepper-title first-bst done-bs-stepper-title">New
                                                    Application</span>
                                            </span>
                                        </button>
                                    </div>
                                    <span class="first-middle-line active-middle-line"></span>
                                    <div class="step" data-target="#personal-info active-step-trigger">
                                        <button type="button" class="step-trigger second-st  active-bs-stepper-box">
                                            <span class="bs-stepper-box second-bsb  active-bs-stepper-box">
                                                <span class="bs-stepper-number second-bsn active-bs-stepper-number">2</span>
                                            </span>
                                            <span class="bs-stepper-label">
                                                <span class="bs-stepper-title second-bst active-bs-stepper-title">HR
                                                    Approval</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="bs-stepper-content">
                            <div class="container">
                                <div class="row">
                                    <div class="employee-details col-6">
                                        <h5><b>Employee's Information</b></h5>
                                        <div class="employee-information">
                                            <div class="employee-data">
                                                <span>Employee Name :
                                                    {{ $employee->first_name . ' ' . $employee->last_name }}
                                                </span>
                                            </div>
                                            <div class="employee-data">
                                                <span>ID Employee :
                                                    {{ $employee->nip }}
                                                </span>
                                            </div>
                                            <div class="employee-data">
                                                <span>Entity :
                                                    {{ $employee->branch_name }}
                                                </span>
                                            </div>
                                            <div class="employee-data">
                                                <span>SBU :
                                                    {{ $employee->department_name }}
                                                </span>
                                            </div>
                                            <div class="employee-data">
                                                <span>Job Position :
                                                    {{ $employee->job_position }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="employee-details col col-6">
                                        <h5><b>Updating Data</b></h5>
                                        <div class="employee-information">
                                            <div class="employee-data">
                                                <span>Employee Information :
                                                    {{ $employee->employee_information }}
                                                </span>
                                            </div>
                                            <div class="employee-data">
                                                <span>Category Information :
                                                    {{ $employee->category_information }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div><br>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input all_checked" id="select-all">
                                    <label class="custom-control-label" for="select-all"><b>Select All</b></label>
                                </div><br>
                                <div class="card shadow-md card-custom">
                                    <div class="container">
                                        <div class="overtime-bar">
                                            <div class="row text-center p-0">
                                                <div class="widget col-md-6">
                                                    <div class="info border-right-cust">
                                                        <div class="info-content">
                                                            <h5>
                                                                Before
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="widget col-md-6">
                                                    <div class="info ">
                                                        <div class="info-content">
                                                            <h5>
                                                                After
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><br>
                                <form id="form_data">
                                    <div class='form-group'>
                                        @foreach ($dataEmployee as $key => $value)
                                            <!-- <div class="custom-control custom-checkbox">
                                                                                                <input type="checkbox" class="custom-control-input all_checked" name="updateData[]"
                                                                                                    value="{{ $value->temp_employee }}">
                                                                                                <label class="custom-control-label" for="select"><b>{{ $value->title }}</b></label>
                                                                                            </div><br> -->
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input checkbox check-all"
                                                    id="{{ $key }}" name="updateData[]"
                                                    value="{{ $key }}">
                                                <label class="custom-control-label"
                                                    for="{{ $key }}"><b>{{ $value->title }}</b></label>
                                            </div>
                                            <div class="card shadow-md card-custom">
                                                <div class="container">
                                                    <div class="overtime-bar">
                                                        <div class="row text-center p-0">
                                                            <div class="widget col-md-6">
                                                                <div class="info border-right-cust">
                                                                    <div class="info-content">
                                                                        <h5 class="time-color">
                                                                            {{ $value->employee }}
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="widget col-md-6">
                                                                <div class="info ">
                                                                    <div class="info-content">
                                                                        <h5 class="time-color">
                                                                            {{ $value->temp_employee }}
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-right">
                                @can('update_data.reject')
                                    <button onclick=EditDataAction($(this)) data-id="{{ $employee->id }}"
                                        class="btn btn-danger">Reject</button>
                                @else
                                    <button class="btn btn-danger" disabled>Reject</button>
                                @endcan

                                @can('update_data.approve')
                                    <button onclick=EditDataApproveAction($(this)) data-id="{{ $employee->id }}"
                                        class="btn btn-success">Approve</button>
                                @else
                                    <button class="btn btn-success" disabled>Approve</button>
                                @endcan
                            </div>
                        </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
@endsection

@section('page_script')
    <script>
        $('.all_checked').on('click', function() {
            const allCheckedCheckbox = $(this);
            $('.checkbox').each(function() {
                $(this).prop('checked', allCheckedCheckbox.prop('checked'));
            });
        });
        const EditDataAction = (e) => {

            swal.fire({
                title: 'Sure you want to Reject?',
                text: "Are you sure want to reject this?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonClass: 'btn btn-outline-primary ml-1',
                cancelButtonText: 'No, Cancel',
                confirmButtonText: 'Yes, Confirm',
                confirmButtonClass: 'btn btn-primary',
                showCloseButton: true,
                buttonsStyling: false,
            }).then((result) => {
                if (result.value) {
                    const id = e.attr('data-id');
                    const base_url = "{{ url('approval/update-data') }}";
                    $.ajax({
                        url: `${base_url}/${id}/reject`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: (result) => {
                            if (result.error) {
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: result.message,
                                    confirmButtonClass: 'btn btn-primary',
                                })
                            } else {
                                setTimeout(function() {
                                    $('#ajax-update').DataTable().ajax
                                        .reload();
                                }, 1000);
                                setTimeout(() => {
                                    window.location.replace("{{ url('approval') }}");
                                }, 3000);

                                Swal.fire({
                                    type: "success",
                                    icon: 'success',
                                    title: 'Succesfully rejected!',
                                    confirmButtonClass: 'btn btn-success',
                                    confirmButtonClass: 'btn btn-primary',
                                    showConfirmButton: false,
                                    html: ` <p>${result.message}</p>
                    <div class="row mt-2 pb-2">
                        <div class="col-12">
                            <button type="button" onclick="window.location.href='{{ url('dashboard') }}'" class="btn btn-primary col-12" tabindex="0">Dashboard</button><br>
                        </div>
                        <!--<div class="col-12 mt-2">
                            <button type="button" class="btn btn-outline-primary block col-12" tabindex="0">Undo</button> 
                        </div>-->
                    </div>`,
                                    showCloseButton: true,
                                })
                            }
                        }
                    })
                }
            })
        }

        const EditDataApproveAction = (e) => {

            swal.fire({
                title: 'Sure you want to approve?',
                text: "Are you sure want to approve this?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonClass: 'btn btn-outline-primary ml-1',
                cancelButtonText: 'No, Cancel',
                confirmButtonText: 'Yes, Confirm',
                confirmButtonClass: 'btn btn-primary',
                showCloseButton: true,
                buttonsStyling: false,
            }).then((result) => {
                if (result.value) {
                    const id = e.attr('data-id');
                    const base_url = "{{ url('approval/update-data') }}";
                    $.ajax({
                        url: `${base_url}/${id}/approve`,
                        type: 'POST',
                        data: $('#form_data').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: (result) => {
                            if (result.error) {
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: result.message,
                                    confirmButtonClass: 'btn btn-primary',
                                })
                            } else {
                                setTimeout(function() {
                                    $('#ajax-update').DataTable().ajax
                                        .reload();
                                }, 1000);
                                setTimeout(() => {
                                    window.location.replace("{{ url('approval') }}");
                                }, 3000);

                                Swal.fire({
                                    type: "success",
                                    icon: 'success',
                                    title: 'Succesfully accepted!',
                                    confirmButtonClass: 'btn btn-success',
                                    confirmButtonClass: 'btn btn-primary',
                                    showConfirmButton: false,
                                    html: ` <p>${result.message}</p>
                    <div class="row mt-2 pb-2">
                        <div class="col-12">
                            <button type="button" onclick="window.location.href='{{ url('dashboard') }}'" class="btn btn-primary col-12" tabindex="0">Dashboard</button><br>
                        </div>
                        <!--<div class="col-12 mt-2">
                            <button type="button" class="btn btn-outline-primary block col-12" tabindex="0">Undo</button> 
                        </div>-->
                    </div>`,
                                    showCloseButton: true,
                                })
                            }
                        }
                    })
                }
            })
        }
    </script>
@endsection
