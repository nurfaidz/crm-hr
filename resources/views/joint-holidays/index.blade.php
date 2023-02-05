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
                                        <h3>Joint Holidays</h3>
                                        <div class="card-datatable table-responsive table-rounded">
                                            <table class="table table-borderless" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Name</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Description</th>
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
                    <h4 class="modal-title" id="modal-title">Add Joint Holiday</h4>
                    <button type="button" class="close mr-0 mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_data" class="form-data-validate" novalidate>
                    @csrf
                    <input type="hidden" name="id" id="id" value="">
                    <div class="modal-body">
                        <label>Joint Holiday Occasion:</label>
                        <div class="form-group">
                            <input type="text" placeholder="Joint Holiday Occasion" name="occasion" id="occasion" required
                                class="form-control" />
                            <div class="invalid-feedback occasion_error"></div>
                        </div>
                        <label>Date Range:</label>
                        <div class="form-group">
                            <input type="text" placeholder="Date Range" name="date_range" id="date_range" required
                                class="form-control bg-white" />
                            <div class="invalid-feedback date_range_error"></div>
                        </div>
                        <input type="hidden" name="from_date" id="from_date" value="">
                        <input type="hidden" name="to_date" id="to_date" value="">
                        <label>Description:</label>
                        <div class="form-group">
                            <textarea placeholder="Description" name="description" id="description" cols="30" rows="5" class="form-control"
                                style="resize: none"></textarea>
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

            $('#occasion').removeClass('was-validated');
            $('#occasion').removeClass('is-invalid');
            $('#occasion').removeClass('invalid-more');
        });

        $(document).ready(function() {
            const dt_ajax_table = $("#datatables-ajax");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#create.btn btn-md btn-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('joint-holidays') }}",
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
                            data: 'occasion',
                            name: 'occasion'
                        },
                        {
                            data: (data) => data.from_date.split('-').reverse().join('/'),
                            name: 'from_date'
                        },
                        {
                            data: (data) => data.to_date.split('-').reverse().join('/'),
                            name: 'to_date'
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

            $("#create").html('Add Joint Holiday');
            $("#create").attr('style', 'margin-bottom: 7px');
            $("#create").attr('data-toggle', 'modal');
            $("#create").attr('data-target', '#modal-form');
        });

        $(document).on('click', '#create', function(event) {
            $('#modal-title').text('Add Joint Holiday');
            const idForm = $('form#form_edit_data').attr('id', 'form_data');
            const occasion = document.querySelector('#occasion');
            const from = document.querySelector('#from_date');
            const to = document.querySelector('#to_date');
            const description = document.querySelector('#description');
            const btnSubmit = $('#btnEdit').attr('id', 'submit');

            occasion.value = '';
            from.value = '';
            to.value = '';
            description.value = '';

            config = {
                allowInput: true,
                altInput: true,
                altFormat: "d/m/Y",
                mode: "range",
                locale: {
                    rangeSeparator: " - "
                },
                onOpen: function(selectedDates, dateStr, instance) {
                    $(instance.altInput).prop('readonly', true);
                },
                onClose: function(selectedDates, dateStr, instance) {
                    $(instance.altInput).prop('readonly', false);

                    let fromDate = instance.formatDate(selectedDates[0], "Y/m/d");
                    let toDate = instance.formatDate(selectedDates[1], "Y/m/d");

                    $('#from_date').val(fromDate);
                    $('#to_date').val(toDate);
                }
            }

            flatpickr("#date_range", config);

            submit();
        });

        $(document).on('click', '#edit', async function(event) {
            $('#modal-form').modal('show');
            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            const jointHolidayId = document.querySelector('#id');
            const occasion = document.querySelector('#occasion');
            const from = document.querySelector('#from_date');
            const to = document.querySelector('#to_date');
            const description = document.querySelector('#description');
            $('#modal-title').text('Edit Joint Holiday');
            const idForm = $('form#form_data').attr('id', 'form_edit_data');

            await fetch(`/joint-holidays/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    jointHolidayId.value = id;
                    occasion.value = response.occasion;
                    from.value = response.from_date;
                    to.value = response.to_date;
                    description.value = response.description;

                    config = {
                        allowInput: true,
                        altInput: true,
                        altFormat: "d/m/Y",
                        defaultDate: `${response.from_date} - ${response.to_date}`,
                        mode: "range",
                        locale: {
                            rangeSeparator: " - "
                        },
                        onOpen: function(selectedDates, dateStr, instance) {
                            $(instance.altInput).prop('readonly', true);
                        },
                        onClose: function(selectedDates, dateStr, instance) {
                            $(instance.altInput).prop('readonly', false);

                            let fromDate = instance.formatDate(selectedDates[0], "Y/m/d");
                            let toDate = instance.formatDate(selectedDates[1], "Y/m/d");

                            $('#from_date').val(fromDate);
                            $('#to_date').val(toDate);
                        }
                    }

                    flatpickr("#date_range", config);
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
                            occasion: request.get('occasion'),
                            date_range: request.get('date_range'),
                            from_date: request.get('from_date'),
                            to_date: request.get('to_date'),
                            description: request.get('description')
                        };

                        const id = $('#id').val();

                        fetch(`/joint-holidays/${id}`, {
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

                                    const error = data.error.occasion ? true : false

                                    if (error) {
                                        $('#occasion').removeClass('was-validated');
                                        $('#occasion').addClass('is-invalid');
                                        $('#occasion').addClass('invalid-more');
                                    } else {
                                        $('#occasion').removeClass('is-invalid');
                                        $('#occasion').removeClass('invalid-more');
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
                            occasion: request.get('occasion'),
                            date_range: request.get('date_range'),
                            from_date: request.get('from_date'),
                            to_date: request.get('to_date'),
                            description: request.get('description')
                        };

                        fetch('/joint-holidays', {
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

                                    const error = data.error.occasion ? true : false

                                    if (error) {
                                        $('#occasion').removeClass('was-validated');
                                        $('#occasion').addClass('is-invalid');
                                        $('#occasion').addClass('invalid-more');
                                    } else {
                                        $('#occasion').removeClass('is-invalid');
                                        $('#occasion').removeClass('invalid-more');
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

                    fetch(`/joint-holidays/${id}`, {
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
