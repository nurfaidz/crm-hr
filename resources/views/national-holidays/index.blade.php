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
                                        <h3>National Holidays</h3>
                                        <div class="card-datatable table-rounded-outline">
                                            <table class="table table-borderless nowrap" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Occasion</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
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
                            <select name="holiday_id" id="holiday_id" required class="form-control">
                                <option hidden disabled selected value>Select Holiday</option>
                                @foreach ($holidays as $holiday)
                                    <option value="{{ $holiday->holiday_id }}">{{ $holiday->holiday_occasion }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback holiday_id_error"></div>
                        </div>
                        <label>Date Range<span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" placeholder="Date Range" name="date_range" id="date_range" required
                                class="form-control bg-white" />
                            <div class="invalid-feedback date_range_error"></div>
                        </div>
                        <input type="hidden" name="start_date" id="start_date" value="">
                        <input type="hidden" name="end_date" id="end_date" value="">
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

            $('#holiday_id').removeClass('was-validated');
            $('#holiday_id').removeClass('is-invalid');
            $('#holiday_id').removeClass('invalid-more');
            $('#holiday_id').attr("readonly", false);
        });

        $(document).ready(function() {
            const dt_ajax_table = $("#datatables-ajax");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#create.btn btn-md btn-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('national-holidays') }}",
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
                            data: (data) => data.start_date.split('-').reverse().join('/'),
                            name: 'start_date'
                        },
                        {
                            data: (data) => data.end_date.split('-').reverse().join('/'),
                            name: 'end_date'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    columnDefs: [{
                        targets: 4,
                        className: 'text-center'
                    }]
                });
            }

            $("#create").html('Add Holiday Date');
            $("#create").attr('style', 'margin-bottom: 7px');
            $("#create").attr('data-toggle', 'modal');
            $("#create").attr('data-target', '#modal-form');
        });

        $(document).on('click', '#create', function(event) {
            $('#modal-title').text('Add Holiday');
            $('#submit').text('Add Holiday');

            const idForm = $('form#form_edit_data').attr('id', 'form_data');
            const holiday = document.querySelector('#holiday_id');
            const start = document.querySelector('#start_date');
            const end = document.querySelector('#end_date');
            const btnSubmit = $('#btnEdit').attr('id', 'submit');

            holiday.value = '';
            start.value = '';
            end.value = '';

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

                    let startDate = instance.formatDate(selectedDates[0], "Y/m/d");
                    let endDate = instance.formatDate(selectedDates[1], "Y/m/d");

                    $('#start_date').val(startDate);
                    $('#end_date').val(endDate);
                }
            }

            flatpickr("#date_range", config);

            submit();
        });

        $(document).on('click', '#edit', async function(event) {
            $('#modal-form').modal('show');
            $('#modal-title').text('Edit National Holiday');
            $('#submit').text('Edit Holiday');

            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            const nationalHolidayId = document.querySelector('#id');
            const holiday = document.querySelector('#holiday_id');
            $('#holiday_id').attr("readonly", true); 
            const start = document.querySelector('#start_date');
            const end = document.querySelector('#end_date');
            const idForm = $('form#form_data').attr('id', 'form_edit_data');

            await fetch(`/national-holidays/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    nationalHolidayId.value = id;
                    holiday.value = response.holiday_id;
                    start.value = response.start_date;
                    end.value = response.end_date;

                    config = {
                        allowInput: true,
                        altInput: true,
                        altFormat: "d/m/Y",
                        defaultDate: `${response.start_date} - ${response.end_date}`,
                        mode: "range",
                        locale: {
                            rangeSeparator: " - "
                        },
                        onOpen: function(selectedDates, dateStr, instance) {
                            $(instance.altInput).prop('readonly', true);
                        },
                        onClose: function(selectedDates, dateStr, instance) {
                            $(instance.altInput).prop('readonly', false);

                            let startDate = instance.formatDate(selectedDates[0], "Y/m/d");
                            let endDate = instance.formatDate(selectedDates[1], "Y/m/d");

                            $('#start_date').val(startDate);
                            $('#end_date').val(endDate);
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
                            holiday_id: request.get('holiday_id'),
                            date_range: request.get('date_range'),
                            start_date: request.get('start_date'),
                            end_date: request.get('end_date')
                        };

                        const id = $('#id').val();

                        fetch(`/national-holidays/${id}`, {
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

                                    const error = data.error.id ? true : false

                                    if (error) {
                                        $('#id').removeClass('was-validated');
                                        $('#id').addClass('is-invalid');
                                        $('#id').addClass('invalid-more');
                                    } else {
                                        $('#id').removeClass('is-invalid');
                                        $('#id').removeClass('invalid-more');
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
                            holiday_id: request.get('holiday_id'),
                            date_range: request.get('date_range'),
                            start_date: request.get('start_date'),
                            end_date: request.get('end_date')
                        };

                        fetch('/national-holidays', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    console.log(data)
                                    $.each(data.error, (prefix, val) => {
                                        $('div.' + prefix + '_error').text(val[0]);
                                    });

                                    let uniqueField = ["holiday_id"]
                                    for (let i = 0; i < uniqueField.length; i++) {
                                    let error = (data.error.hasOwnProperty(uniqueField[i])) ? true : false
                                    if (!error) {
                                        $("#" + uniqueField[i]).removeClass("is-invalid");
                                        $("#" + uniqueField[i]).removeClass("invalid-more");
                                    } else {
                                        $("#" + uniqueField[i]).removeClass('was-validated');
                                        $("#" + uniqueField[i]).addClass("is-invalid");
                                        $("#" + uniqueField[i]).addClass("invalid-more");
                                    }
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
    </script>
@endsection
