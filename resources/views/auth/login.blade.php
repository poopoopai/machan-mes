@extends('layouts.app')
@section('css')
    <style>
        .card-header, .card-body {
            margin-left: 5%;
            background-color: rgba(0, 102, 204, 0.5);
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 50px;
            font-weight: bold;
            color: #ffffff;
        }
        .form-control {
            background-color:#ffffff;
        }
        .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>    
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="panel panel-default login-panel">
                <div class="card-body" style="width:80%; " >
                    <div class="title">Mes平台</div>
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-2">
                                <input id="account" type="text" class="form-control" name="account" value="{{ old('account') }}" required autofocus placeholder="{{ __('帳號') }}">

                                 @if ($errors->has('account'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('account') }}</strong>
                                    </span>
                                @endif 
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-2">
                                <input id="password" type="password" class="form-control" name="password" required placeholder="{{ __('密碼') }}">

                                 @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-2">
                                <button type="submit" class="btn btn-secondary btn-block">
                                    {{ __('登入') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
@endsection
