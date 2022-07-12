<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- STYLE CSS -->
    {{-- <link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet" /> --}}
    @include('frontend.section.head')
</head>

<body class="app sidebar-mini">
    <!-- GLOBAL-LOADER -->
    {{-- <div id="global-loader">
        <img src="{{ URL::asset('assets/images/loader.svg') }}" class="loader-img" alt="Loader">
    </div> --}}
    <!-- /GLOBAL-LOADER -->
    <!-- PAGE -->
    @include('frontend.section.header')
    @yield('content')
    @include('frontend.section.footer')
    @include('frontend.section.footer-scripts')

</body>

</html>
