<!-- Topbar Start -->
<div class="topbar-custom">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li>
                    <button class="button-toggle-menu nav-link">
                        <i data-feather="menu" class="noti-icon"></i>
                    </button>
                </li>
                <li class="d-none d-lg-block">
                    <h5 class="mb-0">Good Morning, {{ Auth::user()->name }}</h5>
                </li>
            </ul>

            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        {{-- <img  alt="user-image" class="rounded-circle"> --}}
                       <div class="d-flex align-items-center gap-2">
                        <div class="mt-3" style="line-height: 0px; ">
                            <p style="font-weight: 500;">
                                {{ $user->name }}
                            </p>
                            <p style="font-size: 12px">{{ ucfirst($user->role) }}</p>
                            </div>
                            <i class="mdi mdi-chevron-down"></i>
                       </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                        <!-- item-->
                        {{-- <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome !</h6>
                        </div> --}}

                        {{-- <!-- item-->
                        <a href="{{route('profile.edit')}}" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-circle-outline fs-16 align-middle"></i>
                            <span>My Account</span>
                        </a> --}}


                        {{-- <div class="dropdown-divider"></div> --}}
                        <form id="logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item notify-item">
                                <i class="mdi mdi-logout fs-16 align-middle"></i>
                                <span>Logout</span>
                            </button>
                        </form>

                    </div>
                </li>

            </ul>
        </div>

    </div>

</div>
<!-- end Topbar -->
