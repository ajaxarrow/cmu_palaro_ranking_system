<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.partials/title-meta', ['title' => 'CMU Palaro Ranking System'])
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/palaroLogo.png') }}">
    @include('layouts.partials/head-css')
    @vite(['resources/css/layout.css'])
    @vite(['resources/css/override.css'])
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script> --}}


</head>

<body data-menu-color="light" data-sidebar="default">
    <!-- Success Snackbar -->
    @if (session('success'))
        <div id="successToast" class="toast align-items-center text-white border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 1055; background-color: #02681e;">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif

     <!-- Success Snackbar -->
     @if (session('error'))
        <div id="errorToast" class="toast align-items-center text-white border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 1055; background-color:rgb(104, 2, 2);">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif


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

 @vite(['resources/js/pdf-export.js'])


    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var successToastEl = document.getElementById('successToast');
        var errorToastEl = document.getElementById('errorToast');

        if (successToastEl) {
            var successToast = new bootstrap.Toast(successToastEl);
            successToast.show();
        }

        if (errorToastEl) {
            var errorToast = new bootstrap.Toast(errorToastEl);
            errorToast.show();
        }
    });
</script>

</body>

</html>
