<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.partials/title-meta', ['title' => 'CMU Palaro Ranking System'])

    @include('layouts.partials/head-css')
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
