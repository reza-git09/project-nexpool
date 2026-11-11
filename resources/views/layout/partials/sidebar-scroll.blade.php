<style>
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
