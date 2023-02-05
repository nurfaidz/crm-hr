@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="card">
                    <div class="card-body">
                        <section id="ajax-datatable">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <h3>Sub Business Units</h3>
                                        <div class="card-datatable teble-responsive table-rounded">
                                            <table class="table table-borderless" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Code</th>
                                                        <th>SBU Name</th>
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
                    <h4 class="modal-title" id="modal-title">Create New SBU</h4>
                    <button type="button" class="close mr-0 mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_data" class="form-data-validate" novalidate>
                    @csrf
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" id="select_blank" value="--- Select ---" />
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="department_branch_id">Entity</label>
                            <select class="select2-data-ajax form-control" id="department_branch_id"
                                name="department_branch_id" required>
                                <option value="">--- Select ---</option>
                            </select>
                            <div class="invalid-feedback department_branch_id_error"></div>
                        </div>
                        <div class="form-group">
                            <label>Code:</label>
                            <input type="text" placeholder="SBU Code" name="code" id="code" class="form-control"
                                maxlength="6" autofocus required />
                            <div class="invalid-feedback code_error">Please enter SBU code.</div>
                        </div>
                        <div class="form-group">
                            <label>SBU Name:</label>
                            <input type="text" placeholder="SBU Name" name="department_name" id="department_name"
                                required class="form-control" />
                            <div class="invalid-feedback department_name_error">Please enter SBU name.</div>
                        </div>
                        <label>Manager: </label>
                        <div class="form-group">
                            <select class="select2-data-ajax form-control" id="manager" name="manager" required>
                                <option value="">--- Select ---</option>
                            </select>
                            <div class="invalid-feedback manager_error">Please select manager name.</div>
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
                    ajax: "{{ url('departments/list') }}",
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
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: (data) => {
                                return `<b>${data.department_name}</b>
                                        <br/> Entity: ${data.branch_name}
                                        <br/> Manager: ${data.first_name} ${data.last_name}`;
                            },
                            name: 'Department Name'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    columnDefs: [{
                        targets: 3,
                        className: 'text-center',
                    }]
                });
            }
            $("#create").html('Add a SBU');
            $("#create").attr('style', 'margin-bottom: 7px');
            $("#create").attr('data-toggle', 'modal');
            $("#create").attr('data-target', '#modal-form');
        });

        $(document).ready(function() {
            $('.select2').select2();

            $.ajax({
                url: "{{ url('branch/select') }}",
                method: "GET",
                dataType: "json",
                success: function(result) {

                    if ($('#department_branch_id').data('select2')) {
                        $("#department_branch_id").val("");
                        $("#department_branch_id").trigger("change");
                        $('#department_branch_id').empty().trigger("change");

                    }

                    $("#department_branch_id").select2({
                        data: result.data
                    });

                }
            });

            const branch = $('#department_branch_id').change(() => {
                const data = $('#department_branch_id option:selected').val();
                getManager(data);
            })
        })

        const getManager = (id) => {
            const base_url = "{{ url('departments/manager_list') }}";
            $.ajax({
                url: base_url,
                method: "POST",
                data: {
                    'branch_id': id,
                    '_token': $('input[name=_token]').val()

                },
                success: function(result) {

                    if ($('#manager').data('select2')) {
                        $("#manager").val("");
                        $("#manager").trigger("change");
                        $('#manager').empty().trigger("change");
                    }

                    let data = [{
                        id: "",
                        text: $("#select_blank").val()
                    }];

                    for (let i = 0; i < result.length; i++) {
                        data[(i + 1)] = {
                            id: result[i].manager_id,
                            text: result[i].manager
                        }
                    }

                    $("#manager").select2({
                        data: data,
                        width: '100%'
                    });

                }
            });
        }

        $(document).on('click', '#create', function(event) {
            let reset_form = $('#form_data')[0];

            $("#form_data").trigger("reset");
            $(reset_form).removeClass('was-validated');
            $("#department_branch_id").val("").trigger('change');
            $("#manager").val("").trigger('change');

            $('#code').removeClass('was-validated');
            $('#code').removeClass("is-invalid");
            $('#code').removeClass("invalid-more");
            $('#manager').removeClass('was-validated');
            $('#manager').removeClass("is-invalid");

            $('#modal-title').text('Add a SBU');
            $(document).ready(function() {
                $('#modal-form').on('shown.bs.modal', function() {
                    $('#code').trigger('focus');
                });
            });
            const idForm = $('form#form_edit_data').attr('id', 'form_data');
            const departmentId = document.querySelector('#id');
            const code = document.querySelector('#code');
            const name = document.querySelector('#department_name');
            const branch = document.querySelector('#department_branch_id');
            const manager = document.querySelector('#manager');
            const btnSubmit = $('#btnEdit').attr('id', 'submit');
            id.value = '';
            code.value = '';
            name.value = '';
            branch.value = '';
            manager.value = '';
            submit();
        });

        $(document).on('click', '#edit', async function(event) {
            let reset_form = $('#form_data')[0];

            $('#code').removeClass('was-validated');
            $('#code').removeClass("is-invalid");
            $('#code').removeClass("invalid-more");
            $('#manager').removeClass('was-validated');
            $('#manager').removeClass("is-invalid");

            $('#modal-form').modal('show');
            $(document).ready(function() {
                $('#modal-form').on('shown.bs.modal', function() {
                    $("#form_data").trigger("reset");
                    $(reset_form).removeClass('was-validated');
                    $("#department_branch_id").val("").trigger('change');
                    $("#manager").val("").trigger('change');
                    $('#code').trigger('focus');
                });
            });
            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');

            const departmentId = document.querySelector('#id');
            const code = document.querySelector('#code');
            const name = document.querySelector('#department_name');
            $('#modal-title').text('Edit a SBU');
            const branch = $('#department_branch_id');
            const idForm = $('form#form_data').attr('id', 'form_edit_data');

            await fetch(`/departments/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    departmentId.value = id;
                    code.value = response.department.code;
                    name.value = response.department.department_name;
                    branch.val(response.department.department_branch_id).trigger('change');
                    //manager.val(response.department.manager).trigger('change');
                    let checkExist_interval = setInterval(() => {
                        if ($('#manager option').length > 1) {
                            $('#manager').val(response.department.manager).trigger('change');
                            clearInterval(checkExist_interval);
                        }
                    }, 1000);

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
                            code: request.get('code'),
                            department_name: request.get('department_name'),
                            department_branch_id: request.get('department_branch_id'),
                            manager: request.get('manager')
                        };

                        const id = $('#id').val();

                        fetch(`/departments/${id}`, {
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
                            code: request.get('code'),
                            department_name: request.get('department_name'),
                            department_branch_id: request.get('department_branch_id'),
                            manager: request.get('manager')
                        };

                        fetch('/departments', {
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
        }

        function sweetConfirm(id) {
            event.preventDefault(); // prevent form submit
            const form = event.target.form; // storing the form
            Swal.fire({
                title: "Are you sure?",
                text: "But you will still be able to retrieve this file.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, archive it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((result) => {
                if (result.value) {
                    const request = new FormData(document.getElementById('form_delete_data'));
                    const data = {
                        _token: request.get('_token'),
                    };

                    fetch(`/departments/${id}`, {
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
                            }, 1000);

                            Swal.fire({
                                type: "success",
                                title: 'Success!',
                                text: data.message,
                                confirmButtonClass: 'btn btn-success',
                            });

                            const reset_form = $('#form_delete_data')[0];
                            $(reset_form).removeClass('was-validated');
                            reset_form.reset();
                            $('#modal-form').modal('hide');
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            Swal.fire({
                                type: "error",
                                title: 'Oops...',
                                text: `Deleted fail or ${error}`,
                                confirmButtonClass: 'btn btn-success',
                            });
                        });
                } else {
                    Swal.fire("Cancelled", "Your imaginary file is safe :)", "error");
                }
            });
        }
    </script>
@endsection
