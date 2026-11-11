@extends('layout.admin_master')

@section('title', 'Persetujuan Admin - NEXPOOL')
@section('header-title', 'Persetujuan Admin')
@section('header-subtitle', 'Tinjau dan proses pengajuan admin kolam renang')

@push('styles')
<style>
    [hidden] { display:none !important; }
    .filter-card, .table-container { background:#fff; border:1px solid #e8ecf3; border-radius:14px; box-shadow:0 3px 12px rgba(15,23,42,.03); }
    .filter-card { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:15px; padding:18px 20px; margin-bottom:20px; }
    .filter-form { display:flex; flex-wrap:wrap; gap:9px; }
    .filter-form input, .filter-form select { min-height:38px; border:1px solid #cbd5e1; border-radius:8px; padding:8px 11px; font-size:13px; }
    .btn-filter, .btn-reset, .btn-detail, .btn-approve, .btn-reject, .btn-cancel, .btn-submit-reject {
        display:inline-flex; justify-content:center; align-items:center; gap:7px; border:0; border-radius:8px; padding:9px 13px; font-size:12px; font-weight:700; cursor:pointer;
    }
    .btn-filter { background:#1e3c72; color:#fff; }
    .btn-reset, .btn-cancel { background:#f1f5f9; color:#475569; }
    .btn-detail { background:#eff6ff; color:#1d4ed8; }
    .btn-approve { background:#059669; color:#fff; }
    .btn-reject, .btn-submit-reject { background:#dc2626; color:#fff; }
    .pending-count { color:#64748b; font-size:13px; }
    .app-table { width:100%; min-width:820px; border-collapse:collapse; font-size:13px; }
    .app-table th { padding:14px 16px; background:#f8fafc; border-bottom:1px solid #e2e8f0; color:#475569; font-size:11px; text-align:left; text-transform:uppercase; letter-spacing:.5px; }
    .app-table td { padding:14px 16px; border-bottom:1px solid #f1f5f9; color:#1e293b; vertical-align:middle; }
    .app-table tr:last-child td { border-bottom:0; }
    .badge { display:inline-flex; padding:5px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .badge-pending { background:#fef3c7; color:#92400e; }
    .badge-approved { background:#d1fae5; color:#065f46; }
    .badge-rejected { background:#fee2e2; color:#991b1b; }
    .table-scroll { overflow-x:auto; }
    .pagination-wrapper { display:flex; justify-content:flex-end; padding:17px 20px; }
    .alert { padding:13px 16px; border-radius:9px; margin-bottom:17px; font-size:13px; }
    .alert-success { background:#d1fae5; color:#065f46; }
    .alert-danger { background:#fee2e2; color:#991b1b; }
    .alert-warning { background:#fef3c7; color:#92400e; }
    .modal-overlay { display:none; position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,.62); align-items:center; justify-content:center; padding:18px; }
    .modal-overlay.active { display:flex; }
    .modal-card { width:100%; max-width:590px; max-height:90vh; overflow-y:auto; padding:25px; border-radius:14px; background:#fff; box-shadow:0 20px 50px rgba(15,23,42,.25); }
    .modal-header { display:flex; align-items:center; justify-content:space-between; gap:15px; margin-bottom:20px; }
    .modal-header h3 { color:#172033; font-size:18px; }
    .modal-close { border:0; background:transparent; color:#64748b; font-size:22px; cursor:pointer; }
    .detail-list { display:grid; grid-template-columns:150px 1fr; gap:13px 16px; font-size:13px; }
    .detail-list dt { color:#64748b; }
    .detail-list dd { color:#172033; font-weight:600; overflow-wrap:anywhere; }
    .modal-actions { display:flex; justify-content:flex-end; flex-wrap:wrap; gap:9px; margin-top:22px; }
    .reject-panel { margin-top:20px; padding-top:18px; border-top:1px solid #e8ecf3; }
    .reject-panel label { display:block; margin-bottom:8px; color:#334155; font-size:12px; font-weight:700; }
    .reject-panel textarea { width:100%; min-height:100px; resize:vertical; border:1px solid #cbd5e1; border-radius:8px; padding:11px; font:inherit; font-size:13px; }
    .reject-error { color:#dc2626; font-size:12px; margin-top:6px; }
    @media(max-width:600px) {
        .detail-list { grid-template-columns:1fr; gap:5px; }
        .detail-list dd { margin-bottom:9px; }
        .modal-actions { justify-content:stretch; }
        .modal-actions button { flex:1; }
    }
</style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning" role="alert">{{ session('warning') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif

    <div class="filter-card">
        <form method="GET" action="{{ route('admin.master.applications.index') }}" class="filter-form">
            <input type="search" name="search" placeholder="Cari nama atau email" value="{{ $search }}">
            <select name="status" aria-label="Filter status">
                <option value="pending" @selected($status === 'pending')>Menunggu Persetujuan</option>
                <option value="approved" @selected($status === 'approved')>Diterima</option>
                <option value="rejected" @selected($status === 'rejected')>Ditolak</option>
                <option value="all" @selected($status === 'all')>Semua Status</option>
            </select>
            <button type="submit" class="btn-filter"><i class="fa-solid fa-magnifying-glass"></i> Filter</button>
            @if($search !== '' || $status !== 'pending')
                <a href="{{ route('admin.master.applications.index') }}" class="btn-reset">Reset</a>
            @endif
        </form>
        <div class="pending-count"><strong>{{ $pendingCount }}</strong> pengajuan menunggu persetujuan</div>
    </div>

    <div class="table-container">
        <div class="table-scroll">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Nomor HP</th>
                        <th>Tanggal Diajukan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr>
                            <td>{{ $application->full_name }}</td>
                            <td>{{ $application->email }}</td>
                            <td>{{ $application->phone }}</td>
                            <td>{{ $application->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}</td>
                            <td><span class="badge badge-{{ $application->status }}">{{ $application->status_label }}</span></td>
                            <td>
                                <button
                                    type="button"
                                    class="btn-detail"
                                    data-id="{{ $application->id }}"
                                    data-name="{{ $application->full_name }}"
                                    data-email="{{ $application->email }}"
                                    data-phone="{{ $application->phone }}"
                                    data-position="{{ $application->position_label }}"
                                    data-pool="{{ $application->pool?->name ?? $application->pool_id }}"
                                    data-submitted="{{ $application->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}"
                                    data-status="{{ $application->status_label }}"
                                    data-reason="{{ $application->rejection_reason }}"
                                    data-approve-url="{{ route('admin.master.applications.approve', $application) }}"
                                    data-reject-url="{{ route('admin.master.applications.reject', $application) }}"
                                    data-pending="{{ $application->status === 'pending' ? '1' : '0' }}"
                                    onclick="openApplicationDetail(this)"
                                >Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="padding:36px;text-align:center;color:#94a3b8;">Tidak ada pengajuan admin.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->hasPages())
            <div class="pagination-wrapper">{{ $applications->links() }}</div>
        @endif
    </div>

    <div id="applicationDetailModal" class="modal-overlay" aria-hidden="true">
        <section class="modal-card" role="dialog" aria-modal="true" aria-labelledby="detailTitle">
            <div class="modal-header">
                <h3 id="detailTitle">Detail Pengajuan Admin</h3>
                <button type="button" class="modal-close" aria-label="Tutup" onclick="closeApplicationDetail()">&times;</button>
            </div>
            <dl class="detail-list">
                <dt>Nama lengkap</dt><dd id="detailName"></dd>
                <dt>Email</dt><dd id="detailEmail"></dd>
                <dt>Nomor HP</dt><dd id="detailPhone"></dd>
                <dt>Jabatan</dt><dd id="detailPosition"></dd>
                <dt>Wisata/kolam renang asal</dt><dd id="detailPool"></dd>
                <dt>Tanggal diajukan</dt><dd id="detailSubmitted"></dd>
                <dt>Status</dt><dd id="detailStatus"></dd>
                <dt id="detailReasonLabel" hidden>Alasan penolakan</dt><dd id="detailReason" hidden></dd>
            </dl>

            <div id="pendingActions" class="modal-actions" hidden>
                <form id="approveForm" method="POST" onsubmit="return confirmApproval()">
                    @csrf
                    <button type="submit" class="btn-approve"><i class="fa-solid fa-check"></i> Terima</button>
                </form>
                <button type="button" class="btn-reject" onclick="showRejectForm()">Tolak</button>
            </div>

            <div id="rejectPanel" class="reject-panel" hidden>
                <form id="rejectForm" method="POST">
                    @csrf
                    <label for="rejection_reason">Alasan penolakan</label>
                    <textarea id="rejection_reason" name="rejection_reason" required minlength="10" maxlength="500" placeholder="Tuliskan alasan penolakan (minimal 10 karakter)."></textarea>
                    <div class="modal-actions">
                        <button type="button" class="btn-cancel" onclick="hideRejectForm()">Batal</button>
                        <button type="submit" class="btn-submit-reject" onclick="return confirm('Tolak pengajuan admin ini?')">Konfirmasi Tolak</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    let selectedApplicantName = '';

    function openApplicationDetail(button) {
        const detail = button.dataset;
        selectedApplicantName = detail.name;
        document.getElementById('detailName').textContent = detail.name;
        document.getElementById('detailEmail').textContent = detail.email;
        document.getElementById('detailPhone').textContent = detail.phone;
        document.getElementById('detailPosition').textContent = detail.position;
        document.getElementById('detailPool').textContent = detail.pool;
        document.getElementById('detailSubmitted').textContent = detail.submitted;
        document.getElementById('detailStatus').textContent = detail.status;

        const hasReason = detail.reason !== '';
        document.getElementById('detailReasonLabel').hidden = !hasReason;
        document.getElementById('detailReason').hidden = !hasReason;
        document.getElementById('detailReason').textContent = detail.reason;

        const pending = detail.pending === '1';
        document.getElementById('pendingActions').hidden = !pending;
        document.getElementById('rejectPanel').hidden = true;
        document.getElementById('approveForm').action = detail.approveUrl;
        document.getElementById('rejectForm').action = detail.rejectUrl;
        document.getElementById('rejection_reason').value = '';

        const modal = document.getElementById('applicationDetailModal');
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
    }

    function showRejectForm() {
        document.getElementById('pendingActions').hidden = true;
        document.getElementById('rejectPanel').hidden = false;
        document.getElementById('rejection_reason').focus();
    }

    function hideRejectForm() {
        document.getElementById('rejectPanel').hidden = true;
        document.getElementById('pendingActions').hidden = false;
    }

    function confirmApproval() {
        return confirm(`Terima pengajuan admin atas nama ${selectedApplicantName}?`);
    }

    function closeApplicationDetail() {
        const modal = document.getElementById('applicationDetailModal');
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }

    document.getElementById('applicationDetailModal').addEventListener('click', (event) => {
        if (event.target === event.currentTarget) {
            closeApplicationDetail();
        }
    });
</script>
@endpush
