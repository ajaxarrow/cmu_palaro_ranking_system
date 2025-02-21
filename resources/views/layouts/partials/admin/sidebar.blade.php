<!-- Left Sidebar Start -->
<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box mt-3">
                <a href="/">
                    <i data-feather="edit-2"></i>
                    <span class="fs-16 font-bold">
                        Ranking System
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
                <li>
                    <a href="{{ route('results.index')}}" class="tp-link">
                        <i data-feather="map-pin"></i>
                        <span> Results </span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>
<!-- Left Sidebar End -->
