@extends('layout.admin')

@section('title', 'Daftar Pengajuan Akun Admin - NEXPOOL')
@section('header-title', 'Pengajuan Akun Admin Wisata')
@section('header-subtitle', 'Kelola persetujuan calon admin kolam renang NEXPOOL')

@push('styles')
<style>
    .card-filter {
        background: #fff;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }
    .filter-group {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .filter-input {
        padding: 9px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        min-width: 240px;
    }
    .filter-input:focus {
        border-color: #2563eb;
    }
    .filter-select {
        padding: 9px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        background: #fff;
    }
    .btn-filter {
        padding: 9px 18px;
        background: #1e3c72;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-filter:hover { background: #16315f; }
    .btn-reset {
        padding: 9px 14px;
        background: #f3f4f6;
        color: #4b5563;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 13px;
        text-decoration: none;
    }
    .table-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
        overflow-x: auto;
    }
    .app-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 950px;
    }
    .app-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        text-align: left;
        padding: 14px 16px;
        border-bottom: 2px solid #e2e8f0;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .app-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        vertical-align: middle;
    }
    .app-table tr:hover {
        background: #f8fafc;
    }
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .badge-pending  { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .badge-approved { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

    .wa-link {
        color: #059669;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .wa-link:hover { text-decoration: underline; color: #047857; }

    .action-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-approve {
        padding: 7px 13px;
        background: #10b981;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background .15s;
    }
    .btn-approve:hover { background: #059669; }

    .btn-reject {
        padding: 7px 13px;
        background: #ef4444;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background .15s;
    }
    .btn-reject:hover { background: #dc2626; }

    .pagination-wrapper {
        padding: 18px 24px;
        display: flex;
        justify-content: flex-end;
    }

    /* Modal Styling */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, .6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-overlay.active { display: flex; }
    .modal-card {
        background: #fff;
        border-radius: 14px;
        width: 100%;
        max-width: 480px;
        padding: 26px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,.2);
    }
    .modal-card h3 {
        color: #dc2626;
        font-size: 18px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-card p {
        color: #4b5563;
        font-size: 13px;
        margin-bottom: 16px;
    }
    .modal-card textarea {
        width: 100%;
        min-height: 110px;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        resize: vertical;
        box-sizing: border-box;
    }
    .modal-card textarea:focus { border-color: #dc2626; }
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 18px;
    }
    .btn-modal-cancel {
        padding: 9px 18px;
        background: #f3f4f6;
        color: #4b5563;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
    }
    .btn-modal-reject {
        padding: 9px 20px;
        background: #dc2626;
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
    }
    .btn-modal-reject:hover { background: #b91c1c; }

    .alert {
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-success { background: #d1fae5; border: 1px solid #86efac; color: #065f46; }
    .alert-danger  { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
</style>
@endpush

@section('content')

    {{-- Pesan Sukses / Error --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    {{-- Filter & Search Form --}}
    <div class="card-filter">
        <form method="GET" action="{{ route('admin.master.applications.index') }}" class="filter-group">
            <input
                type="text"
                name="search"
                class="filter-input"
                placeholder="Cari nama, username, email..."
                value="{{ request('search', $search) }}"
            >

            <select name="status" class="filter-select">
                <option value="pending"  {{ request('status', $status) == 'pending'  ? 'selected' : '' }}>Status: Menunggu (Pending)</option>
                <option value="approved" {{ request('status', $status) == 'approved' ? 'selected' : '' }}>Status: Disetujui (Approved)</option>
                <option value="rejected" {{ request('status', $status) == 'rejected' ? 'selected' : '' }}>Status: Ditolak (Rejected)</option>
                <option value="all"      {{ request('status', $status) == 'all'      ? 'selected' : '' }}>Semua Status</option>
            </select>

            <button type="submit" class="btn-filter">
                <i class="fa-solid fa-magnifying-glass"></i> Filter
            </button>

            @if(request('search') || (request('status') && request('status') !== 'pending'))
                <a href="{{ route('admin.master.applications.index') }}" class="btn-reset">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            @endif
        </form>

        <div style="font-size: 13px; color: #64748b;">
            <i class="fa-solid fa-clock" style="color: #f59e0b;"></i>
            <strong>{{ $pendingCount }}</strong> pengajuan menunggu persetujuan
        </div>
    </div>

    {{-- Tabel Pengajuan --}}
    <div class="table-container">
        <table class="app-table">
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nama & Username</th>
                    <th>Email</th>
                    <th>No HP / WhatsApp</th>
                    <th>Jabatan</th>
                    <th>Wisata Kolam</th>
                    <th>Tanggal Diajukan</th>
                    <th>Status</th>
                    <th style="text-align:center; width:180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $index => $app)
                    @php
                        // Format no HP untuk WhatsApp (08xx -> 628xx)
                        $cleanPhone = preg_replace('/[^0-9]/', '', $app->phone);
                        if (str_starts_with($cleanPhone, '0')) {
                            $waNumber = '62' . substr($cleanPhone, 1);
                        } else {
                            $waNumber = $cleanPhone;
                        }
                    @endphp
                    <tr>
                        <td>{{ $applications->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $app->full_name }}</strong>
                            <div style="font-size:12px; color:#64748b; font-family:monospace;">@ {{ $app->username }}</div>
                        </td>
                        <td>{{ $app->email }}</td>
                        <td>
                            <a href="https://wa.me/{{ $waNumber }}?text=Halo%20{{ urlencode($app->full_name) }},%20kami%20dari%20Admin%20Master%20NEXPOOL"
                               target="_blank"
                               class="wa-link"
                               title="Hubungi lewat WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i> {{ $app->phone }}
                            </a>
                        </td>
                        <td>{{ $app->position_label }}</td>
                        <td>
                            <strong>{{ $app->pool->name ?? $app->pool_id }}</strong>
                            <div style="font-size:11px; color:#94a3b8;">Kode: {{ $app->access_code }}</div>
                        </td>
                        <td>
                            <div>{{ $app->created_at?->translatedFormat('d M Y') ?? '-' }}</div>
                            <div style="font-size:11px; color:#94a3b8;">{{ $app->created_at?->format('H:i') }} WIB</div>
                        </td>
                        <td>
                            @if($app->status === 'pending')
                                <span class="badge badge-pending">
                                    <i class="fa-solid fa-clock"></i> Menunggu
                                </span>
                            @elseif($app->status === 'approved')
                                <span class="badge badge-approved">
                                    <i class="fa-solid fa-circle-check"></i> Disetujui
                                </span>
                            @else
                                <span class="badge badge-rejected" title="{{ $app->rejection_reason }}">
                                    <i class="fa-solid fa-circle-xmark"></i> Ditolak
                                </span>
                                @if($app->rejection_reason)
                                    <div style="font-size:11px; color:#dc2626; margin-top:3px; max-width:180px;" title="{{ $app->rejection_reason }}">
                                        <em>"{{ Str::limit($app->rejection_reason, 35) }}"</em>
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($app->status === 'pending')
                                <div class="action-group" style="justify-content:center;">
                                    {{-- Form Approve --}}
                                    <form method="POST"
                                          action="{{ route('admin.master.applications.approve', $app->id) }}"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengajuan atas nama {{ addslashes($app->full_name) }}?\n\nSistem akan membuat akun dan mengirimkan password sementara ke email {{ $app->email }}.');">
                                        @csrf
                                        <button type="submit" class="btn-approve" title="Setujui dan buat akun">
                                            <i class="fa-solid fa-check"></i> Setujui
                                        </button>
                                    </form>

                                    {{-- Tombol Buka Modal Reject --}}
                                    <button type="button"
                                            class="btn-reject"
                                            onclick="openRejectModal('{{ $app->id }}', '{{ addslashes($app->full_name) }}')"
                                            title="Tolak pengajuan">
                                        <i class="fa-solid fa-xmark"></i> Tolak
                                    </button>
                                </div>
                            @elseif($app->status === 'approved')
                                <span style="font-size:12px; color:#059669; font-weight:600;">
                                    <i class="fa-solid fa-check-double"></i> Akun Aktif
                                </span>
                            @else
                                <span style="font-size:12px; color:#94a3b8;">
                                    <i class="fa-solid fa-ban"></i> Selesai
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center; padding: 40px 20px; color:#94a3b8;">
                            <i class="fa-solid fa-inbox" style="font-size:32px; margin-bottom:10px; display:block;"></i>
                            Tidak ada data pengajuan yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($applications->hasPages())
            <div class="pagination-wrapper">
                {{ $applications->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL PENOLAKAN --}}
    <div id="rejectModal" class="modal-overlay">
        <div class="modal-card">
            <h3><i class="fa-solid fa-triangle-exclamation"></i> Tolak Pengajuan</h3>
            <p>Tuliskan alasan penolakan untuk <strong id="modalApplicantName">-</strong>. Alasan ini akan dikirimkan ke email calon admin.</p>

            <form id="rejectForm" method="POST" action="">
                @csrf
                <textarea
                    name="rejection_reason"
                    id="rejection_reason"
                    required
                    minlength="10"
                    placeholder="Contoh: Nomor telepon tidak dapat dihubungi atau data verifikasi tidak valid..."></textarea>

                <div class="modal-actions">
                    <button type="button" class="btn-modal-cancel" onclick="closeRejectModal()">Batal</button>
                    <button type="submit" class="btn-modal-reject">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openRejectModal(appId, name) {
        const modal = document.getElementById('rejectModal');
        const form  = document.getElementById('rejectForm');
        const nameEl = document.getElementById('modalApplicantName');
        const textarea = document.getElementById('rejection_reason');

        form.action = `/admin/master/pengajuan/${appId}/reject`;
        nameEl.innerText = name;
        textarea.value = '';

        modal.classList.add('active');
        textarea.focus();
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('active');
    }

    // Tutup modal jika klik di luar area modal card
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });
</script>
@endpush

