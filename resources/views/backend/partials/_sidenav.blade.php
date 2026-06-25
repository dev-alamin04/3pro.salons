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
        {{-- <li class="">
            <a href="/admin/cms-contents" aria-expanded="false" class="active">
                <div class="nav_icon_small">
                    <i class="fas fa-sticky-note"></i>
                </div>
                <div class="nav_title">
                    <span>CMS</span>
                </div>
            </a>
        </li> --}}

        <li class="">
            <hr>
        </li>


        <li class="">
            <a class="has-arrow" href="#" aria-expanded="false">
                <div class="nav_icon_small">
                    <i class="fa-solid fa-gear"></i>
                </div>
                <div class="nav_title">
                    <span>{{ __('sidebar.settings') }}</span>
                </div>
            </a>
            <ul>
                <li> <a href="/admin/profile">{{ __('settings.profile') }}</a> </li>
                <li> <a href="/admin/system-setting">{{ __('settings.system') }}</a> </li>
                <li> <a href="/admin/dynamic-page">{{ __('settings.dynamic_page') }}</a> </li>
                <li> <a href="/admin/mail-setting">{{ __('settings.mail') }}</a> </li>
            </ul>
        </li>
    </ul>
</nav>
<!--/ sidebar  -->
