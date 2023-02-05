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
            <h2 class="content-header-title float-left mb-0">Bank</h2>
          </div>
        </div>
      </div>
      <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <button type="button" id="add-bank" class="btn btn-outline-primary" data-toggle="modal"
          data-target="#modal-form"><i data-feather="plus"></i> Add Bank</button>
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
                          <th>Bank Name</th>
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
        <h4 class="modal-title" id="modal_title">Add Bank</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_data" class="form-data-validate" novalidate>
        {{csrf_field()}}
        <input type="hidden" name="bank_id" id="bank_id" value="">
        <div class="modal-body">
          <label>Bank Name : </label>
          <div class="form-group">
            <input type="text" placeholder="ex. BCA" name="bank_name" id="bank_name" required class="form-control" />
            <div class="invalid-feedback bank_name_error"></div>
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
  $("#modal-form").on("hidden.bs.modal", function (e) {
    const reset_form = $('#form_data')[0];
    const reset_form_edit = $('#form_edit_data')[0];
    $(reset_form).removeClass('was-validated');
    $(reset_form_edit).removeClass('was-validated');
    let uniqueField = ["bank_name"]
    for (let i = 0; i < uniqueField.length; i++) {
      $("#" + uniqueField[i]).removeClass('was-validated');
      $("#" + uniqueField[i]).removeClass("is-invalid");
      $("#" + uniqueField[i]).removeClass("invalid-more");
    }
  });
  $(document).ready(function () {
    // reset form setelah klik edit
    document.getElementById("add-bank").addEventListener("click", function () {
      document.getElementById("form_data").reset();
      $("#modal_title").html("Add Data bank");
      document.getElementById("bank_id").value = null;
    });


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
        dom:
          '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: "{{ url('bank')}}",
        scrollX: 'true',
        resposie: 'true',
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
            data: 'bank_name',
            name: 'Bank Name'
          },
          {
            data: 'action',
            name: 'action'
          },
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

      let bank_id = $("#bank_id").val();

      var url = (bank_id !== undefined && bank_id !== null) && bank_id ? "{{ url('bank')}}" + "/" + bank_id : "{{ url('bank')}}";
      $.ajax({
        url: url,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'post',
        data: $('#form_data').serialize(),
        // contentType: 'application/json',
        processData: false,
        success: function (response) {
          console.log(response)
          if (response.error) {
            $.each(response.error, function (prefix, val) {
              $('div.' + prefix + '_error').text(val[0]);
            });
            let uniqueField = ["bank_name"]
            for (let i = 0; i < uniqueField.length; i++) {
              let error = (response.error.hasOwnProperty(uniqueField[i])) ? true : false
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
            setTimeout(function () { $('#datatables-ajax').DataTable().ajax.reload(); }, 1000);
            Swal.fire(
              {
                type: "success",
                title: 'Success!',
                text: response.message,
                confirmButtonClass: 'btn btn-success',
              }
            );
            var reset_form = $('#form_data')[0];
            $(reset_form).removeClass('was-validated');
            reset_form.reset();
            $('#modal-form').modal('hide');
            $("#modal_title").html("Add bank")
            $("#bank_id").val()
          }
        },
        error: function (xhr) {
          console.log(xhr.responseText);
        }
      });

    });

  });


  function edit_data(e) {

    $('#modal-form').modal('show')
    var url = "{{url('bank')}}" + "/" + e.attr('data-id') + "/" + "edit"
    $.ajax({
      url: url,
      method: "GET",
      // dataType: "json",
      success: function (result) {
        $("#modal_title").html("Edit Bank Name")
        $('#bank_id').val(result.bank_id).trigger('change');
        $('#bank_name').val(result.bank_name);
      },
      error: function (xhr) {
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

    }).then(function (result) {

      if (result.value) {

        var id = e.attr('data-id');
        jQuery.ajax({
          url: "{{url('/bank/delete')}}" + "/" + id,
          type: 'post',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
            '_method': 'delete'
          },
          success: function (result) {

            if (result.error) {

              Swal.fire({
                type: "error",
                title: 'Oops...',
                text: result.message,
                confirmButtonClass: 'btn btn-success',
              })

            } else {

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