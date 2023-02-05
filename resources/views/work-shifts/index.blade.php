@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="card">
                    <div class="card-body">
                        <section id="ajax-datatable">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <h3>Work Shifts</h3>
                                        <div class="card-datatable table-responsive table-rounded">
                                            <table class="table table-borderless" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Holding</th>
                                                        <th>Shift Name</th>
                                                        <th>Start Time</th>
                                                        <th>End Time</th>
                                                        <th>Maximum Arrival Time</th>
                                                        <th class="text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Add a Shift</h4>
                    <button type="button" class="close mr-0 mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_data" class="form-data-validate" novalidate>
                    @csrf
                    <input type="hidden" name="id" id="id" value="">
                    <div class="modal-body">
                        <label>Holding:</label>
                        <div class="form-group">
                            <select class="form-control" name="company_id" id="company_id" required>
                                <option selected disabled value="">Select Holding</option>
                                @foreach ($holdings as $item)
                                    <option value="{{ $item->company_id }}">
                                        {{ $item->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback company_id_error"></div>
                        </div>
                        <label>Shift Name:</label>
                        <div class="form-group">
                            <input type="text" placeholder="Shift Name" name="shift_name" id="shift_name" required
                                class="form-control" />
                            <div class="invalid-feedback shift_name_error"></div>
                        </div>
                        <label>Start Time:</label>
                        <div class="form-group">
                            <input type="text" placeholder="Start Time" name="start_time" id="start_time" required
                                class="form-control bg-white" />
                            <div class="invalid-feedback start_time_error"></div>
                        </div>
                        <label>End Time:</label>
                        <div class="form-group">
                            <input type="text" placeholder="End Time" name="end_time" id="end_time" required
                                class="form-control bg-white" />
                            <div class="invalid-feedback end_time_error"></div>
                        </div>
                        <label>Maximum Arrival Time:</label>
                        <div class="form-group">
                            <input type="text" placeholder="Maximum Arrival Time" name="max_arrival" id="max_arrival"
                                required class="form-control bg-white" />
                            <div class="invalid-feedback max_arrival_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="submit" class="btn btn-primary">Submit</button>
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
    @if (session()->has('flash_message'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    title: "{{ session('flash_message') }}",
                    text: "Suceess message sent!!",
                    icon: "success",
                    button: "Ok",
                    timer: 2000
                });
            });
        </script>
    @endif
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

        $(document).ready(function() {
            const dt_ajax_table = $("#datatables-ajax");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#create.btn btn-md btn-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('work-shifts') }}",
                    language: {
                        paginate: {
                            // remove previous & next text from pagination
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'company_name',
                            name: 'holding'
                        },
                        {
                            data: 'shift_name',
                            name: 'shift_name'
                        },
                        {
                            data: (data) => {
                                let startTime = data.start_time.split(':');

                                return `${startTime[0]}:${startTime[1]}`;
                            },
                            name: 'start_time'
                        },
                        {
                            data: (data) => {
                                let endTime = data.end_time.split(':');

                                return `${endTime[0]}:${endTime[1]}`;
                            },
                            name: 'end_time'
                        },
                        {
                            data: (data) => {
                                let maxArrival = data.max_arrival.split(':');

                                return `${maxArrival[0]}:${maxArrival[1]}`;
                            },
                            name: 'max_arrival'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    columnDefs: [{
                        targets: 5,
                        className: 'text-center'
                    }]
                });
            }

            $("#create").html('Add a Shift');
            $("#create").attr('style', 'margin-bottom: 7px');
            $("#create").attr('data-toggle', 'modal');
            $("#create").attr('data-target', '#modal-form');
        });

        $(document).on('click', '#create', function(event) {
            $('#modal-title').text('Add a Shift');
            const idForm = $('form#form_edit_data').attr('id', 'form_data');
            const company = document.querySelector('#company_id');
            const name = document.querySelector('#shift_name');
            const start = document.querySelector('#start_time');
            const end = document.querySelector('#end_time');
            const max = document.querySelector('#max_arrival');
            const btnSubmit = $('#btnEdit').attr('id', 'submit');

            company.value = '';
            name.value = '';
            start.value = '';
            end.value = '';
            max.value = '';

            $(document).ready(() => {
                $('#company_id').select2();
            });

            config = {
                noCalendar: true,
                enableTime: true,
                time_24hr: true,
                minuteIncrement: 1
            }

            flatpickr("#start_time", config);
            flatpickr("#end_time", config);
            flatpickr("#max_arrival", config);

            submit();
        });

        $(document).on('click', '#edit', async function(event) {
            $('#modal-form').modal('show');
            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            const workShiftId = document.querySelector('#id');
            const company = document.querySelector('#company_id');
            const name = document.querySelector('#shift_name');
            const start = document.querySelector('#start_time');
            const end = document.querySelector('#end_time');
            const max = document.querySelector('#max_arrival');
            $('#modal-title').text('Edit a Shift');
            const idForm = $('form#form_data').attr('id', 'form_edit_data');

            await fetch(`/work-shifts/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    workShiftId.value = id;
                    company.value = response.company_id;
                    $(document).ready(() => {
                        $('#company_id').select2();
                    });
                    name.value = response.shift_name;
                    start.value = response.start_time;
                    end.value = response.end_time;
                    max.value = response.max_arrival;

                    flatpickr("#start_time", {
                        noCalendar: true,
                        enableTime: true,
                        time_24hr: true,
                        defaultDate: response.start_time,
                        minuteIncrement: 1
                    });

                    flatpickr("#end_time", {
                        noCalendar: true,
                        enableTime: true,
                        time_24hr: true,
                        defaultDate: response.end_time,
                        minuteIncrement: 1
                    });

                    flatpickr("#max_arrival", {
                        noCalendar: true,
                        enableTime: true,
                        time_24hr: true,
                        defaultDate: response.max_arrival,
                        minuteIncrement: 1
                    });
                });

            submitEdit();
        });

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
                            company_id: request.get('company_id'),
                            shift_name: request.get('shift_name'),
                            start_time: request.get('start_time'),
                            end_time: request.get('end_time'),
                            max_arrival: request.get('max_arrival')
                        };

                        const id = $('#id').val();

                        fetch(`/work-shifts/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    $.each(data.error, (prefix, val) => {
                                        $('div.' + prefix + '_error').text(val[0]);
                                    });

                                    const error = data.error.work_shift_name ? true : false

                                    if (error) {
                                        $('#work_shift_name').removeClass('was-validated');
                                        $('#work_shift_name').addClass('is-invalid');
                                        $('#work_shift_name').addClass('invalid-more');
                                    } else {
                                        $('#work_shift_name').removeClass('is-invalid');
                                        $('#work_shift_name').removeClass('invalid-more');
                                    }
                                } else {
                                    setTimeout(() => {
                                        $('#datatables-ajax').DataTable().ajax.reload();
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-form').modal('hide');
                                }
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
                            company_id: request.get('company_id'),
                            shift_name: request.get('shift_name'),
                            start_time: request.get('start_time'),
                            end_time: request.get('end_time'),
                            max_arrival: request.get('max_arrival')
                        };

                        fetch('/work-shifts', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    $.each(data.error, (prefix, val) => {
                                        $('div.' + prefix + '_error').text(val[0]);
                                    });

                                    const error = data.error.work_shift_name ? true : false

                                    if (error) {
                                        $('#work_shift_name').removeClass('was-validated');
                                        $('#work_shift_name').addClass('is-invalid');
                                        $('#work_shift_name').addClass('invalid-more');
                                    } else {
                                        $('#work_shift_name').removeClass('is-invalid');
                                        $('#work_shift_name').removeClass('invalid-more');
                                    }
                                } else {
                                    setTimeout(() => {
                                        $('#datatables-ajax').DataTable().ajax.reload();
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-form').modal('hide');
                                }
                            });
                    } else {
                        submitEdit();
                    }
                });
            });
        }

        function sweetConfirm(id) {
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
                    const request = new FormData(document.getElementById('form_delete_data'));
                    const data = {
                        _token: request.get('_token'),
                    };

                    fetch(`/work-shifts/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        })
                        .then(response => response.json())
                        .then(data => {
                            setTimeout(function() {
                                $('#datatables-ajax').DataTable().ajax.reload();
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
        }
    </script>
@endsection
