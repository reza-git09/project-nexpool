@extends('layout.admin')

@section('title', 'Tambah Admin - NEXPOOL')
@section('header-title', 'Tambah Admin')
@section('header-subtitle', 'Ajukan admin baru untuk kolam renang Anda')

@push('styles')
<style>
    [hidden] { display:none !important; }
    .application-heading { display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:22px; }
    .application-heading h2 { color:#172033; font-size:20px; }
    .application-heading p { color:#7b8494; font-size:13px; margin-top:6px; }
    .btn-primary, .btn-detail, .btn-secondary, .btn-success, .btn-danger {
        border:0; border-radius:8px; cursor:pointer; font-size:13px; font-weight:600;
        display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:10px 15px;
    }
    .btn-primary { background:#2563eb; color:#fff; }
    .btn-primary:hover { background:#1d4ed8; }
    .btn-detail { background:#eff6ff; color:#1d4ed8; }
    .btn-secondary { background:#f3f4f6; color:#475569; }
    .btn-success { background:#059669; color:#fff; }
    .btn-danger { background:#dc2626; color:#fff; }
    .table-container { background:#fff; border:1px solid #e8ecf3; border-radius:14px; overflow-x:auto; }
    .app-table { width:100%; min-width:720px; border-collapse:collapse; font-size:13px; }
    .app-table th { background:#f8fafc; color:#64748b; font-size:11px; text-align:left; text-transform:uppercase; letter-spacing:.5px; padding:14px 16px; }
    .app-table td { color:#1e293b; border-top:1px solid #eef1f5; padding:14px 16px; }
    .badge { display:inline-flex; border-radius:20px; padding:5px 10px; font-size:11px; font-weight:700; }
    .badge-pending { background:#fef3c7; color:#92400e; }
    .badge-approved { background:#d1fae5; color:#065f46; }
    .badge-rejected { background:#fee2e2; color:#991b1b; }
    .alert { padding:13px 16px; border-radius:9px; margin-bottom:18px; font-size:13px; }
    .alert-success { background:#d1fae5; color:#065f46; }
    .alert-danger { background:#fee2e2; color:#991b1b; }
    .validation-list { margin:8px 0 0 20px; }
    .pagination-wrapper { padding:16px; display:flex; justify-content:flex-end; }
    .modal-overlay { display:none; position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,.62); align-items:center; justify-content:center; padding:18px; }
    .modal-overlay.active { display:flex; }
    .modal-card { width:100%; max-width:560px; max-height:90vh; overflow-y:auto; padding:25px; border-radius:14px; background:#fff; box-shadow:0 20px 50px rgba(15,23,42,.25); }
    .modal-header { display:flex; justify-content:space-between; align-items:center; gap:15px; margin-bottom:20px; }
    .modal-header h3 { font-size:18px; color:#172033; }
    .modal-close { border:0; background:transparent; color:#64748b; font-size:22px; cursor:pointer; }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
    .form-field { display:flex; flex-direction:column; gap:7px; }
    .form-field.full { grid-column:1 / -1; }
    .form-field label { color:#334155; font-size:12px; font-weight:700; }
    .form-field input, .form-field select { width:100%; border:1px solid #cbd5e1; border-radius:8px; padding:11px 12px; outline:none; font-size:13px; }
    .form-field input:focus, .form-field select:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.1); }
    .field-error { color:#dc2626; font-size:11px; }
    .modal-actions { display:flex; justify-content:flex-end; gap:9px; margin-top:20px; }
    .detail-list { display:grid; grid-template-columns:150px 1fr; gap:13px 16px; font-size:13px; }
    .detail-list dt { color:#64748b; }
    .detail-list dd { color:#172033; font-weight:600; overflow-wrap:anywhere; }
    .detail-divider { border:0; border-top:1px solid #e8ecf3; margin:20px 0; }
    @media(max-width:600px) {
        .application-heading { align-items:flex-start; flex-direction:column; }
        .form-grid { grid-template-columns:1fr; }
        .form-field.full { grid-column:auto; }
        .detail-list { grid-template-columns:1fr; gap:5px; }
        .detail-list dd { margin-bottom:9px; }
    }
</style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            Periksa kembali data pengajuan.
            <ul class="validation-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="application-heading">
        <div>
            <h2>Riwayat Pengajuan Admin</h2>
            <p>Pengajuan hanya dapat dilihat dan dikelola untuk kolam renang Anda.</p>
        </div>
        <button type="button" class="btn-primary" onclick="openRegistrationModal()">
            <i class="fa-solid fa-user-plus"></i> Daftarkan Admin
        </button>
    </div>

    <div class="table-container">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $application)
                    <tr>
                        <td>{{ $application->full_name }}</td>
                        <td>{{ $application->email }}</td>
                        <td><span class="badge badge-{{ $application->status }}">{{ $application->status_label }}</span></td>
                        <td>
                            <button
                                type="button"
                                class="btn-detail"
                                data-name="{{ $application->full_name }}"
                                data-email="{{ $application->email }}"
                                data-phone="{{ $application->phone }}"
                                data-position="{{ $application->position_label }}"
                                data-status="{{ $application->status_label }}"
                                data-submitted="{{ $application->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}"
                                data-reason="{{ $application->rejection_reason }}"
                                onclick="openApplicationDetail(this)"
                            >Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="padding:35px;text-align:center;color:#94a3b8;">Belum ada riwayat pengajuan admin.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($applications->hasPages())
            <div class="pagination-wrapper">{{ $applications->links() }}</div>
        @endif
    </div>

    <div id="registrationModal" class="modal-overlay" aria-hidden="true">
        <section class="modal-card" role="dialog" aria-modal="true" aria-labelledby="registrationTitle">
            <div class="modal-header">
                <h3 id="registrationTitle">Daftarkan Admin</h3>
                <button type="button" class="modal-close" aria-label="Tutup" onclick="closeModal('registrationModal')">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.pool.applications.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-field full">
                        <label for="full_name">Nama lengkap</label>
                        <input id="full_name" name="full_name" value="{{ old('full_name') }}" required maxlength="255" autocomplete="name">
                        @error('full_name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email">
                        @error('email')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-field">
                        <label for="phone">Nomor HP aktif</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required minlength="9" maxlength="20" autocomplete="tel">
                        @error('phone')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-field full">
                        <label for="position">Jabatan</label>
                        <select id="position" name="position" required>
                            <option value="">Pilih jabatan</option>
                            <option value="kasir_tiket" @selected(old('position') === 'kasir_tiket')>Kasir Tiket</option>
                            <option value="pengelola_fasilitas" @selected(old('position') === 'pengelola_fasilitas')>Pengelola Fasilitas</option>
                            <option value="staf_admin" @selected(old('position') === 'staf_admin')>Staf Admin</option>
                        </select>
                        @error('position')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" required minlength="8" maxlength="72" autocomplete="new-password">
                        @error('password')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-field">
                        <label for="password_confirmation">Konfirmasi password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" maxlength="72" autocomplete="new-password">
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('registrationModal')">Batal</button>
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-paper-plane"></i> Kirim</button>
                </div>
            </form>
        </section>
    </div>

    <div id="applicationDetailModal" class="modal-overlay" aria-hidden="true">
        <section class="modal-card" role="dialog" aria-modal="true" aria-labelledby="detailTitle">
            <div class="modal-header">
                <h3 id="detailTitle">Detail Pengajuan Admin</h3>
                <button type="button" class="modal-close" aria-label="Tutup" onclick="closeModal('applicationDetailModal')">&times;</button>
            </div>
            <dl class="detail-list">
                <dt>Nama lengkap</dt><dd id="detailName"></dd>
                <dt>Email</dt><dd id="detailEmail"></dd>
                <dt>Nomor HP</dt><dd id="detailPhone"></dd>
                <dt>Jabatan</dt><dd id="detailPosition"></dd>
                <dt>Tanggal diajukan</dt><dd id="detailSubmitted"></dd>
                <dt>Status</dt><dd id="detailStatus"></dd>
                <dt id="detailReasonLabel" hidden>Alasan penolakan</dt><dd id="detailReason" hidden></dd>
            </dl>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    function openRegistrationModal() {
        const modal = document.getElementById('registrationModal');
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        document.getElementById('full_name').focus();
    }

    function openApplicationDetail(button) {
        const detail = button.dataset;
        document.getElementById('detailName').textContent = detail.name;
        document.getElementById('detailEmail').textContent = detail.email;
        document.getElementById('detailPhone').textContent = detail.phone;
        document.getElementById('detailPosition').textContent = detail.position;
        document.getElementById('detailSubmitted').textContent = detail.submitted;
        document.getElementById('detailStatus').textContent = detail.status;

        const hasReason = detail.reason !== '';
        document.getElementById('detailReasonLabel').hidden = !hasReason;
        document.getElementById('detailReason').hidden = !hasReason;
        document.getElementById('detailReason').textContent = detail.reason;

        const modal = document.getElementById('applicationDetailModal');
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }

    document.querySelectorAll('.modal-overlay').forEach((modal) => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal(modal.id);
            }
        });
    });

    @if($errors->any())
        openRegistrationModal();
    @endif
</script>
@endpush
