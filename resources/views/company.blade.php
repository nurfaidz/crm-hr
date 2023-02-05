{{-- @extends('partials.template')
@section('main')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-body">
        <div class="row">
			
			<div class="col-md-12">
                <div class="card">
                    <div class="card-header">Customer</div>
                    <div class="card-body">
                        <a href="{{ url('/companys/create') }}" class="btn btn-success btn-sm" title="Add New customer">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

						<form method="GET" action="{{ url('/admin/company') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>

						<br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Nohp</th>
                                        <th>email</th>
							            <th>website</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($company as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->alamat }}</td>
                                        <td>{{ $item->nohp }}</td>
                                        <td>{{ $item->email }}</td>
							            <td>{{ $item->website }}</td>
                                        <td>
											<a href="{{ url('/companys/edit/' . $item->id ) }}" title="Edit company"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                            <br/>

                                            <form method="POST" action="{{ url('/companys/delete' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete company" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $company->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- END: Content-->
@endsection  --}}


















<!DOCTYPE html>
<html>
<head>
	<title>Company</title>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

	<div class="container">
		<div class="card mt-5">
			<div class="card-body">
				<h1 class="text-center my-4">Company</h1>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>nama</th>
                            <th>alamat</th>
                            <th>telepon</th>
                            <th>email</th>
                            <th>website</th>
						</tr>
					</thead>
					<tbody>
						@foreach($company as $c)
						<tr>
							<td>{{ $c->nama }}</td>
							<td>{{ $c->alamat }}</td>
							<td>{{ $c->telepon }}</td>
							<td>{{ $c->email }}</td>
							<td>{{ $c->website }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

</body>
</html>