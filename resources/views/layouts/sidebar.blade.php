<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lock In')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="app-bg">
<div class="app-shell">
    @php
        $currentGroupId = request()->route('group')?->id;
    @endphp

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-top">
            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-mark"></span>
                {{ config('app.name') }}
            </a>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}"
               class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                @svg('heroicon-o-clipboard-document-check', 'w-4 h-4')
                <span>Personal</span>
            </a>
        </nav>

        <div class="sidebar-heading-row">
            <p class="sidebar-heading">Lists</p>
            <button type="button" class="sidebar-add" aria-label="New list"
                    onclick="document.getElementById('group-form-popup').showModal()">
                @svg('heroicon-o-plus', 'w-3.5 h-3.5')
            </button>
        </div>

        <details class="sidebar-group" open>
            <summary>
                @svg('heroicon-o-chevron-down', 'w-3 h-3 chevron')
                My group lists
            </summary>
            @forelse ($ownedGroups ?? [] as $group)
                <a href="{{ route('groups.show', $group) }}"
                   class="sidebar-item sub {{ $currentGroupId === $group->id ? 'active' : '' }}">
                    {{ $group->name }}
                </a>
            @empty
                <span class="sidebar-item sub muted" style="padding-left: 24px; font-size: 0.85rem;">No owned groups yet</span>
            @endforelse
        </details>

        <details class="sidebar-group" open>
            <summary>
                @svg('heroicon-o-chevron-down', 'w-3 h-3 chevron')
                Shared with me
            </summary>
            @forelse ($sharedGroups ?? [] as $group)
                <a href="{{ route('groups.show', $group) }}"
                   class="sidebar-item sub {{ $currentGroupId === $group->id ? 'active' : '' }}">
                    {{ $group->name }}
                    <small class="sidebar-owner">by {{ $group->owner->name ?? 'Unknown' }}</small>
                </a>
            @empty
                <span class="sidebar-item sub muted" style="padding-left: 24px; font-size: 0.85rem;">No shared groups yet</span>
            @endforelse
        </details>

        <div class="sidebar-bottom">
            <div class="trash-card">
                <a href="/tasks/trash"
                   class="sidebar-link sidebar-trash {{ request()->routeIs('tasks.trash') ? 'is-active' : '' }}">
                    @svg('heroicon-o-trash', 'w-4 h-4')
                    <span>Recently Deleted</span>
                    @if (($trashCount ?? 0) > 0)
                        <span class="sidebar-count">{{ $trashCount }}</span>
                    @endif
                </a>
            </div>

            <div class="user-card">
                <span class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                <div class="user-card-text">
                    <strong>{{ Auth::user()->name }}</strong>
                    <small>{{ Auth::user()->email }}</small>
                </div>
            </div>

            <div class="user-actions">
                <a href="{{ route('profile.edit') }}">Edit Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Log out</button>
                </form>
            </div>
        </div>
    </aside>

    <main class="main-content">@yield('content')</main>

    {{-- new list popup --}}
    <dialog id="group-form-popup" class="popup-modal">
        <button type="button" class="close-form" onclick="this.closest('dialog').close()">X</button>
        <h2>Group Lists</h2>
        <form method="POST" action="{{ route('groups.store') }}" class="popup-form">
            @csrf
            <label for="group-name">Owned by Me</label>
            <input type="text" id="group-name" name="name" maxlength="255" required>

            <label>Members (optional)</label>
            <div id="email-inputs-container" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 12px;">
                <div style="display: flex; gap: 8px;">
                    <input type="email" name="emails[]" placeholder="teammate@example.com" style="flex: 1;">
                    <button type="button" class="btn btn-outline" onclick="addEmailField()">Add</button>
                </div>
            </div>

            <button type="submit">Create</button>
        </form>
    </dialog>

</body>
</html>