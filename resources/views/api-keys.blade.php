@extends('layouts.app')

@section('title', __('api_keys.title'))

@push('styles')
<style>
    .ak-header { margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
    .ak-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; }
    .ak-header p { color: var(--text-muted); font-size: 0.875rem; margin: 0.25rem 0 0; }
    .btn-gold { background: linear-gradient(135deg, var(--gold), #e8c84a); color: #0a0d14; border: none; padding: 0.65rem 1.4rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: opacity 0.2s, transform 0.2s; }
    .btn-gold:hover { opacity: 0.9; transform: translateY(-1px); }
    .ak-table-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
    .ak-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .ak-table thead th { padding: 0.85rem 1.25rem; text-align: left; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); border-bottom: 1px solid var(--border); background: var(--bg-secondary); }
    .ak-table tbody td { padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); color: var(--text-primary); vertical-align: middle; }
    .ak-table tbody tr:last-child td { border-bottom: none; }
    .ak-table tbody tr:hover { background: rgba(255,255,255,0.02); }
    .ak-prefix { font-family: 'Courier New', monospace; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 6px; padding: 0.2rem 0.6rem; font-size: 0.8rem; color: var(--gold); letter-spacing: 0.04em; }
    .ak-name { font-weight: 500; }
    .ak-date { color: var(--text-muted); font-size: 0.8rem; }
    .ak-never { color: var(--text-muted); font-style: italic; font-size: 0.8rem; }
    .btn-delete { background: transparent; border: 1px solid rgba(239,68,68,0.35); color: #f87171; border-radius: 6px; padding: 0.3rem 0.75rem; font-size: 0.775rem; font-weight: 500; cursor: pointer; transition: all 0.2s; }
    .btn-delete:hover { background: rgba(239,68,68,0.1); border-color: #f87171; }
    .ak-empty { text-align: center; padding: 3rem 1rem; color: var(--text-muted); }

    /* Modal */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); backdrop-filter: blur(4px); z-index: 200; align-items: center; justify-content: center; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2rem; width: 100%; max-width: 460px; box-shadow: 0 24px 64px rgba(0,0,0,0.5); }
    .modal-box h3 { font-size: 1.1rem; font-weight: 600; margin: 0 0 0.5rem; }
    .modal-box p { font-size: 0.85rem; color: var(--text-muted); margin: 0 0 1.25rem; }
    .modal-input { width: 100%; padding: 0.65rem 0.9rem; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.2s; box-sizing: border-box; }
    .modal-input:focus { border-color: var(--gold); }
    .modal-actions { display: flex; gap: 0.75rem; margin-top: 1.25rem; justify-content: flex-end; }
    .btn-cancel { background: transparent; border: 1px solid var(--border); color: var(--text-muted); padding: 0.6rem 1.1rem; border-radius: 8px; font-size: 0.875rem; cursor: pointer; transition: border-color 0.2s; }
    .btn-cancel:hover { border-color: var(--text-muted); color: var(--text-primary); }

    /* Key reveal box */
    .key-reveal { background: var(--bg-secondary); border: 1px solid rgba(212,175,55,0.3); border-radius: 10px; padding: 1rem; margin: 1rem 0; }
    .key-reveal label { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.4rem; }
    .key-reveal-row { display: flex; gap: 0.5rem; align-items: center; }
    .key-reveal-value { font-family: 'Courier New', monospace; font-size: 0.78rem; color: var(--gold); word-break: break-all; flex: 1; background: transparent; border: none; outline: none; resize: none; width: 100%; }
    .btn-copy { background: transparent; border: 1px solid rgba(212,175,55,0.4); color: var(--gold); border-radius: 6px; padding: 0.3rem 0.7rem; font-size: 0.75rem; cursor: pointer; white-space: nowrap; transition: background 0.2s; }
    .btn-copy:hover { background: rgba(212,175,55,0.1); }
    .key-warning { font-size: 0.78rem; color: #f87171; margin-top: 0.5rem; }

    /* Toast */
    .ak-toast { position: fixed; bottom: 1.5rem; right: 1.5rem; background: #1a1e26; border: 1px solid var(--border); border-radius: 10px; padding: 0.75rem 1.25rem; font-size: 0.875rem; color: var(--text-primary); box-shadow: 0 8px 24px rgba(0,0,0,0.4); z-index: 300; transform: translateY(20px); opacity: 0; transition: all 0.3s; pointer-events: none; }
    .ak-toast.show { opacity: 1; transform: translateY(0); }
    .ak-toast.success { border-color: rgba(5,150,105,0.5); color: #6ee7b7; }
    .ak-toast.error { border-color: rgba(239,68,68,0.4); color: #f87171; }
</style>
@endpush

@section('content')
<main>
    <div class="ak-header">
        <div>
            <h1>{{ __('api_keys.title') }}</h1>
            <p>{{ __('api_keys.subtitle') }}</p>
        </div>
        <button class="btn-gold" onclick="openCreateModal()">{{ __('api_keys.new_key') }}</button>
    </div>

    <div class="ak-table-wrap">
        <table class="ak-table">
            <thead>
                <tr>
                    <th>{{ __('api_keys.col_name') }}</th>
                    <th>{{ __('api_keys.col_prefix') }}</th>
                    <th>{{ __('api_keys.col_created') }}</th>
                    <th>{{ __('api_keys.col_last_used') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="keysBody">
                @forelse($apiKeys as $key)
                <tr id="row-{{ $key->id }}">
                    <td class="ak-name">{{ $key->name }}</td>
                    <td><span class="ak-prefix">{{ $key->prefix }}...</span></td>
                    <td class="ak-date">{{ $key->created_at->format('d M Y') }}</td>
                    <td>
                        @if($key->last_used_at)
                            <span class="ak-date">{{ \Carbon\Carbon::parse($key->last_used_at)->diffForHumans() }}</span>
                        @else
                            <span class="ak-never">{{ __('api_keys.never_used') }}</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn-delete" data-id="{{ $key->id }}" data-name="{{ $key->name }}">{{ __('api_keys.delete') }}</button>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="5" class="ak-empty">{{ __('api_keys.empty_state') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p style="font-size:0.8rem; color:var(--text-muted); margin-top:1rem;">
        {{ __('api_keys.shown_once_note') }}
    </p>
</main>

{{-- Create Key Modal --}}
<div class="modal-overlay" id="createModal">
    <div class="modal-box">
        <h3>{{ __('api_keys.create_title') }}</h3>
        <p>{{ __('api_keys.create_subtitle') }}</p>
        <input type="text" class="modal-input" id="keyNameInput" placeholder="{{ __('api_keys.key_name_placeholder') }}" maxlength="50" autocomplete="off">
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeCreateModal()">{{ __('api_keys.cancel') }}</button>
            <button class="btn-gold" onclick="submitCreateKey()" id="createBtn">{{ __('api_keys.create_key') }}</button>
        </div>
    </div>
</div>

{{-- Key Reveal Modal --}}
<div class="modal-overlay" id="revealModal">
    <div class="modal-box">
        <h3>{{ __('api_keys.reveal_title') }}</h3>
        <p>{{ __('api_keys.reveal_subtitle') }}</p>
        <div class="key-reveal">
            <label>{{ __('api_keys.your_api_key') }}</label>
            <div class="key-reveal-row">
                <textarea class="key-reveal-value" id="revealKeyValue" rows="2" readonly></textarea>
                <button class="btn-copy" onclick="copyKey()">{{ __('api_keys.copy') }}</button>
            </div>
        </div>
        <p class="key-warning">{{ __('api_keys.key_warning') }}</p>
        <div class="modal-actions">
            <button class="btn-gold" onclick="closeRevealModal()">{{ __('api_keys.done') }}</button>
        </div>
    </div>
</div>

<div class="ak-toast" id="toast"></div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

// ── Toast ──────────────────────────────────────────
function showToast(msg, type) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'ak-toast show' + (type ? ' ' + type : '');
    clearTimeout(t._timer);
    t._timer = setTimeout(function() { t.className = 'ak-toast'; }, 3500);
}

// ── Delete via event delegation ────────────────────
document.getElementById('keysBody').addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-delete');
    if (!btn) return;
    const id = btn.dataset.id;
    const name = btn.dataset.name;
    if (!confirm('Delete API key "' + name + '"? This cannot be undone.')) return;

    fetch('/api-keys/' + encodeURIComponent(id), {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    })
    .then(function(r) { return r.json().then(function(d) { return { status: r.status, data: d }; }); })
    .then(function(res) {
        if (res.status === 200) {
            const row = document.getElementById('row-' + id);
            if (row) row.remove();
            showToast('API key deleted.', 'success');
            if (!document.querySelector('#keysBody tr')) {
                const tr = document.createElement('tr');
                tr.id = 'emptyRow';
                const td = document.createElement('td');
                td.colSpan = 5;
                td.className = 'ak-empty';
                td.textContent = '{{ __('api_keys.empty_state') }}';
                tr.appendChild(td);
                document.getElementById('keysBody').appendChild(tr);
            }
        } else {
            showToast(res.data.message || 'Failed to delete key.', 'error');
        }
    })
    .catch(function() { showToast('Network error. Please try again.', 'error'); });
});

// ── Create Modal ───────────────────────────────────
function openCreateModal() {
    document.getElementById('keyNameInput').value = '';
    document.getElementById('createModal').classList.add('active');
    setTimeout(function() { document.getElementById('keyNameInput').focus(); }, 80);
}
function closeCreateModal() {
    document.getElementById('createModal').classList.remove('active');
}
document.getElementById('createModal').addEventListener('click', function(e) {
    if (e.target === this) closeCreateModal();
});
document.getElementById('keyNameInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') submitCreateKey();
});

function submitCreateKey() {
    const nameInput = document.getElementById('keyNameInput');
    const name = nameInput.value.trim();
    if (!name) { showToast('Please enter a key name.', 'error'); return; }

    const btn = document.getElementById('createBtn');
    btn.disabled = true;
    btn.textContent = 'Creating...';

    fetch('/api-keys', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ name: name })
    })
    .then(function(r) { return r.json().then(function(d) { return { status: r.status, data: d }; }); })
    .then(function(res) {
        btn.disabled = false;
        btn.textContent = '{{ __('api_keys.create_key') }}';
        if (res.status === 201) {
            closeCreateModal();
            addKeyRow(res.data, name);
            openRevealModal(res.data.key);
        } else {
            var errors = res.data.errors;
            var msg = errors ? Object.values(errors).flat()[0] : (res.data.message || 'Failed to create key.');
            showToast(msg, 'error');
        }
    })
    .catch(function() {
        btn.disabled = false;
        btn.textContent = '{{ __('api_keys.create_key') }}';
        showToast('Network error. Please try again.', 'error');
    });
}

