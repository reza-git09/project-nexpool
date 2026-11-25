<style>
    .delete-confirm-overlay {
        position: fixed;
        inset: 0;
        z-index: 11000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 23, 42, 0.58);
        backdrop-filter: blur(4px);
    }

    .delete-confirm-overlay.is-open {
        display: flex;
    }

    .delete-confirm-dialog {
        width: min(100%, 430px);
        padding: 28px;
        border: 1px solid #e8ecf3;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 24px 70px rgba(15, 23, 42, 0.24);
        animation: delete-confirm-enter 0.18s ease-out;
    }

    .delete-confirm-icon {
        display: flex;
        width: 52px;
        height: 52px;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        border-radius: 15px;
        background: #fef2f2;
        color: #dc2626;
        font-size: 20px;
    }

    .delete-confirm-title {
        margin-bottom: 8px;
        color: #172033;
        font-size: 19px;
        font-weight: 700;
    }

    .delete-confirm-message {
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .delete-confirm-note {
        margin-top: 10px;
        color: #94a3b8;
        font-size: 12px;
        line-height: 1.5;
    }

    .delete-confirm-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 25px;
    }

    .delete-confirm-button {
        min-height: 40px;
        padding: 0 16px;
        border: 1px solid transparent;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.18s ease, transform 0.18s ease;
    }

    .delete-confirm-cancel {
        border-color: #e2e8f0;
        background: #fff;
        color: #475569;
    }

    .delete-confirm-cancel:hover {
        background: #f8fafc;
    }

    .delete-confirm-submit {
        background: #dc2626;
        color: #fff;
    }

    .delete-confirm-submit:hover {
        background: #b91c1c;
        transform: translateY(-1px);
    }

    .delete-confirm-button:focus-visible {
        outline: 3px solid #93c5fd;
        outline-offset: 2px;
    }

    @keyframes delete-confirm-enter {
        from {
            opacity: 0;
            transform: translateY(8px) scale(0.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @media (max-width: 480px) {
        .delete-confirm-dialog {
            padding: 23px;
        }

        .delete-confirm-actions {
            flex-direction: column-reverse;
        }

        .delete-confirm-button {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .delete-confirm-dialog {
            animation: none;
        }
    }
</style>
