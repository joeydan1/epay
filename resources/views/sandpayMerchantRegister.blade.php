@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('注册新商户') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{url('sandpayMerchantRegister')}}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('杉德商户号') }}</label>

                            <div class="col-md-6">
                                <input id="mid" type="text" class="form-control{{ $errors->has('mid') ? ' is-invalid' : '' }}" name="mid" value="{{ old('mid') }}" required autofocus>

                                @if ($errors->has('mid'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('mid') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('商户号') }}</label>

                            <div class="col-md-6">
                                <input id="memberId" type="text" class="form-control{{ $errors->has('memberId') ? ' is-invalid' : '' }}" name="memberId" value="{{ old('memberId') }}" required autofocus>

                                @if ($errors->has('memberId'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('memberId') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="priKey" class="col-md-4 col-form-label text-md-right">{{ __('商户私钥') }}</label>

                            <div class="col-md-6">
                                <input id="priKey" type="text" class="form-control{{ $errors->has('priKey') ? ' is-invalid' : '' }}" name="priKey" value="{{ old('priKey') }}" required autofocus>

                                @if ($errors->has('priKey'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('priKey') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cellNo" class="col-md-4 col-form-label text-md-right">{{ __('私钥密码') }}</label>

                            <div class="col-md-6">
                                <input id="pubKey" type="text" class="form-control{{ $errors->has('certPwd') ? ' is-invalid' : '' }}" name="certPwd" value="{{ old('certPwd') }}" required autofocus>

                                @if ($errors->has('pubKey'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('certPwd') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                       

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-10">
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
