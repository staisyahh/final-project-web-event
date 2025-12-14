{{-- Dashboard --}}
<li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <a href="{{ route('admin.dashboard') }}" class='sidebar-link' wire:navigate>
        <i class="bi bi-grid-fill"></i>
        <span>Dashboard</span>
    </a>
</li>

{{-- Manajemen Event --}}
<li class="sidebar-item has-sub {{ request()->routeIs(['admin.events.*', 'admin.categories.*', 'admin.speakers.*']) ? 'active' : '' }}">
    <a href="#" class='sidebar-link'>
        <i class="bi bi-calendar-event-fill"></i>
        <span>Manajemen Event</span>
    </a>
    <ul class="submenu {{ request()->routeIs(['admin.events.*', 'admin.categories.*', 'admin.speakers.*']) ? 'active' : '' }}">
        <li class="submenu-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <a href="{{ route('admin.events.index') }}" class="submenu-link" wire:navigate>Events</a>
        </li>
        <li class="submenu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a href="{{ route('admin.categories.index') }}" class="submenu-link" wire:navigate>Categories</a>
        </li>
        <li class="submenu-item {{ request()->routeIs('admin.speakers.*') ? 'active' : '' }}">
            <a href="{{ route('admin.speakers.index') }}" class="submenu-link" wire:navigate>Speakers</a>
        </li>
    </ul>
</li>

{{-- Operasional --}}
<li class="sidebar-item has-sub {{ request()->routeIs(['admin.registrations.*', 'admin.check-in.*', 'admin.confirmations.*']) ? 'active' : '' }}">
    <a href="#" class='sidebar-link'>
        <i class="bi bi-gear-wide-connected"></i>
        <span>Operasional</span>
    </a>
    <ul class="submenu {{ request()->routeIs(['admin.registrations.*', 'admin.check-in.*', 'admin.confirmations.*']) ? 'active' : '' }}">
        <li class="submenu-item {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}">
            <a href="{{ route('admin.registrations.index') }}" class="submenu-link" wire:navigate>Registrations / Orders</a>
        </li>
        <li class="submenu-item {{ request()->routeIs('admin.check-in.*') ? 'active' : '' }}">
            <a href="{{ route('admin.check-in.index') }}" class="submenu-link" wire:navigate>Absensi / Check-in</a>
        </li>
        <li class="submenu-item {{ request()->routeIs('admin.confirmations.*') ? 'active' : '' }}">
            <a href="{{ route('admin.confirmations.index') }}" class="submenu-link" wire:navigate>Konfirmasi Pembayaran</a>
        </li>
        <li class="submenu-item {{ request()->routeIs('admin.reminders.*') ? 'active' : '' }}">
            <a href="{{ route('admin.reminders.index') }}" class="submenu-link" wire:navigate>Email Reminder</a>
        </li>
    </ul>
</li>

{{-- Manajemen Pengguna --}}
<li class="sidebar-item has-sub {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
    <a href="#" class='sidebar-link'>
        <i class="bi bi-people-fill"></i>
        <span>Manajemen Pengguna</span>
    </a>
    <ul class="submenu {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <li class="submenu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}" class="submenu-link" wire:navigate>Users</a>
        </li>
    </ul>
</li>

{{-- Konten --}}
<li class="sidebar-item has-sub {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
    <a href="#" class='sidebar-link'>
        <i class="bi bi-file-text-fill"></i>
        <span>Konten</span>
    </a>
    <ul class="submenu {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
        <li class="submenu-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <a href="{{ route('admin.reviews.index') }}" class="submenu-link" wire:navigate>Reviews</a>
        </li>
    </ul>
</li>

{{-- Pengaturan --}}
<li class="sidebar-item has-sub {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
    <a href="#" class='sidebar-link'>
        <i class="bi bi-person-fill-gear"></i>
        <span>Pengaturan</span>
    </a>
    <ul class="submenu {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
        <li class="submenu-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
            <a href="#" class="submenu-link" wire:navigate>Profile</a>
        </li>
    </ul>
</li>
