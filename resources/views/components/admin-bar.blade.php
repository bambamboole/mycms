<style>
    body {
        padding-top: 50px;
    }
    .admin-bar {
        background-color: #333;
        color: #fff;
        padding: 5px;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .admin-bar a {
        color: #fff;
        text-decoration: none;
        &:hover{
        text-decoration: underline;

        }
    }
    .admin-bar .logo {
        display: inline-block;
        padding-left: 10px;
        padding-right: 10px;
    }
    .admin-bar .icon {
        height: 1rem;
        width: 1rem;
        display: inline-block;
        margin-right: 5px;
    }
</style>

<div class="admin-bar">
    <span class="logo">MyCMS</span>
    <a href="/admin">@svg('heroicon-o-link', 'icon')Back to Dashboard</a>
</div>
