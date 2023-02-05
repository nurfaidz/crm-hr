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
                        <div class="card mb-0 test">
                            <div class="card-body">
                                <a href="javascript:void(0);" class="brand-logo">
                                    <img class="imagelogo" src="{{ url('img/background/logo.svg') }}">
                                </a>

                                @if (session('status'))
                                    <div class=" alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class=" alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <form class="auth-login-form mt-4" action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="login-email" class="form-label">
                                            <h2 class="brand-text text-primary ml-auto">SIGN IN</h2>
                                        </label>
                                        @if (session('message'))
                                            <div class="input-group">
                                                <input type="email" class="line form-control @error('email') is-invalid @enderror" id="login-email" name="email" placeholder="Email ID" value="{{ session('error-email') }}" aria-describedby="login-email" tabindex="1" autofocus required autocomplete="email" style="border-bottom: 1px solid #ea5455">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="border: none; border-bottom: 1px solid #ea5455; border-radius: 0">
                                                        <img src="{{ url('img/icons/alert-circle-red.svg') }}" alt="Alert" width="16">
                                                    </span>
                                                </div>
                                            </div>
                                            <span style="color: #ea5455; font-size: 12px">{{ session('message') }}</span>
                                        @else
                                            <input type="email" class="line form-control @error('email') is-invalid @enderror" id="login-email" name="email" placeholder="Email ID" value="{{ old('email') }}" aria-describedby="login-email" tabindex="1" autofocus required autocomplete="email">
                                        @endif
                                        @if ($errors->has('email'))
                                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input type="password" class="line form-control form-control-merge @error('password') is-invalid @enderror" id="login-password" required autocomplete="current-password" name="password" tabindex="2" placeholder="Password" aria-describedby="login-password" />
                                            <div class="input-group-append">
                                                <span class="eye input-group-text cursor-pointer logo"><i data-feather="eye"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input rememberme" type="checkbox" name="remember" id="remember-me" tabindex="3" {{ old('remember') ? 'checked' : '' }}/>
                                                <label class="custom-control-label" for="remember-me"> Remember Me </label>
                                            </div>
                                        </div>
                                        <a class="ml-auto" href="{{ route('forgotpass')}}">
                                            <small>Forgot Password?</small>
                                        </a> 
                                    </div>
    
                                    <button class="btn btn-primary btn-block mb-2" tabindex="4" type="submit">Sign in</button>
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