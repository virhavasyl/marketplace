@extends('admin::layouts.master')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card-group">
          <div class="card p-4">
            <div class="card-body">
              <h1>@lang('admin::login.header')</h1>
              <p class="text-muted">@lang('admin::login.description')</p>
              <form method="POST" action="{{ LaravelLocalization::getLocalizedURL(null, route('login.post')) }}">
                @csrf
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="icon-user"></i>
                    </span>
                  </div>
                  <input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" name="email" value="{{ old('email') }}" placeholder="@lang('admin::field.email')" autofocus />
                  @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                  @endif
                </div>
                <!-- /.input-group -->
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="icon-lock"></i>
                    </span>
                  </div>
                  <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" placeholder="@lang('admin::field.password')">
                  @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('password') }}</strong>
                    </span>
                  @endif
                </div>
                <!-- /.input-group -->
                <div class="input-group mb-4">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                      @lang('admin::field.remember_me')
                    </label>
                  </div>
                </div>
                <!-- /.input-group -->
                <div class="row">
                  <div class="col-12 text-center">
                    <button class="btn btn-primary px-4" type="submit">@lang('admin::button.login')</button>
                  </div>
                </div>
                <!-- /.row -->
              </form>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.card-group -->
      </div>
      <!-- /.col-md-5 -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->
@stop
