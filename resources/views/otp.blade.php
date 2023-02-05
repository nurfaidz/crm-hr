@extends('layouts.app')

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-v1 px-2">
                    <div class="auth-inner py-2">
                        <!-- Login v1 -->
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="javascript:void(0);" class="brand-logo">
                                    <h2 class="brand-text text-primary ml-1">Reset Password</h2>
                                </a>

                                @error('password')
                                    <div class="alert alert-danger">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <form class="auth-login-form mt-2" action="{{ route('resetpass') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <div class="form-group">
                                        <label for="login-email" class="form-label">New Password</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input type="password"
                                                class="form-control form-control-merge @error('password') is-invalid @enderror"
                                                id="login-password" required autocomplete="current-password" name="password"
                                                tabindex="2"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="login-password" />
                                            {{-- @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror --}}
                                            {{-- <div class="input-group-append">
                                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div> --}}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="login-email" class="form-label">Confirm Password</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input type="password"
                                                class="form-control form-control-merge @error('password') is-invalid @enderror"
                                                id="login-password" required autocomplete="current-password"
                                                name="password_confirmation" tabindex="2"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="login-password" />
                                        </div>
                                    </div>


                                    <button class="btn btn-primary btn-block" tabindex="4" type="submit">Change
                                        Password</button>
                                </form>
                            </div>
                        </div>
                        <!-- /Login v1 -->
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
