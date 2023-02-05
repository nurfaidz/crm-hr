<!DOCTYPE html>
<html>
<head>
	<title>Event</title>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

	<div class="container">
		<div class="card mt-5">
			<div class="card-body">
				<h1 class="text-center my-4">Event</h1>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>start</th>
                            <th>nama event</th>
                            <th>tempat event</th>
                            <th>finish</th>
						</tr>
					</thead>
					<tbody>
						@foreach($event as $c)
						<tr>
							<td>{{ $c->start }}</td>
							<td>{{ $c->nama_event }}</td>
							<td>{{ $c->tempat_event }}</td>
							<td>{{ $c->finish }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

</body>
</html>