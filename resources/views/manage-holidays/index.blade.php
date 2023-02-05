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
                                        <h3>List of Holidays</h3>
                                        <div class="card-datatable table-rounded-outline">
                                            <table class="table table-borderless nowrap" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Occasion</th>
                                                        <th>Description</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
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
                    <h4 class="modal-title" id="modal-title">Add Holiday</h4>
                    <button type="button" class="close mr-0 mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_data" class="form-data-validate" novalidate>
                    @csrf
                    <input type="hidden" name="id" id="id" value="">
                    <div class="modal-body input-manage-holidays">
                        <label>Holiday Occasion<span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" placeholder="Holiday Occasion" name="holiday_occasion" id="holiday_occasion" required class="form-control" />
                            <div class="invalid-feedback holiday_occasion_error"></div>
                        </div>
                        <label>Description<span class="text-danger">*</span></label>
                        <div class="form-group">
                            <textarea placeholder="Description" name="description" id="description" cols="30" rows="5" class="form-control bg-white" required></textarea>
                            <div class="invalid-feedback description_error"></div>
                        </div>
                        <label>Status<span class="text-danger">*</span></label>
                        <div class="form-group">
                            <select name="status" id="status" class="form-control" required>
                                <option hidden disabled selected value>Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Not Active</option>
                            </select>
                            <div class="invalid-feedback status_error"></div>
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
        $("#modal-form").on("hidden.bs.modal", function(e) {
            const reset_form = $('#form_data')[0];
            const reset_form_edit = $('#form_edit_data')[0];

            $(reset_form).removeClass('was-validated');
            $(reset_form_edit).removeClass('was-validated');

            $('#holiday_occasion').removeClass('was-validated');
            $('#holiday_occasion').removeClass('is-invalid');
            $('#holiday_occasion').removeClass('invalid-more');
        });

        $(document).ready(function() {
            const dt_ajax_table = $("#datatables-ajax");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#create.btn btn-md btn-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('manage-holidays') }}",
                    ordering: false,
                    scrollX: true,
                    scrollCollapse: true,
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
                            data: 'holiday_occasion',
                            name: 'holiday_occasion'
                        },
                        {
                            data: (data) => {
                                if (data.description === null) {
                                    return '-';
                                } else if (data.description.length > 50) {
                                    return data.description.substr(0, 50) + '...';
                                }

                                return data.description;
                            },
                            name: 'description'
                        },
                        {
                            data: (data) => {
                                if (data.status === 0) {
                                    return `<span class="badge badge-pill badge-light-danger">Not Active</span>`;
                                } else {
                                    return `<span class="badge badge-pill badge-light-success">Active</span>`;
                                }
                            },
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    columnDefs: [{
                        targets: [3, 4],
                        className: 'text-center'
                    }]
                });
            }

            $("#create").html('Add Holiday');
            $("#create").attr('style', 'margin-bottom: 7px');
            $("#create").attr('data-toggle', 'modal');
            $("#create").attr('data-target', '#modal-form');
        });

        $(document).on('click', '#create', function(event) {
            $('#modal-title').text('Add Holiday');
            $('#submit').text('Add Holiday');

            const idForm = $('form#form_edit_data').attr('id', 'form_data');
            const occasion = document.querySelector('#holiday_occasion');
            const description = document.querySelector('#description');
            const status = document.querySelector('#status');
            const btnSubmit = $('#btnEdit').attr('id', 'submit');

            occasion.value = '';
            description.value = '';
            status.value = '';

            submit();
        });

        $(document).on('click', '#edit', async function(event) {
            $('#modal-form').modal('show');
            $('#modal-title').text('Edit Holiday');
            $('#submit').text('Edit Holiday');

            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            const holidayId = document.querySelector('#id');
            const occasion = document.querySelector('#holiday_occasion');
            const description = document.querySelector('#description');
            const status = document.querySelector('#status');
            const idForm = $('form#form_data').attr('id', 'form_edit_data');

            await fetch(`/manage-holidays/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    holidayId.value = id;
                    occasion.value = response.holiday_occasion;
                    description.value = response.description;
                    status.value = response.status;
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
                            holiday_occasion: request.get('holiday_occasion'),
                            description: request.get('description'),
                            status: request.get('status')
                        };

                        const id = $('#id').val();

                        fetch(`/manage-holidays/${id}`, {
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

                                    const error = data.error.holiday_occasion ? true : false

                                    if (error) {
                                        $('#holiday_occasion').removeClass('was-validated');
                                        $('#holiday_occasion').addClass('is-invalid');
                                        $('#holiday_occasion').addClass('invalid-more');
                                    } else {
                                        $('#holiday_occasion').removeClass('is-invalid');
                                        $('#holiday_occasion').removeClass('invalid-more');
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
                            holiday_occasion: request.get('holiday_occasion'),
                            description: request.get('description'),
                            status: request.get('status')
                        };

                        fetch('/manage-holidays', {
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

                                    const error = data.error.holiday_occasion ? true : false

                                    if (error) {
                                        $('#holiday_occasion').removeClass('was-validated');
                                        $('#holiday_occasion').addClass('is-invalid');
                                        $('#holiday_occasion').addClass('invalid-more');
                                    } else {
                                        $('#holiday_occasion').removeClass('is-invalid');
                                        $('#holiday_occasion').removeClass('invalid-more');
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

                    fetch(`/manage-holidays/${id}`, {
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
