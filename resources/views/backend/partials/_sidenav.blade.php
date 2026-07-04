<!-- sidebar  -->
<nav id="sidebar" class="sidebar dark_sidebar">

    <!-- Logo  -->
    <div class="logo d-flex justify-content-center">
        <a class="large_logo" href="{{ route('admin.dashboard') }}"><img
                src="{{ asset($systemSetting->logo ?? 'backend/assets/images/logo-default.png') }}" alt="" /></a>
        <a class="small_logo" href="{{ route('admin.dashboard') }}">
            <img class="rounded-circle"
                src="{{ asset($systemSetting->favicon ?? 'backend/assets/images/logo-default.png') }}" alt="logo"
                style="height: 50px;width:50px;" />
        </a>

        <div class="sidebar_close_icon d-lg-none">
            <i class="ti-close"></i>
        </div>
    </div>
      <ul id="sidebar_menu" class="mt-5">
        <li class="">
            <a href="/admin/dashboard" aria-expanded="false" class="active">
                <div class="nav_icon_small">
                    <i class="fas fa-home"></i>
                </div>
                <div class="nav_title">
                    <span>Dashboard</span>
                </div>
            </a>
        </li>
        <li class="">
            <a href="/admin/users" aria-expanded="false" class="active">
                <div class="nav_icon_small">
                    <i class="fas fa-users"></i>
                </div>
                <div class="nav_title">
                    <span>User Management</span>
                </div>
            </a>
        </li>
        <li class="">
            <a href="{{ route('salons.index') }}" aria-expanded="false">
                <div class="nav_icon_small">
                    <i class="fas fa-store"></i>
                </div>
                <div class="nav_title">
                    <span>Salon Management</span>
                </div>
            </a>
        </li>        <li class="">
            <a href="{{ route('onboardings.index') }}" aria-expanded="false">
                <div class="nav_icon_small">
                    <i class="fas fa-list-check"></i>
                </div>
                <div class="nav_title">
                    <span>Onboarding</span>
                </div>
            </a>
        </li>

        <li class="">
            <a href="{{ route('admin.goals.index') }}" aria-expanded="false">
                <div class="nav_icon_small">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="nav_title">
                    <span>Goals</span>
                </div>
            </a>
        </li>

        <li class="">
            <a href="{{ route('admin.reports.index') }}" aria-expanded="false">
                <div class="nav_icon_small">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="nav_title">
                    <span>Reports</span>
                </div>
            </a>
        </li>

        <li class="">
            <a href="{{ route('admin.badges.index') }}" aria-expanded="false">
                <div class="nav_icon_small">
                    <i class="fas fa-award"></i>
                </div>
                <div class="nav_title">
                    <span>Badges</span>
                </div>
            </a>
        </li>

        <li class="">
            <a href="{{ route('admin.team.index') }}" aria-expanded="false">
                <div class="nav_icon_small">
                    <i class="fas fa-users-gear"></i>
                </div>
                <div class="nav_title">
                    <span>Team Management</span>
                </div>
            </a>
        </li>

        <li class="">
            <a href="{{ route('admin.tasks.index') }}" aria-expanded="false">
                <div class="nav_icon_small">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="nav_title">
                    <span>Daily Tasks</span>
                </div>
            </a>
        </li>

        <li class="">
            <a class="has-arrow" href="#" aria-expanded="false">
                <div class="nav_icon_small">
                    <i class="fa-solid fa-gear"></i>
                </div>
                <div class="nav_title">
                    <span>Settings</span>
                </div>
            </a>
            <ul>
                <li> <a href="/admin/profile">Profile</a> </li>
                <li> <a href="/admin/system-setting">System Settings</a> </li>
                <li> <a href="/admin/dynamic-page">Dynamic pages</a> </li>
                <li> <a href="/admin/mail-setting">Mail Settings</a> </li>
            </ul>
        </li>
    </ul>
</nav>
<!--/ sidebar  -->
