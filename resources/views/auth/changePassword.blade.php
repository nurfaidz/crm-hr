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

			<div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                            <h2 class="content-header-title float-left mb-10">Change Password</h2>
                        </div>
                    </div>
                </div>
		
		<div class="card-body">
		@if (session('error'))
		<div class="alert alert-danger">
		{{ session('error') }}
		</div>
		@endif
		@if (session('success'))
		<div class="alert alert-success">
		{{ session('success') }}
		</div>
		@endif
		<form class="form-horizontal" method="POST" action="{{ route('changePassword') }}">
		{{ csrf_field() }}
		
		<div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
		<label for="new-password" class="col-md-4 control-label">Current Password</label>
		
		<div class="col-md-6">
		<input id="current-password" type="password" class="form-control" name="current-password" required>
		
		@if ($errors->has('current-password'))
		<span class="help-block">
		<strong>{{ $errors->first('current-password') }}</strong>
		</span>
		@endif
		</div>
		</div>
		
		<div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
		<label for="new-password" class="col-md-4 control-label">New Password</label>
		
		<div class="col-md-6">
		<input id="new-password" type="password" class="form-control" name="new-password" required>
		
		@if ($errors->has('new-password'))
		<span class="help-block">
		<strong>{{ $errors->first('new-password') }}</strong>
		</span>
		@endif
		</div>
		</div>
		
		<div class="form-group">
		<label for="new-password-confirm" class="col-md-4 control-label">Confirm New Password</label>
		
		<div class="col-md-6">
		<input id="new-password-confirm" type="password" class="form-control" name="new-password-confirm" required>
		</div>
		</div>
		
		<div class="form-group">
		<div class="col-md-6 col-md-offset-4">
		<button type="submit" class="btn btn-primary">
		Change Password
		</button>
		</div>
		</div>
		</form>
		</div>
		</div>
		</div>
		</div>
		</div>
		@endsection