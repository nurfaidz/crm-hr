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
                                    <h2 class="brand-text text-primary ml-1">RECOVERY ACCOUNT</h2>
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

                                <form class="auth-login-form mt-2" action="{{ route('forgotpass') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="login-email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="login-email" name="email" placeholder="john@example.com"
                                            value="{{ old('email') }}" aria-describedby="login-email" tabindex="1"
                                            autofocus required autocomplete="email" />
                                    </div>

                                    <button class="btn btn-primary btn-block" tabindex="4" type="submit">Send</button>
                                </form>

                                <div class="divider">
                                    <hr>
                                </div>

                                <a class="mb-2" href="{{ route('login') }}"><small>Back to login</small></a>
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
