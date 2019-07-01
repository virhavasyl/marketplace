<div class="sidebar">
  <nav class="sidebar-nav">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="/">
          <i class="nav-icon icon-speedometer"></i> @lang('admin::sidebar.dashboard')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ LaravelLocalization::getLocalizedURL(null, '/categories') }}">
          <i class="nav-icon icon-folder-alt"></i> @lang('admin::sidebar.categories')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ LaravelLocalization::getLocalizedURL(null, '/products') }}">
          <i class="nav-icon icon-handbag"></i> @lang('admin::sidebar.products')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ LaravelLocalization::getLocalizedURL(null, '/attributes') }}">
          <i class="nav-icon icon-tag"></i> @lang('admin::sidebar.attributes')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/">
          <i class="nav-icon icon-basket-loaded"></i> @lang('admin::sidebar.orders')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/">
          <i class="nav-icon icon-credit-card"></i> @lang('admin::sidebar.payments')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ LaravelLocalization::getLocalizedURL(null, '/locations') }}">
          <i class="nav-icon icon-location-pin"></i> @lang('admin::sidebar.locations')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ LaravelLocalization::getLocalizedURL(null, '/roles') }}">
          <i class="nav-icon icon-magic-wand"></i> @lang('admin::sidebar.roles')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ LaravelLocalization::getLocalizedURL(null, '/users') }}">
          <i class="nav-icon icon-people"></i> @lang('admin::sidebar.users')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/">
          <i class="nav-icon icon-speech"></i> @lang('admin::sidebar.reviews')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/">
          <i class="nav-icon icon-bubbles"></i> @lang('admin::sidebar.chat')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/">
          <i class="nav-icon icon-docs"></i> @lang('admin::sidebar.pages')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/">
          <i class="nav-icon icon-envelope-open"></i> @lang('admin::sidebar.mailing')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/">
          <i class="nav-icon icon-question"></i> @lang('admin::sidebar.user_questions')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/">
          <i class="nav-icon icon-ghost"></i> @lang('admin::sidebar.complaints')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ LaravelLocalization::getLocalizedURL(null, '/currencies') }}">
          <i class="nav-icon fa fa-money"></i> @lang('admin::sidebar.currencies')
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ LaravelLocalization::getLocalizedURL(null, '/settings') }}">
          <i class="nav-icon icon-settings"></i> @lang('admin::sidebar.settings')
        </a>
      </li>
    </ul>
  </nav>
  <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>