
@extends('layouts.auth')

@section('content')

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-3">
                            <label for="username" class="col-form-label text-md-right">{{ __('Employee ID') }}</label>
                            </div>
                            <div class="col-md-9">
                                <input id="text" type="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3">
                            <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>
                            </div>
                            <div class="col-md-9">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="submit" class="form-control btn btn-primary" id="btn_login">
                                    {{ __('Login') }}
                                </button>
                             </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12" style="color:red">
                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </form>
                        <h4 style="text-align:center;"><a href="/password/reset" class="btn btn-success">Forgot Password?</a></h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
