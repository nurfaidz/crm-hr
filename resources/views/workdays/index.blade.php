@extends('partials.template')
@section('meta_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="app-assets/css/pages/app-calendar.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/calendars/fullcalendar.min.css">
@endsection

@section('main')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="card">
                    <div class="card-body">
                        <div class="card shadow-none border-0 mb-0 rounded-0">
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <h3 class="font-weight-bold">Working Trackers</h3>
                                        {{-- <h3 class="font-weight-bold">Workdays</h3> --}}
                                    </div>
                                    <div
                                        class="content-header-right text-md-right col-lg-9 col-md-3 col-12 d-md-block d-none">
                                        <button type="button" id="create" class="btn btn-primary" data-toggle="modal"
                                            data-target="#modal-form">Add Workday </button>
                                    </div>
                                </div>
                                <section>
                                    <form action="" method="post">
                                        @method('get')
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label>Entity</label>
                                                    <select class="form-control" name="branch" id="branch"
                                                        onchange="selectFunction()">
                                                        <option selected disabled value="">Select Entity</option>
                                                        @foreach ($branches as $item)
                                                            <option value="{{ $item->branch_id }}"
                                                                {{ request('branch') == $item->branch_id ? 'selected' : '' }}>
                                                                {{ $item->branch_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                    </form>
                                </section>
                                <!-- Full calendar start -->
                                <section>
                                    <div class="app-calendar overflow-hidden border">
                                        <div class="row no-gutters">
                                            <!-- Calendar -->
                                            <div class="col position-relative">
                                                <div class="card shadow-none border-0 mb-0 rounded-0">
                                                    <div class="card-body pb-0">
                                                        <div id="calendar"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Calendar -->
                                            <div class="body-content-overlay"></div>
                                        </div>
                                    </div>
                                    <!-- Calendar Add/Update/Delete event modal-->
                                    <div class="modal modal-slide-in event-sidebar fade" id="modal-form">
                                        <div class="modal-dialog sidebar-lg">
                                            <div class="modal-content p-0">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">×</button>
                                                <div class="modal-header mb-1">
                                                    <h5 class="modal-title">Add a Workdays</h5>
                                                </div>
                                                <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                                    <form id="form_data" class="form-data-validate" novalidate>
                                                        @csrf
                                                        <input type="hidden" name="id" id="id" value="">
                                                        <div class="form-group">
                                                            <label for="title" class="form-label">Entity</label>
                                                            <select class="form-control" name="branch_id" id="branch_id"
                                                                onchange="changeOptions()">
                                                                <option selected disabled value="">Select Entity
                                                                </option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->branch_id }}"
                                                                        {{ request('branch') == $branch->branch_id ? 'selected' : '' }}>
                                                                        {{ $branch->branch_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback branch_id_error">Please select
                                                                branch name.</div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="title" class="form-label">Work Shift</label>
                                                            <select class="form-control" name="work_shift_id"
                                                                id="work_shift_id" disabled>
                                                                <option selected disabled value="">Select Work Shift
                                                                </option>
                                                            </select>
                                                            <div class="invalid-feedback work_shift_id_error">Please
                                                                select
                                                                work shift name.</div>
                                                        </div>
                                                        <div class="form-group position-relative">
                                                            <label for="title" class="form-label">Day</label>
                                                            <select class="form-control" name="days_id" id="days_id">
                                                                <option selected disabled value="">Select Day
                                                                </option>
                                                                @foreach ($days as $day)
                                                                    <option value="{{ $day->days_id }}"
                                                                        {{ request('days') == $day->days_id ? 'selected' : '' }}>
                                                                        {{ $day->day_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback days_id_error">Please select
                                                                day name.</div>
                                                        </div>
                                                        <div class="form-group d-flex">
                                                            <button type="submit" id="submit"
                                                                class="btn btn-primary add-event-btn mr-1">Add</button>
                                                            <button type="submit" id="btnEdit"
                                                                class="btn btn-primary update-event-btn d-none mr-1">Update</button>
                                                            <button
                                                                class="btn btn-outline-danger btn-delete-event d-none mr-1"
                                                                id="btnDelete">Delete</button>
                                                            <button type="button"
                                                                class="btn btn-outline-secondary btn-cancel"
                                                                data-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/ Calendar Add/Update/Delete event modal-->
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/calendar/fullcalendar.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/moment.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ url('app-assets/js/scripts/pages/app-calendar-events.js') }}"></script>
    <script src="{{ url('app-assets/js/scripts/pages/app-calendar.js') }}"></script>
    <!-- END: Page JS-->
@endsection

@section('page_script')
    <script>
        $("#modal-form").on("hidden.bs.modal", function(e) {
            const reset_form = $('#form_data')[0];
            const reset_form_edit = $('#form_edit_data')[0];

            $(reset_form).removeClass('was-validated');
            $(reset_form_edit).removeClass('was-validated');

            $('#work_shift_name').removeClass('was-validated');
            $('#work_shift_name').removeClass('is-invalid');
            $('#work_shift_name').removeClass('invalid-more');
        });

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: 'UTC',
                initialView: 'timeGridWeek',
            });

            calendar.render();
        });

        $(document).ready(() => {
            $('#branch').select2();
            $('#branch_id').select2();
            $('#work_shift_id').select2();
            $('#days_id').select2();
        });

        const selectFunction = async () => {
            const entity = $('#branch').val();
            await changeCalendar(entity);
        };

        const changeCalendar = (entity) => {
            const calendarEl = document.getElementById('calendar');

            $.ajax({
                url: `/workdays/events?branch=${entity}`,
                method: "GET",
                dataType: "json",
                success: function(result) {
                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        timeZone: 'UTC',
                        initialView: 'timeGridWeek',
                        events: result.events,
                        eventColor: '#ffff00',
                        eventTextColor: '#000000',
                        eventClick: function(info, element) {
                            event.title = "CLICKED!";
                            // console.log(info.event.id);
                            edit(info.event.id);
                        }
                    });

                    calendar.render();
                }
            });
        };

        $(document).on('click', '#create', function(event) {
            let reset_form = $('#form_data')[0];

            $("#form_data").trigger("reset");
            $(reset_form).removeClass('was-validated');

            $('.modal-title').text('Add a Workday');

            const idForm = $('form#form_edit_data').attr('id', 'form_data');
            const branch = document.querySelector('#branch_id');
            const workShift = document.querySelector('#work_shift_id');
            const day = document.querySelector('#days_id');
            const btnAdd = document.querySelector('#submit');
            const btnEdit = document.querySelector('#btnEdit');
            const btnDelete = document.querySelector('#btnDelete');
            btnAdd.classList.remove('d-none');
            btnEdit.classList.add('d-none');
            btnDelete.classList.add('d-none');
            branch.value = '';
            workShift.value = '';
            day.value = '';

            $(document).ready(() => {
                $('#branch').select2();
                $('#branch_id').select2();
                $('#work_shift_id').select2();
                $('#days_id').select2();
            });

            if (!workShift.hasAttribute('disabled')) {
                workShift.setAttribute('disabled', 'disabled');
            }

            submit();
        });

        const edit = async (id) => {
            $('select').removeClass('is-invalid');
            $('#modal-form').modal('show');
            $('.modal-title').text('Edit a Workday');
            const idForm = $('form#form_data').attr('id', 'form_edit_data');
            const workDayId = document.querySelector('#id');
            const branch = document.querySelector('#branch_id');
            const workShift = document.querySelector('#work_shift_id');
            const day = document.querySelector('#days_id');
            const btnAdd = document.querySelector('#submit');
            const btnEdit = document.querySelector('#btnEdit');
            const btnDelete = document.querySelector('#btnDelete');
            btnAdd.classList.add('d-none');
            btnEdit.classList.remove('d-none');
            btnDelete.classList.remove('d-none');

            fetch(`/workdays/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    workDayId.value = id;
                    branch.value = response.branch_id;
                    workShift.value = response.work_shift_id;
                    day.value = response.days_id;
                    changeOptions(response.work_shift_id);

                    $(document).ready(() => {
                        $('#branch').select2();
                        $('#branch_id').select2();
                        $('#work_shift_id').select2();
                        $('#days_id').select2();
                    });
                });

            submitEdit();
        };

        function submitEdit() {
            Array.prototype.filter.call($('#form_edit_data'), function(form) {
                $('#btnEdit').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formEditData = document.querySelector('#form_edit_data');
                    if (formEditData) {
                        const request = new FormData(formEditData);

                        const data = {
                            _token: request.get('_token'),
                            branch_id: request.get('branch_id'),
                            work_shift_id: request.get('work_shift_id'),
                            days_id: request.get('days_id')
                        };

                        const id = $('#id').val();

                        fetch(`/workdays/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if(data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $('div.' + prefix + '_error').text(val[0]);
                                    });

                                    const branch = data.error.branch_id ? true : false;
                                    const workShift = data.error.work_shift_id ? true : false;
                                    const days = data.error.days_id ? true : false;

                                    if (branch) {
                                        $('#branch_id').removeClass('was-validated');
                                        $('#branch_id').addClass('is-invalid');
                                        $('#branch_id').addClass('invalid-more');
                                    } else {
                                        $('#branch_id').removeClass('is-invalid');
                                        $('#branch_id').removeClass('invalid-more');
                                    }

                                    if (workShift) {
                                        $('#work_shift_id').removeClass('was-validated');
                                        $('#work_shift_id').addClass('is-invalid');
                                        $('#work_shift_id').addClass('invalid-more');
                                    } else {
                                        $('#work_shift_id').removeClass('is-invalid');
                                        $('#work_shift_id').removeClass('invalid-more');
                                    }

                                    if (days) {
                                        $('#days_id').removeClass('was-validated');
                                        $('#days_id').addClass('is-invalid');
                                        $('#days_id').addClass('invalid-more');
                                    } else {
                                        $('#days_id').removeClass('is-invalid');
                                        $('#days_id').removeClass('invalid-more');
                                    }
                                } else {
                                    const branch = document.querySelector('#branch').value;

                                    setTimeout(() => {
                                        changeCalendar(branch);
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-form').modal('hide');
                                }
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    } else {
                        submit();
                    }
                });
            });
        }

        function submit() {
            Array.prototype.filter.call($('#form_data'), function(form) {
                $('#submit').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();
                    const formData = document.querySelector('#form_data');
                    if (formData) {
                        const request = new FormData(formData);

                        const data = {
                            _token: request.get('_token'),
                            branch_id: request.get('branch_id'),
                            work_shift_id: request.get('work_shift_id'),
                            days_id: request.get('days_id')
                        };

                        fetch('/workdays', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if(data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $('div.' + prefix + '_error').text(val[0]);
                                    });

                                    const branch = data.error.branch_id ? true : false;
                                    const workShift = data.error.work_shift_id ? true : false;
                                    const days = data.error.days_id ? true : false;

                                    if (branch) {
                                        $('#branch_id').removeClass('was-validated');
                                        $('#branch_id').addClass('is-invalid');
                                        $('#branch_id').addClass('invalid-more');
                                    } else {
                                        $('#branch_id').removeClass('is-invalid');
                                        $('#branch_id').removeClass('invalid-more');
                                    }

                                    if (workShift) {
                                        $('#work_shift_id').removeClass('was-validated');
                                        $('#work_shift_id').addClass('is-invalid');
                                        $('#work_shift_id').addClass('invalid-more');
                                    } else {
                                        $('#work_shift_id').removeClass('is-invalid');
                                        $('#work_shift_id').removeClass('invalid-more');
                                    }

                                    if (days) {
                                        $('#days_id').removeClass('was-validated');
                                        $('#days_id').addClass('is-invalid');
                                        $('#days_id').addClass('invalid-more');
                                    } else {
                                        $('#days_id').removeClass('is-invalid');
                                        $('#days_id').removeClass('invalid-more');
                                    }
                                } else {
                                    const branch = document.querySelector('#branch').value;
                                    console.log(!branch);

                                    setTimeout(() => {
                                        if (!branch) {
                                            // $("#calendar").load(location.href + " #calendar");
                                            const calendar = new FullCalendar.Calendar(
                                                calendarEl, {
                                                    timeZone: 'UTC',
                                                    initialView: 'timeGridWeek',
                                                });

                                            calendar.render();
                                        } else {
                                            changeCalendar(branch);
                                        }
                                    }, 0);


                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-form').modal('hide');
                                }
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    } else {
                        submitEdit();
                    }
                });
            });
        }

        $(document).on('click', '#btnDelete', function(event) {
            event.preventDefault(); // prevent form submit
            const form = event.target.form; // storing the form
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn btn-primary",
                confirmButtonText: "Yes, delete it!",
                cancelButtonClass: "btn btn-danger ml-1",
                cancelButtonText: "Cancel",
                buttonsStyling: false
            }).then((result) => {
                if (result.value) {
                    const request = new FormData(document.getElementById('form_edit_data'));
                    const id = document.querySelector('#id').value;
                    const data = {
                        _token: request.get('_token'),
                    };

                    fetch(`/workdays/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        })
                        .then(response => response.json())
                        .then(data => {
                            const branch = document.querySelector('#branch').value;

                            setTimeout(() => {
                                changeCalendar(branch);
                            }, 0);

                            Swal.fire({
                                type: 'success',
                                title: 'Success!',
                                text: data.message,
                                confirmButtonClass: 'btn btn-success',
                            });

                            $('#modal-form').modal('hide');
                        })
                        .catch((error) => {
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: error.message,
                                confirmButtonClass: 'btn btn-success',
                            });
                        });
                }
            });
        });

        const changeOptions = (id) => {
            const entity = document.querySelector('#branch_id').value;
            const workShift = document.querySelector('#work_shift_id');

            // $("#work_shift_id").empty();

            fetch(`/workdays/shifts?branch=${entity}`)
                .then(response => response.json())
                .then(response => {
                    workShift.removeAttribute('disabled');

                    if (workShift.length > 0) {
                        for (let i = 0; i < workShift.length; i++) {
                            response.shifts.forEach((item, index) => {
                                if (workShift.options[i].value == item.work_shift_id) {
                                    workShift.remove(i);
                                }
                            });
                        }
                    }

                    response.shifts.forEach((item, index) => {
                        const opt = document.createElement('option');
                        opt.value = item.work_shift_id;
                        opt.innerHTML = item.shift_name;
                        workShift.appendChild(opt);
                    });

                    workShift.value = id;

                    $(document).ready(() => {
                        $('#branch').select2();
                        $('#branch_id').select2();
                        $('#work_shift_id').select2();
                        $('#days_id').select2();
                    });
                });
        }
    </script>
@endsection
