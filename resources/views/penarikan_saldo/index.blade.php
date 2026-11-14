@extends('layout.admin')

@section('title', 'Penarikan Saldo')
@section('header-title', 'Penarikan Saldo')
@section('header-subtitle', 'Ajukan pencairan saldo wisata kolam renang')

@push('styles')
<style>
    .withdrawal-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }
    .withdrawal-heading p { color: #64748b; font-size: 13px; }
    .button-primary, .button-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 0;
        border-radius: 8px;
        padding: 11px 16px;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }
    .button-primary { background: #2563eb; }
    .button-primary:hover { background: #1d4ed8; }
    .notice, .alert {
        padding: 13px 16px;
        border-radius: 9px;
        margin-bottom: 18px;
        font-size: 13px;
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
    .withdrawal-table { width: 100%; min-width: 620px; border-collapse: collapse; }
    .withdrawal-table th, .withdrawal-table td {
        padding: 15px 18px;
        border-bottom: 1px solid #eef2f7;
        text-align: left;
        font-size: 13px;
    }
    .withdrawal-table th {
        color: #64748b;
        background: #f8fafc;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .withdrawal-table td { color: #1e293b; }
    .status-badge {
        display: inline-flex;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }
    .status-proses { color: #92400e; background: #fef3c7; }
    .status-berhasil, .status-terkonfirmasi { color: #166534; background: #dcfce7; }
    .status-ditolak { color: #991b1b; background: #fee2e2; }
    .proof-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        margin-top: 8px;
        padding: 7px 11px;
        border: 1px solid #bfdbfe;
        border-radius: 7px;
        color: #1d4ed8;
        background: #eff6ff;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }
    .proof-link:hover { color: #1e40af; background: #dbeafe; }
    .empty-state { padding: 36px 20px; color: #64748b; text-align: center; }
    .pagination-wrapper { padding: 16px 20px; }
    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 2000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 23, 42, .6);
    }
    .modal-overlay.active { display: flex; }
    .modal-card {
        width: min(100%, 480px);
        padding: 25px;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 20px 50px rgba(15, 23, 42, .2);
    }
    .modal-card h2 { margin-bottom: 7px; color: #172033; font-size: 19px; }
    .modal-card > p { margin-bottom: 20px; color: #64748b; font-size: 13px; line-height: 1.5; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 6px; color: #334155; font-size: 12px; font-weight: 700; }
    .form-group input, .form-group select {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        background: #fff;
    }
    .form-group input:focus, .form-group select:focus { outline: 2px solid #bfdbfe; border-color: #2563eb; }
    .balance-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 0 0 18px;
        padding: 13px 15px;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        color: #1e40af;
        background: #eff6ff;
        font-size: 13px;
    }
    .balance-summary strong { white-space: nowrap; font-size: 15px; }
    .field-error { display: block; margin-top: 5px; color: #b91c1c; font-size: 12px; }
    .field-help { display: block; margin-top: 5px; color: #64748b; font-size: 11px; }
    .modal-actions { display: flex; justify-content: flex-end; gap: 9px; margin-top: 20px; }
    .button-cancel {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 14px;
        color: #334155;
        background: #fff;
        font-weight: 600;
        cursor: pointer;
    }
    .proof-modal {
        position: fixed;
        inset: 0;
        z-index: 3000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 23, 42, .72);
        backdrop-filter: blur(3px);
    }
    .proof-modal[hidden] { display: none; }
    .proof-modal-card {
        position: relative;
        width: min(100%, 900px);
        max-height: 90vh;
        padding: 18px;
        border: 1px solid rgba(255,255,255,.35);
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .3);
    }
    .proof-modal-heading { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 12px; }
    .proof-modal-heading h2 { margin: 0; color: #172033; font-size: 16px; }
    .proof-modal-close {
        width: 34px;
        height: 34px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #475569;
        background: #fff;
        font-size: 18px;
        cursor: pointer;
    }
    .proof-modal-image {
        display: block;
        max-width: 100%;
        max-height: calc(90vh - 100px);
        margin: 0 auto;
        border-radius: 8px;
        object-fit: contain;
    }
    @media (max-width: 600px) {
        .withdrawal-heading { align-items: flex-start; flex-direction: column; }
        .withdrawal-heading .button-primary { width: 100%; }
        .modal-card { padding: 20px; }
    }
</style>
@endpush

@section('content')
<div class="withdrawal-heading">
    <p>Riwayat pengajuan penarikan saldo untuk wisata ini.</p>
    <button class="button-primary" id="openWithdrawalModal" type="button">
        <i class="fa-solid fa-plus"></i> Ajukan Pencairan
    </button>
</div>

<div class="notice">
    Saldo tersedia saat ini: <strong>Rp {{ number_format((float) $availableBalance, 2, ',', '.') }}</strong><br>
    Transfer ke rekening dilakukan oleh Admin Master di luar aplikasi. Status akan berubah setelah pengajuan diproses.
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="table-card">
    <table class="withdrawal-table">
        <thead>
            <tr>
                <th>Tanggal Pengajuan</th>
                <th>Jumlah Saldo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($withdrawals as $withdrawal)
                <tr>
                    <td>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                    <td>Rp {{ number_format((float) $withdrawal->amount, 2, ',', '.') }}</td>
                    <td>
                        <span class="status-badge status-{{ $withdrawal->status }}">
                            {{ $withdrawal->status_label }}
                        </span>
                        @if ($withdrawal->transfer_proof_path)
                            <button
                                class="proof-link"
                                type="button"
                                data-proof-preview-url="{{ route('penarikan-saldo.proof.show', $withdrawal) }}"
                            >
                                <i class="fa-regular fa-image"></i> Lihat
                            </button>
                            @if ($withdrawal->transfer_proof_uploaded_at)
                                <div class="field-error" style="color:#64748b">
                                    Bukti dikirim {{ $withdrawal->transfer_proof_uploaded_at->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        @else
                            <div class="field-error" style="color:#64748b">
                                Bukti transfer belum tersedia.
                            </div>
                        @endif
                        @if ($withdrawal->status === \App\Models\PenarikanSaldo::STATUS_DITOLAK && $withdrawal->rejection_reason)
                            <div class="field-error">{{ $withdrawal->rejection_reason }}</div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td class="empty-state" colspan="3">Belum ada pengajuan penarikan saldo.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if ($withdrawals->hasPages())
        <div class="pagination-wrapper">{{ $withdrawals->links() }}</div>
    @endif
</div>

<div class="proof-modal" id="proofPreviewModal" role="dialog" aria-modal="true" aria-labelledby="proofPreviewTitle" hidden>
    <div class="proof-modal-card">
        <div class="proof-modal-heading">
            <h2 id="proofPreviewTitle">Bukti Transfer</h2>
            <button class="proof-modal-close" type="button" data-close-proof aria-label="Tutup preview">&times;</button>
        </div>
        <img class="proof-modal-image" data-proof-image alt="Bukti transfer pencairan saldo">
    </div>
</div>

<div class="modal-overlay {{ $errors->any() ? 'active' : '' }}" id="withdrawalModal" role="dialog" aria-modal="true" aria-labelledby="withdrawalModalTitle">
    <div class="modal-card">
        <h2 id="withdrawalModalTitle">Ajukan Pencairan Saldo</h2>
        <p>Isi rekening tujuan dan jumlah yang ingin dicairkan. Pastikan data rekening sudah benar.</p>
        <div class="balance-summary">
            <span>Saldo tersedia untuk diajukan</span>
            <strong>Rp {{ number_format((float) $availableBalance, 2, ',', '.') }}</strong>
        </div>
        <form action="{{ route('penarikan-saldo.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="bank_name">Bank</label>
                <select id="bank_name" name="bank_name" required>
                    <option value="">-- Pilih Bank --</option>
                    @foreach ($bankOptions as $bankCode => $bankFullName)
                        <option value="{{ $bankCode }}" {{ old('bank_name') === $bankCode ? 'selected' : '' }}>
                            {{ $bankCode }} ({{ $bankFullName }})
                        </option>
                    @endforeach
                </select>
                @error('bank_name') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="account_number">Nomor Rekening</label>
                <input id="account_number" name="account_number" type="text" value="{{ old('account_number') }}" inputmode="numeric" pattern="[0-9]{5,30}" maxlength="30" autocomplete="off" placeholder="Masukkan nomor rekening" required>
                @error('account_number') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="amount_display">Jumlah Saldo (Rp)</label>
                <input id="amount_display" type="text" inputmode="decimal" autocomplete="off" data-max-amount="50000000" value="{{ old('amount') }}" placeholder="Contoh: 20.000" required>
                <input id="amount" name="amount" type="hidden" value="{{ old('amount') }}">
                <span class="field-help">Maksimal Rp 50.000.000 per pengajuan; nominal lebih akan dibatasi.</span>
                @error('amount') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="modal-actions">
                <button class="button-cancel" id="closeWithdrawalModal" type="button">Batal</button>
                <button class="button-primary" type="submit">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const withdrawalModal = document.getElementById('withdrawalModal');
    const openWithdrawalModal = document.getElementById('openWithdrawalModal');
    const closeWithdrawalModal = document.getElementById('closeWithdrawalModal');
    const accountNumberInput = document.getElementById('account_number');
    const amountDisplayInput = document.getElementById('amount_display');
    const amountInput = document.getElementById('amount');
    const maximumWithdrawalAmount = Number(amountDisplayInput.dataset.maxAmount);
    const proofPreviewModal = document.getElementById('proofPreviewModal');
    const proofImage = proofPreviewModal.querySelector('[data-proof-image]');

    const sanitizeAccountNumber = () => {
        accountNumberInput.value = accountNumberInput.value.replace(/\D/g, '').slice(0, 30);
    };

    accountNumberInput.addEventListener('input', sanitizeAccountNumber);
    sanitizeAccountNumber();

    const parseAmount = (value) => {
        const cleaned = value.replace(/[^\d,.]/g, '');
        const decimalSeparator = cleaned.lastIndexOf(',');
        const integerPart = (decimalSeparator >= 0
            ? cleaned.slice(0, decimalSeparator)
            : cleaned
        ).replace(/\D/g, '');
        const decimalPart = decimalSeparator >= 0
            ? cleaned.slice(decimalSeparator + 1).replace(/\D/g, '').slice(0, 2)
            : '';

        return { integerPart, decimalPart };
    };

    const formatAmount = (integerPart) => integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    const updateAmount = () => {
        const { integerPart, decimalPart } = parseAmount(amountDisplayInput.value);
        const parsedAmount = Number(integerPart + (decimalPart.length > 0 ? `.${decimalPart}` : ''));
        if (parsedAmount > maximumWithdrawalAmount) {
            const maximumAmount = String(maximumWithdrawalAmount);
            amountDisplayInput.value = formatAmount(maximumAmount);
            amountInput.value = maximumAmount;
            amountDisplayInput.setSelectionRange(
                amountDisplayInput.value.length,
                amountDisplayInput.value.length,
            );

            return;
        }

        const caretPosition = amountDisplayInput.selectionStart ?? amountDisplayInput.value.length;
        const digitsBeforeCaret = amountDisplayInput.value.slice(0, caretPosition).replace(/\D/g, '').length;
        const formattedInteger = formatAmount(integerPart);
        const formattedValue = decimalPart.length > 0
            ? `${formattedInteger},${decimalPart}`
            : formattedInteger;
        amountDisplayInput.value = formattedValue;
        amountInput.value = integerPart + (decimalPart.length > 0 ? `.${decimalPart}` : '');

        let nextCaretPosition = 0;
        let digitCount = 0;
        while (nextCaretPosition < formattedValue.length && digitCount < digitsBeforeCaret) {
            if (/\d/.test(formattedValue[nextCaretPosition])) {
                digitCount += 1;
            }
            nextCaretPosition += 1;
        }
        amountDisplayInput.setSelectionRange(nextCaretPosition, nextCaretPosition);
    };

    amountDisplayInput.addEventListener('input', () => {
        amountDisplayInput.setCustomValidity('');
        updateAmount();
    });
    amountDisplayInput.form.addEventListener('submit', (event) => {
        updateAmount();
        if (!amountInput.value || Number(amountInput.value) <= 0) {
            event.preventDefault();
            amountDisplayInput.setCustomValidity('Masukkan nominal pencairan yang valid.');
            amountDisplayInput.reportValidity();
        } else if (Number(amountInput.value) > maximumWithdrawalAmount) {
            event.preventDefault();
            amountDisplayInput.setCustomValidity('Maksimal jumlah pencairan per pengajuan adalah Rp 50.000.000.');
            amountDisplayInput.reportValidity();
        } else {
            amountDisplayInput.setCustomValidity('');
        }
    });

    if (amountDisplayInput.value) {
        const oldAmount = amountDisplayInput.value;
        const normalizedOldAmount = oldAmount.replace(/^(\d+)\.(\d{1,2})$/, '$1,$2');
        amountDisplayInput.value = normalizedOldAmount;
        updateAmount();
    }

    openWithdrawalModal.addEventListener('click', () => withdrawalModal.classList.add('active'));
    closeWithdrawalModal.addEventListener('click', () => withdrawalModal.classList.remove('active'));
    withdrawalModal.addEventListener('click', (event) => {
        if (event.target === withdrawalModal) {
            withdrawalModal.classList.remove('active');
        }
    });

    const closeProofPreview = () => {
        proofPreviewModal.hidden = true;
        proofImage.removeAttribute('src');
    };

    document.querySelectorAll('[data-proof-preview-url]').forEach((button) => {
        button.addEventListener('click', () => {
            proofImage.src = button.dataset.proofPreviewUrl;
            proofPreviewModal.hidden = false;
        });
    });

    proofPreviewModal.querySelector('[data-close-proof]').addEventListener('click', closeProofPreview);
    proofPreviewModal.addEventListener('click', (event) => {
        if (event.target === proofPreviewModal) {
            closeProofPreview();
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !proofPreviewModal.hidden) {
            closeProofPreview();
        }
    });
</script>
@endpush
