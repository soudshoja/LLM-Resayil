<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>API Settings - LLM Resayil Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-text {
            background: linear-gradient(to right, #f59e0b, #d4af37);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        input[type="password"]:not(:focus) { font-family: monospace; letter-spacing: 0.15em; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen">
<div class="flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 flex-shrink-0 flex flex-col">
        <div class="p-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold gradient-text">LLM Resayil</h1>
                    <span class="text-xs text-amber-500 font-medium uppercase tracking-wider">Admin</span>
                </div>
            </div>
        </div>

        <nav class="mt-2 px-4 space-y-1">
            <a href="/admin/dashboard" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="/admin/users" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Users</span>
            </a>
            <a href="/admin/api-settings" class="flex items-center gap-3 px-4 py-3 bg-amber-600/20 text-amber-400 border border-amber-600/30 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>API Settings</span>
            </a>
        </nav>

        <div class="mt-auto p-4">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition-colors text-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto bg-gray-900">

        <!-- Header -->
        <header class="bg-gray-800 border-b border-gray-700 px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">API Settings</h1>
                    <p class="text-gray-400 mt-1">Configure external service credentials and endpoints</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="text-sm font-medium text-white">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-amber-500">Administrator</div>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-medium text-sm">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Alerts -->
        <div class="px-8 pt-6">
            @if(session('success'))
                <div class="flex items-center gap-3 bg-green-900/40 border border-green-600/40 text-green-400 px-5 py-4 rounded-xl mb-6">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 bg-red-900/40 border border-red-600/40 text-red-400 px-5 py-4 rounded-xl mb-6">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Form -->
        <form method="POST" action="/admin/api-settings" class="px-8 pb-10 space-y-8">
            @csrf
            @method('PUT')

            <!-- Ollama Section -->
            <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-700">
                    <div class="w-9 h-9 bg-blue-500/10 border border-blue-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">Ollama</h2>
                        <p class="text-xs text-gray-400">Local GPU server and cloud failover configuration</p>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Local GPU URL <span class="text-gray-500 font-normal">(OLLAMA_GPU_URL)</span></label>
                        <input type="text" name="OLLAMA_GPU_URL" value="{{ $settings['OLLAMA_GPU_URL'] }}"
                            placeholder="http://208.110.93.90:11434"
                            class="w-full bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition font-mono text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Cloud Failover URL <span class="text-gray-500 font-normal">(OLLAMA_CLOUD_URL)</span></label>
                        <input type="text" name="OLLAMA_CLOUD_URL" value="{{ $settings['OLLAMA_CLOUD_URL'] }}"
                            placeholder="https://ollama.com"
                            class="w-full bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition font-mono text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Cloud API Key <span class="text-gray-500 font-normal">(CLOUD_API_KEY)</span></label>
                        <div class="relative">
                            <input type="password" name="CLOUD_API_KEY" value="{{ $settings['CLOUD_API_KEY'] }}"
                                id="cloud_api_key"
                                placeholder="Enter cloud API key"
                                class="w-full bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 pr-12 text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition font-mono text-sm">
                            <button type="button" onclick="toggleVisibility('cloud_api_key', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-200 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MyFatoorah Section -->
            <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-700">
                    <div class="w-9 h-9 bg-amber-500/10 border border-amber-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">MyFatoorah</h2>
                        <p class="text-xs text-gray-400">Payment gateway for KWD transactions</p>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-2">API Key <span class="text-gray-500 font-normal">(MYFATOORAH_API_KEY)</span></label>
                        <div class="relative">
                            <input type="password" name="MYFATOORAH_API_KEY" value="{{ $settings['MYFATOORAH_API_KEY'] }}"
                                id="myfatoorah_api_key"
                                placeholder="Enter MyFatoorah API key"
                                class="w-full bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 pr-12 text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition font-mono text-sm">
                            <button type="button" onclick="toggleVisibility('myfatoorah_api_key', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-200 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Base URL <span class="text-gray-500 font-normal">(MYFATOORAH_BASE_URL)</span></label>
                        <input type="text" name="MYFATOORAH_BASE_URL" value="{{ $settings['MYFATOORAH_BASE_URL'] }}"
                            placeholder="https://ap-gateway.myfatoorah.com"
                            class="w-full bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition font-mono text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Callback URL <span class="text-gray-500 font-normal">(MYFATOORAH_CALLBACK_URL)</span></label>
                        <input type="text" name="MYFATOORAH_CALLBACK_URL" value="{{ $settings['MYFATOORAH_CALLBACK_URL'] }}"
                            placeholder="https://llm.resayil.io/webhooks/payment"
                            class="w-full bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition font-mono text-sm">
                    </div>
                </div>
            </div>

            <!-- WhatsApp Section -->
            <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-700">
                    <div class="w-9 h-9 bg-green-500/10 border border-green-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">Resayil WhatsApp</h2>
                        <p class="text-xs text-gray-400">WhatsApp notification service for bilingual alerts</p>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">API URL <span class="text-gray-500 font-normal">(WHATSAPP_API_URL)</span></label>
                        <input type="text" name="WHATSAPP_API_URL" value="{{ $settings['WHATSAPP_API_URL'] }}"
                            placeholder="https://api.resayil.io/whatsapp"
                            class="w-full bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition font-mono text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Admin Phone <span class="text-gray-500 font-normal">(ADMIN_PHONE)</span></label>
                        <input type="text" name="ADMIN_PHONE" value="{{ $settings['ADMIN_PHONE'] }}"
                            placeholder="96550000000"
                            class="w-full bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition font-mono text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-2">API Key <span class="text-gray-500 font-normal">(WHATSAPP_API_KEY)</span></label>
                        <div class="relative">
                            <input type="password" name="WHATSAPP_API_KEY" value="{{ $settings['WHATSAPP_API_KEY'] }}"
                                id="whatsapp_api_key"
                                placeholder="Enter WhatsApp API key"
                                class="w-full bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 pr-12 text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition font-mono text-sm">
                            <button type="button" onclick="toggleVisibility('whatsapp_api_key', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-200 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Redis Section -->
            <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-700">
                    <div class="w-9 h-9 bg-red-500/10 border border-red-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">Redis</h2>
                        <p class="text-xs text-gray-400">Cache and rate limiting store</p>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Host <span class="text-gray-500 font-normal">(REDIS_HOST)</span></label>
                        <input type="text" name="REDIS_HOST" value="{{ $settings['REDIS_HOST'] }}"
                            placeholder="127.0.0.1"
                            class="w-full bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition font-mono text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Port <span class="text-gray-500 font-normal">(REDIS_PORT)</span></label>
                        <input type="text" name="REDIS_PORT" value="{{ $settings['REDIS_PORT'] }}"
                            placeholder="6379"
                            class="w-full bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition font-mono text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Password <span class="text-gray-500 font-normal">(REDIS_PASSWORD)</span></label>
                        <div class="relative">
                            <input type="password" name="REDIS_PASSWORD" value="{{ $settings['REDIS_PASSWORD'] }}"
                                id="redis_password"
                                placeholder="Leave empty if none"
                                class="w-full bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 pr-12 text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition font-mono text-sm">
                            <button type="button" onclick="toggleVisibility('redis_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-200 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="flex items-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white font-semibold px-8 py-3 rounded-xl transition-all shadow-lg shadow-amber-900/30">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save All Settings
                </button>
            </div>

        </form>
    </main>
</div>

<script>
function toggleVisibility(id, btn) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
