@extends('partials.template')
@section('main')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="float-left mb-0">Event</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group text-right mb-2">
                <button type="button" class="btn btn-outline-primary">Export Excell</button>
            </div>
            <div class="content-body">
                <!-- Basic table -->
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('/events/create') }}" class="btn btn-success" title="Add New event">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>
                        <form method="GET" action="{{ url('/event') }}" accept-charset="UTF-8"
                            class="form-inline my-2 my-lg-0 float-right" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..."
                                    value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="ficon" data-feather="search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table mt-2">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Start</th>
                                        <th>Nama Event</th>
                                        <th>Tempat Event</th>
                                        <th>Finish</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($event as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            @php
                                                $newLocale = setlocale(LC_TIME, 'en');
                                            @endphp
                                            <td>{{ Carbon\Carbon::parse($item->start)->formatLocalized('%d %B %Y') }}</td>
                                            <td>{{ $item->nama_event }}</td>
                                            <td>{{ $item->tempat_event }}</td>
                                            <td>{{ Carbon\Carbon::parse($item->finish)->formatLocalized('%d %B %Y') }}</td>
                                            <td class="text-center">
                                                {{-- <a href="{{ url('/event/' . $item->id) }}" title="View event"><button class="btn btn-info btn-sm mr-50"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a> --}}
                                                <a href="{{ url('/events/edit/' . $item->id) }}"
                                                    title="Edit Event"><button class="btn btn-icon btn-icon rounded-circle btn-flat-primary waves-effect mr-50"><i class="feather" data-feather="edit" aria-hidden="true"></i></button>
                                                </a>
                                                <form method="POST" action="{{ url('/events/delete/' . $item->id) }}"
                                                    accept-charset="UTF-8" style="display:inline">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-icon btn-icon rounded-circle btn-flat-danger waves-effect" title="Delete Event"
                                                        onclick="return confirm(&quot;Confirm delete?&quot;)"><i
                                                        class="feather" data-feather="trash" aria-hidden="true"></i></button>
                                                </form>

                                                    {{-- <button class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" data-placement="right" title data-original-title="Delete">Delete</button>

                                                    <!-- Modal Delete -->
                                                    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="exampleModal1_{{  $item->id }}" role="dialog" tabindex="-1">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        Delete Confirmation
                                                                    </h5>
                                                                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>You can't undo this action. Are you sure to delete this <b>{{ $item->nama_event }}</b> account?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <a href="{{ url('/events/delete/' . $item->id) }}" class="btn btn-danger">Delete</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $event->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
