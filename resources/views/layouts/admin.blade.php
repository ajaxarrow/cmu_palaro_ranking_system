<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.partials/title-meta', ['title' => 'CMU Palaro Ranking System'])

    @include('layouts.partials/head-css')
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@1&display=swap" rel="stylesheet">
    @vite(['resources/css/layout.css'])
    @vite(['resources/css/override.css'])

    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ag-grid/33.0.3/ag-grid-community.min.js" integrity="sha512-2bPPlED0cr8Mg0om7YNIyFkU1Uwsh+FPmDeoCW8QkHKnex+AIGbGXFqI7g7Bz6+TbvLQFpr7PJoZcWPWjzaNqA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-grid.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@33.1.1/styles/ag-theme-alpine.min.css">


<script src="https://cdn.jsdelivr.net/npm/ag-grid-enterprise@33.1.1/dist/ag-grid-enterprise.min.js"></script>
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
    @vite(['resources/js/pages/datatable.init.js'])


</body>

</html>
