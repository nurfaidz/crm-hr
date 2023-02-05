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
                    <a href="{{ $medicalReimbursement->medical_reimbursement_id }}" class="leave-application-link">/
                        Medical Tracker Reimbursement / Medical Reimbursement Details</a>
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
                                        <span class="bs-stepper-title first-bst done-bs-stepper-title">New
                                            Application</span>
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
                                        <span class="bs-stepper-title first-bst done-bs-stepper-title">Higher-Up
                                            Approval</span>
                                    </span>
                                </button>
                            </div>
                            <span class="first-middle-line active-middle-line"></span>
                            <div class="step">
                                <button type="button"
                                    class="step-trigger first-st active-step-trigger medical-step-trigger">
                                    <span class="bs-stepper-box first-bsb active-bs-stepper-box">
                                        <span class="bs-stepper-number first-bsn active-bs-stepper-number">
                                            {{-- <i class="fa-solid fa-check"></i> --}}
                                            3
                                        </span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title first-bst active-bs-stepper-title">HR Approval</span>
                                    </span>
                                </button>
                            </div>
                            <span class="first-middle-line"></span>
                            <div class="step" style="margin-right: 0">
                                <button type="button" class="step-trigger first-st medical-step-trigger">
                                    <span class="bs-stepper-box first-bsb">
                                        <span class="bs-stepper-number first-bsn">
                                            4
                                            {{-- <i class="fa-solid fa-check"></i> --}}
                                        </span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title first-bst">Finance Approval</span>
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
                                    } elseif ($medicalReimbursement->short === 'Canceled') {
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
                                        <div>Employee Name :
                                            {{ $medicalReimbursement->first_name . ' ' . $medicalReimbursement->last_name }}
                                        </div>
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
                                                    class="text-primary">Rp{{ number_format($balance, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="text-left d-inline-block">
                                                <p>Expenses</p>
                                                <span
                                                    class="text-primary">Rp{{ number_format($expenses, 0, ',', '.') }}</span>
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
                            <div id="leave-details-notes">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Notes</p>
                                        <textarea name="notes" id="notes" cols="30" rows="5" class="form-control bg-white"
                                            placeholder="{{ $medicalReimbursement->notes }}" disabled></textarea>
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
                        <div class="card-footer">
                            <div class="text-right pr-1 leave-details-buttons">
                                @can('role_or_permission:Super Admin|medical_reimbursement.manage')
                                    <a href="{{ url('approval/medical-reimbursement') }}/{{ $medicalReimbursement->medical_reimbursement_id }}/details-higher-up"
                                        class="btn btn-primary">Previous</a>

                                    <a href="{{ url('approval/medical-reimbursement') }}/{{ $medicalReimbursement->medical_reimbursement_id }}/details-finance"
                                        class="btn btn-primary mr-1">Next</a>
                                @endcan

                                @can('medical_reimbursement_approval.reject_hr')
                                    <button type="button" onclick=RejectAction($(this))
                                        class="btn btn-danger leave-details-reject"
                                        data-id="{{ $medicalReimbursement->medical_reimbursement_id }}"data-toggle="modal"
                                        data-target="#modal-reversible">Reject</button>
                                @else
                                    <button type="button" class="btn btn-danger mr-1" disabled>Reject</button>
                                @endcan
                                @can('medical_reimbursement_approval.approve_hr')
                                    <button type="button" onclick=ApproveAction($(this))
                                        data-id="{{ $medicalReimbursement->medical_reimbursement_id }}"
                                        class="btn btn-success leave-details-approve">Approve</button>
                                @else
                                    <button type="button" class="btn btn-success" disabled>Approve</button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_data" class="form-data-validate" novalidate>
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <label id="label_name"></label>
                        <div class="form-group">
                            <textarea type="text" name="reason" id="reason" style="height: 130px" required class="form-control"></textarea>
                            {{-- <div class="invalid-feedback reason_error">Please enter appropriate value.</div> --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary rounded-pill ml-1" data-dismiss="modal"
                            aria-label="Close">Cancel</button>
                        <button type="submit" id="submit" class="btn btn-primary rounded-pill">Submit</button>
                    </div>
                </form>
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

        const ApproveAction = (e) => {
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
                    const base_url = "{{ url('approval/medical-reimbursement') }}";
                    $.ajax({
                        url: `${base_url}/${id}/approve-human-resource`,
                        type: 'PUT',
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
                                    $('#approve-by').load(window.location.href +
                                        " #approve-by")
                                }, 1000);

                                "@can('medical_reimbursement_approval.approve_hr')"
                                    setTimeout(() => {
                                        window.location.replace("{{ url('approval') }}");
                                    }, 3000);
                                "@endcan"

                                Swal.fire({
                                    type: "success",
                                    icon: 'success',
                                    title: 'Succesfully Approved!',
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

        const RejectAction = (e) => {

            $('#modal-form').modal('show');
            $("#modal_title").html("Rejection Reason")
            $('#label_name').html('Enter The Rejection Reason For Advetiser')

            Array.prototype.filter.call($('#form_data'), (form) => {
                $('#submit').unbind().on('click', (event) => {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }
                    form.classList.add('was-validated');
                    event.preventDefault();
                    swal.fire({
                        title: 'Are you Sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonClass: 'btn btn-outline-danger ml-1',
                        cancelButtonText: 'Cancel',
                        confirmButtonText: 'Yes, Reject it',
                        confirmButtonClass: 'btn btn-primary',
                        showCloseButton: true,
                        buttonsStyling: false,
                    }).then((result) => {
                        if (result.value) {
                            const base_url = "{{ url('approval/medical-reimbursement') }}";
                            const id = e.attr('data-id');
                            $.ajax({
                                url: `${base_url}/${id}/reject-human-resource`,
                                method: 'PUT',
                                data: $('#form_data').serialize(),
                                success: (response) => {
                                    if (response.error) {
                                        Swal.fire({
                                            type: "error",
                                            title: 'Oops...',
                                            text: response.message,
                                            confirmButtonClass: 'btn btn-primary',
                                        })
                                    } else {
                                        setTimeout(() => {
                                            $('#approve-by').load(window
                                                .location.href +
                                                " #approve-by")
                                        }, 1000);

                                        // setTimeout(() => {
                                        //     window.location.replace(
                                        //         "{{ url('approval') }}"
                                        //     );
                                        // }, 3000);

                                        Swal.fire({
                                            type: "success",
                                            title: 'Success!',
                                            text: response.message,
                                            confirmButtonClass: 'btn btn-primary',
                                        });

                                    }


                                    $("#form_data").trigger("reset");
                                    $('#modal-form').modal('hide');
                                }
                            });
                        }

                    })

                });

            });

        }
    </script>
@endsection
