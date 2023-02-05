@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <p class="d-flex align-items-center">
                    <img src="{{ url('./img/icons/dashboard.svg') }}" alt="Dashboard">
                    <a href="{{ url('dashboard') }}" class="dashboard-link">Dashboard</a>
                    <a href="{{ $medicalReimbursement->medical_reimbursement_id }}" class="leave-application-link">/ Medical Tracker Reimbursement / Medical Reimbursement Details</a>
                </p>
                <section class="horizontal-wizard">
                    <div class="bs-stepper horizontal-wizard-example">
                        <div class="bs-stepper-content">
                            <h3>Reimbursement Details</h3>
                        </div>
                        <div class="bs-stepper-header">
                            <div class="step" style="margin-left: 0">
                                <button type="button" class="step-trigger first-st done-step-trigger medical-step-trigger">
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
                                <button type="button" class="step-trigger first-st done-step-trigger medical-step-trigger">
                                    <span class="bs-stepper-box first-bsb done-bs-stepper-box">
                                        <span class="bs-stepper-number first-bsn done-bs-stepper-number">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title first-bst done-bs-stepper-title">Higher-Up Approval</span>
                                    </span>
                                </button>
                            </div>
                            <span class="first-middle-line active-middle-line"></span>
                            <div class="step">
                                <button type="button" class="step-trigger first-st done-step-trigger medical-step-trigger">
                                    <span class="bs-stepper-box first-bsb done-bs-stepper-box">
                                        <span class="bs-stepper-number first-bsn done-bs-stepper-number">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title first-bst done-bs-stepper-title">HR Approval</span>
                                    </span>
                                </button>
                            </div>
                            <span class="first-middle-line active-middle-line"></span>
                            <div class="step" style="margin-right: 0">
                                <button type="button" class="step-trigger first-st done-step-trigger medical-step-trigger">
                                    <span class="bs-stepper-box first-bsb done-bs-stepper-box">
                                        <span class="bs-stepper-number first-bsn done-bs-stepper-number">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title first-bst done-bs-stepper-title">Finance Approval</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <div id="leave-approved-by">
                                @php
                                    if ($medicalReimbursement->short === 'Rejected') {
                                        echo '<p>Rejected By</p>';
                                        echo "<div>$medicalReimbursement->first_name $medicalReimbursement->last_name</div>";
                                    } else if ($medicalReimbursement->short === 'Canceled') {
                                        echo '<p>Canceled By</p>';
                                        echo "<div>$medicalReimbursement->first_name $medicalReimbursement->last_name</div>";
                                    } else {
                                        echo '<p>Approved By</p>';
                                        echo '<ul id="medical-details-approve-by"><li><span>1st Approver</span>: ' . $approvedByManager . '</li><li><span>2nd Approver</span>: ' . $approvedByHumanResources . '</li><li><span>3rd Approver</span>: ' . $approvedByFinance . '</li></ul>';
                                    }
                                @endphp
                            </div>
                            <div id="leave-details-information">
                                <div class="row">
                                    <div class="col-6 employee-information">
                                        <div>Employee Information</div>
                                        <div>Employee Name : {{ $medicalReimbursement->first_name . ' ' . $medicalReimbursement->last_name }}</div>
                                        <div>Employee ID : {{ $medicalReimbursement->nip }}</div>
                                        <div>Entity : {{ $medicalReimbursement->branch_name }}</div>
                                        <div>SBU : {{ $medicalReimbursement->department_name }}</div>
                                        <div>Job Position : {{ $medicalReimbursement->job_position }}</div>
                                    </div>
                                    <div class="col-6 leave-application">
                                        <div>Reimbursement</div>
                                        @if ($medicalReimbursement->category == 0)
                                            <div>Category : Inpatient</div>
                                        @else
                                            @if ($medicalReimbursement->outpatient_type == 0)
                                                <div>Category : Outpatient - Laboratory Examinations</div>
                                            @elseif ($medicalReimbursement->outpatient_type == 1)
                                                <div>Category : Outpatient - Doctor Consultation</div>
                                            @else
                                                <div>Category : Outpatient - Glasses Replacement</div>
                                            @endif
                                        @endif
                                        <div>Transaction Date : {{ \Carbon\Carbon::parse($medicalReimbursement->reimbursement_date)->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card leave-details-statistics">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4 text-center">
                                            <div class="text-left d-inline-block">
                                                <div>Balance</div>
                                                <span class="text-primary">Rp{{ number_format($balance, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="text-left d-inline-block">
                                                <div>Expenses</div>
                                                <span class="text-primary">Rp{{ number_format($expenses, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="text-left d-inline-block">
                                                <div>Request Amount</div>
                                                <span class="text-primary">Rp{{ number_format($medicalReimbursement->amount, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="leave-details-notes">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Notes</p>
                                        <textarea name="notes" id="notes" cols="30" rows="5" class="form-control bg-white" placeholder="{{ $medicalReimbursement->notes }}" disabled></textarea>
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

            let images = {!! json_encode($medicalReimbursement->attachment, JSON_HEX_TAG) !!};
            let isMultiple = images.includes('|');

            if (isMultiple) {
                let files = images.split('|');
                let array = [];

                files.forEach(element => {
                    array.push(findBaseName(element));
                });

                for (let i = 0; i < array.length; i++) {
                    $('#leave-details-files .detail-files-container').append('<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="Insert Drive File"></span></div><input type="text" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled="disabled"><div class="input-group-append"><a href="{{ url('medical-reimbursement/download') }}/' + array[i] + '" class="input-group-text bg-white"><img src="{{ url('./img/icons/file-download.svg') }}" alt="Insert Drive File"></a></div></div>');
                }

                $('#leave-details-files .detail-files').each(function(j) {
                    $(this).attr('placeholder', array[j]);
                });
            } else {
                let file = findBaseName(images);

                if (file != '') {
                    $('#leave-details-files .detail-files-container').append('<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="Insert Drive File"></span></div><input type="text" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled="disabled"><div class="input-group-append"><a href="{{ url('medical-reimbursement/download') }}/' + file + '" class="input-group-text bg-white"><img src="{{ url('./img/icons/file-download.svg') }}" alt="Insert Drive File"></a></div></div>');
                    
                    $('#leave-details-files .detail-files').attr('placeholder', file);
                } else {
                    $('#leave-details-files .detail-files-container').append('<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="Insert Drive File"></span></div><input type="text" placeholder="No file available" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled><div class="input-group-append"><button class="input-group-text bg-white" disabled><img src="{{ url('./img/icons/file-download.svg') }}" alt="Insert Drive File"></button></div></div>');
                }
            }
        });
    </script>
@endsection