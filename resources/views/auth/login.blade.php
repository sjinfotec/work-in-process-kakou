@extends('layouts.app')

@section('content')
<div id="loginbg">
    <div id="loginarea" style="text-align:center;">
        <div class="">
            <!--<img src="{{ asset('images/3_logo.png') }}" alt="スムーズシステム" width="25%" height="25%">-->
            Work-in-Process System
        </div>
        
        <div class="inputzone">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @if (count($errors) > 0)
            <div>
                <p style="color:red; background:rgba(255, 230, 200, 0.5)">ログインできませんでした</p>
            </div>
            @endif                  
            <div class="">
                <div class="inputgroup">
                    <div class="text1">
                        <span>ログイン ID</span>
                    </div>
                    <input id="code" type="text" class="formstyle{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value="{{ old('code') }}" required autofocus>
                    <!--
                    @if ($errors->has('code'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('code') }}</strong>
                        </span>
                    @endif
                    -->
                </div>
            </div>
            <div class="">
                <div class="inputgroup">
                    <div class="text1">
                        <span>パスワード</span>
                    </div>
                    <input id="password" type="password" class="formstyle{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                    <!--
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    -->
                </div>
            </div>
            <!--
            <div class="col pb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text font-size-sm line-height-xs label-width-120" id="basic-addon1">アカウント ID</span>
                    </div>
                    <input id="account_id" type="text" class="form-control{{ $errors->has('account_id') ? ' is-invalid' : '' }}" name="account_id" value="{{ old('account_id') }}" required>
                    @if ($errors->has('account_id'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('account_id') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            -->
            <!--
            <div class="col pb-2">
                <div class="custom-control custom-checkbox">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="customCheck">ログインの持続</label>
                </div>
            </div>
            -->
            <div class="col pb-2 mgt20">
                <div class="btn-group d-flex">
                    <button type="submit" class="btn btn-primary btn-lg font-size-rg w-100">ログインする</button>
                </div>
            </div>
            <!--
            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </div>
            -->
        </form>
        </div>
    </div>
</div>
@endsection
