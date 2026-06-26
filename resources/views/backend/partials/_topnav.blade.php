<style>
a#themeToggleBtn,
a#fullscreenBtn {
    font-size: 16px;
    color: rgb(16, 146, 179);
    cursor: pointer;
}

.header_iner {
    padding: 10px 20px;
}

.nav_icon_wrap {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(166, 90, 138, 0.08);
    transition: background 0.2s;
    cursor: pointer;
}

.nav_icon_wrap:hover {
    background: rgba(166, 90, 138, 0.18);
}

.nav_icon_wrap i {
    font-size: 16px;
    color: rgb(73, 122, 187);
}

.admin_picture {
    width: 38px;
    height: 38px;
    object-fit: cover;
    border: 2px solid rgba(53, 132, 206, 0.4);
}
</style>

<!-- menu -->
<div class="container-fluid g-0">
    <div class="row">
        <div class="col-lg-12 p-0">
            <div class="header_iner d-flex justify-content-between align-items-center">

                {{-- Mobile sidebar toggle --}}
                <div class="sidebar_icon d-lg-none">
                    <div class="nav_icon_wrap">
                        <i class="ti-menu"></i>
                    </div>
                </div>

                {{-- Desktop sidebar toggle --}}
                <div class="line_icon open_miniSide d-none d-lg-block">
                    <div class="nav_icon_wrap">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>

                {{-- Right side --}}
                <div class="header_right d-flex align-items-center gap-2">

                    {{-- Fullscreen --}}
                    <div class="nav_icon_wrap fullscreen-toggle">
                        <a id="fullscreenBtn">
                            <i class="ti-fullscreen"></i>
                        </a>
                    </div>

                    {{-- Divider --}}
                    <div style="width:1px; height:24px; background: rgba(166, 90, 138, 0.2);"></div>

                    {{-- Profile --}}
                    <div class="profile_info d-flex align-items-center gap-2">
                        <div class="profile_thumb" style="position:relative;">
                            @php
                                $profilePhoto = Auth::user()->profile_photo_url;
                                $Photo = Auth::user()->avatar_path;
                            @endphp
                            @if ($Photo == null)
                                <img class="img-xs rounded-circle admin_picture" src="{{ $profilePhoto }}"
                                    alt="" />
                            @else
                                <img class="img-xs rounded-circle admin_picture"
                                    src="{{ asset("uploads/profileImages/$Photo") }}" alt="" />
                            @endif

                            {{-- Online dot --}}
                            <span style="
                                position:absolute; bottom:1px; right:1px;
                                width:9px; height:9px; border-radius:50%;
                                background:#28a745; border:2px solid white;
                            "></span>
                        </div>

                        <div class="d-none d-lg-block">
                            <p class="mb-0 fw-semibold" style="font-size:13px; color: rgba(166, 90, 138, 1); line-height:1.2;">
                                {{ Auth::user()->name }}
                            </p>
                            <small class="text-muted" style="font-size:11px;">Administrator</small>
                        </div>

                        <div class="profile_info_iner card">
                            <div class="profile_author_name">
                                <h5>{{ Auth::user()->name }}</h5>
                            </div>
                            <div class="profile_info_details">
                                <a href="{{ route('admin.profile.show') }}">My Profile</a>
                                <form method="POST" action="{{ route('logout') }}" x-data class="d-inline">
                                    @csrf
                                    <button @click.prevent="$root.submit();"
                                        class="btn text-sm text-danger text-decoration-none p-0 m-0 align-baseline">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <hr class="m-0 p-0">
</div>
<!--/ menu -->
