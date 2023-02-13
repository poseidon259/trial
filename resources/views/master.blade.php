<!DOCTYPE html>
<html lang="en">

<header>
    <title>Master Blade- @yield('title')</title>
    @yield('css')
</header>

<body>
    @include('content.header')

    @yield('content')

    @include('content.footer')

    @yield('script')

</body>

</html>