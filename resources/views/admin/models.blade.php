@extends('layouts.app')

@push('styles')
<style>
    .models-table { width: 100%; border-collapse: collapse; }
    .models-table th { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; padding: 0.6rem 0.75rem; border-bottom: 1px solid var(--border); text-align: left; }
    .models-table td { padding: 0.75rem; border-bottom: 1px solid rgba(30,34,48,0.5); font-size: 0.875rem; }
    .models-table tr:last-child td { border-bottom: none; }
    .toggle-btn { cursor: pointer; position: relative; width: 48px; height: 24px; background: #374151; border-radius: 9999px; transition: all 0.2s; }
    .toggle-btn.active { background: #d4af37; }
    .toggle-btn::after { content: ''; position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background: white; border-radius: 50%; transition: all 0.2s; }
    .toggle-btn.active::after { left: 26px; }
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
        <h1 style="font-size:1.5rem;font-weight:700">Model Management</h1>
        <p class="text-secondary text-sm">Configure model availability and credit multipliers</p>
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
            <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem">Total Models</div>
            <div style="font-size:1.75rem;font-weight:700;color:var(--gold)">{{ $totalModels }}</div>
        </div>
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1.25rem">
            <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem">Active Models</div>
            <div style="font-size:1.75rem;font-weight:700;color:var(--gold)">{{ $activeModels }}</div>
        </div>
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1.25rem">
            <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem">Local Models</div>
            <div style="font-size:1.75rem;font-weight:700;color:#3b82f6">{{ $localModels }}</div>
        </div>
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1.25rem">
            <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem">Cloud Models</div>
            <div style="font-size:1.75rem;font-weight:700;color:#f59e0b">{{ $cloudModels }}</div>
        </div>
    </div>

    <!-- Filter & Actions Bar -->
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1rem;margin-bottom:1rem">
        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;align-items:center;margin-bottom:0.75rem">
            <div style="display:flex;align-items:center;gap:0.4rem">
                <span style="font-size:0.75rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.05em">Family:</span>
                <select id="filterFamily" onchange="applyFilters()" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.6rem;color:var(--text);font-size:0.8rem">
                    <option value="">All</option>
                    @foreach(array_unique(array_column($modelList, 'family')) as $fam)
                    <option value="{{ $fam }}">{{ $fam }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;align-items:center;gap:0.4rem">
                <span style="font-size:0.75rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.05em">Category:</span>
                <select id="filterCategory" onchange="applyFilters()" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.6rem;color:var(--text);font-size:0.8rem">
                    <option value="">All</option>
                    <option value="chat">Chat</option>
                    <option value="code">Code</option>
                    <option value="embedding">Embedding</option>
                    <option value="vision">Vision</option>
                    <option value="thinking">Thinking</option>
                    <option value="tools">Tools</option>
                </select>
            </div>
            <div style="display:flex;align-items:center;gap:0.4rem">
                <span style="font-size:0.75rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.05em">Type:</span>
                <select id="filterType" onchange="applyFilters()" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.6rem;color:var(--text);font-size:0.8rem">
                    <option value="">All</option>
                    <option value="local">Local</option>
                    <option value="cloud">Cloud</option>
                </select>
            </div>
            <div style="display:flex;align-items:center;gap:0.4rem">
                <span style="font-size:0.75rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.05em">Size:</span>
                <select id="filterSize" onchange="applyFilters()" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.6rem;color:var(--text);font-size:0.8rem">
                    <option value="">All</option>
                    <option value="small">Small</option>
                    <option value="medium">Medium</option>
                    <option value="large">Large</option>
                </select>
            </div>
            <div style="position:relative;flex:1;min-width:200px">
                <input type="text" id="searchInput" oninput="applyFilters()" placeholder="Search models..." style="width:100%;background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.75rem 0.35rem 2.25rem;color:var(--text);font-size:0.8rem;box-sizing:border-box">
                <svg style="position:absolute;left:0.6rem;top:50%;transform:translateY(-50%);width:0.9rem;height:0.9rem;color:var(--text-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
        <div style="display:flex;gap:0.5rem">
            <button type="button" onclick="bulkUpdate('enable')" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.75rem;color:var(--text-muted);cursor:pointer;font-size:0.8rem">
                <svg style="width:0.85rem;height:0.85rem;vertical-align:middle" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Enable
            </button>
            <button type="button" onclick="bulkUpdate('disable')" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.35rem 0.75rem;color:var(--text-muted);cursor:pointer;font-size:0.8rem">
                <svg style="width:0.85rem;height:0.85rem;vertical-align:middle" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Disable
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
                        <th>Model ID</th>
                        <th>Family</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Multiplier</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                        data-size="{{ $model['size'] }}">
                        <td><input type="checkbox" class="model-checkbox" value="{{ $model['model_id'] }}"></td>
                        <td>
                            <div class="model-id">{{ $model['model_id'] }}</div>
                            @if($model['credit_multiplier_override'] !== null)
                                <div style="font-size:0.65rem;color:#f59e0b">override: {{ $model['credit_multiplier_override'] }}</div>
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
                        <td>{{ $model['credit_multiplier_override'] ?? $model['credit_multiplier'] }}</td>
                        <td>
                            <div class="toggle-btn {{ $model['is_active'] ? 'active' : '' }}" onclick="toggleModelStatus({{ $loop->index }}, {{ $model['is_active'] ? 'true' : 'false' }})" data-model-id="{{ $model['model_id'] }}"></div>
                            <form id="form-{{ $loop->index }}" method="POST" action="{{ route('admin.models.update') }}" style="display:none">
                                @csrf
                                <input type="hidden" name="model_id" value="{{ $model['model_id'] }}">
                                <input type="hidden" name="is_active" value="{{ $model['is_active'] ? '0' : '1' }}">
                            </form>
                        </td>
                        <td>
                            <button type="button" onclick="openEditModal('{{ $model['model_id'] }}', '{{ $model['name'] }}', {{ $model['credit_multiplier'] }}, '{{ $model['credit_multiplier_override'] ?? '' }}')" style="background:var(--bg-card);border:1px solid var(--border);border-radius:6px;padding:0.3rem 0.6rem;color:var(--text);font-size:0.75rem;cursor:pointer;transition:all 0.2s">Edit</button>
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
                <h2 style="font-size:1.25rem;font-weight:600">Edit Model Settings</h2>
                <button type="button" onclick="closeEditModal()" style="background:none;border:none;color:var(--text-muted);cursor:pointer">
                    <svg style="width:1.5rem;height:1.5rem" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editForm" method="POST" action="{{ route('admin.models.update') }}" style="padding:1.5rem">
                @csrf
                <input type="hidden" name="model_id" id="editModelId">
                <div style="margin-bottom:1.5rem">
                    <label style="display:block;font-size:0.875rem;font-weight:500;margin-bottom:0.5rem">Credit Multiplier Override</label>
                    <input type="number" name="credit_multiplier_override" id="editMultiplier" step="0.1" min="0" placeholder="Leave empty for default" style="width:100%;background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;padding:0.75rem;color:var(--text)">
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.5rem">Set to override default multiplier. Leave empty to use default from config.</p>
                </div>
                <div style="margin-bottom:1.5rem">
                    <label style="display:block;font-size:0.875rem;font-weight:500;margin-bottom:0.5rem">Status</label>
                    <div style="display:flex;gap:1rem">
                        <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
                            <input type="radio" name="is_active" value="1" checked>
                            <span>Active</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
                            <input type="radio" name="is_active" value="0">
                            <span>Inactive</span>
                        </label>
                    </div>
                </div>
                <div style="display:flex;justify-content:flex-end;gap:0.75rem">
                    <button type="button" onclick="closeEditModal()" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;padding:0.5rem 1rem;color:var(--text);cursor:pointer">Cancel</button>
                    <button type="submit" style="background:var(--gold);border:none;border-radius:8px;padding:0.5rem 1rem;color:#0f1115;font-weight:600;cursor:pointer">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
// Filter functionality
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
    document.getElementById('visibleCount').textContent = visible < total ? `Showing ${visible} of ${total}` : `${total} models`;
}
applyFilters();

// Toggle single model status (uses loop index to avoid slash issues in IDs)
function toggleModelStatus(index, isActive) {
    const form = document.getElementById(`form-${index}`);
    if (!form) return;

    // Update visual state
    const modelId = form.querySelector('input[name="model_id"]').value;
    const btn = document.querySelector(`.toggle-btn[data-model-id="${CSS.escape(modelId)}"]`);
    if (btn) btn.classList.toggle('active', !isActive);

    form.submit();
}

// Toggle all checkboxes
function toggleAll(source) {
    const checkboxes = document.querySelectorAll('.model-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
    });
}

// Bulk update
function bulkUpdate(action) {
    const checked = Array.from(document.querySelectorAll('.model-checkbox:checked'));
    if (checked.length === 0) {
        alert('Please select models to update.');
        return;
    }

    if (!confirm(`Are you sure you want to ${action} ${checked.length} model(s)?`)) {
        return;
    }

    // Submit forms for each checked model (find form by model_id in hidden input)
    checked.forEach(checkbox => {
        const form = Array.from(document.querySelectorAll('form[id^="form-"]')).find(
            f => f.querySelector('input[name="model_id"]')?.value === checkbox.value
        );
        if (form) {
            form.querySelector('input[name="is_active"]').value = action === 'enable' ? '1' : '0';
            form.submit();
        }
    });
}

// Edit modal functions
function openEditModal(modelId, modelName, defaultMultiplier, currentOverride) {
    document.getElementById('editModelId').value = modelId;
    document.getElementById('editMultiplier').value = currentOverride;
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal on outside click
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Keyboard escape to close
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
    }
});
</script>
@endsection
