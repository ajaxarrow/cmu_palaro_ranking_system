<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.partials/title-meta', ['title' => 'CMU Palaro Ranking System'])

    @include('layouts.partials/head-css')
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@1&display=swap" rel="stylesheet">
    @vite(['resources/css/layout.css'])
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
</head>

<body data-menu-color="light" data-sidebar="default">

    <div id="app-layout">

        @include('layouts.partials/admin/topbar')
        @include('layouts.partials/admin/sidebar')

        <div class="content-page">

            <div class="content">

                @yield('content')

            </div>

            @include('layouts.partials/footer')

        </div>

    </div>

    @include('layouts.partials/vendor')

</body>

</html>
