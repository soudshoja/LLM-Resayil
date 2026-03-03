@extends('layouts.app')

@push('styles')
<style>
    .models-table { width: 100%; border-collapse: collapse; }
    .models-table th { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; padding: 0.6rem 0.75rem; border-bottom: 1px solid var(--border); text-align: left; }
    .models-table td { padding: 0.75rem; border-bottom: 1px solid rgba(30,34,48,0.5); font-size: 0.875rem; }
    .models-table tr:last-child td { border-bottom: none; }
    .toggle-btn { cursor: pointer; position: relative; width: 48px; height: 24px; background: #374151; border-radius: 9999px; transition: all 0.2s; user-select: none; }
    .toggle-btn.active { background: #d4af37; }
    .toggle-btn::after { content: ''; position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background: white; border-radius: 50%; transition: all 0.2s; }
    .toggle-btn.active::after { left: 26px; }
    .toggle-btn.loading { opacity: 0.5; pointer-events: none; }
    .badge-local { background: #1e3a8a; color: #93c5fd; }
    .badge-cloud { background: #7c2d12; color: #fdba74; }
    .badge-small { background: #1e293b; color: #94a3b8; }
    .badge-medium { background: #1e3a8a; color: #60a5fa; }
    .badge-large { background: #7c2d12; color: #fca5a5; }
    .model-id { font-family: monospace; font-size: 0.8rem; color: var(--gold); }
</style>
@endpush

@section('content')
<main>
    <div style="margin-bottom:2rem">
        <h1 style="font-size:1.5rem;font-weight:700">{{ __('admin.models.title') }}</h1>
        <p class="text-secondary text-sm">{{ __('admin.models.subtitle') }}</p>
    </div>

    @php
        $totalModels = count($modelList);
        $activeModels = count(array_filter($modelList, fn($m) => $m['is_active']));
        $localModels = count(array_filter($modelList, fn($m) => $m['type'] === 'local'));
        $cloudModels = count(array_filter($modelList, fn($m) => $m['type'] === 'cloud'));
    @endphp

    <!-- Stats -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem">
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1.25rem">
            <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem">{{ __('admin.models.total_models') }}</div>
            <div style="font-size:1.75rem;font-weight:700;color:var(--gold)">{{ $totalModels }}</div>
        </div>
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1.25rem">
            <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem">{{ __('admin.models.active_models') }}</div>
            <div id="activeCount" style="font-size:1.75rem;font-weight:700;color:var(--gold)">{{ $activeModels }}</div>
        </div>
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1.25rem">
            <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem">{{ __('admin.models.local_models') }}</div>
            <div style="font-size:1.75rem;font-weight:700;color:#3b82f6">{{ $localModels }}</div>
        </div>
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1.25rem">
            <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem">{{ __('admin.models.cloud_models') }}</div>
            <div style="font-size:1.75rem;font-weight:700;color:#f59e0b">{{ $cloudModels }}</div>
        </div>
    </div>

    <!-- Filter & Actions Bar -->
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1rem;margin-bottom:1rem">
        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;align-items:center;margin-bottom:0.75rem">
            <div style="display:flex;align-items:center;gap:0.4rem">
                <span style="font-size:0.75rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.05em">{{ __('admin.models.family') }}:</span>
                <select id="filterFamily" onchange="applyFilters()" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.6rem;color:var(--text);font-size:0.8rem">
                    <option value="">{{ __('admin.models.all_families') }}</option>
                    @foreach(array_unique(array_column($modelList, 'family')) as $fam)
                    <option value="{{ $fam }}">{{ $fam }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;align-items:center;gap:0.4rem">
                <span style="font-size:0.75rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.05em">{{ __('admin.models.category') }}:</span>
                <select id="filterCategory" onchange="applyFilters()" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.6rem;color:var(--text);font-size:0.8rem">
                    <option value="">{{ __('admin.models.all_families') }}</option>
                    <option value="chat">{{ __('admin.models.chat') }}</option>
                    <option value="code">{{ __('admin.models.code') }}</option>
                    <option value="embedding">{{ __('admin.models.embedding') }}</option>
                    <option value="vision">{{ __('admin.models.vision') }}</option>
                    <option value="thinking">{{ __('admin.models.thinking') }}</option>
                    <option value="tools">{{ __('admin.models.tools') }}</option>
                </select>
            </div>
            <div style="display:flex;align-items:center;gap:0.4rem">
                <span style="font-size:0.75rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.05em">{{ __('admin.models.type') }}:</span>
                <select id="filterType" onchange="applyFilters()" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.6rem;color:var(--text);font-size:0.8rem">
                    <option value="">{{ __('admin.models.all_families') }}</option>
                    <option value="local">{{ __('admin.models.local') }}</option>
                    <option value="cloud">{{ __('admin.models.cloud') }}</option>
                </select>
            </div>
            <div style="display:flex;align-items:center;gap:0.4rem">
                <span style="font-size:0.75rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.05em">{{ __('admin.models.size') }}:</span>
                <select id="filterSize" onchange="applyFilters()" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.6rem;color:var(--text);font-size:0.8rem">
                    <option value="">{{ __('admin.models.all_families') }}</option>
                    <option value="small">{{ __('admin.models.small') }}</option>
                    <option value="medium">{{ __('admin.models.medium') }}</option>
                    <option value="large">{{ __('admin.models.large') }}</option>
                </select>
            </div>
            <div style="position:relative;flex:1;min-width:200px">
                <input type="text" id="searchInput" oninput="applyFilters()" placeholder="{{ __('admin.models.search_models') }}" style="width:100%;background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.75rem 0.35rem 2.25rem;color:var(--text);font-size:0.8rem;box-sizing:border-box">
                <svg style="position:absolute;left:0.6rem;top:50%;transform:translateY(-50%);width:0.9rem;height:0.9rem;color:var(--text-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
        <div style="display:flex;gap:0.5rem">
            <button type="button" onclick="bulkUpdate('enable')" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.75rem;color:var(--text-muted);cursor:pointer;font-size:0.8rem">
                <svg style="width:0.85rem;height:0.85rem;vertical-align:middle" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ __('admin.models.enable') }}
            </button>
            <button type="button" onclick="bulkUpdate('disable')" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.75rem;color:var(--text-muted);cursor:pointer;font-size:0.8rem">
                <svg style="width:0.85rem;height:0.85rem;vertical-align:middle" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ __('admin.models.disable') }}
            </button>
            <span id="visibleCount" style="font-size:0.75rem;color:var(--text-muted);align-self:center;margin-left:0.5rem"></span>
        </div>
    </div>

    <!-- Models Table -->
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden">
        <div style="overflow-x:auto">
            <table class="models-table">
                <thead>
                    <tr>
                        <th style="width:40px"><input type="checkbox" onclick="toggleAll(this)"></th>
                        <th>{{ __('admin.models.model_id') }}</th>
                        <th>{{ __('admin.models.family') }}</th>
                        <th>{{ __('admin.models.category') }}</th>
                        <th>{{ __('admin.models.type') }}</th>
                        <th>{{ __('admin.models.size') }}</th>
                        <th>{{ __('admin.models.multiplier') }}</th>
                        <th>{{ __('admin.models.status') }}</th>
                        <th>{{ __('admin.models.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="modelsTableBody">
                    @foreach($modelList as $model)
                    <tr class="model-row"
                        data-model-id="{{ $model['model_id'] }}"
                        data-search="{{ $model['name'] }} {{ $model['model_id'] }} {{ $model['family'] }}"
                        data-family="{{ $model['family'] }}"
                        data-category="{{ $model['category'] }}"
                        data-type="{{ $model['type'] }}"
                        data-size="{{ $model['size'] }}"
                        data-default-multiplier="{{ $model['credit_multiplier'] }}">
                        <td><input type="checkbox" class="model-checkbox" value="{{ $model['model_id'] }}"></td>
                        <td>
                            <div class="model-id">{{ $model['model_id'] }}</div>
                            @if($model['credit_multiplier_override'] !== null)
                                <div class="multiplier-override" style="font-size:0.65rem;color:#f59e0b">{{ __('admin.models.override', ['value' => $model['credit_multiplier_override']]) }}</div>
                            @endif
                        </td>
                        <td>{{ $model['family'] }}</td>
                        <td>
                            @php
                                $catEmoji = ['chat'=>'💬','code'=>'💻','embedding'=>'🔗','vision'=>'👁','thinking'=>'🧠','tools'=>'🔧'];
                            @endphp
                            <span class="badge" style="background:rgba(212,175,55,0.12);color:var(--gold);border:1px solid rgba(212,175,55,0.25)">{{ ($catEmoji[$model['category']] ?? '') }} {{ $model['category'] }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $model['type'] }}">{{ $model['type'] }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $model['size'] }}">{{ $model['size'] }}</span>
                        </td>
                        <td class="multiplier-cell">{{ $model['credit_multiplier_override'] ?? $model['credit_multiplier'] }}</td>
                        <td>
                            <div class="toggle-btn {{ $model['is_active'] ? 'active' : '' }}"
                                 onclick="toggleModelStatus(this)"
                                 data-model-id="{{ $model['model_id'] }}"
                                 data-active="{{ $model['is_active'] ? '1' : '0' }}"></div>
                        </td>
                        <td>
                            <button type="button" onclick="openEditModal('{{ $model['model_id'] }}', '{{ $model['name'] }}', {{ $model['credit_multiplier'] }}, '{{ $model['credit_multiplier_override'] ?? '' }}', {{ $model['is_active'] ? '1' : '0' }})" style="background:var(--bg-card);border:1px solid var(--border);border-radius:6px;padding:0.3rem 0.6rem;color:var(--text);font-size:0.75rem;cursor:pointer;transition:all 0.2s">{{ __('admin.models.edit_model') }}</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:1000;align-items:center;justify-content:center">
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;width:90%;max-width:500px;max-height:90vh;overflow-y:auto">
            <div style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
                <h2 style="font-size:1.25rem;font-weight:600">{{ __('admin.models.edit_model') }}</h2>
                <button type="button" onclick="closeEditModal()" style="background:none;border:none;color:var(--text-muted);cursor:pointer">
                    <svg style="width:1.5rem;height:1.5rem" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editForm" method="POST" action="{{ route('admin.models.update') }}" style="padding:1.5rem">
                @csrf
                <input type="hidden" name="model_id" id="editModelId">
                <div style="margin-bottom:1.5rem">
                    <label style="display:block;font-size:0.875rem;font-weight:500;margin-bottom:0.5rem">{{ __('admin.models.credit_multiplier_override') }}</label>
                    <input type="number" name="credit_multiplier_override" id="editMultiplier" step="0.1" min="0" placeholder="{{ __('admin.models.leave_empty_default') }}" style="width:100%;background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;padding:0.75rem;color:var(--text)">
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.5rem">{{ __('admin.models.use_default_config') }}</p>
                </div>
                <div style="margin-bottom:1.5rem">
                    <label style="display:block;font-size:0.875rem;font-weight:500;margin-bottom:0.5rem">{{ __('admin.models.status') }}</label>
                    <div style="display:flex;gap:1rem">
                        <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
                            <input type="radio" name="is_active" value="1" checked>
                            <span>{{ __('admin.models.active_status') }}</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
                            <input type="radio" name="is_active" value="0">
                            <span>{{ __('admin.models.inactive_status') }}</span>
                        </label>
                    </div>
                </div>
                <div style="display:flex;justify-content:flex-end;gap:0.75rem">
                    <button type="button" onclick="closeEditModal()" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;padding:0.5rem 1rem;color:var(--text);cursor:pointer">{{ __('admin.dashboard.cancel') }}</button>
                    <button type="submit" id="editSaveBtn" style="background:var(--gold);border:none;border-radius:8px;padding:0.5rem 1rem;color:#0f1115;font-weight:600;cursor:pointer">{{ __('admin.models.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
const UPDATE_URL = '{{ route("admin.models.update") }}';

function getCsrf() {
    return document.querySelector('meta[name="csrf-token"]')?.content
        || document.querySelector('input[name="_token"]')?.value
        || '';
}

// ── Toast notification ────────────────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const toast = document.createElement('div');
    toast.textContent = msg;
    const bg     = type === 'success' ? '#166534' : '#7f1d1d';
    const border = type === 'success' ? '#22c55e' : '#ef4444';
    toast.style.cssText = `position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;
        background:${bg};border:1px solid ${border};color:#fff;
        padding:0.75rem 1.25rem;border-radius:8px;font-size:0.875rem;
        opacity:0;transition:opacity 0.2s;max-width:320px;box-shadow:0 4px 12px rgba(0,0,0,0.4)`;
    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.style.opacity = '1');
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 200);
    }, 3000);
}

// ── Active count stat updater ─────────────────────────────────────────────────
function updateActiveCount(delta) {
    const el = document.getElementById('activeCount');
    if (el) el.textContent = Math.max(0, parseInt(el.textContent) + delta);
}

// ── Single toggle (no page reload) ───────────────────────────────────────────
async function toggleModelStatus(btn) {
    const modelId  = btn.dataset.modelId;
    const wasActive = btn.dataset.active === '1';
    const newActive = !wasActive;

    // Optimistic UI update
    btn.classList.toggle('active', newActive);
    btn.dataset.active = newActive ? '1' : '0';
    btn.classList.add('loading');

    try {
        const res = await fetch(UPDATE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrf(),
            },
            body: new URLSearchParams({
                _token: getCsrf(),
                model_id: modelId,
                is_active: newActive ? '1' : '0',
            }),
        });

        if (!res.ok) throw new Error('Server error');
        showToast(newActive ? '{{ __('admin.models.model_enabled') }}' : '{{ __('admin.models.model_disabled') }}');
        updateActiveCount(newActive ? 1 : -1);
    } catch {
        // Revert on failure
        btn.classList.toggle('active', wasActive);
        btn.dataset.active = wasActive ? '1' : '0';
        showToast('{{ __('admin.models.update_failed') }}', 'error');
    } finally {
        btn.classList.remove('loading');
    }
}

// ── Bulk enable/disable (no page reload) ─────────────────────────────────────
async function bulkUpdate(action) {
    // Only act on checked rows that are currently visible
    const checked = Array.from(document.querySelectorAll('.model-checkbox:checked'))
        .filter(cb => cb.closest('.model-row').style.display !== 'none');

    if (!checked.length) {
        showToast('{{ __('admin.models.please_select_models') }}', 'error');
        return;
    }
    if (!confirm(`${action === 'enable' ? '{{ __('admin.models.enable') }}' : '{{ __('admin.models.disable') }}' ${checked.length} model(s)?`)) return;

    const newActive = action === 'enable';
    const csrf = getCsrf();

    const promises = checked.map(cb => {
        const row = cb.closest('.model-row');
        const btn = row.querySelector('.toggle-btn');
        const wasActive = btn?.dataset.active === '1';
        const modelId = cb.value;

        btn?.classList.add('loading');

        return fetch(UPDATE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: new URLSearchParams({ _token: csrf, model_id: modelId, is_active: newActive ? '1' : '0' }),
        })
        .then(r => ({ modelId, ok: r.ok, wasActive, btn }))
        .catch(() => ({ modelId, ok: false, wasActive, btn }));
    });

    const results = await Promise.all(promises);
    let delta = 0;

    results.forEach(({ ok, wasActive, btn }) => {
        btn?.classList.remove('loading');
        if (ok && btn) {
            btn.classList.toggle('active', newActive);
            btn.dataset.active = newActive ? '1' : '0';
            if (newActive && !wasActive) delta++;
            if (!newActive && wasActive) delta--;
        }
    });

    const successCount = results.filter(r => r.ok).length;
    updateActiveCount(delta);
    showToast(`{{ __('admin.models.models_enabled', ['count' => $successCount]) }}` .replace('{count}', successCount));
}

// ── Filter functionality ──────────────────────────────────────────────────────
function applyFilters() {
    const search   = document.getElementById('searchInput').value.toLowerCase();
    const family   = document.getElementById('filterFamily').value.toLowerCase();
    const category = document.getElementById('filterCategory').value.toLowerCase();
    const type     = document.getElementById('filterType').value.toLowerCase();
    const size     = document.getElementById('filterSize').value.toLowerCase();

    let visible = 0;
    document.querySelectorAll('.model-row').forEach(row => {
        const matches =
            (!search   || row.dataset.search.toLowerCase().includes(search)) &&
            (!family   || row.dataset.family.toLowerCase() === family) &&
            (!category || row.dataset.category.toLowerCase() === category) &&
            (!type     || row.dataset.type.toLowerCase() === type) &&
            (!size     || row.dataset.size.toLowerCase() === size);
        row.style.display = matches ? '' : 'none';
        if (matches) visible++;
    });

    const total = document.querySelectorAll('.model-row').length;
    document.getElementById('visibleCount').textContent =
        visible < total ? '{{ __('admin.models.showing_count') }}'.replace(':current', visible).replace(':total', total) : '{{ __('admin.models.models_count') }}'.replace(':count', total);
}
applyFilters();

// ── Toggle all checkboxes ─────────────────────────────────────────────────────
function toggleAll(source) {
    document.querySelectorAll('.model-checkbox').forEach(cb => cb.checked = source.checked);
}

// ── Edit modal ────────────────────────────────────────────────────────────────
function openEditModal(modelId, modelName, defaultMultiplier, currentOverride, isActive) {
    document.getElementById('editModelId').value = modelId;
    document.getElementById('editMultiplier').value = currentOverride;
    document.querySelector(`#editForm input[name="is_active"][value="${isActive}"]`).checked = true;
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Edit form: intercept submit → AJAX (preserves filters)
document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const modelId    = formData.get('model_id');
    const override   = formData.get('credit_multiplier_override');
    const newActive  = formData.get('is_active') === '1';
    const saveBtn    = document.getElementById('editSaveBtn');

    saveBtn.disabled = true;
    saveBtn.textContent = '{{ __('admin.models.saving') }}';

    try {
        const res = await fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrf(),
            },
            body: new URLSearchParams({
                _token: getCsrf(),
                model_id: modelId,
                is_active: newActive ? '1' : '0',
                credit_multiplier_override: override,
            }),
        });

        if (!res.ok) throw new Error();

        // Update the row in the table without reload
        const row = document.querySelector(`.model-row[data-model-id="${CSS.escape(modelId)}"]`);
        if (row) {
            const toggleBtn = row.querySelector('.toggle-btn');
            const wasActive = toggleBtn?.dataset.active === '1';

            // Update toggle
            if (toggleBtn) {
                toggleBtn.classList.toggle('active', newActive);
                toggleBtn.dataset.active = newActive ? '1' : '0';
                if (newActive !== wasActive) updateActiveCount(newActive ? 1 : -1);
            }

            // Update multiplier cell
            const defaultMult = row.dataset.defaultMultiplier;
            row.querySelector('.multiplier-cell').textContent = override || defaultMult;

            // Update override label under model ID
            const overrideEl = row.querySelector('.multiplier-override');
            if (override) {
                if (overrideEl) {
                    overrideEl.textContent = '{{ __('admin.models.override', ['value' => '']) }}'.replace(':value', override);
                } else {
                    const modelIdEl = row.querySelector('.model-id');
                    const newEl = document.createElement('div');
                    newEl.className = 'multiplier-override';
                    newEl.style.cssText = 'font-size:0.65rem;color:#f59e0b';
                    newEl.textContent = '{{ __('admin.models.override', ['value' => '']) }}'.replace(':value', override);
                    modelIdEl.insertAdjacentElement('afterend', newEl);
                }
            } else if (overrideEl) {
                overrideEl.remove();
            }
        }

        closeEditModal();
        showToast('{{ __('admin.models.model_settings_saved') }}');
    } catch {
        showToast('{{ __('admin.models.failed_save') }}', 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.textContent = '{{ __('admin.models.save_changes') }}';
    }
});

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeEditModal();
});
</script>
@endsection
