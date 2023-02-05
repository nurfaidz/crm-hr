@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">

                <section class="horizontal-wizard">
                    <div class="bs-stepper horizontal-wizard-example">
                        <div class="bs-stepper-content">
                            <h3 class="m-0">Overtime Details</h3>
                        </div>
                        <div class="bs-stepper-header">
                            <div class="step">
                                <button type="button" class="step-trigger first-st done-step-trigger medical-step-trigger">
                                    <span class="bs-stepper-box first-bsb done-bs-stepper-box">
                                        <span class="bs-stepper-number first-bsn done-bs-stepper-number">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title first-bst done-bs-stepper-title">New
                                            Application</span>
                                    </span>
                                </button>
                            </div>
                            <span class="first-middle-line active-middle-line"></span>
                            <div class="step">
                                <button type="button"
                                    class="step-trigger first-st {{ $approve1 }}-step-trigger medical-step-trigger">
                                    <span class="bs-stepper-box first-bsb {{ $approve1 }}-bs-stepper-box">
                                        <span class="bs-stepper-number first-bsn {{ $approve1 }}-bs-stepper-number">
                                            @if ($approve1 == 'done')
                                                <i class="fa-solid fa-check"></i>
                                            @endif
                                            @if ($approve1 == 'danger')
                                                <i class="fa-solid fa-close"></i>
                                            @endif
                                            @if ($approve1 == 'active')
                                                2
                                            @endif
                                        </span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span
                                            class="bs-stepper-title first-bst {{ $approve1 }}-bs-stepper-title">Higher-Up
                                            Approval</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            @if ($overtime->status == 'oap' && $overtime->update_by > 0)
                                <div>
                                    <p class="font-weight-bolder">Approved By</p>
                                    <p class="mb-50">
                                        {{ $overtime->status == 'oap' && $overtime->update_by > 0 ? $approvedBy->first_name . ' ' . $approvedBy->last_name : '-' }}
                                    </p>
                                </div>
                            @endif
                            @if ($overtime->status == 'opd')
                                <div>
                                    <p class="font-weight-bolder">Approved By</p>
                                    <p class="mb-50">
                                        -
                                    </p>
                                </div>
                            @endif
                            @if ($overtime->status == 'orj' && $overtime->update_by > 0)
                                <div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="font-weight-bolder mb-0">Rejected By</p>
                                            <p class="mt-0">
                                                {{ $approvedBy->first_name . ' ' . $approvedBy->last_name }}
                                            </p>
                                            <p class="font-weight-bolder">Reason Rejection</p>
                                        </div>
                                        <div class="col-6">
                                            <textarea name="notes" id="notes" cols="20" rows="5" class="form-control bg-white"
                                                placeholder="{{ $overtime->notes }}" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div id="leave-details-information">
                                <div class="row">
                                    <div class="col-6 employee-information">
                                        <div>Employee Information</div>
                                        <div>Employee Name :
                                            {{ $employee->first_name . ' ' . $employee->last_name }}
                                        </div>
                                        <div>Employee ID : {{ $employee->nip }}</div>
                                        <div>Entity : {{ $branch->branch_name }}</div>
                                        <div>SBU : {{ $department->department_name }}</div>
                                        <div>Job Position : {{ $jobPosition->job_position }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-md card-custom">
                                <div class="container">
                                    <div class="overtime-bar">
                                        <div class="row">
                                            <div class="widget col-md-3">
                                                <div class="info border-right-cust">
                                                    <div class="info-header">
                                                        <span>Remaining Overtime</span>
                                                    </div>
                                                    <div class="info-content">
                                                        <h5 class="time-color">
                                                            {{ "{$remainingOvertime->h} H {$remainingOvertime->m} M" }}
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget col-md-3">
                                                <div class="info border-right-cust">
                                                    <div class="info-header">
                                                        <span>Start Time</span>
                                                    </div>
                                                    <div class="info-content">
                                                        <h5 class="time-color">
                                                            {{ date('H:i', strtotime($overtime->start_time)) }} WIB
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget col-md-3">
                                                <div class="info border-right-cust">
                                                    <div class="info-header">
                                                        <span>End Time</span>
                                                    </div>
                                                    <div class="info-content">
                                                        <h5 class="time-color">
                                                            {{ date('H:i', strtotime($overtime->end_time)) }} WIB
                                                        </h5>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="widget col-md-3">
                                                <div class="info">
                                                    <div class="info-header">
                                                        <span>Total Request Time</span>
                                                    </div>
                                                    <div class="info-content">
                                                        <h5 class="time-color" id="total">0 H 0 M</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="overtime-date">
                                <h5>Overtime Date</h5>
                                <span>{{ date('d M Y', strtotime($overtime->date)) }}</span>
                            </div>
                            <div id="leave-details">
                                <div class="row">
                                    <div class="col-6" id="leave-details-notes">
                                        <p>Notes</p>
                                        <textarea name="notes" id="notes" cols="30" rows="5" class="form-control bg-white"
                                            placeholder="{{ $overtime->notes }}" disabled></textarea>
                                    </div>

                                    <div class="custom-input-image-container"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-right pr-1 leave-details-buttons">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
@endsection

@section('page_script')
    <script>
        $(document).ready(() => {

            let start = "{{ date('H:i', strtotime($overtime->start_time)) }}";
            let end = "{{ date('H:i', strtotime($overtime->end_time)) }}";

            // $("#duration-h").text(0)
            // $("#duration-m").text(0)

            const startTime = moment(start, "hh:mm");
            const endTime = moment(end, "hh:mm");

            const minutesDiff = endTime.diff(startTime, 'minutes');

            const hours = Math.floor(minutesDiff / 60);
            const minutes = Math.floor(minutesDiff % 60);

            $("#total").html(hours + ' H ' + minutes + ' M');
            // console.log(hours)
            // $("#duration-m").text(minutes);
            // if(isNaN(start) && isNaN(end)){
            // }
        })
    </script>
@endsection
