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
                                        <h3>Late</h3>
                                        <div class="card-datatable table-responsive table-rounded">
                                            <table class="table table-borderless" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Start Minutes</th>
                                                        <th>Percentage</th>
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


    <div class="modal fade text-left" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Add Late</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_data" action="{{ url('late/store') }}" class="form-data-validate" novalidate>
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="">
                    <div class="modal-body">

                        <label>Start Minutes: </label>
                        <div class="form-group">
                            <input type="number" name="start_minutes" id="start_minutes" required class="form-control" />
                            <div class="invalid-feedback start_minutes_error">Please enter appropriate value.</div>
                        </div>

                        <label>Percentage: </label>
                        <div class="form-group">
                            <input type="number" name="percentage" id="percentage" required class="form-control" />
                            <div class="invalid-feedback percentage_error">Please enter appropriate value.</div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
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

            let uniqueField = ["start_minutes", 'percentage']
            for (let i = 0; i < uniqueField.length; i++) {
                $("#" + uniqueField[i]).removeClass('was-validated');
                $("#" + uniqueField[i]).removeClass("is-invalid");
                $("#" + uniqueField[i]).removeClass("invalid-more");
            }
        });

        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            var dt_ajax_table = $("#datatables-ajax");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                var dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#create.btn btn-md btn-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('late') }}",
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
                            data: 'start_minutes',
                            name: 'Start Minutes'
                        },
                        {
                            data: 'percentage',
                            name: 'Percentage'
                        },
                        {
                            data: 'action',
                            name: 'Action'
                        },
                    ],
                    columnDefs: [{
                        targets: 3,
                        className: "text-center",
                    }]
                });
            }
            $("#create").html('Add an Late');
            $("#create").attr('style', 'margin-bottom: 7px');
            $("#create").attr('data-toggle', 'modal');
            $("#create").attr('data-target', '#modal-form');
        });


        Array.prototype.filter.call($('#form_data'), function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    form.classList.add('invalid');
                }
                form.classList.add('was-validated');
                event.preventDefault();

                let id = $("#id").val();
                // console.log($('#form_data').serialize()) 

                var url = (id !== undefined && id !== null) && id ?
                    "{{ url('late/update') }}" + "/" + id :
                    "{{ url('late/store') }}";


                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    data: $('#form_data').serialize(),
                    // contentType: 'application/json', 
                    processData: false,
                    success: function(response) {
                        console.log(response)
                        if (response.error) {
                            $.each(response.error, function(prefix, val) {
                                $('div.' + prefix + '_error').text(val[0]);
                            });

                            let uniqueField = ["start_minutes", 'percentage']

                            for (let i = 0; i < uniqueField.length; i++) {
                                let error = (response.error.hasOwnProperty(uniqueField[i])) ?
                                    true : false
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

                            setTimeout(function() {
                                $('#datatables-ajax').DataTable().ajax.reload();
                            }, 1000);

                            Swal.fire({
                                type: "success",
                                title: 'Success!',
                                text: response.message,
                                confirmButtonClass: 'btn btn-success',
                            });
                            var reset_form = $('#form_data')[0];
                            $(reset_form).removeClass('was-validated');
                            reset_form.reset();
                            $('#modal-form').modal('hide');
                            $("#modal_title").html("Add an Late")
                            $("#id").val()
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });

            });

        });


        function edit_data(e) {

            $('#modal-form').modal('show');

            $.ajax({
                url: "{{ url('late/edit') }}" + "/" + e.attr('data-id'),
                method: "GET",
                // dataType: "json", 
                success: function(result) {
                    $("#modal_title").html("Edit an Late")
                    $('#id').val(result.data.id).trigger('change');
                    $('#start_minutes').val(result.data.start_minutes);
                    $('#percentage').val(result.data.percentage);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function delete_data(e) {

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,

            }).then(function(result) {

                if (result.value) {

                    var id = e.attr('data-id');

                    jQuery.ajax({
                        url: "{{ url('late/delete') }}" + "/" + id,
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            '_method': 'delete'
                        },
                        success: function(result) {

                            if (result.error) {

                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: result.message,
                                    confirmButtonClass: 'btn btn-success',
                                })

                            } else {

                                setTimeout(function() {
                                    $('#datatables-ajax').DataTable().ajax.reload();
                                }, 1000);

                                Swal.fire({
                                    type: "success",
                                    title: 'Deleted!',
                                    text: result.message,
                                    confirmButtonClass: 'btn btn-success',
                                })

                            }
                        }
                    });
                }
            });
        }
    </script>
@endsection
