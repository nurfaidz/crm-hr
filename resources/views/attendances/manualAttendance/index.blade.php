@extends('partials.template')
@section('meta_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">

                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Data Manual Attendance</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="card">
                    <div class="card-body">
                        <section id="ajax-datatable">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-datatable">
                                            <div class="row">
                                                <div class="col align-self-start">
                                                    <label>Entity: </label>
                                                    <div class="form-group">
                                                        <select class="select2-data-ajax form-control" id="branch_id"
                                                            name="branch_id"></select>

                                                        <div class="valid-feedback">Looks good!</div>
                                                        <div class="invalid-feedback">Please Select Entity.</div>
                                                    </div>
                                                </div>
                                                <div class="col align-self-center">
                                                    <label>SBU: </label>
                                                    <div class="form-group">
                                                        <select class="select2-data-ajax form-control" id="department_id"
                                                            name="department_id"></select>

                                                        <div class="valid-feedback">Looks good!</div>
                                                        <div class="invalid-feedback">Please Select SBU.</div>
                                                    </div>
                                                </div>
                                                <div class="col align-self-center">
                                                    <label>Date: </label>
                                                    <div class="form-group">
                                                        <input type="date" name="date" id="date" required
                                                            class="form-control" />
                                                        <div class="valid-feedback">Looks good!</div>
                                                        <div class="invalid-feedback">Please Select Date.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <table class="table" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Employee's Name</th>
                                                        <th>Check In</th>
                                                        <th>Check Out</th>
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


    {{-- <div class="modal fade text-left" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Add Data BOR</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_data" class="form-data-validate" novalidate>
                    {{ csrf_field() }}
                    <input type="hidden" name="id_bor" id="id_bor" value="">
                    <div class="modal-body">

                        <label>Date: </label>
                        <div class="form-group">
                            <input type="text" id="fp-default" class="form-control flatpickr-human-friendly" name="tanggal"
                                id="tanggal" placeholder="YYYY-MM-DD" />
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please Select Date.</div>
                        </div>

                        <label>Rumah Sakit: </label>
                        <div class="form-group">
                            <select class="select2-data-ajax form-control" id="id" name="id"></select>

                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please Select Rumah Sakit.</div>
                        </div>

                        <label>Isolasi Tersedia: </label>
                        <div class="form-group">
                            <input type="number" placeholder="ex. 30" name="isolate_slot" id="isolate_slot" required
                                class="form-control" />
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter appropriate value.</div>
                        </div>

                        <label>Total Tempat Tidur: </label>
                        <div class="form-group">
                            <input type="number" placeholder="ex. 30" name="all_tt" id="all_tt" required
                                class="form-control" />
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter appropriate value.</div>
                        </div>

                        <label>Isolasi Terisi: </label>
                        <div class="form-group">
                            <input type="number" placeholder="ex. 30" name="isolate_isi" id="isolate_isi" required
                                class="form-control" />
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter appropriate value.</div>
                        </div>

                        <label>ICU Tersedia: </label>
                        <div class="form-group">
                            <input type="number" placeholder="ex. 30" name="icu_slot" id="icu_slot" required
                                class="form-control" />
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter appropriate value.</div>
                        </div>

                        <label>ICU Terisi: </label>
                        <div class="form-group">
                            <input type="number" placeholder="ex. 30" name="icu_isi" id="icu_isi" required
                                class="form-control" />
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter appropriate value.</div>
                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
@endsection

@section('page_script')
    <script>
        $(document).ready(function() {

            $.ajax({
                url: "{{ url('manualattendance/branches') }}",
                method: "GET",
                dataType: "json",
                success: function(result) {

                    if ($('#branch_id').data('select2')) {
                        $("#branch_id").val("");
                        $("#branch_id").trigger("change");
                        $('#branch_id').empty().trigger("change");

                    }

                    $("#branch_id").select2({
                        data: result.data
                    });

                }
            });

            $.ajax({
                url: "{{ url('manualattendance/departments') }}",
                method: "GET",
                dataType: "json",
                success: function(result) {

                    if ($('#department_id').data('select2')) {
                        $("#department_id").val("");
                        $("#department_id").trigger("change");
                        $('#department_id').empty().trigger("change");

                    }

                    $("#department_id").select2({
                        data: result.data
                    });

                }
            });


            var dt_ajax_table = $("#datatables-ajax");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                var dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('manualattendance/employees') }}",
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
                            data: 'nama_depan',
                            name: 'Nama Depan'
                        },
                        {
                            data: 'updated_at',
                            name: 'Check In'
                        },
                        {
                            data: 'updated_at',
                            name: 'Check Out'
                        },
                    ],
                    columnDefs: [{
                        targets: 3,
                        className: "text-center",
                    }]
                });
            }


        });


        // Array.prototype.filter.call($('#form_data'), function(form) {
        //     form.addEventListener('submit', function(event) {
        //         if (form.checkValidity() === false) {
        //             form.classList.add('invalid');
        //         }
        //         form.classList.add('was-validated');
        //         event.preventDefault();

        //         let employee_id = $("#employee_id").val();
        //         // console.log($('#form_data').serialize())

        //         var url = (employee_id !== undefined && employee_id !== null) && employee_id ?
        //             "{{ url('manualattendance/update') }}" + "/" + employee_id :
        //             "{{ url('manualattendance/store') }}";

        //         $.ajax({
        //             url: url,
        //             type: 'post',
        //             data: $('#form_data').serialize(),
        //             // contentType: 'application/json',
        //             processData: false,
        //             success: function(response) {

        //                 if (response.error) {

        //                     Swal.fire({
        //                         type: "error",
        //                         title: 'Oops...',
        //                         text: response.message,
        //                         confirmButtonClass: 'btn btn-success',
        //                     });

        //                 } else {

        //                     setTimeout(function() {
        //                         $('#datatables-ajax').DataTable().ajax.reload();
        //                     }, 1000);

        //                     Swal.fire({
        //                         type: "success",
        //                         title: 'Success!',
        //                         text: response.message,
        //                         confirmButtonClass: 'btn btn-success',
        //                     });

        //                 }
        //                 var reset_form = $('#form_data')[0];
        //                 $(reset_form).removeClass('was-validated');
        //                 reset_form.reset();
        //                 $('#modal-form').modal('hide');
        //                 $("#modal_title").html("Add Data BOR")
        //                 $("#id_bor").val()

        //             },
        //         });

        //     });

        // });


        // function edit_data(e) {

        //     $('#modal-form').modal('show');

        //     $.ajax({
        //         url: "{{ url('yankesin/input/bor-edit') }}" + "/" + e.attr('data-id'),
        //         method: "GET",
        //         dataType: "json",
        //         success: function(result) {

        //             $("#modal_title").html("Edit Data BOR")
        //             $('#id_rs').val(result.data.id_rs).trigger('change');
        //             $('#all_tt').val(result.data.all_tt);
        //             $('#isolasi_slot').val(result.data.isolasi_slot);
        //             $('#isolasi_isi').val(result.data.isolasi_isi);
        //             $('#icu_slot').val(result.data.icu_slot);
        //             $('#icu_isi').val(result.data.icu_isi);

        //         }
        //     });
        // }

        // function delete_data(e) {

        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You won't be able to revert this!",
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, delete it!',
        //         confirmButtonClass: 'btn btn-primary',
        //         cancelButtonClass: 'btn btn-danger ml-1',
        //         buttonsStyling: false,

        //     }).then(function(result) {

        //         if (result.value) {

        //             var id = e.attr('data-id');

        //             jQuery.ajax({
        //                 url: "{{ url('yankesin/input/bor-delete/') }}" + "/" + id,
        //                 type: 'post',
        //                 headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 },
        //                 data: {
        //                     '_method': 'delete'
        //                 },
        //                 success: function(result) {

        //                     if (result.error) {

        //                         Swal.fire({
        //                             type: "error",
        //                             title: 'Oops...',
        //                             text: result.message,
        //                             confirmButtonClass: 'btn btn-success',
        //                         })

        //                     } else {

        //                         setTimeout(function() {
        //                             $('#datatables-ajax').DataTable().ajax.reload();
        //                         }, 1000);

        //                         Swal.fire({
        //                             type: "success",
        //                             title: 'Deleted!',
        //                             text: result.message,
        //                             confirmButtonClass: 'btn btn-success',
        //                         })

        //                     }
        //                 }
        //             });

        //         }
        //     });
        // }
    </script>
@endsection
