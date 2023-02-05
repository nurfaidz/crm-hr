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
                    <a href="{{ $data->id }}" class="manual-attendance-link">/ Approval Queue / Manual
                        Attendance / Manual Attendance Details</a>
                </p>
                <section class="horizontal-wizard">
                    <div class="bs-stepper horizontal-wizard-example">
                        <div class="bs-stepper-content">
                            <h3>Manual Attendance Details</h3>
                        </div>
                        <div class="bs-stepper-header">
                            <div class="step" data-target="#account-details">
                                <button type="button" class="step-trigger first-st done-step-trigger">
                                    <span class="bs-stepper-box first-bsb done-bs-stepper-box">
                                        <span class="bs-stepper-number first-bsn done-bs-stepper-number">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title first-bst done-bs-stepper-title">New
                                            Manual Attendance</span>
                                    </span>
                                </button>
                            </div>
                            <span class="first-middle-line active-middle-line"></span>
                            <div class="step" data-target="#personal-info">
                                <button type="button" class="step-trigger second-st active-step-trigger">
                                    <span class="bs-stepper-box second-bsb active-bs-stepper-box">
                                        <span class="bs-stepper-number second-bsn active-bs-stepper-number">2</span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title second-bst active-bs-stepper-title">Higher-Up
                                            Approval</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <div id="manual-approved-by">
                                @php
                                    if ($data->short === 'Check In') {
                                        echo '<p><b>Approved By</b></p>';
                                        echo "<span>$approver->first_name $approver->last_name</span>";
                                    } elseif ($data->short === 'Reject') {
                                        echo '<p><b>Approved By</b></p>';
                                        echo "<span>$rejecter->first_name $rejecter->last_name</span>";
                                    } else {
                                        echo '<p><b>Approved By</b></p>';
                                        echo '<span>-</span>';
                                    }
                                @endphp
                            </div>
                            <div id="manual-details-information">
                                <div class="row">
                                    <div class="col-6 employee-information my-2">
                                        <div><b>Employee Information</b></div>
                                        <div>Employee Name: {{ $data->first_name . ' ' . $data->last_name }}</div>
                                        <div>Employee ID: {{ $data->nip }}</div>
                                        <div>Entity: {{ $data->branch_name }}</div>
                                        <div>SBU: {{ $data->department_name }}</div>
                                        <div>Job Position: {{ $data->job_position }}</div>
                                    </div>
                                    <div class="col-6 manual-attendance">
                                    </div>
                                </div>
                            </div>

                            <div id="manual-details-shift">
                                <div class="row">
                                    <div class="col-6 employee-information my-2">
                                        <div><b>SHIFT</b></div>
                                        <div>{{ $shift->shift_name }}</div>

                                    </div>
                                    <div class="col-6 manual-attendance">
                                    </div>
                                </div>
                            </div>

                            <div class="card manual-details-statistics">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6 text-center border-right">
                                            <p>Request Check In Time</p>
                                            <span
                                                class="text-primary">{{ \Carbon\Carbon::parse($data->check_in)->format('h:i') }}
                                                WIB</span>
                                        </div>
                                        <div class="col-6 text-center">
                                            <p>Request Check Out Time</p>
                                            <span
                                                class="text-primary">{{ \Carbon\Carbon::parse($data->check_out)->format('h:iJ') }}
                                                WIB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="attendance-details-attendance-date">
                                <div class="row">
                                    <div class="col-6 my-2">
                                        <p><b>Attendance Date</b></p>
                                        @php
                                            $year = \Carbon\Carbon::parse($data->date)->format('Y');
                                            $month = \Carbon\Carbon::parse($data->date)->format('F');
                                            $dates = \Carbon\Carbon::parse($data->date)->format('d');
                                            
                                        @endphp
                                        <span>{{ $dates }} {{ $month }} {{ $year }}</span>
                                    </div>
                                </div>
                            </div>
                            <div id="manual-details-notes">
                                <div class="row">
                                    <div class="col-6">
                                        <p><b>Notes</b></p>
                                        <textarea name="purpose" id="purpose" cols="30" rows="5" class="form-control bg-white" style="resize: none"
                                            placeholder="{{ $data->note }}" disabled></textarea>
                                    </div>
                                </div>
                            </div>
                            <div id="manual-details-files">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Files</p>
                                        <div class="detail-files-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-right pr-1 pb-1 manual-details-buttons">
                            @can('manual_attendance.reject')
                                <button type="button" class="btn btn-danger mr-1 manual-details-reject" data-toggle="modal"
                                    data-target="#modal-reversible">Reject</button>
                            @else
                                <button type="button" class="btn btn-danger mr-1" disabled>Reject</button>
                            @endcan
                            @can('manual_attendance.approve')
                                <button type="button" data-id="{{ $data->id }}}"
                                    class="btn btn-success manual-details-approve">Approve</button>
                            @else
                                <button type="button" class="btn btn-success" disabled>Approve</button>
                            @endcan
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-reversible" tabindex="-1" role="dialog" aria-labelledby="modalReversibleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-reversible-content">
                <div class="scene scene--reversible">
                    <div class="card reversible">
                        <div class="card__face card__face--front">
                            <p>Rejection Reason</p>
                            <span>Enter the rejection reason for the applicant.</span>
                            <form id="form-data" class="form-data-validate" novalidate>
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <textarea name="reason" id="reject-reason" cols="30" rows="5" class="form-control bg-white"
                                        onkeyup="checkValidation(this.value)"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="modal-reversible-buttons text-right">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill"
                                        data-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button type="button" id="next"
                                        class="btn btn-primary rounded-pill">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="card__face card__face--back text-center">
                            <img src="../../../img/icons/alert-circle.svg" alt="Alert Circle">
                            <p>Are you sure?</p>
                            <span>You won't be able to revert this!</span>
                            <div class="modal-reversible-buttons">
                                <button type="button" id="cancel" class="btn btn-outline-primary"
                                    data-dismiss="modal" aria-label="Close">Cancel</button>
                                <button type="button" data-id="{{ $data->id }}}" id="submit"
                                    class="btn btn-primary">Yes, reject it!</button>
                            </div>
                        </div>
                    </div>
                </div>
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

            let images = {!! json_encode($data->image_manual_attendance, JSON_HEX_TAG) !!};
            let isMultiple = images.includes('|');

            if (isMultiple) {
                let files = images.split('|');
                let array = [];

                files.forEach(element => {
                    array.push(findBaseName(element));
                });

                for (let i = 0; i < array.length; i++) {
                    $('#manual-details-files .detail-files-container').append(
                        '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="Insert Drive File"></span></div><input type="text" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled="disabled"><div class="input-group-append"><a href="{{ url('approval/manual-attendance/download') }}/' +
                        array[i] +
                        '" class="input-group-text bg-white"><img src="{{ url('./img/icons/file-download.svg') }}" alt="Insert Drive File"></a></div></div>'
                    );
                }

                $('#manual-details-files .detail-files').each(function(j) {
                    $(this).attr('placeholder', array[j]);
                });
            } else {
                let file = findBaseName(images);

                if (file != '') {
                    $('#manual-details-files .detail-files-container').append(
                        '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="Insert Drive File"></span></div><input type="text" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled="disabled"><div class="input-group-append"><a href="{{ url('approval/manual-attendance/download') }}/' +
                        file +
                        '" class="input-group-text bg-white"><img src="{{ url('./img/icons/file-download.svg') }}" alt="Insert Drive File"></a></div></div>'
                    );

                    $('#manual-details-files .detail-files').attr('placeholder', file);
                } else {
                    $('#manual-details-files .detail-files-container').append(
                        '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="Insert Drive File"></span></div><input type="text" placeholder="No file available" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled><div class="input-group-append"><button class="input-group-text bg-white" disabled><img src="{{ url('./img/icons/file-download.svg') }}" alt="Insert Drive File"></button></div></div>'
                    );
                }
            }
        });

        $('#modal-reversible').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();

            $('#reject-reason').removeClass('is-invalid');
            $('#reject-reason').removeClass('invalid-more');

            $('.reversible').removeClass('is-flipped');
        });

        function checkValidation(e) {
            if (e) {
                $('#reject-reason').removeClass('is-invalid');
                $('#reject-reason').removeClass('invalid-more');

                $('.card__face--front .modal-reversible-buttons').attr('style', 'margin-top: 20px');
            }
        }

        $(document).on('click', '#next', function(e) {
            e.preventDefault();

            if (!(!$.trim($("#reject-reason").val()))) {
                $('.reversible').toggleClass('is-flipped');
            } else {
                $('#reject-reason').removeClass('was-validated');
                $('#reject-reason').addClass('is-invalid');
                $('#reject-reason').addClass('invalid-more');

                $('.invalid-feedback').html('The rejection reason field is required.');

                $('.card__face--front .modal-reversible-buttons').attr('style', 'margin-top: -6px');
            }
        });

        $(document).on('click', '#submit', function(e) {
            e.preventDefault();

            let id = $(this).data('id');
            let reason = $('#reject-reason').val();

            console.log(reason);

            $.ajax({
                url: `{{ url('approval/manual-attendance/reject') }}/${id}`,
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    'reason': reason
                },
                success: (result) => {
                    if (result.error) {
                        alertify.error(result.message);
                    } else {
                        $('#cancel').click();

                        setTimeout(() => {
                            $('#manual-approved-by').load(location.href +
                                ' #manual-approved-by');
                        }, 0);

                        alertify.success(result.message);
                    }
                }
            });
        });

        $(document).on('click', '.manual-details-approve', function(e) {
            e.preventDefault();

            let id = $(this).data('id');

            Swal.fire({
                type: 'warning',
                title: 'Are you sure?',
                confirmButtonText: 'Yes, Confirm',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonText: 'No, Cancel',
                cancelButtonClass: 'btn btn-danger',
                html: '<p class="manual-details-text">You won\'t be able to revert this!</p><img src="../../../img/icons/warning.svg" alt="Warning" class="manual-details-close">',
                focusConfirm: false,
                showCancelButton: true,
                showCloseButton: true,
                reverseButtons: true,
                customClass: {
                    title: 'manual-details-title',
                    cancelButton: 'manual-details-reject-button',
                    closeButton: 'manual-details-close-button',
                    confirmButton: 'manual-details-approve-button'
                }
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: `{{ url('approval/manual-attendance/approve') }}/${id}`,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: (result) => {
                            if (result.error) {
                                alertify.error(result.message);
                            } else {
                                setTimeout(() => {
                                    $('#manual-approved-by').load(location.href +
                                        ' #manual-approved-by');
                                }, 0);

                                alertify.success(result.message);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
