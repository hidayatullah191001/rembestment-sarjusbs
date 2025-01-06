<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">   
    <title>{{ App\Helpers\MyHelper::getSetting('app_name') }} | @yield('title')</title>
    @include('includes.style')
    @stack('addon-style')
</head>

<body>
    <div class="container-scroller">
        @include('includes.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('includes.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                   @yield('content')
                </div>
                @include('includes.footer')
            </div>
        </div>
    </div>
    @include('includes.script')
    @stack('addon-script')
</body>
</html>
