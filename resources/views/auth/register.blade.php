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
                    <!-- Register v1 -->
                    <div class="card mb-0">
                        <div class="card-body">
                            <a href="javascript:void(0);" class="brand-logo">                                
                                <h2 class="brand-text text-primary ml-1">SIGN UP</h2>
                            </a>

                            <form class="auth-register-form mt-2" action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="register-username" class="form-label">Username</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="johndoe" aria-describedby="register-username" tabindex="1" autofocus required autocomplete="name" autofocus/>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="register-email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="john@example.com" aria-describedby="register-email" tabindex="2" />

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="register-password" class="form-label">Password</label>

                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input type="password" class="form-control form-control-merge @error('password') is-invalid @enderror" id="password" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="register-password" tabindex="3" required autocomplete="new-password"/>
                                        <div class="input-group-append">
                                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password-confirm" class="form-label">Confirm Password</label>

                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input type="password" class="form-control form-control-merge" id="password-confirm" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="register-password" tabindex="3" required autocomplete="new-password"/>
                                        <div class="input-group-append">
                                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="register-privacy-policy" tabindex="4" />
                                        <label class="custom-control-label" for="register-privacy-policy">
                                            I agree to <a href="javascript:void(0);">privacy policy & terms</a>
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block" tabindex="5">Sign up</button>
                            </form>

                            <p class="text-center mt-2">
                                <span>Already have an account?</span>
                                <a href="{{ route('login') }}">
                                    <span>Sign in</span>
                                </a>
                            </p>

                            <div class="divider my-2">
                                <div class="divider-text">or</div>
                            </div>

                            <div class="auth-footer-btn d-flex justify-content-center">
                                <a href="javascript:void(0)" class="btn btn-facebook">
                                    <i data-feather="facebook"></i>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-twitter white">
                                    <i data-feather="twitter"></i>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-google">
                                    <i data-feather="mail"></i>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-github">
                                    <i data-feather="github"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /Register v1 -->
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content-->
@endsection