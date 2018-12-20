@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('注册新商户') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{url('myMerchantRegister')}}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('商户名称') }}</label>

                            <div class="col-md-6">
                                <input id="merchantName" type="text" class="form-control{{ $errors->has('merchantName') ? ' is-invalid' : '' }}" name="merchantName" value="{{ old('merchantName') }}" required autofocus>

                                @if ($errors->has('merchantName'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('merchantName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('密钥') }}</label>

                            <div class="col-md-6">
                                <input id="payKey" type="text" class="form-control{{ $errors->has('payKey') ? ' is-invalid' : '' }}" name="payKey" value="{{ old('payKey') }}" required autofocus>

                                @if ($errors->has('payKey'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('payKey') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="cellNo" class="col-md-4 col-form-label text-md-right">{{ __('登录手机号') }}</label>

                            <div class="col-md-6">
                                <input id="cellNo" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="cellNo" value="{{ old('cellNo') }}" required autofocus>

                                @if ($errors->has('cellNo'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cellNo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="verifyCellNo" class="col-md-4 col-form-label text-md-right">{{ __('验证手机号') }}</label>

                            <div class="col-md-6">
                                <input id="verifyCellNo" type="text" class="form-control{{ $errors->has('verifyCellNo') ? ' is-invalid' : '' }}" name="verifyCellNo" value="{{ old('verifyCellNo') }}" required autofocus>

                                @if ($errors->has('verifyCellNo'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('verifyCellNo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('邮箱') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <!--
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                         -->
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('是否为代理') }}</label>

                            <div class="col-md-6 btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="options" id="option1" autocomplete="off" checked> 否
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="options" id="option3" autocomplete="off"> 是
                                </label>
                                
                            </div>
                            
                            <div class="col-md-14">
                            <button type="submit" class="btn btn-primary">
                                    {{ __('注册') }}
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
