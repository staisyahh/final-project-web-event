{{-- Jelajah Event (Home) --}}
<li class="sidebar-item {{ request()->routeIs('home') ? 'active' : '' }}">
    <a href="/" class='sidebar-link'>
        <i class="bi bi-compass"></i>
        <span>Jelajah Event</span>
    </a>
</li>

{{-- Tiket Saya --}}
<li class="sidebar-item {{ request()->routeIs('member.tiket') ? 'active' : '' }}">
    <a href="{{ route('member.tiket') }}" class='sidebar-link' wire:navigate>
        <i class="bi bi-ticket-perforated-fill"></i>
        <span>Tiket Saya</span>
    </a>
</li>

{{-- Bookmark/Favorit --}}
<li class="sidebar-item {{ request()->routeIs('member.bookmarks') ? 'active' : '' }}">
    <a href="{{ route('member.bookmarks') }}" class='sidebar-link' wire:navigate>
        <i class="bi bi-bookmark-heart-fill"></i>
        <span>Favorit</span>
    </a>
</li>
