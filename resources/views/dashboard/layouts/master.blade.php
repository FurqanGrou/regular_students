<!DOCTYPE html>
<html class="loading" lang="ar" data-textdirection="rtl">
<head>

    @include('dashboard.layouts.head-links')

</head>

<body class="vertical-layout vertical-menu 2-columns fixed-navbar pace-done menu-collapsed" data-open="click" data-menu="vertical-menu" data-col="2-columns">
   @include('dashboard.layouts.header-navbar')

   @include('dashboard.layouts.side-menu')

  <div class="app-content content">
    <div class="content-wrapper">
      <div class="content-header row">
      </div>
      <div class="content-body">

          @yield('content')

      </div>
    </div>
  </div>

  @include('dashboard.layouts.footer')

  @include('dashboard.layouts.footer-links')

</body>
</html>
