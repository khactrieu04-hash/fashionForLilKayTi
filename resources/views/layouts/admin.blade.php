@include('layouts.admin-header')
@include('layouts.admin-sidebar')
<div class="content-wrapper">
    @if (Session::has('success'))
        <span id="toast__js" message="{{ session('success') }}" type="success"></span>
    @elseif (Session::has('error'))
        <span id="toast__js" message="{{ session('error') }}" type="error"></span>
    @endif
      <!-- /.content-header -->
    <main id="main" class="main">
      <div class="pagetitle">
        <h1>{{ $title ??'' }}</h1>
      </div>
        @yield('content')
    </main>
</div>
<x-loading />
@vite(['resources/admin/js/toast-message.js'])
@include('layouts.admin-footer')