<!-- Left Sidebar Start -->
<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box mt-3">
                <a href="/" class="d-flex align-items-center gap-1">
                    <i class="ph-duotone fs-24 ph-ranking" ></i>
                    <span class="fs-18" style="font-weight: 500;">
                        Ranking System
                    </span>
                </a>
            </div>

            <ul id="side-menu">

                <li class="menu-title mt-2">General</li>

                <li>
                    <a href="{{ route('colleges.index')}}" class="tp-link">
                        <i class="ph-duotone fs-18 ph-chalkboard" ></i>
                        <span> Colleges </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('sports.index')}}" class="tp-link">
                        <i class="ph-duotone fs-18 ph-racquet" ></i>
                        <span> Sports </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('medal_weight_categories.index')}}" class="tp-link">
                        <i class="ph-duotone fs-18 ph-medal" ></i>
                        <span> Medal Weight Categories </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('teams.index')}}" class="tp-link">
                        <i class="ph-duotone fs-18 ph-flag-banner" ></i>
                        <span> Teams </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('event_categories.index')}}" class="tp-link">
                        <i class="ph-duotone fs-18 ph-flag-checkered" ></i>
                        <span> Event Categories </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('medal_records.index')}}" class="tp-link">
                        <i class="ph-duotone fs-18 ph-medal-military" ></i>
                        <span> Tally </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('results.index')}}" class="tp-link">
                        <i class="ph-duotone fs-18 ph-trophy" ></i>
                        <span> Results </span>
                    </a>
                </li>

                @if(Auth::user()->role == 'admin')
                <li class="menu-title mt-2">Management</li>

                <li>
                    <a href="{{ route('users.index')}}" class="tp-link">
                        <i class="ph-duotone fs-18 ph-users" ></i>
                        <span> Users </span>
                    </a>
                </li>
                @endif

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>
<!-- Left Sidebar End -->
