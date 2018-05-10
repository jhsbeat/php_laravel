@extends('layouts.app')

@section('content')
  <form action="{{ route('users.store') }}" method="post" class="form__auth">
    {!! csrf_field() !!}

    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
      <input type="text" name="name" class="form-control" placeholde="이름" value="{{ old('name') }}" autofocus />
      {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
      <input type="email" name="email" class="form-control" placeholder="{{ trans('auth.form.email') }}" value="{{ old('email') }}"/>
      {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
      <input type="password" name="password" class="form-control" placeholder="{{ trans('auth.form.password') }}"/>
      {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
      <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('auth.form.password_confirmation') }}" />
      {!! $errors->first('password_confirmation', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <button class="btn btn-primary btn-lg btn-block" type="submit">
        가입하기
      </button>
    </div>
  </form>
@stop