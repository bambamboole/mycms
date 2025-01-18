@php
    $currentUser =auth()->user();
    $currentPage = \Illuminate\Support\Facades\Context::get('current_page');
    $currentPost = \Illuminate\Support\Facades\Context::get('current_post');
    $panel = \Filament\Facades\Filament::getDefaultPanel();
@endphp
@if($currentUser instanceof \Filament\Models\Contracts\FilamentUser && $currentUser->canAccessPanel($panel))
    <style>
        body {
            padding-top: 34px;
        }

        .admin-bar {
            background-color: #333;
            color: #fff;
            padding: 0 5px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 5px 8px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: row;
        }

        .admin-bar a {
            color: #fff;
            text-decoration: none;
            padding: 5px 15px 5px 5px;
            border-right: 2px solid #3f3f3f;
            margin-right: 5px;
        }

        .admin-bar a:hover {
            text-decoration: underline;
        }

        .admin-bar .logo {
            display: inline-block;
            font-weight: 600;
            padding: 5px 10px;
            border-right: 2px solid #3f3f3f;
            margin-right: 5px;
        }

        .admin-bar .icon {
            height: 1rem;
            width: 1rem;
            display: inline-block;
            margin-right: 5px;
        }

        .admin-bar-hide-button {
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            font-size: 1rem;
            margin-right: 10px;
            margin-left: auto;
        }

        .admin-bar-show-button {
            background: none;
            border: none;
            color: #000;
            cursor: pointer;
            font-size: 1rem;
            margin-right: 10px;
            position: fixed;
            right: 5px;
            top: 5px;

            .icon {
                height: 1rem;
                width: 1rem;
                display: inline-block;
                margin-right: 5px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const adminBar = document.getElementById('adminBar');
            const isHidden = localStorage.getItem('adminBarHidden') === 'true';

            if (isHidden) {
                adminBar.style.display = 'none';
            }

            document.querySelector('.admin-bar-hide-button').addEventListener('click', function () {
                adminBar.style.display = 'none';
                localStorage.setItem('adminBarHidden', 'true');
            });
            document.querySelector('.admin-bar-show-button').addEventListener('click', function () {
                adminBar.style.display = 'flex';
                localStorage.setItem('adminBarHidden', 'false');
            });
        });
    </script>

    <div class="admin-bar" id="adminBar">
        <span class="logo">{{config('app.name')}}</span>
            <a href="{{$panel->getUrl()}}">@svg('heroicon-o-wrench-screwdriver', 'icon'){{ __('mycms::general.back-to-dashboard') }}</a>
        @if($currentPage && $currentUser->can('update_page', $currentPage))
            <a href="{{$panel->getResourceUrl($currentPage, 'edit')}}">@svg('heroicon-o-paint-brush', 'icon'){{ __('mycms::general.edit-page') }}</a>
        @endif
        @if($currentPost && $currentUser->can('update_post', $currentPost))
            <a href="{{$panel->getResourceUrl($currentPost, 'edit')}}">@svg('heroicon-o-paint-brush', 'icon'){{ __('mycms::general.edit-post') }}</a>
        @endif
        <button class="admin-bar-hide-button">@svg('heroicon-o-arrow-up', 'icon')</button>
    </div>
    <button class="admin-bar-show-button">@svg('heroicon-o-arrow-down', 'icon')</button>
@endif
