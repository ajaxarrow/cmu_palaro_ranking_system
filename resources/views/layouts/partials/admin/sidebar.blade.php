<!-- Left Sidebar Start -->
<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="/" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/imgs/logo/CMUicon-1.svg" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/imgs/logo/CMUicon-1.svg" alt="" height="50">
                    </span>
                </a>
                <a href="/" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/imgs/logo/CMUicon-1.svg" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/imgs/logo/CMUicon-1.svg" alt="" height="50">
                    </span>
                </a>
            </div>

            <ul id="side-menu">

                <li class="menu-title mt-2">General</li>

                <li>
                    <a href="{{ route('colleges.index')}}" class="tp-link">
                        <i data-feather="book"></i>
                        <span> Colleges </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('sports.index')}}" class="tp-link">
                        <i data-feather="book"></i>
                        <span> Sports </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('medal_weight_categories.index')}}" class="tp-link">
                        <i data-feather="map-pin"></i>
                        <span> Medal Weight Categories </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('teams.index')}}" class="tp-link">
                        <i data-feather="calendar"></i>
                        <span> Teams </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('event_categories.index')}}" class="tp-link">
                        <i data-feather="map-pin"></i>
                        <span> Event Categories </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('medal_records.index')}}" class="tp-link">
                        <i data-feather="map-pin"></i>
                        <span> Tally </span>
                    </a>
                </li>


            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>
<!-- Left Sidebar End -->
