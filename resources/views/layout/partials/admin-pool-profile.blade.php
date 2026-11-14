@php
    $poolName = trim((string) session('admin_pool_nama', ''));
    $displayName = $poolName === '' ? 'Admin' : 'Admin '.$poolName;
@endphp

<div style="display:flex;align-items:center;gap:12px;min-width:0;max-width:70%;flex:0 1 auto;margin-left:auto;">
    <div style="min-width:0;flex:1 1 auto;text-align:right;">
        <strong style="display:block;max-width:100%;color:#1e293b;font-size:13px;font-weight:700;line-height:1.25;overflow-wrap:anywhere;white-space:normal;">
            {{ $displayName }}
        </strong>
    </div>
    <div aria-hidden="true" style="display:flex;flex:0 0 42px;align-items:center;justify-content:center;width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#2563eb,#38bdf8);box-shadow:0 6px 15px rgba(37,99,235,.2);color:#fff;font-size:15px;font-weight:700;">
        A
    </div>
</div>
