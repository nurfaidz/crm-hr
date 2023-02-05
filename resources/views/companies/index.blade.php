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
                                        <h3>Holdings</h3>
                                        <div class="card-datatable table-responsive table-rounded">
                                            <table class="table table-borderless" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Name</th>
                                                        <th>Address</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                        <th>Website</th>
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
                    <h4 class="modal-title" id="modal-title">Add New Holding</h4>
                    <button type="button" class="close mr-0 mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_data" class="form-data-validate" novalidate>
                    @csrf
                    <input type="hidden" name="id" id="id" value="">
                    <div class="modal-body">
                        <label>Holding Name:</label>
                        <div class="form-group">
                            <input type="text" placeholder="Holding Name" name="company_name" id="company_name" required
                                class="form-control" />
                            <div class="invalid-feedback company_name_error"></div>
                        </div>
                        <label>Holding Address:</label>
                        <div class="form-group">
                            <input type="text" placeholder="Company Address" name="company_address" id="company_address"
                                required class="form-control" />
                            <div class="invalid-feedback company_address_error"></div>
                        </div>
                        <label>Holding Phone:</label>
                        <div class="form-group">
                            <input type="text" placeholder="Holding Phone" name="company_phone" id="company_phone" required
                                class="form-control" />
                            <div class="invalid-feedback company_phone_error"></div>
                        </div>
                        <label>Holding Email:</label>
                        <div class="form-group">
                            <input type="email" placeholder="Holding Email" name="company_email" id="company_email" required
                                class="form-control" />
                            <div class="invalid-feedback company_email_error"></div>
                        </div>
                        <label>Holding Website:</label>
                        <div class="form-group">
                            <input type="url" placeholder="Company Website" name="company_website" id="company_website"
                                required class="form-control" onblur="checkURL(this)" />
                            <div class="invalid-feedback company_website_error"></div>
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
        function checkURL(url) {
            let string = url.value;

            if (!~string.indexOf('http')) {
                string = 'http://' + string;
            }

            url.value = string;

            return url;
        }
        
        $("#modal-form").on("hidden.bs.modal", function(e) {
            const reset_form = $('#form_data')[0];
            const reset_form_edit = $('#form_edit_data')[0];
            $(reset_form).removeClass('was-validated');
            $(reset_form_edit).removeClass('was-validated');
            let uniqueField = ["company_name", "company_address", "company_phone", "company_email",
                "company_website"
            ]
            for (let i = 0; i < uniqueField.length; i++) {
                $("#" + uniqueField[i]).removeClass('was-validated');
                $("#" + uniqueField[i]).removeClass("is-invalid");
                $("#" + uniqueField[i]).removeClass("invalid-more");
            }
        });

        $(document).ready(function() {
            const dt_ajax_table = $("#datatables-ajax");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#create.btn btn-md btn-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('companies') }}",
                    scrollX: true,
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
                            name: 'company_name'
                        },
                        {
                            data: 'company_address',
                            name: 'company_address'
                        },
                        {
                            data: 'company_phone',
                            name: 'company_phone'
                        },
                        {
                            data: 'company_email',
                            name: 'company_email'
                        },
                        {
                            data: 'company_website',
                            name: 'company_website'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    columnDefs: [{
                        targets: 6,
                        className: 'text-center'
                    }]
                });
            }

            $("#create").html('Add a Holding');
            $("#create").attr('style', 'margin-bottom: 7px');
            $("#create").attr('data-toggle', 'modal');
            $("#create").attr('data-target', '#modal-form');
        });

        $(document).on('click', '#create', function(event) {
            $('#modal-title').text('Add a Holding');
            const idForm = $('form#form_edit_data').attr('id', 'form_data');
            const name = document.querySelector('#company_name');
            const address = document.querySelector('#company_address');
            const phone = document.querySelector('#company_phone');
            const email = document.querySelector('#company_email');
            const website = document.querySelector('#company_website');
            const btnSubmit = $('#btnEdit').attr('id', 'submit');
            name.value = '';
            address.value = '';
            phone.value = '';
            email.value = '';
            website.value = '';
            submit();
        });

        $(document).on('click', '#edit', async function(event) {
            $('#modal-form').modal('show');
            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            const companyId = document.querySelector('#id');
            const name = document.querySelector('#company_name');
            const address = document.querySelector('#company_address');
            const phone = document.querySelector('#company_phone');
            const email = document.querySelector('#company_email');
            const website = document.querySelector('#company_website');
            $('#modal-title').text('Edit a Holding');
            const idForm = $('form#form_data').attr('id', 'form_edit_data');

            await fetch(`/companies/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    companyId.value = id;
                    name.value = response.company_name;
                    address.value = response.company_address;
                    phone.value = response.company_phone;
                    email.value = response.company_email;
                    website.value = response.company_website;
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
                            company_name: request.get('company_name'),
                            company_address: request.get('company_address'),
                            company_phone: request.get('company_phone'),
                            company_email: request.get('company_email'),
                            company_website: request.get('company_website')
                        };

                        const id = $('#id').val();

                        fetch(`/companies/${id}`, {
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

                                    const error = data.error.company_name ? true : false

                                    if (error) {
                                        $('#company_name').removeClass('was-validated');
                                        $('#company_name').addClass('is-invalid');
                                        $('#company_name').addClass('invalid-more');
                                    } else {
                                        $('#company_name').removeClass('is-invalid');
                                        $('#company_name').removeClass('invalid-more');
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
                            company_name: request.get('company_name'),
                            company_address: request.get('company_address'),
                            company_phone: request.get('company_phone'),
                            company_email: request.get('company_email'),
                            company_website: request.get('company_website')
                        };

                        fetch('/companies', {
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

                                    const error = data.error.company_name ? true : false
                                    const error1 = data.error.company_website ? true : false

                                    if (error || error1) {
                                        $('#company_website').removeClass('was-validated');
                                        $('#company_website').addClass('is-invalid');
                                        $('#company_website').addClass('invalid-more');
                                    } else {
                                        $('#company_website').removeClass('is-invalid');
                                        $('#company_website').removeClass('invalid-more');
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

                    fetch(`/companies/${id}`, {
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
