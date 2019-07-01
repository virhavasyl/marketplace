<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="content-language" content="{{ $currentLocale }}">
    <title>BUM Admin</title>

    {{-- Laravel Mix - CSS File --}}
    <link rel="stylesheet" href="{{ mix('admin/css/vendor.css') }}">
    <link rel="stylesheet" href="{{ mix('admin/css/app.css') }}">
  </head>
  <body class="app {{ Auth::check() ? "header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show" : "flex-row align-items-center" }}">
    @if (Auth::check())
      @include('admin::includes.header')
      <div class="app-body">
        @include('admin::includes.sidebar')
        <main class="main">
          {{--@yield('breadcrumb')--}}
          @include('admin::includes.breadcrumb')
          <div class="container-fluid">
            @yield('content')
          </div>
        </main>
      </div>
      @include('admin::includes.footer')
    @else
      @yield('content')
    @endif

    {{-- Flash messages  --}}
    <div class="position-fixed flash-message-container">
      @if(Session::has('info'))
        <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fa fa-exclamation-circle"></i> {{ Session::get('info') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
      @endif
      @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
          <i class="fa fa-check-circle"></i> {{ Session::get('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
          <i class="fa fa-times-circle"></i> {{ Session::get('error') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
    </div>

    <script>
      window.global_translations = {!! Cache::get('global_translations_' . $currentLocale) !!};
      window.admin_translations = {!! Cache::get('admin_translations_' . $currentLocale) !!};
    </script>
    {{-- Laravel Mix - JS File --}}
    <script src="{{ mix('admin/js/app.js') }}"></script>
  </body>
</html>