function addKeyRow(data, fallbackName) {
    var emptyRow = document.getElementById('emptyRow');
    if (emptyRow) emptyRow.remove();

    var prefix = data.prefix || data.key.substring(0, 12);
    var now = new Date();
    var dateStr = now.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    var id = data.id || ('tmp-' + Date.now());
    var keyName = data.name || fallbackName || '';

    var tr = document.createElement('tr');
    tr.id = 'row-' + id;

    var tdName = document.createElement('td');
    tdName.className = 'ak-name';
    tdName.textContent = keyName;

    var tdPrefix = document.createElement('td');
    var span = document.createElement('span');
    span.className = 'ak-prefix';
    span.textContent = prefix + '...';
    tdPrefix.appendChild(span);

    var tdDate = document.createElement('td');
    tdDate.className = 'ak-date';
    tdDate.textContent = dateStr;

    var tdLastUsed = document.createElement('td');
    var neverSpan = document.createElement('span');
    neverSpan.className = 'ak-never';
    neverSpan.textContent = '{{ __('api_keys.never_used') }}';
    tdLastUsed.appendChild(neverSpan);

    var tdAction = document.createElement('td');
    var delBtn = document.createElement('button');
    delBtn.className = 'btn-delete';
    delBtn.dataset.id = id;
    delBtn.dataset.name = keyName;
    delBtn.textContent = '{{ __('api_keys.delete') }}';
    tdAction.appendChild(delBtn);

    tr.appendChild(tdName);
    tr.appendChild(tdPrefix);
    tr.appendChild(tdDate);
    tr.appendChild(tdLastUsed);
    tr.appendChild(tdAction);

    var tbody = document.getElementById('keysBody');
    if (tbody.firstChild) {
        tbody.insertBefore(tr, tbody.firstChild);
    } else {
        tbody.appendChild(tr);
    }
}

// ── Reveal Modal ───────────────────────────────────
function openRevealModal(key) {
    document.getElementById('revealKeyValue').value = key;
    document.getElementById('revealModal').classList.add('active');
}
function closeRevealModal() {
    document.getElementById('revealModal').classList.remove('active');
    document.getElementById('revealKeyValue').value = '';
}
function copyKey() {
    var val = document.getElementById('revealKeyValue').value;
    navigator.clipboard.writeText(val).then(function() { showToast('Key copied to clipboard.', 'success'); });
}
</script>
@endpush
@endsection
