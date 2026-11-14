@extends('layout.admin_master')

@section('title', 'Cairkan Saldo')
@section('header-title', 'Cairkan Saldo')
@section('header-subtitle', 'Periksa pengajuan dan konfirmasi transfer dana')

@push('styles')
<style>
    .withdrawal-notice, .withdrawal-alert {
        margin-bottom: 18px;
        padding: 14px 17px;
        border-radius: 10px;
        font-size: 13px;
        line-height: 1.5;
    }
    .withdrawal-notice { color: #1e40af; background: #eff6ff; border: 1px solid #bfdbfe; }
    .alert-success { color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; }
    .alert-error { color: #991b1b; background: #fef2f2; border: 1px solid #fecaca; }
    .withdrawal-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e8ecf3;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
    }
    .table-scroll { overflow-x: auto; }
    .withdrawal-table { width: 100%; min-width: 760px; border-collapse: collapse; }
    .withdrawal-table th, .withdrawal-table td {
        padding: 15px 17px;
        border-bottom: 1px solid #eef2f7;
        text-align: left;
        vertical-align: middle;
        font-size: 13px;
    }
    .withdrawal-table th {
        color: #64748b;
        background: #f8fafc;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .55px;
    }
    .withdrawal-table td { color: #1e293b; }
    .withdrawal-table tbody tr:last-child td { border-bottom: 0; }
    .primary-cell { display: block; color: #172033; font-weight: 750; }
    .secondary-cell { display: block; margin-top: 4px; color: #64748b; font-size: 11px; }
    .amount-cell { color: #172033; font-size: 14px; font-weight: 800; white-space: nowrap; }
    .copy-account {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 6px;
        padding: 2px 0;
        border: 0;
        color: #2563eb;
        background: transparent;
        font: inherit;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
    }
    .copy-account:hover { color: #1e40af; }
    .copy-account:disabled { color: #64748b; cursor: wait; }
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }
    .status-proses { color: #92400e; background: #fef3c7; }
    .status-berhasil, .status-terkonfirmasi { color: #166534; background: #dcfce7; }
    .status-ditolak { color: #991b1b; background: #fee2e2; }
    .action-cell { min-width: 120px; }
    .action-stack { display: flex; flex-direction: column; align-items: flex-start; gap: 9px; }
    .button-detail, .button-send, .proof-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 34px;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 11px;
        font-weight: 750;
        text-decoration: none;
        cursor: pointer;
    }
    .button-detail { color: #1d4ed8; background: #eff6ff; border: 1px solid #bfdbfe; }
    .button-detail:hover { background: #dbeafe; }
    .button-send { color: #fff; background: #2563eb; border: 0; }
    .button-send:hover { background: #1d4ed8; }
    .button-send:disabled { background: #94a3b8; cursor: wait; }
    .proof-link { min-height: 0; padding: 0; color: #1d4ed8; }
    .proof-link:hover { color: #1e40af; }
    .detail-proof-form { margin-top: 20px; padding-top: 18px; border-top: 1px solid #eef2f7; }
    .detail-proof-form[hidden], .detail-proof-link[hidden] { display: none; }
    .proof-upload-label { display: block; margin-bottom: 8px; color: #334155; font-size: 12px; font-weight: 800; }
    .proof-file-input {
        display: block;
        width: 100%;
        padding: 11px;
        border: 1px dashed #94a3b8;
        border-radius: 9px;
        color: #475569;
        background: #f8fafc;
        font-size: 12px;
    }
    .proof-help { display: block; margin-top: 7px; color: #64748b; font-size: 11px; }
    .proof-form-actions { display: flex; justify-content: flex-end; margin-top: 14px; }
    .empty-state { padding: 40px 20px !important; color: #64748b !important; text-align: center !important; }
    .pagination-wrapper { padding: 16px 20px; border-top: 1px solid #eef2f7; }
    .withdrawal-modal {
        position: fixed;
        inset: 0;
        z-index: 3000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 23, 42, .62);
        backdrop-filter: blur(3px);
    }
    .withdrawal-modal[hidden] { display: none; }
    .detail-card {
        width: min(100%, 560px);
        max-height: min(90vh, 720px);
        overflow-y: auto;
        padding: 25px;
        border: 1px solid #e8ecf3;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .24);
    }
    .detail-heading { display: flex; justify-content: space-between; align-items: flex-start; gap: 15px; margin-bottom: 20px; }
    .detail-heading h2 { margin: 0 0 5px; color: #172033; font-size: 20px; }
    .detail-heading p { margin: 0; color: #64748b; font-size: 12px; }
    .modal-close {
        width: 34px;
        height: 34px;
        flex: 0 0 auto;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        color: #475569;
        background: #fff;
        font-size: 18px;
        cursor: pointer;
    }
    .detail-list { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 0; }
    .detail-item { padding: 13px 14px; border: 1px solid #eef2f7; border-radius: 10px; background: #f8fafc; }
    .detail-item-wide { grid-column: 1 / -1; }
    .detail-item dt { margin-bottom: 6px; color: #64748b; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .45px; }
    .detail-item dd { margin: 0; overflow-wrap: anywhere; color: #172033; font-size: 13px; font-weight: 700; }
    @media (max-width: 600px) {
        .detail-card { padding: 20px; }
        .detail-list { grid-template-columns: 1fr; }
        .detail-item-wide { grid-column: auto; }
    }
</style>
@endpush

@section('content')
<div class="withdrawal-notice">
    Periksa informasi pengajuan melalui tombol <strong>Detail</strong>. Setelah transfer dilakukan di luar sistem,
    pilih bukti transfer JPG, JPEG, atau PNG (maksimal 5 MB), lalu tekan <strong>Kirim</strong> untuk menyimpan bukti
    sekaligus mengonfirmasi pencairan.
</div>

@if (session('success'))
    <div class="withdrawal-alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="withdrawal-alert alert-error">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="withdrawal-alert alert-error">{{ $errors->first() }}</div>
@endif

<div class="withdrawal-card">
    <div class="table-scroll">
        <table class="withdrawal-table">
            <thead>
                <tr>
                    <th>Tanggal Pengajuan</th>
                    <th>Admin Pengaju</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($withdrawals as $withdrawal)
                    @php
                        $requesterName = $withdrawal->requester?->full_name
                            ?: $withdrawal->requester?->name
                            ?: $withdrawal->requester?->username
                            ?: 'Admin kolam';
                    @endphp
                    <tr>
                        <td>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $requesterName }}</td>
                        <td class="amount-cell">Rp {{ number_format((float) $withdrawal->amount, 2, ',', '.') }}</td>
                        <td class="action-cell">
                            <div class="action-stack">
                                <span class="status-badge status-{{ $withdrawal->status }}">{{ $withdrawal->status_label }}</span>
                                <button
                                    class="button-detail"
                                    type="button"
                                    data-withdrawal-detail
                                    data-pool="{{ $withdrawal->pool?->name ?? $withdrawal->pool_id }}"
                                    data-requester="{{ $requesterName }}"
                                    data-bank="{{ $withdrawal->bank_name }}"
                                    data-account="{{ $withdrawal->account_number }}"
                                    data-amount="Rp {{ number_format((float) $withdrawal->amount, 2, ',', '.') }}"
                                    data-status="{{ $withdrawal->status_label }}"
                                    @if ($withdrawal->status === \App\Models\PenarikanSaldo::STATUS_PROSES)
                                        data-proof-upload-url="{{ route('admin.master.penarikan-saldo.proof', $withdrawal) }}"
                                    @endif
                                    @if ($withdrawal->transfer_proof_path)
                                        data-proof-show-url="{{ route('admin.master.penarikan-saldo.proof.show', $withdrawal) }}"
                                    @endif
                                >
                                    <i class="fa-regular fa-eye"></i> Detail
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td class="empty-state" colspan="4">Belum ada pengajuan pencairan saldo.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($withdrawals->hasPages())
        <div class="pagination-wrapper">{{ $withdrawals->links() }}</div>
    @endif
</div>

<div class="withdrawal-modal" id="withdrawalDetailModal" role="dialog" aria-modal="true" aria-labelledby="detailModalTitle" hidden>
    <div class="detail-card">
        <div class="detail-heading">
            <div>
                <h2 id="detailModalTitle">Detail Pengajuan Pencairan</h2>
                <p>Informasi pengajuan bersifat read-only.</p>
            </div>
            <button class="modal-close" type="button" data-close-detail aria-label="Tutup detail">&times;</button>
        </div>
        <dl class="detail-list">
            <div class="detail-item detail-item-wide"><dt>Wisata / Kolam Renang</dt><dd data-detail-pool></dd></div>
            <div class="detail-item detail-item-wide"><dt>Admin Pengaju</dt><dd data-detail-requester></dd></div>
            <div class="detail-item"><dt>Bank</dt><dd data-detail-bank></dd></div>
            <div class="detail-item">
                <dt>Nomor Rekening</dt>
                <dd data-detail-account></dd>
                <button
                    class="copy-account"
                    type="button"
                    aria-label="Salin nomor rekening"
                    data-copy-account
                >
                    <i class="fa-solid fa-copy" aria-hidden="true"></i>
                    <span data-copy-label aria-live="polite">Salin</span>
                </button>
            </div>
            <div class="detail-item"><dt>Jumlah Diajukan</dt><dd data-detail-amount></dd></div>
            <div class="detail-item"><dt>Status Pengajuan</dt><dd data-detail-status></dd></div>
        </dl>
        <a
            class="proof-link detail-proof-link"
            data-detail-proof-link
            target="_blank"
            rel="noopener"
            hidden
        >
            <i class="fa-regular fa-image"></i> Lihat bukti transfer
        </a>
        <form
            class="detail-proof-form"
            action="#"
            method="POST"
            enctype="multipart/form-data"
            data-detail-proof-form
            hidden
        >
            @csrf
            <label class="proof-upload-label" for="detail_transfer_proof">Unggah bukti transfer</label>
            <input
                class="proof-file-input"
                id="detail_transfer_proof"
                type="file"
                name="transfer_proof"
                accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                aria-label="Pilih bukti transfer JPG, JPEG, atau PNG"
                required
            >
            <span class="proof-help">Format JPG, JPEG, atau PNG. Maksimal 5 MB.</span>
            <div class="proof-form-actions">
                <button class="button-send" type="submit">
                    <i class="fa-solid fa-paper-plane"></i> Kirim
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const detailModal = document.getElementById('withdrawalDetailModal');
    const detailProofForm = detailModal.querySelector('[data-detail-proof-form]');
    const detailProofInput = detailProofForm.querySelector('input[type="file"]');
    const detailProofButton = detailProofForm.querySelector('button[type="submit"]');
    const detailProofLink = detailModal.querySelector('[data-detail-proof-link]');
    const detailCopyAccountButton = detailModal.querySelector('[data-copy-account]');
    const detailFields = {
        pool: detailModal.querySelector('[data-detail-pool]'),
        requester: detailModal.querySelector('[data-detail-requester]'),
        bank: detailModal.querySelector('[data-detail-bank]'),
        account: detailModal.querySelector('[data-detail-account]'),
        amount: detailModal.querySelector('[data-detail-amount]'),
        status: detailModal.querySelector('[data-detail-status]'),
    };

    document.querySelectorAll('[data-withdrawal-detail]').forEach((button) => {
        button.addEventListener('click', () => {
            Object.entries(detailFields).forEach(([field, element]) => {
                element.textContent = button.dataset[field];
            });
            detailCopyAccountButton.dataset.copyAccount = button.dataset.account;
            detailCopyAccountButton.querySelector('[data-copy-label]').textContent = 'Salin';
            detailCopyAccountButton.disabled = false;

            const uploadUrl = button.dataset.proofUploadUrl;
            detailProofForm.hidden = !uploadUrl;
            detailProofForm.action = uploadUrl || '#';
            detailProofInput.value = '';
            detailProofButton.disabled = false;
            detailProofButton.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Kirim';

            const proofUrl = button.dataset.proofShowUrl;
            detailProofLink.hidden = !proofUrl;
            if (proofUrl) {
                detailProofLink.href = proofUrl;
            }

            detailModal.hidden = false;
        });
    });

    const copyAccountNumber = async (accountNumber) => {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            try {
                await navigator.clipboard.writeText(accountNumber);
                return;
            } catch (error) {
                // Use the compatibility fallback when clipboard permissions are unavailable.
            }
        }

        const temporaryInput = document.createElement('textarea');
        temporaryInput.value = accountNumber;
        temporaryInput.setAttribute('readonly', '');
        temporaryInput.style.position = 'fixed';
        temporaryInput.style.opacity = '0';
        document.body.appendChild(temporaryInput);
        temporaryInput.focus();
        temporaryInput.select();

        let copied = false;
        try {
            copied = document.execCommand('copy');
        } finally {
            temporaryInput.remove();
        }

        if (!copied) {
            throw new Error('Nomor rekening tidak dapat disalin.');
        }
    };

    document.querySelectorAll('[data-copy-account]').forEach((button) => {
        button.addEventListener('click', async () => {
            const label = button.querySelector('[data-copy-label]');
            button.disabled = true;

            try {
                await copyAccountNumber(button.dataset.copyAccount);
                label.textContent = 'Tersalin';
            } catch (error) {
                label.textContent = 'Gagal menyalin';
            }

            window.setTimeout(() => {
                label.textContent = 'Salin';
                button.disabled = false;
            }, 1500);
        });
    });

    const closeDetailModal = () => {
        detailModal.hidden = true;
    };

    detailModal.querySelector('[data-close-detail]').addEventListener('click', closeDetailModal);
    detailModal.addEventListener('click', (event) => {
        if (event.target === detailModal) {
            closeDetailModal();
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !detailModal.hidden) {
            closeDetailModal();
        }
    });

    detailProofForm.addEventListener('submit', () => {
        detailProofButton.disabled = true;
        detailProofButton.textContent = 'Mengirim...';
    });
</script>
@endpush
