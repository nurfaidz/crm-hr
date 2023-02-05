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
                                        <h3>Job Positions</h3>
                                        <div class="card-datatable table-responsive table-rounded">
                                            <table class="table table-borderless" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Job Position</th>
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
                    <h4 class="modal-title" id="modal-title">Add Position</h4>
                    <button type="button" class="close mr-0 mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_data" class="form-data-validate" novalidate>
                    @csrf
                    <input type="hidden" name="id" id="id" value="">
                    <div class="modal-body">
                        <label>Job Position:</label>
                        <div class="form-group">
                            <input type="text" placeholder="Job Position" name="job_position" id="job_position" required
                                class="form-control" />
                            <div class="invalid-feedback job_position_error"></div>
                        </div>
                        <label>SBU:</label>
                        <div class="form-group">
                            <select name="department_id" id="department_id" required class="form-control">
                                <option hidden disabled selected value>Select SBU</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->department_id }}">{{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback department_id_error"></div>
                        </div>
                        <label>Job Level:</label>
                        <div class="form-group">
                            <select name="job_class_id" id="job_class_id" required class="form-control">
                                <option hidden disabled selected value>Select a Job Level</option>
                                @foreach ($jobClasses as $jobClass)
                                    <option value="{{ $jobClass->job_class_id }}">{{ $jobClass->job_class }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback job_class_id_error"></div>
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
    <script>
        $(document).ready(function() {
            const dt_ajax_table = $("#datatables-ajax");
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#create.btn btn-md btn-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('job-positions') }}",
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
                            data: (data) => {
                                return `<b>${data.job_position}</b>
                                    <br />Entity: ${data.branch_name}
                                    <br />SBU: ${data.department_name}
                                    <br />Job Level: ${data.job_class}`;
                            },
                            name: 'job_position'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    columnDefs: [{
                        targets: 2,
                        className: 'text-center'
                    }]
                });
            }

            $("#create").html('Add a Job Position');
            $("#create").attr('style', 'margin-bottom: 7px');
            $("#create").attr('data-toggle', 'modal');
            $("#create").attr('data-target', '#modal-form');

            $('#department_id').select2();
            $('#job_class_id').select2();
        });

        $(document).on('click', '#create', function(event) {
            $('#modal-title').text('Add a Job Position');
            const idForm = $('form#form_edit_data').attr('id', 'form_data');
            const jobPosition = document.querySelector('#job_position');
            const department = document.querySelector('#department_id');
            const jobClass = document.querySelector('#job_class_id');
            const btnEdit = $('#btnEdit').attr('id', 'submit');
            $('#job_position').removeClass('was-validated');
            $('#job_position').removeClass('is-invalid');
            $('#job_position').removeClass('invalid-more');
            $('#department_id').removeClass('was-validated');
            $('#department_id').removeClass("is-invalid");
            $('#job_class_id').removeClass('was-validated');
            $('#job_class_id').removeClass("is-invalid");
            jobPosition.value = '';
            department.value = '';
            jobClass.value = '';

            $(document).ready(() => {
                $('#department_id').select2();
                $('#job_class_id').select2();
            });

            submit();
        });

        $(document).on('click', '#edit', async function(event) {
            $('#modal-form').modal('show');
            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            const jobPositionId = document.querySelector('#id');
            const jobPosition = document.querySelector('#job_position');
            const department = document.querySelector('#department_id');
            const jobClass = document.querySelector('#job_class_id');
            $('#modal-title').text('Edit a Job Position');
            const idForm = $('form#form_data').attr('id', 'form_edit_data');
            $('#job_position').removeClass('was-validated');
            $('#job_position').removeClass('is-invalid');
            $('#job_position').removeClass('invalid-more');
            $('#department_id').removeClass('was-validated');
            $('#department_id').removeClass("is-invalid");
            $('#job_class_id').removeClass('was-validated');
            $('#job_class_id').removeClass("is-invalid");

            await fetch(`/job-positions/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    jobPositionId.value = id;
                    jobPosition.value = response.job_position;
                    department.value = response.department_id;
                    jobClass.value = response.job_class_id;
                });

            $(document).ready(() => {
                $('#department_id').select2();
                $('#job_class_id').select2();
            });

            submitEdit();
        });

        const submitEdit = () => {
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
                            job_position: request.get('job_position'),
                            department_id: request.get('department_id'),
                            job_class_id: request.get('job_class_id')
                        };

                        const id = $('#id').val();

                        fetch(`/job-positions/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    console.log(data);
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).removeClass('is-invalid');
                                        $(`#${prefix}`).removeClass('invalid-more');
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    setTimeout(function() {
                                        $('#datatables-ajax').DataTable().ajax.reload();
                                    }, 1000);

                                    Swal.fire({
                                        type: "success",
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success',
                                    });

                                    const reset_form = $('#form_edit_data')[0];
                                    $(reset_form).removeClass('was-validated');
                                    reset_form.reset();
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
        };

        const submit = () => {
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
                            job_position: request.get('job_position'),
                            department_id: request.get('department_id'),
                            job_class_id: request.get('job_class_id')
                        };

                        fetch('/job-positions', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    console.log(data);
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).removeClass('is-invalid');
                                        $(`#${prefix}`).removeClass('invalid-more');
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    setTimeout(function() {
                                        $('#datatables-ajax').DataTable().ajax.reload();
                                    }, 1000);

                                    Swal.fire({
                                        type: "success",
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success',
                                    });

                                    const reset_form = $('#form_data')[0];
                                    $(reset_form).removeClass('was-validated');
                                    reset_form.reset();
                                    $('#modal-form').modal('hide');
                                }
                            })
                            .catch((error) => {
                                console.log(error);
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
        };

        const sweetConfirm = (id) => {
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

                    fetch(`/job-positions/${id}`, {
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
        };
    </script>
@endsection
