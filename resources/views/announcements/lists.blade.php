@extends('partials.template')
@section('meta_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .ck-editor__editable {
            min-height: 200px;
        }
    </style>
@endsection
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
                                        <h3>Announcement Lists</h3>
                                        <div class="card-datatable table-responsive table-rounded-outline">
                                            <table class="table table-borderless" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>ID</th>
                                                        <th>Title</th>
                                                        <th>Publish Date</th>
                                                        <th>Content</th>
                                                        <th>Status</th>
                                                        <th class="text-center">Publish</th>
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

    <div class="modal fade text-left" id="modal-view" tabindex="-1" role="dialog" aria-labelledby="modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header pb-0 pt-2">
                    <h3 name="announcement_title" id="announcement_title"></h3>
                    <button type="button" class="close mr-0 mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="px-2 mt-0 pb-1" name="published_at" id="published_at" style="background-color: #f8f8f8"></div>
                <div class="dropdown-divider my-0 mx-2"></div>
                <div class="modal-body justify-content-start">
                    <input type="hidden" name="announcement_id" id="announcement_id">
                    <input type="hidden" name="announcement_status" id="announcement_status" value="Published">
                    <img class="img-fluid pb-1" name="announcement_image" id="announcement_image_view" />
                    <div name="announcement_content_view" id="announcement_content_view"></div>
                    <label class="file pt-1" for="Files">Files</label><br>
                    <div class="btn btn-outline-primary" style="overflow: visible;">
                        <div class="row align-items-center">
                            <div class="col">
                                <img src="/img/icons/logo-file.svg" alt="">
                            </div>
                            <div class="col">
                                <div class="row">
                                    <a name="file_name" id="file_name" style="white-space: nowrap;"></a>
                                </div>
                                <div class="row">
                                    <a name="announcement_attachment" id="announcement_attachment_view"
                                        style="font-weight: 400; white-space: nowrap;"></a>
                                </div>
                            </div>
                            <div class="col">
                                <div id="file_size" style="font-weight: 400; white-space: nowrap;"></div>
                            </div>
                            <div class="col">
                                <div class="btn-group">
                                    <div id="menu_option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img src="/img/icons/three-dots.svg" alt="">
                                    </div>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" type="button" id="open_file">Open File</button>
                                        <div class="dropdown-divider mx-1 my-0"></div>
                                        <button class="dropdown-item" type="button" id="download_file"
                                            download>Download</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Add an Announcement</h4>
                    <button type="button" class="close mr-0 mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_data" class="form-data-validate" action="{{ url('/announcement/store') }}" novalidate
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="announcement_id" id="announcement_id_edit" value="">
                    <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="announcement_status" id="announcement_status" value="Unpublished">
                    <input type="hidden" name="published_at" id="published_at" value="{{ null }}">
                    <div class="modal-body">
                        <label>Title:</label>
                        <div class="form-group">
                            <input type="text" placeholder="Announcement Title" name="announcement_title"
                                id="announcement_title_edit" value="" required autofocus class="form-control" />
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter an announcement title.</div>
                        </div>
                        <label>Content:</label>
                        <div class="form-group">
                            <textarea name="announcement_content" id="announcement_content" required class="form-control">
                            </textarea>
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter your content.</div>
                        </div>
                        <label>Image:</label>
                        <div class="form-group">
                            <img class="img-fluid" id="announcement_image_old" />
                            <div class="dropdown-divider mb-2"></div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="announcement_image"
                                    id="announcement_image" class="form-control">
                                <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                            </div>
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter an announcement image.</div>
                        </div>
                        <label>Attachment:</label>
                        <div class="form-group">
                            <a name="attachment" id="announcement_attachment_old"></a>
                            <div class="dropdown-divider mb-2"></div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="announcement_attachment"
                                    id="announcement_attachment" class="form-control">
                                <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                            </div>
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter announcement attachment.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="spinner-border" id="spinner_submit" role="submit"><span
                                class="sr-only">Loading...</span></div>
                        <button type="submit" id="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.5/dataRender/datetime.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/classic/ckeditor.js"></script>
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
        let YourEditor;
        ClassicEditor
            .create(document.querySelector('#announcement_content'))
            .then(editor => {
                window.editor = editor;
                YourEditor = editor;
            })


        $("#modal-form").on("hidden.bs.modal", function(e) {
            const reset_form = $('#form_data')[0];
            const reset_form_edit = $('#form_edit_data')[0];

            $(reset_form).removeClass('was-validated');
            $(reset_form_edit).removeClass('was-validated');
            $(".ck-blurred p").html("");
            $('.custom-file-label').html('');
            $('#announcement_id').val('');
            $("#announcement_title_edit").attr("value", "");
            $('#announcement_content_view').html('');
            $('#announcement_attachment_old').html('');
            $('#announcement_image_old').removeAttr('src');
            YourEditor.setData('');
        });

        $(document).ready(function() {
            $("#create").click(function() {
                $("#form_data").trigger("reset");
                $("#modal-title").html("Add Data Announcement");
                $("#announcement_id").val("");
            });
            $('#spinner_submit').hide();
            const dt_ajax_table = $("#datatables-ajax");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#add-announcement.btn btn-md btn-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('announcements') }}",
                    language: {
                        paginate: {
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        },
                    },
                    scrollX: true,
                    columnDefs: [{
                        targets: [1, 4],
                        visible: false,
                        searchable: false
                    }, {
                        targets: [5, 6],
                        className: 'text-center'
                    }, {
                        targets: [3],
                        type: 'html',
                        render: function(data, type, row) {
                            return $('<td />').html(data).text();
                        }
                    }],
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'announcement_id',
                            name: 'announcement_id'
                        },
                        {
                            data: 'announcement_title',
                            name: 'announcement_title'
                        },
                        {
                            data: 'published_at',
                            name: 'published_at',
                            render: function(data) {
                                if (data == '01/01/1970') {
                                    return '';
                                } else {
                                    return data;
                                }
                            }
                        },
                        {
                            data: 'announcement_content',
                            name: 'announcement_content'
                        },
                        {
                            data: 'announcement_status',
                            name: 'announcement_status',
                            render: function(data) {
                                if (data == 'Published') {
                                    $status =
                                        '<span class="badge badge-pill badge-sm badge-success" id="status">' +
                                        data +
                                        '</span>';
                                } else {
                                    $status =
                                        '<span class="badge badge-pill badge-sm badge-danger" id="status">' +
                                        data +
                                        '</span>';
                                }
                                return $status;
                            },
                        },
                        {
                            data: 'announcement_status',
                            name: 'announcement_status',
                            render: function(data, type, row) {
                                if (data == 'Published') {
                                    $publishBtn = '<div class="spinner-border" data-id="' + row
                                        .announcement_id +
                                        '" role="status" hidden><span class="sr-only">Loading...</span></div><input id="check" class="check" role="button" type="checkbox" value="' +
                                        data +
                                        '" data-id="' + row
                                        .announcement_id +
                                        '" onclick=publish_data($(this)) checked>';
                                } else {
                                    $publishBtn = '<div class="spinner-border" data-id="' + row
                                        .announcement_id +
                                        '" role="status" hidden><span class="sr-only">Loading...</span></div><input id="check" class="check" role="button" type="checkbox" value="' +
                                        data +
                                        '" data-id="' + row
                                        .announcement_id +
                                        '" onclick=publish_data($(this))>';
                                }
                                return $publishBtn;
                            },
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                });
                if ($('#status').val('Published')) {
                    $('#check').attr('checked');
                    $('#status').attr('class', 'btn btn-sm btn-primary')
                }
            }

            $("#add-announcement").html('Add Announcement');
            $("#add-announcement").attr('style', 'margin-bottom: 7px');
            $("#add-announcement").attr('data-toggle', 'modal');
            $("#add-announcement").attr('data-target', '#modal-form');
        });

        Array.prototype.filter.call($('#form_data'), function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    form.classList.add('invalid');
                }
                form.classList.add('was-validated');
                event.preventDefault();

                let announcement_id = $("#announcement_id_edit").val();
                // console.log($('#form_data').serialize())

                var url = (announcement_id !== undefined && announcement_id !== null) &&
                    announcement_id ?
                    "{{ url('announcements/update') }}" + "/" + announcement_id :
                    "{{ url('announcements/store') }}";

                var data = new FormData(this);
                $('#submit').hide();
                $('#spinner_submit').show();

                setTimeout(function() {
                    $('#submit').show();
                    $('#spinner_submit').hide();
                    $.ajax({
                        url: url,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.error) {

                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: response.message,
                                    confirmButtonClass: 'btn btn-success',
                                });

                            } else {

                                setTimeout(function() {
                                    $('#datatables-ajax').DataTable().ajax
                                        .reload();

                                }, 1000);

                                Swal.fire({
                                    type: "success",
                                    title: 'Success!',
                                    text: response.message,
                                    confirmButtonClass: 'btn btn-success',
                                });

                            }
                            var reset_form = $('#form_data')[0];
                            $(reset_form).removeClass('was-validated');
                            reset_form.reset();
                            $('#modal-form').modal('hide');
                            $("#modal-title").html("Add Data Announcement");
                            $("#announcement_id").val();

                        },
                    });
                }, 1500);

            });

        });

        function publish_data(e) {
            // $('.spinner-border').show();
            var id = e.attr('data-id');
            $('.check').hide();
            $("div [class='spinner-border'][data-id='" + id + "']").removeAttr('hidden');
            $.ajax({
                url: "{{ url('announcements/publish') }}" + "/" + e.attr('data-id'),
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(result) {
                    $('#datatables-ajax').DataTable().ajax.reload();
                }
            });
        }

        function edit_data(e) {

            $('#modal-form').modal('show');

            $.ajax({
                url: "{{ url('announcements/edit') }}" + "/" + e.attr('data-id'),
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(result) {
                    var str = result.data.announcement_content;
                    $("#modal-title").html("Edit Data Announcement");
                    $("#announcement_id_edit").val(result.data.announcement_id);
                    $("#announcement_title_edit").attr("value", result.data.announcement_title);
                    YourEditor.setData(str);
                    $('#announcement_image_old').attr("src", result.image_url);
                    $('#announcement_attachment_old').attr("href", result.file_url);
                    $('#announcement_attachment_old').html(result.file_name);
                    $('#announcement_attachment_old').click(function(e) {
                        e.preventDefault(); //stop the browser from following
                        window.open(result.file_url, '_blank');
                    });
                }
            });
        }

        function view_data(e) {

            $('#modal-view').modal('show');

            $.ajax({
                url: "{{ url('announcements/edit') }}" + "/" + e.attr('data-id'),
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(result) {
                    Number.prototype.formatBytes = function() {
                        var units = [' B', ' KB', ' MB', ' GB', ' TB'],
                            bytes = this,
                            i;

                        for (i = 0; bytes >= 1024 && i < 4; i++) {
                            bytes /= 1024;
                        }

                        return bytes.toFixed(2) + units[i];
                    }
                    var str = result.data.announcement_content;
                    if (result.file_name == null) {
                        $('#modal-view > div > div > div.modal-body.justify-content-start > label').html('');
                        $('#modal-view > div > div > div.modal-body.justify-content-start > div.btn.btn-outline-primary.waves-effect')
                            .hide();
                    } else {
                        $('#modal-view > div > div > div.modal-body.justify-content-start > label').html(
                            'Files');
                        $('#modal-view > div > div > div.modal-body.justify-content-start > div.btn.btn-outline-primary.waves-effect')
                            .show();
                    };
                    $('#published_at').html('Published by <b>' + result.published_by + '</b><br/> ' + result
                        .date);
                    $('#announcement_id').val(result.data.announcement_id);
                    $('#announcement_title').html(result.data.announcement_title);
                    $('#announcement_content_view').html(str).text();
                    $('#announcement_image_view').attr("src", result.image_url);
                    $('#file_name').html(result.file_name);
                    $('#announcement_attachment_view').html(result.file_name_ext);
                    $('#file_size').html(result.file_size.formatBytes());
                    $('#file_name').html(result.file_name);
                    $('#download_file').click(function(e) {
                        e.preventDefault(); //stop the browser from following
                        window.open(result.file_url, '_blank');
                    });
                    $('#open_file').click(function(e) {
                        e.preventDefault(); //stop the browser from following
                        window.open(result.file_url, '_blank');
                    });
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
                        url: "{{ url('announcements/delete') }}" + "/" + id,
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
