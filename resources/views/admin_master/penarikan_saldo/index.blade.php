@extends('layout.admin_master')

@section('title', 'Cairkan Saldo')
@section('header-title', 'Cairkan Saldo')
@section('header-subtitle', 'Periksa pengajuan dan catat transfer manual ke rekening wisata')

@push('styles')
<style>
    .notice, .alert {
        margin-bottom: 18px;
        padding: 13px 16px;
        border-radius: 9px;
        font-size: 13px;
        line-height: 1.5;
    }
    .notice { color: #1e40af; background: #eff6ff; border: 1px solid #bfdbfe; }
    .alert-success { color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; }
    .alert-error { color: #991b1b; background: #fef2f2; border: 1px solid #fecaca; }
    .table-card {
        overflow-x: auto;
        background: #fff;
        border: 1px solid #e8ecf3;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, .04);
    }
    .withdrawal-table { width: 100%; min-width: 1080px; border-collapse: collapse; }
    .withdrawal-table th, .withdrawal-table td {
        padding: 14px 15px;
        border-bottom: 1px solid #eef2f7;
        text-align: left;
        vertical-align: middle;
        font-size: 13px;
    }
    .withdrawal-table th {
        color: #64748b;
        background: #f8fafc;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .withdrawal-table td { color: #1e293b; }
    .account-cell { display: flex; align-items: center; gap: 8px; white-space: nowrap; }
    .copy-button {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 5px 8px;
        color: #334155;
        background: #fff;
        cursor: pointer;
    }
    .copy-button:hover { color: #1d4ed8; border-color: #93c5fd; }
    .status-badge {
        display: inline-flex;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }
    .status-proses { color: #92400e; background: #fef3c7; }
    .status-berhasil { color: #166534; background: #dcfce7; }
    .status-ditolak { color: #991b1b; background: #fee2e2; }
    .action-cell { min-width: 280px; }
    .action-form { display: flex; align-items: center; gap: 7px; margin-top: 7px; }
    .action-form:first-child { margin-top: 0; }
    .action-form input:not([type="file"]) {
        width: 140px;
        padding: 7px 8px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 11px;
    }
    .proof-form { flex-wrap: wrap; }
    .proof-form input[type="file"] { width: 100%; max-width: 250px; font-size: 11px; }
    .proof-link { display: inline-flex; align-items: center; gap: 7px; margin-top: 7px; color: #1d4ed8; font-size: 11px; font-weight: 700; }
    .proof-preview { display: block; width: 76px; height: 54px; border: 1px solid #cbd5e1; border-radius: 6px; object-fit: cover; background: #f8fafc; }
    .selected-proof-preview { display: block; max-width: 180px; max-height: 120px; margin-top: 7px; border: 1px solid #cbd5e1; border-radius: 6px; object-fit: contain; background: #f8fafc; }
    .selected-proof-name { margin-top: 5px; color: #64748b; font-size: 10px; }
    .proof-meta { margin-top: 5px; color: #64748b; font-size: 10px; }
    .button-complete, .button-reject, .button-proof {
        border: 0;
        border-radius: 6px;
        padding: 8px 10px;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        cursor: pointer;
    }
    .button-proof { background: #2563eb; }
    .button-proof:hover { background: #1d4ed8; }
    .button-complete { background: #16a34a; }
    .button-complete:hover { background: #15803d; }
    .button-complete:disabled { background: #94a3b8; cursor: not-allowed; }
    .button-reject { background: #dc2626; }
    .button-reject:hover { background: #b91c1c; }
    .pagination-wrapper { padding: 16px 20px; }
    .empty-state { padding: 36px 20px !important; color: #64748b !important; text-align: center !important; }
    .rejection-note { margin-top: 6px; color: #991b1b; font-size: 11px; }
</style>
@endpush

@section('content')
<div class="notice">
    Unggah bukti transfer (JPG, PNG, atau PDF; maksimal 5 MB) setelah dana dikirim. Pengajuan hanya dapat dikonfirmasi setelah bukti tersimpan.
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<div class="table-card">
    <table class="withdrawal-table">
        <thead>
            <tr>
                <th>Tanggal Pengajuan</th>
                <th>Wisata Kolam Renang</th>
                <th>Admin Pengaju</th>
                <th>Bank</th>
                <th>Nomor Rekening</th>
                <th>Jumlah</th>
                <th>Status / Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($withdrawals as $withdrawal)
                <tr>
                    <td>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $withdrawal->pool?->name ?? $withdrawal->pool_id }}</td>
                    <td>{{ $withdrawal->requester?->full_name ?: $withdrawal->requester?->name ?: $withdrawal->requester?->username ?: 'Admin kolam' }}</td>
                    <td>{{ $withdrawal->bank_name }}</td>
                    <td>
                        <div class="account-cell">
                            <span>{{ $withdrawal->account_number }}</span>
                            <button class="copy-button" type="button" data-copy="{{ $withdrawal->account_number }}" aria-label="Salin nomor rekening">
                                <i class="fa-regular fa-copy"></i> Salin
                            </button>
                        </div>
                    </td>
                    <td>Rp {{ number_format((float) $withdrawal->amount, 2, ',', '.') }}</td>
                    <td class="action-cell">
                        <span class="status-badge status-{{ $withdrawal->status }}">{{ $withdrawal->status_label }}</span>
                        @if ($withdrawal->transfer_proof_path)
                            @php
                                $proofExtension = strtolower(pathinfo($withdrawal->transfer_proof_path, PATHINFO_EXTENSION));
                                $proofUrl = route('admin.master.penarikan-saldo.proof.show', $withdrawal);
                            @endphp
                            <a class="proof-link" href="{{ $proofUrl }}" target="_blank" rel="noopener">
                                @if (in_array($proofExtension, ['jpg', 'jpeg', 'png'], true))
                                    <img class="proof-preview" src="{{ $proofUrl }}" alt="Pratinjau bukti transfer">
                                @else
                                    <i class="fa-solid fa-file-pdf"></i>
                                @endif
                                Lihat bukti transfer
                            </a>
                            @if ($withdrawal->transfer_proof_uploaded_at)
                                <div class="proof-meta">Dikirim {{ $withdrawal->transfer_proof_uploaded_at->format('d/m/Y H:i') }}</div>
                            @endif
                        @endif
                        @if ($withdrawal->status === \App\Models\PenarikanSaldo::STATUS_PROSES)
                            <form class="action-form proof-form" action="{{ route('admin.master.penarikan-saldo.proof', $withdrawal) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="transfer_proof" accept="image/jpeg,image/png,application/pdf,.jpg,.jpeg,.png,.pdf" aria-label="Pilih bukti transfer" data-proof-input required>
                                <div class="selected-proof" data-proof-preview hidden aria-live="polite">
                                    <img class="selected-proof-preview" alt="Pratinjau gambar bukti transfer" hidden>
                                    <div class="selected-proof-name"></div>
                                </div>
                                <button class="button-proof" type="submit">
                                    <i class="fa-solid fa-upload"></i>
                                    {{ $withdrawal->transfer_proof_path ? 'Ganti Bukti' : 'Kirim Bukti Transfer' }}
                                </button>
                            </form>
                            <form class="action-form" action="{{ route('admin.master.penarikan-saldo.complete', $withdrawal) }}" method="POST" onsubmit="return confirm('Pastikan bukti transfer sudah sesuai dan dana berhasil dikirim. Konfirmasi pencairan ini?')">
                                @csrf
                                <button class="button-complete" type="submit" {{ $withdrawal->transfer_proof_path ? '' : 'disabled' }}>
                                    <i class="fa-solid fa-check"></i> Konfirmasi Pencairan
                                </button>
                            </form>
                            @unless ($withdrawal->transfer_proof_path)
                                <div class="proof-meta">Kirim bukti transfer untuk mengaktifkan konfirmasi.</div>
                            @endunless
                            <form class="action-form" action="{{ route('admin.master.penarikan-saldo.reject', $withdrawal) }}" method="POST" onsubmit="return confirm('Tolak pengajuan pencairan saldo ini?')">
                                @csrf
                                <input name="rejection_reason" maxlength="500" placeholder="Alasan (opsional)" aria-label="Alasan penolakan (opsional)">
                                <button class="button-reject" type="submit"><i class="fa-solid fa-xmark"></i> Tolak</button>
                            </form>
                        @elseif ($withdrawal->rejection_reason)
                            <div class="rejection-note">{{ $withdrawal->rejection_reason }}</div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td class="empty-state" colspan="7">Belum ada pengajuan pencairan saldo.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if ($withdrawals->hasPages())
        <div class="pagination-wrapper">{{ $withdrawals->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-proof-input]').forEach((input) => {
        const preview = input.closest('form').querySelector('[data-proof-preview]');
        const image = preview.querySelector('img');
        const fileName = preview.querySelector('.selected-proof-name');
        let previewUrl = null;

        input.addEventListener('change', () => {
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }

            const [file] = input.files || [];
            preview.hidden = !file;
            image.hidden = true;
            image.removeAttribute('src');
            fileName.textContent = '';

            if (!file) {
                return;
            }

            fileName.textContent = `Siap dikirim: ${file.name}`;
            if (file.type.startsWith('image/')) {
                previewUrl = URL.createObjectURL(file);
                image.src = previewUrl;
                image.hidden = false;
            }
        });
    });

    document.querySelectorAll('.copy-button').forEach((button) => {
        button.addEventListener('click', async () => {
            const accountNumber = button.dataset.copy;

            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(accountNumber);
                } else {
                    const input = document.createElement('textarea');
                    input.value = accountNumber;
                    input.style.position = 'fixed';
                    input.style.opacity = '0';
                    document.body.appendChild(input);
                    input.select();
                    const copied = document.execCommand('copy');
                    input.remove();

                    if (!copied) {
                        throw new Error('Clipboard copy was rejected.');
                    }
                }

                button.innerHTML = '<i class="fa-solid fa-check"></i> Tersalin';
                window.setTimeout(() => {
                    button.innerHTML = '<i class="fa-regular fa-copy"></i> Salin';
                }, 1500);
            } catch {
                window.alert('Nomor rekening gagal disalin. Silakan salin secara manual.');
            }
        });
    });
</script>
@endpush
