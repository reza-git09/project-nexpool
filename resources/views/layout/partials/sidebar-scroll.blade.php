<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">

<style>
    .sidebar .menu a,
    .sidebar .logout a {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sidebar .menu a i,
    .sidebar .logout a i {
        flex: 0 0 24px;
        width: 24px;
        height: 20px;
        color: #9fb3d1;
        font-size: 20px;
        line-height: 1;
        text-align: center;
    }

    .sidebar .menu a:hover i,
    .sidebar .menu a.active i {
        color: #ffffff;
    }

    .sidebar .logout a,
    .sidebar .logout a:hover,
    .sidebar .logout a i {
        color: #f2a3a3;
    }

    .sidebar {
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .menu {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
        overflow-x: hidden;
        overflow-y: auto;
        overscroll-behavior: contain;
        scrollbar-width: thin;
        scrollbar-color: rgba(148, 163, 184, .35) transparent;
    }

    .menu::-webkit-scrollbar {
        width: 5px;
    }

    .menu::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, .35);
        border-radius: 8px;
    }

    .menu > a {
        flex: 0 0 auto;
    }

    .logout {
        position: static;
        flex: 0 0 auto;
        margin-top: 10px;
        padding-top: 12px;
        border-top: 1px solid rgba(255, 255, 255, .08);
    }
</style>
