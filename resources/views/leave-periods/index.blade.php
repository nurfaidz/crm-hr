@extends('partials.template')
@section('meta_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

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
                                        <h3>Leave Periods</h3>
                                        <div class="card-datatable table-responsive table-rounded">
                                            <table class="table table-borderless" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Limit</th>
                                                        <th class="text-center">Action</th>
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

    <!-- Modal Form -->
    <div class="modal fade text-left" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Add a Leave Period</h4>
                    <button type="button" class="close mr-0 mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_data" class="form-data-validate" novalidate>
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Year Setting</label>
                            <select class="form-control" name="year" id="year" onchange="selectFunction(event)" required>
                                <option value="same">Same Year
                                </option>
                                <option value="next">Cross Year
                                </option>
                            </select>
                            <div class="invalid-feedback status_error">Please select status.</div>
                        </div>

                        <div class="form-group">
                            <label>Date</label>
                            <input id="range-date" name="range-date"
                                class="form-control g-font-size-12 g-font-size-default--md" type="text"
                                data-rp-wrapper="#rangePickerWrapper2" data-rp-type="range" data-rp-date-format="d M Y"
                                data-rp-default-date='["01 Jan 2016", "31 Dec 2017"]' required />
                            <div class="invalid-feedback leave_type_error">Please select range date.</div>
                        </div>

                        <div class="form-group">
                            <label>Limit:</label>
                            <div class="form-group">
                                <input type="number" placeholder="ex. 12" name="limit" id="limit" required
                                    class="form-control" />
                                <div class="invalid-feedback limit_error">Please enter limit leave period.</div>
                            </div>
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let dt_ajax_table = $("#datatables-ajax");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                let dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#create.btn btn-md btn-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('leave-periods') }}",
                    resposie: 'true',
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
                            data: 'from_date',
                            name: 'From Date'
                        },
                        {
                            data: 'to_date',
                            name: 'To Date'
                        },
                        {
                            data: 'limit',
                            name: 'Limit'
                        },
                        {
                            data: 'action',
                            name: 'Action'
                        },
                    ],
                    columnDefs: [{
                        targets: 4,
                        className: 'text-center',
                    }]
                });
            }

            $("#create").html('Add a Leave Period');
            $("#create").attr('style', 'margin-bottom: 7px');
            $("#create").attr('data-toggle', 'modal');
            $("#create").attr('data-target', '#modal-form');
        });

        $(document).on('click', '#create', function(event) {
            $('#modal-title').text('Add a Leave Period');
            const idForm = $('form#form_edit_data').attr('id', 'form_data');
            const year = document.querySelector('#year');
            const rangeDate = document.querySelector('#range-date');
            const limit = document.querySelector('#limit');
            const btnSubmit = $('#btnEdit').attr('id', 'submit');
            const date = new Date();

            flatpickr("#range-date", {
                // allowInput: true,
                dateFormat: "Y-m-d",
                mode: "range",
                enable: [{
                    from: `${date.getFullYear()}-01-01`,
                    to: `${date.getFullYear()}-12-31`
                }],
                onChange: ([start, end]) => {
                    if (start && end) {
                        console.log({
                            start,
                            end
                        });
                    }
                },
            });

            const arrowUp = document.querySelector('.arrowUp');
            const arrowDown = document.querySelector('.arrowDown');
            year.value = 'same';
            limit.value = '';
            arrowUp.style.display = 'inline-block';
            arrowDown.style.display = 'inline-block';
            submit();
        });

        $(document).on('click', '#edit', async function(event) {
            console.log(event.detail);
            $('#modal-form').modal('show');

            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            const leavePeriodId = document.querySelector('#id');
            const year = document.querySelector('#year');
            const rangeDate = document.querySelector('#range-date');
            const limit = document.querySelector('#limit');
            $('#modal-title').text('Edit a Leave Period');
            const idForm = $('form#form_data').attr('id', 'form_edit_data');
            const date = new Date();

            await fetch(`/leave-periods/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    // console.log(response);
                    leavePeriodId.value = id;
                    year.value = response.year;
                    if (year.value === 'same') {
                        flatpickr("#range-date", {
                            // allowInput: true,
                            dateFormat: "Y-m-d",
                            mode: "range",
                            enable: [{
                                from: `${date.getFullYear()}-01-01`,
                                to: `${date.getFullYear()}-12-31`
                            }],
                            onChange: ([start, end]) => {
                                if (start && end) {
                                    console.log({
                                        start,
                                        end
                                    });
                                }
                            },
                        });
                    }

                    if (year.value === 'next') {
                        flatpickr("#range-date", {
                            // allowInput: true,
                            dateFormat: "Y-m-d",
                            mode: "range",
                            enable: [{
                                from: `${date.getFullYear()}-01-01`,
                                to: `${date.getFullYear() + 1}-12-31`
                            }],
                            onChange: ([start, end], dateStr, instance) => {
                                if (start && end) {
                                    let range = Math.abs(end - start);
                                    range = range / 86400000;
                                    console.log(range);
                                    if (range > 365) {
                                        Swal.fire({
                                            type: "error",
                                            title: 'Oops...',
                                            text: 'The maximum range is 365 days',
                                            confirmButtonClass: 'btn btn-success',
                                        });
                                        instance.clear();
                                    }
                                }
                            },
                        });
                    }
                    rangeDate.value = `${response.from_date} to ${response.to_date}`;
                    limit.value = response.limit;
                });

            const arrowUp = document.querySelector('.arrowUp');
            const arrowDown = document.querySelector('.arrowDown');
            arrowUp.style.display = 'inline-block';
            arrowDown.style.display = 'inline-block';

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
                            year: request.get('year'),
                            rangeDate: request.get('range-date'),
                            limit: request.get('limit'),
                        };

                        const id = $('#id').val();

                        fetch(`/leave-periods/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Success:', data);
                                try {
                                    if (data.error === true) {
                                        throw data.message
                                    }

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
                                } catch (error) {
                                    console.error('Error:', error);
                                    let message = '';
                                    if (error.year) {
                                        message = error.year;
                                    } else if (error.limit) {
                                        message = error.limit;
                                    } else if (error.rangeDate) {
                                        message = error.rangeDate;
                                    } else if (error.from_date) {
                                        message = error.from_date;
                                    } else if (error.to_date) {
                                        message = error.to_date;
                                    } else {
                                        message = error;
                                    }
                                    Swal.fire({
                                        type: "error",
                                        title: 'Oops...',
                                        text: message,
                                        confirmButtonClass: 'btn btn-success',
                                    });

                                    const reset_form = $('#form_edit_data')[0];
                                    $(reset_form).removeClass('was-validated');
                                    reset_form.reset();
                                    window.history.pushState({}, document.title, "/" + "leave-periods");
                                    $('#modal-form').modal('hide');
                                }
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `The name has already been taken or ${error}`,
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
                            year: request.get('year'),
                            rangeDate: request.get('range-date'),
                            limit: request.get('limit'),
                        };

                        fetch('/leave-periods', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Success:', data);
                                try {
                                    if (data.error === true) {
                                        throw data.message
                                    }

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

                                } catch (error) {
                                    console.error('Error:', error);
                                    let message = '';
                                    if (error.year) {
                                        message = error.year;
                                    } else if (error.limit) {
                                        message = error.limit;
                                    } else if (error.rangeDate) {
                                        message = error.rangeDate;
                                    } else if (error.from_date) {
                                        message = error.from_date;
                                    } else if (error.to_date) {
                                        message = error.to_date;
                                    } else {
                                        message = error;
                                    }
                                    Swal.fire({
                                        type: "error",
                                        title: 'Oops...',
                                        text: message,
                                        confirmButtonClass: 'btn btn-success',
                                    });
                                }
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `The name has already been taken or ${error}`,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    } else {
                        submitEdit();
                    }
                });
            });
        }

        function selectFunction(e) {
            console.log(e.target.value);
            const date = new Date();
            if (year.value === 'same') {
                flatpickr("#range-date", {
                    // allowInput: true,
                    dateFormat: "Y-m-d",
                    mode: "range",
                    enable: [{
                        from: `${date.getFullYear()}-01-01`,
                        to: `${date.getFullYear()}-12-31`
                    }],
                    onChange: ([start, end]) => {
                        if (start && end) {
                            console.log({
                                start,
                                end
                            });
                        }
                    },
                });
            }

            if (year.value === 'next') {
                flatpickr("#range-date", {
                    // allowInput: true,
                    dateFormat: "Y-m-d",
                    mode: "range",
                    enable: [{
                        from: `${date.getFullYear()}-01-01`,
                        to: `${date.getFullYear() + 1}-12-31`
                    }],
                    onChange: ([start, end], dateStr, instance) => {
                        if (start && end) {
                            let range = Math.abs(end - start);
                            range = range / 86400000;
                            console.log(range);
                            if (range > 365) {
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: 'The maximum range is 365 days',
                                    confirmButtonClass: 'btn btn-success',
                                });
                                instance.clear();
                            }
                        }
                    },
                });
            }

            const arrowUp = document.querySelector('.arrowUp');
            const arrowDown = document.querySelector('.arrowDown');
            arrowUp.style.display = 'inline-block';
            arrowDown
                .style.display = 'inline-block';
        }
    </script>
@endsection
