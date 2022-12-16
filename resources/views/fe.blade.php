<!doctype html>
<!-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> -->
<html lang="en">

@include('layouts.header')

<body>
    @include('layouts.nav')

    
    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    @include('layouts.sidebar')

    <!-- scrollUp start here-->
    {{-- <div id="toTop">
        <i class="icofont-simple-up"></i>
    </div> --}}

    <!-- Js files -->
    <script src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('front/js/progresscircle.js') }}"></script>
    <script src="{{ asset('front/js/select2.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('front/js/slick.min.js') }}"></script>
    <script src="{{ asset('front/js/tooltip.js') }}"></script>
    <script src="{{ asset('front/js/main.js') }}"></script>
   



</body>

</html>
