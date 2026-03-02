@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; }
    .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--gold); }
    .users-table { width: 100%; border-collapse: collapse; }
    .users-table th { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; padding: 0.6rem 0.75rem; border-bottom: 1px solid var(--border); text-align: left; }
    .users-table td { padding: 0.75rem; border-bottom: 1px solid rgba(30,34,48,0.5); font-size: 0.875rem; }
    .action-buttons { display: flex; gap: 0.375rem; flex-wrap: wrap; }
    .btn-action { padding: 0.25rem 0.5rem; font-size: 0.75rem; border-radius: 4px; border: none; cursor: pointer; transition: all 0.2s; }
    .btn-set-credits { background: rgba(30,144,255,0.15); color: #1e90ff; }
    .btn-set-credits:hover { background: rgba(30,144,255,0.25); }
    .btn-set-tier { background: rgba(255,165,0,0.15); color: #ff8c00; }
    .btn-set-tier:hover { background: rgba(255,165,0,0.25); }
    .btn-set-expiry { background: rgba(128,0,128,0.15); color: #9370db; }
    .btn-set-expiry:hover { background: rgba(128,0,128,0.25); }
    .btn-create-key { background: rgba(212,175,55,0.15); color: #d4af37; }
    .btn-create-key:hover { background: rgba(212,175,55,0.25); }
    @media(max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    /* Modal Styles */
    .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 1000; }
    .modal-overlay.active { display: flex; }
    .modal { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; width: 90%; max-width: 400px; box-shadow: 0 8px 32px rgba(0,0,0,0.3); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
    .modal-title { font-size: 1.1rem; font-weight: 600; color: var(--gold); }
    .modal-close { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted); }
    .modal-body { margin-bottom: 1.25rem; }
    .form-group { margin-bottom: 1rem; }
    .form-label { display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.375rem; }
    .form-input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-secondary); color: var(--text-primary); font-size: 0.9rem; }
    .form-input:focus { outline: 2px solid var(--gold-muted); border-color: transparent; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 0.5rem; }
    .btn-save { padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; }
    .btn-save-primary { background: var(--gold); color: #0a0d14; }
    .btn-save-primary:hover { opacity: 0.9; }
    .btn-save-secondary { background: transparent; border: 1px solid var(--border); color: var(--text-secondary); }
    .btn-save-secondary:hover { background: rgba(255,255,255,0.05); }
    .success-message { display: none; padding: 1rem; background: rgba(40,167,69,0.1); border: 1px solid #28a745; border-radius: 8px; color: #28a745; margin-bottom: 1rem; }
</style>
@endpush

@section('content')
<main>
    <div style="margin-bottom:2rem">
        <h1 style="font-size:1.5rem;font-weight:700">Admin Dashboard</h1>
        <p class="text-secondary text-sm">Platform overview and management</p>
    </div>

    @php
        $totalUsers = \App\Models\User::count();
        $activeSubscriptions = \App\Models\Subscriptions::where('status', 'active')->count();
        $totalRevenue = \App\Models\TopupPurchase::where('status', 'completed')->sum('price');
        $totalApiCalls = \App\Models\UsageLog::count();
        $cloudBudget = \App\Models\CloudBudget::orderByDesc('date')->first();
    @endphp

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ $totalUsers }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Active Subscriptions</div>
            <div class="stat-value">{{ $activeSubscriptions }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total API Calls</div>
            <div class="stat-value">{{ number_format($totalApiCalls) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Cloud Budget Used</div>
            <div class="stat-value">{{ $cloudBudget ? $cloudBudget->requests_today : 0 }}/{{ $cloudBudget ? $cloudBudget->daily_limit : 500 }}</div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 style="font-size:1rem;font-weight:600">All Users</h2>
        </div>
        <table class="users-table">
            <thead>
                <tr>
                    <th>Name / Email</th>
                    <th>Phone</th>
                    <th>Plan</th>
                    <th>Credits</th>
                    <th>Joined</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\User::orderByDesc('created_at')->paginate(20) as $user)
                <tr>
                    <td>
                        <div>{{ $user->name ?: '—' }}</div>
                        <div class="text-muted text-xs">{{ $user->email }}</div>
                    </td>
                    <td class="text-secondary">{{ $user->phone }}</td>
                    <td><span class="badge badge-gold">{{ ucfirst($user->subscription_tier) }}</span></td>
                    <td>{{ number_format($user->credits) }}</td>
                    <td class="text-muted">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-set-credits" onclick="openCreditsModal('{{ $user->id }}')">Credits</button>
                            <button class="btn-action btn-set-tier" onclick="openTierModal('{{ $user->id }}')">Tier</button>
                            <button class="btn-action btn-set-expiry" onclick="openExpiryModal('{{ $user->id }}')">Expiry</button>
                            <button class="btn-action btn-create-key" onclick="openKeyModal('{{ $user->id }}')">API Key</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Set Credits Modal --}}
    <div class="modal-overlay" id="creditsModal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Set Credits</div>
                <button class="modal-close" onclick="closeModal('creditsModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">User ID</label>
                    <div class="form-input" id="creditsUserId" readonly></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Credits</label>
                    <input type="number" id="creditsInput" class="form-input" placeholder="Enter credits" min="0">
                </div>
                <div id="creditsSuccess" class="success-message"></div>
            </div>
            <div class="modal-footer">
                <button class="btn-save btn-save-secondary" onclick="closeModal('creditsModal')">Cancel</button>
                <button class="btn-save btn-save-primary" onclick="saveCredits()">Save</button>
            </div>
        </div>
    </div>

    {{-- Set Tier Modal --}}
    <div class="modal-overlay" id="tierModal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Set Subscription Tier</div>
                <button class="modal-close" onclick="closeModal('tierModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">User ID</label>
                    <div class="form-input" id="tierUserId" readonly></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Tier</label>
                    <select id="tierInput" class="form-input">
                        <option value="starter">Starter</option>
                        <option value="basic">Basic</option>
                        <option value="pro">Pro</option>
                    </select>
                </div>
                <div id="tierSuccess" class="success-message"></div>
            </div>
            <div class="modal-footer">
                <button class="btn-save btn-save-secondary" onclick="closeModal('tierModal')">Cancel</button>
                <button class="btn-save btn-save-primary" onclick="saveTier()">Save</button>
            </div>
        </div>
    </div>

    {{-- Set Expiry Modal --}}
    <div class="modal-overlay" id="expiryModal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Set Subscription Expiry</div>
                <button class="modal-close" onclick="closeModal('expiryModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">User ID</label>
                    <div class="form-input" id="expiryUserId" readonly></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" id="expiryInput" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label" style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" id="expiryClear" class="form-input" style="width: auto; margin: 0;"> Clear expiry (reset to null)
                    </label>
                </div>
                <div id="expirySuccess" class="success-message"></div>
            </div>
            <div class="modal-footer">
                <button class="btn-save btn-save-secondary" onclick="closeModal('expiryModal')">Cancel</button>
                <button class="btn-save btn-save-primary" onclick="saveExpiry()">Save</button>
            </div>
        </div>
    </div>

    {{-- Create API Key Modal --}}
    <div class="modal-overlay" id="keyModal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Create API Key</div>
                <button class="modal-close" onclick="closeModal('keyModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">User ID</label>
                    <div class="form-input" id="keyUserId" readonly></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Key Name</label>
                    <input type="text" id="keyNameInput" class="form-input" placeholder="Enter key name (e.g., Production)" value="Admin Created Key">
                </div>
                <div id="keySuccess" class="success-message">
                    API key created successfully. Copy this key - it won't be shown again:
                    <div id="keyResult" style="margin-top: 0.5rem; padding: 0.75rem; background: var(--bg-secondary); border-radius: 6px; font-family: monospace; word-break: break-all; font-size: 0.8rem;"></div>
                    <button class="btn-save btn-save-primary" style="margin-top: 0.5rem;" onclick="copyKeyToClipboard()">Copy to Clipboard</button>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-save btn-save-secondary" onclick="closeModal('keyModal')">Close</button>
            </div>
        </div>
    </div>
</main>

<script>
let currentUserId = null;

function openCreditsModal(userId) {
    currentUserId = userId;
    document.getElementById('creditsUserId').textContent = userId;
    document.getElementById('creditsInput').value = '';
    document.getElementById('creditsSuccess').style.display = 'none';
    document.getElementById('creditsModal').classList.add('active');
}

function openTierModal(userId) {
    currentUserId = userId;
    document.getElementById('tierUserId').textContent = userId;
    document.getElementById('tierInput').value = 'starter';
    document.getElementById('tierSuccess').style.display = 'none';
    document.getElementById('tierModal').classList.add('active');
}

function openExpiryModal(userId) {
    currentUserId = userId;
    document.getElementById('expiryUserId').textContent = userId;
    document.getElementById('expiryInput').value = '';
    document.getElementById('expiryClear').checked = false;
    document.getElementById('expirySuccess').style.display = 'none';
    document.getElementById('expiryModal').classList.add('active');
}

function openKeyModal(userId) {
    currentUserId = userId;
    document.getElementById('keyUserId').textContent = userId;
    document.getElementById('keyNameInput').value = 'Admin Created Key';
    document.getElementById('keySuccess').style.display = 'none';
    document.getElementById('keyResult').textContent = '';
    document.getElementById('keyModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
    currentUserId = null;
}

function saveCredits() {
    const credits = document.getElementById('creditsInput').value;
    if (credits === '' || credits < 0) {
        alert('Please enter a valid non-negative number of credits.');
        return;
    }

    fetch(`/admin/users/${currentUserId}/credits`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ credits: credits }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('creditsSuccess').textContent = data.message;
            document.getElementById('creditsSuccess').style.display = 'block';
            setTimeout(() => {
                closeModal('creditsModal');
                location.reload();
            }, 1500);
        } else {
            alert('Error: ' + (data.message || 'Failed to update credits'));
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

function saveTier() {
    const tier = document.getElementById('tierInput').value;

    fetch(`/admin/users/${currentUserId}/tier`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ tier: tier }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('tierSuccess').textContent = data.message;
            document.getElementById('tierSuccess').style.display = 'block';
            setTimeout(() => {
                closeModal('tierModal');
                location.reload();
            }, 1500);
        } else {
            alert('Error: ' + (data.message || 'Failed to update tier'));
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

function saveExpiry() {
    const expiry = document.getElementById('expiryInput').value;
    const clearExpiry = document.getElementById('expiryClear').checked;

    fetch(`/admin/users/${currentUserId}/expiry`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ expiry: clearExpiry ? '' : expiry }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('expirySuccess').textContent = data.message;
            document.getElementById('expirySuccess').style.display = 'block';
            setTimeout(() => {
                closeModal('expiryModal');
                location.reload();
            }, 1500);
        } else {
            alert('Error: ' + (data.message || 'Failed to update expiry'));
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

function saveKey() {
    const keyName = document.getElementById('keyNameInput').value;

    fetch(`/admin/users/${currentUserId}/keys`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ key_name: keyName }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('keyResult').textContent = data.message;
            document.getElementById('keySuccess').style.display = 'block';
        } else {
            alert('Error: ' + (data.message || 'Failed to create API key'));
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

function copyKeyToClipboard() {
    const key = document.getElementById('keyResult').textContent;
    navigator.clipboard.writeText(key).then(() => {
        alert('API key copied to clipboard!');
    });
}
</script>
@endsection
