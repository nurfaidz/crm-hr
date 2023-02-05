@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="float-left mb-0">Edit Permission</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-2">
                <a href="{{ url('/permissions/index') }}"><button type="button" class="btn btn-outline-primary"><i
                            class="ficon mr-75" data-feather="chevron-left"></i>Back</button></a>
            </div>
            <div class="content-body">
                {{-- @include('admin.sidebar') --}}
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <form method="POST" action="{{ url('/permissions/update/' . $permission->id) }}"
                            accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @include ('permissions.permission.form', ['formMode' => 'edit'])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
