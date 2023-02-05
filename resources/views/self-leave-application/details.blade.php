@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <p class="d-flex align-items-center">
                    <img src="{{ url('./img/icons/dashboard.svg') }}" alt="dashboard">
                    <a href="{{ url('dashboard') }}" class="dashboard-link">Dashboard</a>
                    <a href="{{ url('self-leave-application') }}" class="this-page-link">/ Leave Tracker</a>
                    <a href="{{ $leaveApplication->leave_application_id }}" class="this-page-link">/ Details</a>
                </p>
                <section class="horizontal-wizard">
                    <div class="bs-stepper horizontal-wizard-example">
                        <div class="bs-stepper-content">
                            <h3>Leave Application Details</h3>
                        </div>
                        <div class="bs-stepper-header">
                            <div class="step">
                                <button type="button" class="step-trigger first-st done-step-trigger">
                                    <span class="bs-stepper-box first-bsb done-bs-stepper-box">
                                        <span class="bs-stepper-number first-bsn done-bs-stepper-number">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title first-bst done-bs-stepper-title">New Application</span>
                                    </span>
                                </button>
                            </div>
                            <span class="first-middle-line active-middle-line"></span>
                            <div class="step">
                                <button type="button" class="step-trigger first-st done-step-trigger">
                                    <span class="bs-stepper-box first-bsb done-bs-stepper-box">
                                        <span class="bs-stepper-number first-bsn done-bs-stepper-number">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title first-bst done-bs-stepper-title">Success</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <div id="leave-approved-by">
                                @php
                                    if ($leaveApplication->short === 'Canceled') {
                                        echo '<p>Canceled By</p>';
                                        echo "<span>$canceler->first_name $canceler->last_name</span>";
                                    } else if ($leaveApplication->short === 'Approved') {
                                        echo '<p>Approved By</p>';
                                        echo "<span>$approver->first_name $approver->last_name</span>";
                                    } else if ($leaveApplication->short === 'Rejected') {
                                        echo '<p>Rejected By</p>';
                                        echo "<span>$rejecter->first_name $rejecter->last_name</span>";
                                    } else {
                                        echo '<p>Approved By</p>';
                                        echo '<span>-</span>';
                                    }
                                @endphp
                            </div>
                            <div id="leave-details-information">
                                <div class="row">
                                    <div class="col-6 employee-information">
                                        <div>Employee Information</div>
                                        <div>Employee Name : {{ $leaveApplication->first_name . ' ' . $leaveApplication->last_name }}</div>
                                        <div>Employee ID : {{ $leaveApplication->nip }}</div>
                                        <div>Entity : {{ $leaveApplication->branch_name }}</div>
                                        <div>SBU : {{ $leaveApplication->department_name }}</div>
                                        <div>Job Position : {{ $leaveApplication->job_position }}</div>
                                    </div>
                                    <div class="col-6 leave-application">
                                        <div>Leave Application</div>
                                        <div>Type of Leave : {{ $leaveApplication->leave_type_name }}</div>
                                        @php
                                            if ($leaveApplication->leave_type_id == 1) {
                                                if ($leaveApplication->option_request === 0) {
                                                    echo '<div>Option Request: Half-day</div>';
                                                } else {
                                                    echo '<div>Option Request: Full Day</div>';
                                                }
                                            }

                                            if ($leaveApplication->leave_type_id == 5) {
                                                echo "<div>Option Leave: $leaveApplication->option_leave</div>";
                                            }
                                        @endphp
                                        <div>Start Date : {{ \Carbon\Carbon::parse($leaveApplication->application_from_date)->format('d/m/Y') }}</div>
                                        <div>End Date : {{ \Carbon\Carbon::parse($leaveApplication->application_to_date)->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card leave-details-statistics">
                                <div class="card-body">
                                    <div class="row">
                                        @if ($leaveApplication->leave_type_id == 1 || $leaveApplication->leave_type_id == 2)
                                            <div class="col-4 text-center">
                                                <div class="text-left d-inline-block">
                                                    @if ($leaveApplication->leave_type_id == 1)
                                                        <div>Annual Leave Balance</div>
                                                        <span class="text-primary">{{ $leaveApplication->balance }} Days</span>
                                                        <div class="period-label">PERIODE</div>
                                                        <div class="custom-annual-leave">
                                                            <div class="d-flex justify-content-between">
                                                                <div>{{ $firstLeaveBalancePeriod }}</div>
                                                                <div>{{ $firstLeaveBalance }}</div>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                <div>{{ $secondLeaveBalancePeriod }}</div>
                                                                <div>{{ $secondLeaveBalance }}</div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div>Big Leave Balance</div>
                                                        <span class="text-primary">{{ $leaveApplication->balance }} Days</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-4 text-center">
                                                <div class="text-left d-inline-block">
                                                    <div>Used Balance</div>
                                                    <span class="text-primary">{{ $leaveApplication->used }} Days</span>
                                                    <div class="period-label">PERIODE</div>
                                                    <div class="custom-annual-leave">
                                                        <div class="d-flex justify-content-between">
                                                            <div>{{ $usedBalancePeriod }}</div>
                                                            <div>{{ $usedBalance }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4 text-center">
                                                <div class="text-left d-inline-block">
                                                    <div>Request Leave</div>
                                                    <span class="text-primary">{{ $leaveApplication->number_of_day }} Days</span>
                                                    <div class="period-label">PERIODE</div>
                                                    <div style="font-weight: 400">{{ $usedBalancePeriod }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-12 text-center">
                                                <div class="text-left d-inline-block">
                                                    <div>Request Leave</div>
                                                    <span class="text-primary">{{ $leaveApplication->number_of_day }} Days</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="leave-details-notes">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Notes</p>
                                        <textarea name="notes" id="notes" cols="30" rows="5" class="form-control bg-white" placeholder="{{ $leaveApplication->notes }}" disabled></textarea>
                                    </div>
                                </div>
                            </div>
                            <div id="leave-details-files">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Files</p>
                                        <div class="detail-files-container"></div>
                                    </div>
                                </div>
                            </div>
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
@endsection

@section('page_script')
    <script>
        $(document).ready(function() {
            function findBaseName(url) {
                return url.substring(url.lastIndexOf('/') + 1);
            }

            let images = {!! json_encode($leaveApplication->attachment, JSON_HEX_TAG) !!};
            let isMultiple = images.includes('|');

            if (isMultiple) {
                let files = images.split('|');
                let array = [];

                files.forEach(element => {
                    array.push(findBaseName(element));
                });

                for (let i = 0; i < array.length; i++) {
                    $('#leave-details-files .detail-files-container').append('<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="insert-drive-file"></span></div><input type="text" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled="disabled"><div class="input-group-append"><a href="{{ url('self-leave-application/download') }}/' + array[i] + '" class="input-group-text bg-white"><img src="{{ url('./img/icons/file-download.svg') }}" alt="insert-drive-file"></a></div></div>');
                }

                $('#leave-details-files .detail-files').each(function(j) {
                    $(this).attr('placeholder', array[j]);
                });
            } else {
                let file = findBaseName(images);

                if (file != '') {
                    $('#leave-details-files .detail-files-container').append('<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="insert-drive-file"></span></div><input type="text" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled="disabled"><div class="input-group-append"><a href="{{ url('self-leave-application/download') }}/' + file + '" class="input-group-text bg-white"><img src="{{ url('./img/icons/file-download.svg') }}" alt="insert-drive-file"></a></div></div>');
                    
                    $('#leave-details-files .detail-files').attr('placeholder', file);
                } else {
                    $('#leave-details-files .detail-files-container').append('<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="insert-drive-file"></span></div><input type="text" placeholder="No file available" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled><div class="input-group-append"><button class="input-group-text bg-white" disabled><img src="{{ url('./img/icons/file-download.svg') }}" alt="insert-drive-file"></button></div></div>');
                }
            }
        });
    </script>
@endsection