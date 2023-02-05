@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <p class="d-flex align-items-center">
                    <img src="{{ url('./img/icons/dashboard.svg') }}" alt="Dashboard">
                    <a href="/dashboard" class="dashboard-link">Dashboard</a>
                    <a href="/medical-reports" class="leave-application-link">/ Medical Report</a>
                    <a href="/medical-reports/{{ $medicalReimbursement->medical_reimbursement_id }}"
                        class="leave-application-link">/ {{ $medicalReimbursement->medical_reimbursement_id }}</a>
                </p>
                <section class="horizontal-wizard">
                    <div class="bs-stepper horizontal-wizard-example">
                        <div class="bs-stepper-content">
                            <h3 class="m-0">Reimbursement Details</h3>
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
                                            @if ($approve1 == 'active' || $approve1 == 'disable')
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
                            <span class="first-middle-line"></span>
                            <div class="step">
                                <button type="button"
                                    class="step-trigger first-st {{ $approve2 }}-step-trigger medical-step-trigger">
                                    <span class="bs-stepper-box first-bsb {{ $approve2 }}-bs-stepper-box">
                                        <span class="bs-stepper-number first-bsn {{ $approve2 }}-bs-stepper-number">
                                            @if ($approve2 == 'done')
                                                <i class="fa-solid fa-check"></i>
                                            @endif
                                            @if ($approve2 == 'danger')
                                                <i class="fa-solid fa-close"></i>
                                            @endif
                                            @if ($approve2 == 'active' || $approve2 == 'disable')
                                                3
                                            @endif
                                        </span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title first-bst {{ $approve2 }}-bs-stepper-title">HR
                                            Approval</span>
                                    </span>
                                </button>
                            </div>
                            <span class="first-middle-line"></span>
                            <div class="step" style="margin-right: 0">
                                <button type="button"
                                    class="step-trigger first-st {{ $approve3 }}-step-trigger medical-step-trigger">
                                    <span class="bs-stepper-box first-bsb {{ $approve3 }}-bs-stepper-box">
                                        <span class="bs-stepper-number first-bsn {{ $approve3 }}-bs-stepper-number">
                                            @if ($approve3 == 'done')
                                                <i class="fa-solid fa-check"></i>
                                            @endif
                                            @if ($approve3 == 'danger')
                                                <i class="fa-solid fa-close"></i>
                                            @endif
                                            @if ($approve3 == 'active' || $approve3 == 'disable')
                                                4
                                            @endif
                                        </span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span
                                            class="bs-stepper-title first-bst {{ $approve3 }}-bs-stepper-title">Finance
                                            Approval</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <div>
                                <p class="font-weight-bolder">Approved By</p>
                                <p class="mb-50">1st Approver :
                                    {{ $medicalReimbursement->approve_by_manager > 0 ? $approvedByManager->first_name . ' ' . $approvedByManager->last_name : '' }}
                                </p>
                                <p class="mb-50">2nd Approver :
                                    {{ $medicalReimbursement->approve_by_human_resources > 0 ? $approvedByHumanResources->first_name . ' ' . $approvedByHumanResources->last_name : '' }}
                                </p>
                                <p>3rd Approver :
                                    {{ $medicalReimbursement->approve_by_finance > 0 ? $approvedByFinance->first_name . ' ' . $approvedByFinance->last_name : '' }}
                                </p>
                            </div>
                            @if ($medicalReimbursement->reject_by > 0)
                                <div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="font-weight-bolder mb-0">Rejected By</p>
                                            <p class="mt-0">
                                                {{ $rejectedBy->first_name . ' ' . $rejectedBy->last_name }}
                                            </p>
                                            <p class="font-weight-bolder">Reason Rejection</p>
                                        </div>
                                        <div class="col-6">
                                            <textarea name="notes" id="notes" cols="20" rows="5" class="form-control bg-white"
                                                placeholder="{{ $medicalReimbursement->notes }}" disabled></textarea>
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
                                        <div>Transaction Date :
                                            {{ \Carbon\Carbon::parse($medicalReimbursement->reimbursement_date)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card leave-details-statistics">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4 text-center">
                                            <div class="text-left d-inline-block">
                                                <p>Balance</p>
                                                <span
                                                    class="text-primary">Rp{{ number_format($medicalReimbursement->balance, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="text-left d-inline-block">
                                                <p>Expenses</p>
                                                <span
                                                    class="text-primary">Rp{{ number_format($medicalReimbursement->expenses, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="text-left d-inline-block">
                                                <p>Request Amount</p>
                                                <span
                                                    class="text-primary">Rp{{ number_format($medicalReimbursement->amount, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="leave-details">
                                <div class="row">
                                    <div class="col-6" id="leave-details-notes">
                                        <p>Notes</p>
                                        <textarea name="notes" id="notes" cols="30" rows="5" class="form-control bg-white"
                                            placeholder="{{ $medicalReimbursement->notes }}" disabled></textarea>
                                    </div>
                                    <div class="col-6" id="leave-details-notes">
                                        <p>Files</p>
                                        @if ($payments)
                                            @foreach ($payments as $payment)
                                                <div class="form-group mt-1 detail-files-container">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-white" id="file-copy">
                                                                <img src="{{ url('./img/icons/file-copy.svg') }}"
                                                                    alt="File Copy">
                                                            </span>
                                                        </div>
                                                        <input type="text" placeholder="{{ $payment }}"
                                                            name="detail-files" id="detail-files"
                                                            class="form-control bg-white border-left-0 detail-files py-2"
                                                            disabled>
                                                        <div class="input-group-append">
                                                            <a href="{{ asset('uploads/' . $payment) }}"
                                                                class="btn btn-primary" download>
                                                                <svg width="16" height="16" viewBox="0 0 16 16"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M14 11V14H2V11H0V14C0 15.1 0.9 16 2 16H14C15.1 16 16 15.1 16 14V11H14ZM13 7L11.59 5.59L9 8.17V0H7V8.17L4.41 5.59L3 7L8 12L13 7Z"
                                                                        fill="#FFFFFF" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="custom-input-image-container"></div>
                                    <div class="col col-6" id="leave-details-files">
                                        <p>Files</p>
                                        <div class="detail-files-container"></div>
                                    </div>
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
@endsection

@section('page_script')
    <script>
        $(document).on('click', '#evidence-subsitute', function() {
            $('#evidence').click();
        });

        function addFile(evidence) {
            $('.custom-input-image-container').html('');
            let files = document.getElementById('evidence').files;

            for (let i = 0; i < files.length; i++) {
                $('.custom-input-image-container').append(
                    `<div class="form-group"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white"><img src="{{ url('img/icons/file-copy.svg') }}" alt="file-copy"></span></div><input type="text" class="form-control bg-white custom-input-image-name" placeholder="${files[i].name}" disabled="disabled"><div class="input-group-append"><span class="input-group-text bg-white custom-input-image-size">${(files[i].size / Math.pow(1024, 1)).toString().split('.')[0]} Kb</span></div></div></div>`
                );
            }
        }

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
                    $('#leave-details-files .detail-files-container').append(
                        '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="Insert Drive File"></span></div><input type="text" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled="disabled"><div class="input-group-append"><a href="{{ url('medical-reimbursement/download') }}/' +
                        array[i] +
                        '" class="input-group-text bg-white"><img src="{{ url('./img/icons/file-download.svg') }}" alt="Insert Drive File"></a></div></div>'
                    );
                }

                $('#leave-details-files .detail-files').each(function(j) {
                    $(this).attr('placeholder', array[j]);
                });
            } else {
                let file = findBaseName(images);

                if (file != '') {
                    $('#leave-details-files .detail-files-container').append(
                        '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="Insert Drive File"></span></div><input type="text" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled="disabled"><div class="input-group-append"><a href="{{ url('medical-reimbursement/download') }}/' +
                        file +
                        '" class="input-group-text bg-white"><img src="{{ url('./img/icons/file-download.svg') }}" alt="Insert Drive File"></a></div></div>'
                    );

                    $('#leave-details-files .detail-files').attr('placeholder', file);
                } else {
                    $('#leave-details-files .detail-files-container').append(
                        '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="Insert Drive File"></span></div><input type="text" placeholder="No file available" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled><div class="input-group-append"><button class="input-group-text bg-white" disabled><img src="{{ url('./img/icons/file-download.svg') }}" alt="Insert Drive File"></button></div></div>'
                    );
                }
            }
        });
    </script>
@endsection
