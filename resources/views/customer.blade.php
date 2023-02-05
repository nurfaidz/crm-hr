@extends('partials.template') 
@section('main')   
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                 <!-- Line Chart Card -->
                 <div class="row">
					<h3>Data Customer</h3>
					<table border="1">
						<tr>
							<th>nama</th>
							<th>alamat</th>
							<th>nohp</th>
							<th>email</th>
							<th>facebook</th>
							<th>instagram</th>
							<th>whatsapp</th>
							<th>company</th>
						</tr>
						@foreach($customer as $c)
						<tr>
							<td>{{ $c->nama }}</td>
							<td>{{ $c->alamat }}</td>
							<td>{{ $c->nohp }}</td>
							<td>{{ $c->email }}</td>
							<td>{{ $c->facebook }}</td>
							<td>{{ $c->instagram }}</td>
							<td>{{ $c->whatsapp }}</td>
							<td>{{ $c->company }}</td>
						</tr>
						@endforeach
					</table>
                </div>
                <!--/ Line Chart Card -->
                              
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection    
				
	{{-- <style type="text/css">
		.pagination li{
			float: left;
			list-style-type: none;
			margin:5px;
		}
	</style>
<div class="home_content">
<h3>Data Customer</h3>


	<table border="1">
		<tr>
			<th>nama</th>
			<th>alamat</th>
			<th>nohp</th>
			<th>email</th>
			<th>facebook</th>
			<th>instagram</th>
			<th>whatsapp</th>
			<th>company</th>
		</tr>
		@foreach($customer as $c)
		<tr>
			<td>{{ $c->nama }}</td>
			<td>{{ $c->alamat }}</td>
			<td>{{ $c->nohp }}</td>
			<td>{{ $c->email }}</td>
			<td>{{ $c->facebook }}</td>
			<td>{{ $c->instagram }}</td>
			<td>{{ $c->whatsapp }}</td>
			<td>{{ $c->company }}</td>
		</tr>
		@endforeach
	</table> --}}

	{{-- <br/>
	Halaman : {{ $customer->currentPage() }} <br/>s
	Jumlah Data : {{ $customer->total() }} <br/>
	Data Per Halaman : {{ $customer->perPage() }} <br/>


	{{ $customer->links() }} --}}

{{-- </div>
			
	@endsection --}}