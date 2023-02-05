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
                            <h2 class="float-left mb-0">Users</h2>
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
                        <a href="{{ url('/users/create') }}" class="btn btn-primary" title="Add New">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>
                        <form method="GET" action="{{ url('/user') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="feather" data-feather="search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($user as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td><td>{{ $item->email }}</td><td>{{ $item->password }}</td>
                                        <td class="text-center">
                                            {{-- <a href="{{ url('/user/' . $item->id) }}" title="View user"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a> --}}
                                            <a href="{{ url('/users/edit/' . $item->id ) }}" title="Edit user"><button class="btn btn-icon rounded-circle btn-flat-primary waves-effect mr-50"><i class="feather" data-feather="edit" aria-hidden="true"></i></button></a>

                                            <form method="POST" action="{{ url('/users/delete/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-icon rounded-circle btn-flat-danger waves-effect" title="Delete user" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="feather" data-feather="trash" aria-hidden="true"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $user->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
