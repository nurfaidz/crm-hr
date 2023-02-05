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
                                        <h3>Entity</h3>
                                        <div class="card-datatable table-responsive table-rounded">
                                            <table class="table table-borderless" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Entity Name</th>
                                                        <th>Holding</th>
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
                    <h4 class="modal-title" id="modal_title">Add an Entity</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_data" action="{{ url('/branch/store') }}" class="form-data-validate" novalidate
                    method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="branch_id" id="branch_id" value="">
                    <div class="modal-body">

                        <label>Entity Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Input Entity" name="branch_name" id="branch_name" required
                                class="form-control" />
                            <div class="invalid-feedback branch_name_error">Please enter appropriate value.</div>
                        </div>

                        <label>Holding: </label>
                        <div class="form-group">
                            <select class="select2-data-ajax form-control" id="company_id" name="company_id" required>
                                <option value="" selected>--- Silahkan Pilih ---</option>
                            </select>
                            <div class="invalid-feedback company_id_error"></div>
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
        $(document).ready(function() {
            // reset form setelah klik edit 
            /*document.getElementById("add_branch").addEventListener("click", function() { 
                document.getElementById("form_data").reset(); 
                $("#modal_title").html("Add Data Branch"); 
            });*/

            $('#modal-form').on('hidden.bs.modal', function(event) {
                let reset_form = $('#form_data')[0];

                $("#form_data").trigger("reset");
                $(reset_form).removeClass('was-validated');
                $("#modal_title").html("Add Data Branch");
                $("#branch_id").val("");
                $("#company_id").val("").change();
                $("#form_data").trigger("reset");

                $('#branch_name').removeClass('was-validated');
                $('#branch_name').removeClass("is-invalid");
                $('#branch_name').removeClass("invalid-more");

                //$('.tunjangan_field').prop('hidden', true);
            });


            $.ajax({
                url: "{{ url('companies/select') }}",
                method: "GET",
                dataType: "json",
                success: function(result) {

                    if ($('#company_id').data('select2')) {
                        $("#company_id").val("");
                        $("#company_id").trigger("change");
                        $('#company_id').empty().trigger("change");

                    }

                    $("#company_id").select2({
                        data: result.data
                    });

                }
            });


            const dt_ajax_table = $("#datatables-ajax");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#create.btn btn-md btn-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('branch/get_list') }}",
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
                            data: 'branch_name',
                            name: 'Branch Name'
                        },
                        {
                            data: 'company_name',
                            name: 'company'
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
            $("#create").html('Add Entity');
            $("#create").attr('style', 'margin-bottom: 7px');
            $("#create").attr('data-toggle', 'modal');
            $("#create").attr('data-target', '#modal-form');
            

            $("#create").html('Add an Entity');
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

                let id_branch = $("#branch_id").val();
                // console.log($('#form_data').serialize()) 

                var url = (id_branch !== undefined && id_branch !== null) && id_branch ?
                    "{{ url('branch/update') }}" + "/" + id_branch :
                    "{{ url('branch/store') }}";
                //let url = "{{ url('branch/update') }}" + "/" + id_branch; 

                $.ajax({
                    url: url,
                    type: 'post',
                    data: $('#form_data').serialize(),
                    // contentType: 'application/json', 
                    processData: false,
                    success: function(response) {

                        if (response.error) {

                            $.each(response.error, function(prefix, val) {
                                $('div.' + prefix + '_error').text(val[0]);
                            });

                            let error = (response.error.branch_name) ? true : false
                            if (!error) {
                                $('#branch_name').removeClass("is-invalid");
                                $('#branch_name').removeClass("invalid-more");
                            } else {
                                $('#branch_name').removeClass('was-validated');
                                $('#branch_name').addClass("is-invalid");
                                $('#branch_name').addClass("invalid-more");
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
                            $("#modal_title").html("Add Data Branch")
                            $("#branch_id").val()
                        }

                    },
                });

            });

        });


        function edit_data(e) {

            $('#modal-form').modal('show');

            $.ajax({
                url: "{{ url('branch/edit') }}" + "/" + e.attr('data-id'),
                method: "GET",
                dataType: "json",
                success: function(result) {

                    $("#modal_title").html("Edit an Entity")
                    //$('#all_tt').val(result.data.all_tt); 
                    //$('#isolasi_slot').val(result.data.isolasi_slot); 
                    $('#branch_id').val(result.data.branch_id).trigger('change');
                    $('#branch_name').val(result.data.branch_name);
                    $('#company_id').val(result.data.company_id).trigger('change');
                    //$('#icu_slot').val(result.data.icu_slot); 
                    //$('#icu_isi').val(result.data.icu_isi); 

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
                        url: "{{ url('branch/delete') }}" + "/" + id,
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
