@extends('partials.template')
@section('meta_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            {{-- <div class="content-header row">
                <div class="dropdown-divider my-1"></div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                    <button type="button" class="btn btn-outline-primary" id="create" data-toggle="modal"
                        data-target="#modal-form"><i data-feather="plus"></i> Add an Announcement</button>
                </div>
            </div> --}}
            <div class="content-body">
                <div class="card">
                    <div class="card-body px-0">
                        <div class="row breadcrumbs-top m-2">
                            <h2 class="content-header-title float-left mb-1">Announcements</h2>
                        </div>
                        <div class="dropdown-divider my-1"></div>
                        <div class="row mx-2">
                            <div class="col-12">
                                <div class="card">
                                    @if (count($announcements) != 0)
                                        @foreach ($announcements as $key => $announcement)
                                            <div class="d-flex">
                                                <div class="flex-column col-8 justify-content-start pr-5">
                                                    <div class="row mb-0">
                                                        <h4>{{ $announcement->announcement_title }}</h4>
                                                    </div>
                                                    <div class="row justify-content-start mt-0" style="color:#82868B">
                                                        <p>Published by
                                                            <strong>{{ $first_name[$key] }}</strong>
                                                            <strong>{{ $last_name[$key] }}</strong>,
                                                            {{ date('d F Y', strtotime($announcement->published_at)) }}
                                                        </p>
                                                    </div>
                                                    <div class="dropdown-divider my-1"></div>
                                                    <div class="row-fluid justify-content-start mb-1">
                                                        {{-- {!! $announcement->announcement_content !!} --}}
                                                        {!! Str::words("$announcement->announcement_content", 55, ' ...') !!}
                                                    </div>
                                                    <div class="row">
                                                        <button onclick=view_data($(this))
                                                            data-id="{{ $announcement->announcement_id }}" id="view"
                                                            class="align-self-end btn btn-sm btn-primary px-3 mt-auto mr-auto">View</button>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="row justify-content-end">
                                                        <img class="img-fluid" src="{{ $image_url[$key++] }}" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-divider my-1"></div>
                                        @endforeach
                                        <div class="d-flex justify-content-center">
                                            {{ $announcements->links() }}
                                        </div>
                                    @else
                                        <div class="row justify-content-center">
                                            <h5>There is no Announcements</h5>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
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
                    <img class="img-fluid pb-1" name="announcement_image" id="announcement_image" />
                    <div name="announcement_content" id="announcement_content"></div>
                    <label class="file pt-1" for="Files">Files</label><br>
                    <div class="btn btn-outline-primary" style="overflow: visible; width: fit-content">
                        <div class="row align-items-center">
                            <div class="col">
                                <img src="/img/icons/logo-file.svg" alt="">
                            </div>
                            <div class="col">
                                <div class="row">
                                    <a name="file_name" id="file_name" style="white-space: nowrap;"></a>
                                </div>
                                <div class="row">
                                    <a name="announcement_attachment" id="announcement_attachment"
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
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>

@section('page_script')
    <script>
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
                    $('#announcement_content').html(str).text();
                    $('#announcement_image').attr("src", result.image_url);
                    $('#file_name').html(result.file_name);
                    $('#announcement_attachment').html(result.file_name_ext);
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
    </script>
@endsection
