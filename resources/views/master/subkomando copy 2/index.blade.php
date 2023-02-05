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
                            <h2 class="content-header-title float-left mb-0">Master Data Sub Komando</h2>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modal-form"><i data-feather="plus"></i> Sub Komando</button>
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
                                            <table class="table" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Sub Komando</th>
                                                        <th>Angkatan</th>
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


    <div class="modal fade text-left" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Create New Sub Komando</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_data" class="form-data-validate" novalidate>
                    {{csrf_field()}}
                    <input type="hidden" name="id_subkomando"  id="id_subkomando" value="">
                    <div class="modal-body">
                        
                        <label>Komando: </label>
                        <div class="form-group">
                            <select class="select2-data-ajax form-control" id="id_komando" name="id_komando"></select>
                        
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please Select Komando.</div>
                        </div>

                        <label>Sub Komando Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Angkatan Name" name="nama_subkomando" id="nama_subkomando" required class="form-control" />
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter Sub Komando Name.</div>
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

@section("page_js")
<script src="{{url('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{url('app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
@endsection

@section("page_script")
<script>
    $(document).ready(function(){

        $.ajax({
        url: "{{url('master/komando/select')}}",
        method: "GET",
        dataType: "json",
        success: function (result) {

            if ($('#id_komando').data('select2')) {

                $("#id_komando").val("");
                $("#id_komando").trigger("change");
                $('#id_komando').empty().trigger("change");

            }

            
            $("#id_komando").select2({ data: result.data });

        }
        });

        var dt_ajax_table=$("#datatables-ajax");
        console.log(dt_ajax_table.length);
        if (dt_ajax_table.length) {
            var dt_ajax = dt_ajax_table.dataTable({
            processing: true,
            dom:
                '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            ajax: "{{ url('master/subkomando/list')}}",
            language: {
                paginate: {
                // remove previous & next text from pagination
                previous: '&nbsp;',
                next: '&nbsp;'
                },
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'nama_subkomando',
                    name: 'Sub-Komando'
                },
                {
                    data: 'id_komando',
                    name: 'Komando'
                },
                {
                    data: 'action',
                    name: 'action'
                },

                //   {data: 'phone', name: 'phone'},
            ],
        });
        }
        
     
    });

   
    Array.prototype.filter.call($('#form_data'), function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          form.classList.add('invalid');
        }
        form.classList.add('was-validated');
        event.preventDefault();
        
        let id_komando =$("#id_subkomando").val();
        // console.log($('#form_data').serialize())

        var url = ( id_komando !== undefined && id_komando !== null) && id_komando ? "{{url('master/subkomando/update')}}"+"/"+id_komando : "{{url('master/subkomando/store')}}";
        
        $.ajax({
            url: url,
            type: 'post',
            data: $('#form_data').serialize(),
            // contentType: 'application/json',
            processData: false,
            success: function (response) {

                if (response.error) {

                    Swal.fire(
                        {
                            type: "error",
                            title: 'Oops...',
                            text: response.message,
                            confirmButtonClass: 'btn btn-success',
                        }
                    );

                } else {

                    setTimeout(function () { $('#datatables-ajax').DataTable().ajax.reload(); }, 1000);

                    Swal.fire(
                        {
                            type: "success",
                            title: 'Success!',
                            text: response.message,
                            confirmButtonClass: 'btn btn-success',
                        }
                    );

                }
                var reset_form = $('#form_data')[0];
                $(reset_form).removeClass('was-validated');
                reset_form.reset();
                $('#modal-form').modal('hide');
                $("#modal_title").html("Create New Komando")
                $("#id_subkomando").val()

            },
      });
       
    });
     
 });


 function edit_data(e) {

    $('#modal-form').modal('show');

    $.ajax({
        url: "{{url('master/subkomando/edit')}}" +"/"+ e.attr('data-id'),
        method: "GET",
        dataType: "json",
        success: function (result) {
            
            $("#modal_title").html("Edit Sub Komando")
            $('#nama_subkomando').val(result.data.nama_subkomando);
            $('#id_komando').val(result.data.id_komando).trigger('change');
            $('#id_subkomando').val(result.data.id_subkomando);

        }
    });
}

 function delete_data(e){
  
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

  }).then(function (result) {

    if (result.value) {

       var id = e.attr('data-id');

      jQuery.ajax({
          url: "{{url('master/subkomando/delete/')}}"+"/"+id,
          type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                '_method': 'delete'
            },
          success: function(result) {

            if(result.error){

              Swal.fire({
                type: "error",
                title: 'Oops...',
                text: result.message,
                confirmButtonClass: 'btn btn-success',
              })

            }else{

              setTimeout(function () { $('#datatables-ajax').DataTable().ajax.reload(); }, 1000);

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