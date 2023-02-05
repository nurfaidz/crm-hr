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
                            <h3>Overtime Detail</h3>
                        </div>
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
                                        <span class="bs-stepper-title second-bst active-bs-stepper-title">Higher-Up
                                            Approval</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <div class="approve-by mb-3" id="approve-by">
                                @if ($overtime->status === 'oap')
                                    <h5><b>Approved by</b></h5>
                                    <span>{{ $overtime->f_name . ' ' . $overtime->l_name }}</span>
                                @elseif ($overtime->status === 'orj')
                                    <h5><b>Rejected by</b></h5>
                                    <span>{{ $overtime->f_name . ' ' . $overtime->l_name }}</span>
                                @else
                                    <h5><b>Approved by</b></h5>
                                @endif
                            </div>
                            <div class="employee-details mb-4">
                                <h5><b>Employee's Information</b></h5>
                                <div class="employee-information">
                                    <div class="employee-data">
                                        <span>Employee Name :
                                            {{ $overtime->first_name . ' ' . $overtime->last_name }}</span>
                                    </div>
                                    <div class="employee-data">
                                        <span>Employee ID : {{ $overtime->nip }}</span>
                                    </div>
                                    <div class="employee-data">
                                        <span>Entity : {{ $overtime->company_name }}</span>
                                    </div>
                                    <div class="employee-data">
                                        <span>SBU : {{ $overtime->department_name }}</span>
                                    </div>
                                    <div class="employee-data">
                                        <span>Job Position : {{ $overtime->job_position }}</span>
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
                            <div class="notes mt-4 mb-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5><b>Notes</b></h5>
                                        <textarea type="text" class="form-control" id="notes" rows="5" readonly>
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            @can('overtime.reject')
                                <button onclick=RejectAction($(this)) data-id="{{ $overtime->overtime_id }}"
                                    class="btn btn-danger">Reject</button>
                            @else
                                <button class="btn btn-danger" disabled>Reject</button>
                            @endcan

                            @can('overtime.approve')
                                <button onclick=ApproveAction($(this)) data-id="{{ $overtime->overtime_id }}"
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
@endsection

@section('page_script')
    <script>
        $(document).ready(() => {
            const textArea = document.getElementById('notes');
            textArea.innerHTML = '{{ $overtime->notes }}';

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
                    const base_url = "{{ url('approval/overtime') }}";
                    $.ajax({
                        url: `${base_url}/${id}/approve`,
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

                                setTimeout(() => {
                                    window.location.replace("{{ url('approval') }}");
                                }, 3000);

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
                            const base_url = "{{ url('approval/overtime') }}";
                            const id = e.attr('data-id');
                            $.ajax({
                                url: `${base_url}/${id}/reject`,
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

                                        setTimeout(() => {
                                            window.location.replace(
                                                "{{ url('approval') }}"
                                                );
                                        }, 3000);

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
