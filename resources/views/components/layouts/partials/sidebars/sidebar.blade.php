<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="/">
                        <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" style="height: 3rem" srcset="">
                        <span class="text-primary text-md">VentNice</span>
                    </a>
                </div>

                {{-- Desktop Toggle --}}
                {{-- <div class="sidebar-toggler d-none d-xl-block" @click="isSidebarMinimized = !isSidebarMinimized">
                    <a href="#"><i class="bi bi-circle-fill"
                            x-bind:class="isSidebarMinimized ? 'bi-circle-fill' : 'bi-record-circle-fill'"></i></a>
                </div> --}}

                {{-- Mobile Toggler --}}
                <div class="sidebar-toggler x d-xl-none d-block">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                {{-- SWITCH MENU BERDASARKAN ROLE --}}
                @auth
                @if(Auth::user()->role === 'admin')
                @include('components.layouts.partials.sidebars.admin-sidebar')
                @elseif(Auth::user()->role === 'peserta')
                @include('components.layouts.partials.sidebars.peserta-sidebar')
                @endif
                @else
                {{-- Menu untuk Guest (Login) --}}
                <li class="sidebar-item {{ request()->routeIs('login') ? 'active' : '' }}">
                    <a href="{{ route('login') }}" class='sidebar-link' wire:navigate>
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Login</span>
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</div>
