# Model Catalog + Admin Panel Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Expose all 45 Ollama models (local + cloud) to all users with rich metadata, a searchable dashboard catalog, and a full admin panel for model management, API key creation, and unrestricted user/credit control.

**Architecture:** Three independent agents. Agent 1 builds the model registry and rewrites the API backend. Agent 2 builds the frontend catalog UI. Agent 3 builds the admin panel. Agents 1+2 run in parallel. Agent 3 runs after Agent 1 (needs the `models` DB table).

**Tech Stack:** Laravel 11, Blade, vanilla JS (no npm build step), MySQL, Guzzle, existing dark-luxury CSS vars

---

## AGENT 1 — Model Registry + API Backend

### Task 1: Create the models migration

**File:** Create `database/migrations/2026_03_02_000001_create_models_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('models', function (Blueprint $table) {
            $table->string('model_id')->primary(); // clean client-facing ID
            $table->boolean('is_active')->default(true);
            $table->float('credit_multiplier_override')->nullable(); // null = use config default
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('models');
    }
};
```

Run: `php artisan migrate`
Expected: `models` table created.

Commit: `git add database/migrations/... && git commit -m "feat: add models table for admin enable/disable and credit override"`

---

### Task 2: Create the Model Eloquent model

**File:** Create `app/Models/ModelConfig.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelConfig extends Model
{
    protected $table = 'models';
    protected $primaryKey = 'model_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['model_id', 'is_active', 'credit_multiplier_override'];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_multiplier_override' => 'float',
    ];
}
```

Commit: `git add app/Models/ModelConfig.php && git commit -m "feat: add ModelConfig eloquent model"`

---

### Task 3: Create the model registry config

**File:** Create `config/models.php`

This is the single source of truth for all 45 models. Format per entry:
```php
'clean-id' => [
    'ollama_name'    => 'internal-ollama-name',
    'display_name'   => 'Human Label',
    'family'         => 'deepseek|qwen|mistral|kimi|glm|gemma|minimax|llama|gpt-oss|nvidia|google|other',
    'parameters'     => '671B',
    'context_length' => 128000,
    'type'           => 'local|cloud',
    'credit_multiplier' => 1, // 1=local, 2=cloud
    'capabilities'   => ['chat'],  // chat, code, vision, reasoning
    'description'    => 'One-liner description',
],
```

Full registry:

```php
<?php
return [

    // ─── LOCAL MODELS ──────────────────────────────────────────────────────
    'smollm2:135m' => [
        'ollama_name'    => 'smollm2:135m',
        'display_name'   => 'SmolLM2 135M',
        'family'         => 'llama',
        'parameters'     => '135M',
        'context_length' => 8192,
        'type'           => 'local',
        'credit_multiplier' => 1,
        'capabilities'   => ['chat'],
        'description'    => 'Ultra-fast tiny model for simple tasks',
    ],
    'llama3.2:3b' => [
        'ollama_name'    => 'llama3.2:3b',
        'display_name'   => 'Llama 3.2 3B',
        'family'         => 'llama',
        'parameters'     => '3B',
        'context_length' => 131072,
        'type'           => 'local',
        'credit_multiplier' => 1,
        'capabilities'   => ['chat'],
        'description'    => 'Fast general-purpose model from Meta',
    ],
    'gemma3:4b' => [
        'ollama_name'    => 'gemma3:4b',
        'display_name'   => 'Gemma 3 4B',
        'family'         => 'gemma',
        'parameters'     => '4B',
        'context_length' => 128000,
        'type'           => 'local',
        'credit_multiplier' => 1,
        'capabilities'   => ['chat'],
        'description'    => 'Google Gemma 3 — efficient 4B model',
    ],
    'qwen2.5-coder:14b' => [
        'ollama_name'    => 'qwen2.5-coder:14b',
        'display_name'   => 'Qwen 2.5 Coder 14B',
        'family'         => 'qwen',
        'parameters'     => '14B',
        'context_length' => 131072,
        'type'           => 'local',
        'credit_multiplier' => 1,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'Specialized code generation model from Alibaba',
    ],
    'mistral-small3.2:24b' => [
        'ollama_name'    => 'mistral-small3.2:24b-instruct-2506-q4_K_M',
        'display_name'   => 'Mistral Small 3.2 24B',
        'family'         => 'mistral',
        'parameters'     => '24B',
        'context_length' => 131072,
        'type'           => 'local',
        'credit_multiplier' => 1,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'Balanced quality and speed from Mistral',
    ],
    'glm-4.7-flash' => [
        'ollama_name'    => 'glm-4.7-flash:latest',
        'display_name'   => 'GLM 4.7 Flash 30B',
        'family'         => 'glm',
        'parameters'     => '30B',
        'context_length' => 32768,
        'type'           => 'local',
        'credit_multiplier' => 1,
        'capabilities'   => ['chat'],
        'description'    => 'ZhiPu AI GLM 4.7 Flash — fast MoE model',
    ],
    'glm-4.7-flash-48k' => [
        'ollama_name'    => 'glm-4.7-flash-48k:latest',
        'display_name'   => 'GLM 4.7 Flash 48K',
        'family'         => 'glm',
        'parameters'     => '30B',
        'context_length' => 49152,
        'type'           => 'local',
        'credit_multiplier' => 1,
        'capabilities'   => ['chat'],
        'description'    => 'GLM 4.7 Flash with extended 48K context window',
    ],
    'qwen3-30b-40k' => [
        'ollama_name'    => 'qwen3-30b-40k:latest',
        'display_name'   => 'Qwen3 30B 40K',
        'family'         => 'qwen',
        'parameters'     => '30B',
        'context_length' => 40960,
        'type'           => 'local',
        'credit_multiplier' => 1,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'Qwen3 MoE 30B with 40K context',
    ],
    'qwen3-30b-a3b' => [
        'ollama_name'    => 'alibayram/Qwen3-30B-A3B-Instruct-2507:latest',
        'display_name'   => 'Qwen3 30B A3B Instruct',
        'family'         => 'qwen',
        'parameters'     => '30B',
        'context_length' => 40960,
        'type'           => 'local',
        'credit_multiplier' => 1,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'Qwen3 30B MoE — A3B activated parameters',
    ],
    'qwen3-vl:32b' => [
        'ollama_name'    => 'hf.co/Qwen/Qwen3-VL-32B-Instruct-GGUF:Q4_K_M',
        'display_name'   => 'Qwen3 VL 32B',
        'family'         => 'qwen',
        'parameters'     => '32B',
        'context_length' => 32768,
        'type'           => 'local',
        'credit_multiplier' => 1,
        'capabilities'   => ['chat', 'vision'],
        'description'    => 'Qwen3 Vision-Language model — image + text understanding',
    ],
    'gpt-oss:20b' => [
        'ollama_name'    => 'gpt-oss:20b',
        'display_name'   => 'GPT-OSS 20B',
        'family'         => 'gpt-oss',
        'parameters'     => '20B',
        'context_length' => 32768,
        'type'           => 'local',
        'credit_multiplier' => 1,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'OpenAI open-source 20B model',
    ],

    // ─── CLOUD PROXY MODELS ────────────────────────────────────────────────
    'deepseek-v3.1:671b' => [
        'ollama_name'    => 'deepseek-v3.1:671b-cloud',
        'display_name'   => 'DeepSeek V3.1 671B',
        'family'         => 'deepseek',
        'parameters'     => '671B',
        'context_length' => 128000,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code', 'reasoning'],
        'description'    => 'DeepSeek flagship — top-tier reasoning and code',
    ],
    'deepseek-v3.2' => [
        'ollama_name'    => 'deepseek-v3.2:cloud',
        'display_name'   => 'DeepSeek V3.2',
        'family'         => 'deepseek',
        'parameters'     => '671B',
        'context_length' => 128000,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code', 'reasoning'],
        'description'    => 'DeepSeek V3.2 — latest generation flagship',
    ],
    'cogito-2.1:671b' => [
        'ollama_name'    => 'cogito-2.1:671b-cloud',
        'display_name'   => 'Cogito 2.1 671B',
        'family'         => 'deepseek',
        'parameters'     => '671B',
        'context_length' => 128000,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'reasoning'],
        'description'    => 'DeepSeek-based reasoning model with extended thinking',
    ],
    'qwen3.5:397b' => [
        'ollama_name'    => 'qwen3.5:cloud',
        'display_name'   => 'Qwen 3.5 397B',
        'family'         => 'qwen',
        'parameters'     => '397B',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code', 'reasoning'],
        'description'    => 'Qwen 3.5 flagship — multilingual powerhouse',
    ],
    'qwen3-coder:480b' => [
        'ollama_name'    => 'qwen3-coder:480b-cloud',
        'display_name'   => 'Qwen3 Coder 480B',
        'family'         => 'qwen',
        'parameters'     => '480B',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'Best-in-class code generation model at 480B scale',
    ],
    'qwen3-coder-next' => [
        'ollama_name'    => 'qwen3-coder-next:cloud',
        'display_name'   => 'Qwen3 Coder Next 80B',
        'family'         => 'qwen',
        'parameters'     => '80B',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'Next-gen Qwen coder — 80B efficient code model',
    ],
    'qwen3-next:80b' => [
        'ollama_name'    => 'qwen3-next:80b-cloud',
        'display_name'   => 'Qwen3 Next 80B',
        'family'         => 'qwen',
        'parameters'     => '80B',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'Qwen3 Next generation 80B model',
    ],
    'qwen3-vl:235b' => [
        'ollama_name'    => 'qwen3-vl:235b-cloud',
        'display_name'   => 'Qwen3 VL 235B',
        'family'         => 'qwen',
        'parameters'     => '235B',
        'context_length' => 32768,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'vision'],
        'description'    => 'Massive vision-language model at 235B scale',
    ],
    'qwen3-vl:235b-instruct' => [
        'ollama_name'    => 'qwen3-vl:235b-instruct-cloud',
        'display_name'   => 'Qwen3 VL 235B Instruct',
        'family'         => 'qwen',
        'parameters'     => '235B',
        'context_length' => 32768,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'vision'],
        'description'    => 'Qwen3 VL 235B — instruction-tuned variant',
    ],
    'devstral-2:123b' => [
        'ollama_name'    => 'devstral-2:123b-cloud',
        'display_name'   => 'Devstral 2 123B',
        'family'         => 'mistral',
        'parameters'     => '123B',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'Mistral code-specialist model at 123B',
    ],
    'devstral-small-2:24b' => [
        'ollama_name'    => 'devstral-small-2:24b-cloud',
        'display_name'   => 'Devstral Small 2 24B',
        'family'         => 'mistral',
        'parameters'     => '24B',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'Efficient Devstral code model at 24B',
    ],
    'mistral-large-3:675b' => [
        'ollama_name'    => 'mistral-large-3:675b-cloud',
        'display_name'   => 'Mistral Large 3 675B',
        'family'         => 'mistral',
        'parameters'     => '675B',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code', 'reasoning'],
        'description'    => 'Mistral flagship — 675B top-performance model',
    ],
    'ministral-3:3b' => [
        'ollama_name'    => 'ministral-3:3b-cloud',
        'display_name'   => 'Ministral 3 3B',
        'family'         => 'mistral',
        'parameters'     => '3B',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat'],
        'description'    => 'Tiny efficient model from Mistral',
    ],
    'ministral-3:8b' => [
        'ollama_name'    => 'ministral-3:8b-cloud',
        'display_name'   => 'Ministral 3 8B',
        'family'         => 'mistral',
        'parameters'     => '8B',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat'],
        'description'    => 'Compact fast model from Mistral',
    ],
    'ministral-3:14b' => [
        'ollama_name'    => 'ministral-3:14b-cloud',
        'display_name'   => 'Ministral 3 14B',
        'family'         => 'mistral',
        'parameters'     => '14B',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat'],
        'description'    => 'Mid-size efficient model from Mistral',
    ],
    'kimi-k2.5' => [
        'ollama_name'    => 'kimi-k2.5:cloud',
        'display_name'   => 'Kimi K2.5',
        'family'         => 'kimi',
        'parameters'     => '1T',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code', 'reasoning'],
        'description'    => 'Moonshot AI Kimi K2.5 — 1T parameter frontier model',
    ],
    'kimi-k2-thinking' => [
        'ollama_name'    => 'kimi-k2-thinking:cloud',
        'display_name'   => 'Kimi K2 Thinking',
        'family'         => 'kimi',
        'parameters'     => '1T',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'reasoning'],
        'description'    => 'Kimi K2 with extended chain-of-thought reasoning',
    ],
    'kimi-k2:1t' => [
        'ollama_name'    => 'kimi-k2:1t-cloud',
        'display_name'   => 'Kimi K2 1T',
        'family'         => 'kimi',
        'parameters'     => '1T',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code', 'reasoning'],
        'description'    => 'Full Kimi K2 at 1 trillion parameters',
    ],
    'glm-4.6' => [
        'ollama_name'    => 'glm-4.6:cloud',
        'display_name'   => 'GLM 4.6 696B',
        'family'         => 'glm',
        'parameters'     => '696B',
        'context_length' => 128000,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'ZhiPu AI GLM 4.6 — Chinese + English flagship',
    ],
    'glm-4.7' => [
        'ollama_name'    => 'glm-4.7:cloud',
        'display_name'   => 'GLM 4.7 696B',
        'family'         => 'glm',
        'parameters'     => '696B',
        'context_length' => 128000,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'ZhiPu AI GLM 4.7 — improved multilingual model',
    ],
    'glm-5' => [
        'ollama_name'    => 'glm-5:cloud',
        'display_name'   => 'GLM 5 756B',
        'family'         => 'glm',
        'parameters'     => '756B',
        'context_length' => 128000,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code', 'reasoning'],
        'description'    => 'ZhiPu AI GLM 5 — latest generation 756B model',
    ],
    'minimax-m2' => [
        'ollama_name'    => 'minimax-m2:cloud',
        'display_name'   => 'MiniMax M2 230B',
        'family'         => 'minimax',
        'parameters'     => '230B',
        'context_length' => 1000000,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'MiniMax M2 — 1M context window',
    ],
    'minimax-m2.1' => [
        'ollama_name'    => 'minimax-m2.1:cloud',
        'display_name'   => 'MiniMax M2.1 230B',
        'family'         => 'minimax',
        'parameters'     => '230B',
        'context_length' => 1000000,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'MiniMax M2.1 — updated 230B with 1M context',
    ],
    'minimax-m2.5' => [
        'ollama_name'    => 'minimax-m2.5:cloud',
        'display_name'   => 'MiniMax M2.5 230B',
        'family'         => 'minimax',
        'parameters'     => '230B',
        'context_length' => 1000000,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'MiniMax M2.5 — latest generation 230B model',
    ],
    'gemma3:12b' => [
        'ollama_name'    => 'gemma3:12b-cloud',
        'display_name'   => 'Gemma 3 12B',
        'family'         => 'gemma',
        'parameters'     => '12B',
        'context_length' => 128000,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat'],
        'description'    => 'Google Gemma 3 12B via cloud',
    ],
    'gemma3:27b' => [
        'ollama_name'    => 'gemma3:27b-cloud',
        'display_name'   => 'Gemma 3 27B',
        'family'         => 'gemma',
        'parameters'     => '27B',
        'context_length' => 128000,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat'],
        'description'    => 'Google Gemma 3 27B — best open Gemma model',
    ],
    'gpt-oss:120b' => [
        'ollama_name'    => 'gpt-oss:120b-cloud',
        'display_name'   => 'GPT-OSS 120B',
        'family'         => 'gpt-oss',
        'parameters'     => '120B',
        'context_length' => 32768,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'code'],
        'description'    => 'OpenAI open-source 120B — 6x larger than local variant',
    ],
    'nemotron-3-nano:30b' => [
        'ollama_name'    => 'nemotron-3-nano:30b-cloud',
        'display_name'   => 'Nemotron 3 Nano 30B',
        'family'         => 'nvidia',
        'parameters'     => '30B',
        'context_length' => 131072,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat'],
        'description'    => 'NVIDIA Nemotron 3 Nano — efficient 30B model',
    ],
    'rnj-1:8b' => [
        'ollama_name'    => 'rnj-1:8b-cloud',
        'display_name'   => 'RNJ-1 8B',
        'family'         => 'other',
        'parameters'     => '8B',
        'context_length' => 32768,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat'],
        'description'    => 'RNJ-1 8B cloud model',
    ],
    'gemini-3-flash-preview' => [
        'ollama_name'    => 'gemini-3-flash-preview:cloud',
        'display_name'   => 'Gemini 3 Flash Preview',
        'family'         => 'google',
        'parameters'     => 'API',
        'context_length' => 1000000,
        'type'           => 'cloud',
        'credit_multiplier' => 2,
        'capabilities'   => ['chat', 'vision', 'reasoning'],
        'description'    => 'Google Gemini 3 Flash — preview via Ollama cloud',
    ],
];
```

Commit: `git add config/models.php && git commit -m "feat: add full model registry with metadata for all 45 models"`

---

### Task 4: Rewrite ModelsController

**File:** Modify `app/Http/Controllers/Api/ModelsController.php`

Replace entirely with:

```php
<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModelsController extends Controller
{
    /**
     * Map clean client-facing model names to internal Ollama names.
     * Sourced from config/models.php — used by resolveModelName().
     */
    public function resolveModelName(string $clientName): string
    {
        $registry = config('models');
        return $registry[$clientName]['ollama_name'] ?? $clientName;
    }

    /**
     * Return the full enriched model list.
     * Admins see all models. Regular users see only active models.
     * No tier filtering — all active models available to everyone.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => ['message' => 'Unauthenticated.', 'code' => 401]], 401);
        }

        // Load DB overrides (is_active, credit_multiplier_override)
        $dbOverrides = ModelConfig::all()->keyBy('model_id');

        $registry = config('models');
        $data = [];

        foreach ($registry as $id => $meta) {
            $override = $dbOverrides->get($id);

            // Skip disabled models (unless admin)
            $isActive = $override ? $override->is_active : true;
            $isAdmin  = $user->email === 'admin@llm.resayil.io';

            if (!$isActive && !$isAdmin) {
                continue;
            }

            $multiplier = $override?->credit_multiplier_override ?? $meta['credit_multiplier'];

            $data[] = [
                'id'                 => $id,
                'object'             => 'model',
                'created'            => 1740000000,
                'owned_by'           => 'llm-resayil',
                'display_name'       => $meta['display_name'],
                'family'             => $meta['family'],
                'parameters'         => $meta['parameters'],
                'context_length'     => $meta['context_length'],
                'type'               => $meta['type'],
                'credit_multiplier'  => $multiplier,
                'capabilities'       => $meta['capabilities'],
                'description'        => $meta['description'],
                'is_active'          => $isActive,
            ];
        }

        return response()->json(['object' => 'list', 'data' => $data]);
    }
}
```

Commit: `git add app/Http/Controllers/Api/ModelsController.php && git commit -m "feat: rewrite ModelsController to serve full registry with metadata, no tier filtering"`

---

### Task 5: Update ChatCompletionsController — remove tier gating, add admin bypass

**File:** Modify `app/Http/Controllers/Api/ChatCompletionsController.php`

**Changes:**
1. Remove the `isModelAllowed()` check (lines 98–108 in store(), lines 196–203 in stream())
2. Use registry for model resolution instead of cloudModelMap
3. Add admin bypass (skip rate limit + credit check if admin)

In `store()`, replace the model access block:
```php
// REMOVE THIS BLOCK:
// Get model access control
$allowedModels = $this->modelAccess->getAllowedModels($tier);
if (!$this->modelAccess->isModelAllowed($validated['model'], $tier)) {
    return response()->json([...], 403);
}
```

Add admin bypass after `$user = $request->user()`:
```php
$isAdmin = $user->email === 'admin@llm.resayil.io';

// Admin bypasses rate limit, credit check, model access
if ($isAdmin) {
    $provider  = 'local';
    $modelName = (new \App\Http\Controllers\Api\ModelsController())->resolveModelName($validated['model']);
    return $this->proxy->proxyChatCompletions($request, $provider, $modelName);
}
```

Replace model resolution lines 112–113 with registry-based lookup:
```php
$isCloudModel = (config('models')[$validated['model']]['type'] ?? 'local') === 'cloud';
$provider  = $isCloudModel ? 'cloud' : ($this->cloudFailover->shouldUseCloud($user) ? 'cloud' : 'local');
$modelName = (new \App\Http\Controllers\Api\ModelsController())->resolveModelName($validated['model']);
```

Apply same changes to `stream()` method.

Commit: `git add app/Http/Controllers/Api/ChatCompletionsController.php && git commit -m "feat: remove tier model gating, add admin bypass, use registry for model resolution"`

---

### Task 6: Deploy Agent 1 changes

```bash
ssh whm-server "cd ~/llm.resayil.io && git stash && git pull origin main && git stash pop && /opt/cpanel/ea-php82/root/usr/bin/php artisan migrate && /opt/cpanel/ea-php82/root/usr/bin/php artisan config:clear && /opt/cpanel/ea-php82/root/usr/bin/php artisan route:clear && /opt/cpanel/ea-php82/root/usr/bin/php artisan view:clear"
```

**Verify:**
```bash
curl -s https://llm.resayil.io/api/v1/models \
  -H "Authorization: Bearer b989f6ee21f0330e0702d67c3e038247a9c4584f6b4658e4717915224deb0a43" | python -m json.tool | grep '"id"' | wc -l
```
Expected: 41 models returned (all active by default).

---

## AGENT 2 — Searchable Dashboard UI

> Run in parallel with Agent 1. Does NOT depend on Agent 1's changes — reads from `/api/v1/models` which works before and after.

### Task 1: Replace model catalog section in dashboard

**File:** Modify `resources/views/dashboard.blade.php`

Replace the entire `<!-- Available Models -->` section (lines 70–91) with:

```html
<!-- Available Models -->
<div class="card" style="margin-bottom:1.5rem">
    <div class="flex items-center justify-between" style="margin-bottom:1rem">
        <h2 style="font-size:1rem;font-weight:600">Available Models</h2>
        <span id="model-count" class="text-xs text-muted"></span>
    </div>

    <!-- Search + Filters -->
    <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1rem;align-items:center">
        <input id="model-search" type="text" placeholder="Search models..."
            style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.4rem 0.75rem;color:var(--text-primary);font-size:0.8rem;width:200px"
            oninput="filterModels()">

        <select id="filter-family" onchange="filterModels()"
            style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.4rem 0.75rem;color:var(--text-primary);font-size:0.8rem">
            <option value="">All Families</option>
            <option value="deepseek">DeepSeek</option>
            <option value="qwen">Qwen</option>
            <option value="mistral">Mistral</option>
            <option value="kimi">Kimi</option>
            <option value="glm">GLM</option>
            <option value="gemma">Gemma</option>
            <option value="minimax">MiniMax</option>
            <option value="llama">Llama</option>
            <option value="gpt-oss">GPT-OSS</option>
            <option value="nvidia">NVIDIA</option>
            <option value="google">Google</option>
        </select>

        <select id="filter-type" onchange="filterModels()"
            style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.4rem 0.75rem;color:var(--text-primary);font-size:0.8rem">
            <option value="">Local + Cloud</option>
            <option value="local">Local only</option>
            <option value="cloud">Cloud only</option>
        </select>

        <select id="filter-size" onchange="filterModels()"
            style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:6px;padding:0.4rem 0.75rem;color:var(--text-primary);font-size:0.8rem">
            <option value="">All Sizes</option>
            <option value="small">Small (&lt;10B)</option>
            <option value="medium">Medium (10–100B)</option>
            <option value="large">Large (100B+)</option>
        </select>
    </div>

    <!-- Model Grid -->
    <div id="model-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:0.75rem">
        <div class="text-muted text-sm" style="grid-column:1/-1;padding:1rem 0">Loading models...</div>
    </div>

    <!-- Detail Panel (hidden by default) -->
    <div id="model-panel" style="display:none;margin-top:1rem;background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;padding:1.25rem">
        <div class="flex items-center justify-between" style="margin-bottom:0.75rem">
            <div id="panel-title" style="font-weight:600;font-size:0.95rem"></div>
            <button onclick="closePanel()" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1.2rem">✕</button>
        </div>
        <div id="panel-body"></div>
    </div>

    <p class="text-xs text-muted" style="margin-top:0.75rem">Click any model to see usage details · Cloud models cost 2× credits</p>
</div>
```

### Task 2: Add model catalog JS

Add at bottom of `dashboard.blade.php` before `</body>`:

```html
<script>
let allModels = [];

// Fetch from API using first available key
async function loadModels() {
    @if(auth()->user()->apiKeys()->where('status','active')->count() > 0)
    const key = '{{ auth()->user()->apiKeys()->where("status","active")->first()->key }}';
    try {
        const res = await fetch('/api/v1/models', {
            headers: { 'Authorization': 'Bearer ' + key }
        });
        const data = await res.json();
        allModels = data.data || [];
        renderModels(allModels);
        document.getElementById('model-count').textContent = allModels.length + ' models available';
    } catch(e) {
        document.getElementById('model-grid').innerHTML = '<div class="text-muted text-sm" style="grid-column:1/-1">Failed to load models. Check your API key.</div>';
    }
    @else
    document.getElementById('model-grid').innerHTML = '<div class="text-muted text-sm" style="grid-column:1/-1">Create an API key first to view available models.</div>';
    @endif
}

function renderModels(models) {
    const grid = document.getElementById('model-grid');
    if (!models.length) {
        grid.innerHTML = '<div class="text-muted text-sm" style="grid-column:1/-1">No models match your filters.</div>';
        return;
    }
    grid.innerHTML = models.map(m => `
        <div onclick="openPanel('${m.id}')" style="background:var(--bg-card);border:1px solid var(--border);border-radius:8px;padding:0.75rem;cursor:pointer;transition:border-color 0.15s" onmouseenter="this.style.borderColor='var(--gold)'" onmouseleave="this.style.borderColor='var(--border)'">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.35rem">
                <span style="font-family:monospace;font-size:0.75rem;color:var(--gold)">${m.id}</span>
                <span style="font-size:0.65rem;padding:0.15rem 0.4rem;border-radius:4px;background:${m.type==='cloud'?'rgba(212,175,55,0.15)':'rgba(100,200,100,0.1)'};color:${m.type==='cloud'?'var(--gold)':'#6dc96d'};white-space:nowrap;margin-left:0.5rem">${m.type==='cloud'?'☁ Cloud':'⚡ Local'}</span>
            </div>
            <div style="font-size:0.8rem;font-weight:500;margin-bottom:0.2rem">${m.display_name||m.id}</div>
            <div style="font-size:0.7rem;color:var(--text-muted)">${m.parameters||''} · ${(m.capabilities||[]).join(', ')}</div>
        </div>
    `).join('');
}

function filterModels() {
    const q      = document.getElementById('model-search').value.toLowerCase();
    const family = document.getElementById('filter-family').value;
    const type   = document.getElementById('filter-type').value;
    const size   = document.getElementById('filter-size').value;

    const filtered = allModels.filter(m => {
        if (q && !m.id.toLowerCase().includes(q) && !(m.display_name||'').toLowerCase().includes(q) && !(m.description||'').toLowerCase().includes(q)) return false;
        if (family && m.family !== family) return false;
        if (type && m.type !== type) return false;
        if (size) {
            const p = parseFloat(m.parameters);
            if (size === 'small'  && (isNaN(p) || p >= 10))  return false;
            if (size === 'medium' && (isNaN(p) || p < 10 || p >= 100)) return false;
            if (size === 'large'  && (isNaN(p) || p < 100))  return false;
        }
        return true;
    });

    document.getElementById('model-count').textContent = filtered.length + ' / ' + allModels.length + ' models';
    renderModels(filtered);
}

function openPanel(modelId) {
    const m = allModels.find(x => x.id === modelId);
    if (!m) return;
    document.getElementById('panel-title').textContent = m.display_name || m.id;

    const caps = (m.capabilities||[]).map(c => `<span style="font-size:0.65rem;padding:0.15rem 0.4rem;border-radius:4px;background:rgba(212,175,55,0.1);color:var(--gold);margin-right:0.25rem">${c}</span>`).join('');

    const curlSnippet = `curl -X POST https://llm.resayil.io/api/v1/chat/completions \\
  -H "Authorization: Bearer YOUR_API_KEY" \\
  -H "Content-Type: application/json" \\
  -d '{"model":"${m.id}","messages":[{"role":"user","content":"Hello"}]}'`;

    const pythonSnippet = `from openai import OpenAI
client = OpenAI(base_url="https://llm.resayil.io/api/v1", api_key="YOUR_API_KEY")
response = client.chat.completions.create(model="${m.id}", messages=[{"role":"user","content":"Hello"}])
print(response.choices[0].message.content)`;

    const n8nSnippet = JSON.stringify({
        "name": `LLM Resayil — ${m.id}`,
        "type": "n8n-nodes-base.httpRequest",
        "parameters": {
            "method": "POST",
            "url": "https://llm.resayil.io/api/v1/chat/completions",
            "authentication": "genericCredentialType",
            "genericAuthType": "httpBearerAuth",
            "sendBody": true,
            "contentType": "json",
            "body": JSON.stringify({"model": m.id, "messages": [{"role": "user", "content": "={{ $json.message }}"}]})
        }
    }, null, 2);

    document.getElementById('panel-body').innerHTML = `
        <div style="font-size:0.82rem;color:var(--text-muted);margin-bottom:0.75rem">${m.description||''}</div>
        <div style="margin-bottom:0.75rem">${caps}</div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.5rem;margin-bottom:1rem;font-size:0.78rem">
            <div style="background:var(--bg-card);padding:0.5rem;border-radius:6px"><div style="color:var(--text-muted);margin-bottom:0.2rem">Parameters</div><strong>${m.parameters||'—'}</strong></div>
            <div style="background:var(--bg-card);padding:0.5rem;border-radius:6px"><div style="color:var(--text-muted);margin-bottom:0.2rem">Context</div><strong>${m.context_length?(m.context_length/1000).toFixed(0)+'K':'—'}</strong></div>
            <div style="background:var(--bg-card);padding:0.5rem;border-radius:6px"><div style="color:var(--text-muted);margin-bottom:0.2rem">Credit cost</div><strong>${m.credit_multiplier||1}× per token</strong></div>
        </div>
        <div style="margin-bottom:0.5rem">
            <div style="display:flex;gap:0.5rem;margin-bottom:0.75rem">
                <button onclick="copyText('${m.id}',this)" class="btn btn-gold" style="padding:0.35rem 0.8rem;font-size:0.75rem">Copy Model ID</button>
            </div>
            <div style="font-size:0.75rem;color:var(--text-muted);margin-bottom:0.35rem">cURL</div>
            <pre onclick="copyText(\`${curlSnippet.replace(/`/g,'\\`')}\`,this)" style="background:var(--bg-card);padding:0.75rem;border-radius:6px;font-size:0.72rem;overflow-x:auto;cursor:pointer;border:1px solid var(--border)" title="Click to copy">${curlSnippet}</pre>
            <div style="font-size:0.75rem;color:var(--text-muted);margin:0.5rem 0 0.35rem">Python (openai SDK)</div>
            <pre onclick="copyText(\`${pythonSnippet.replace(/`/g,'\\`')}\`,this)" style="background:var(--bg-card);padding:0.75rem;border-radius:6px;font-size:0.72rem;overflow-x:auto;cursor:pointer;border:1px solid var(--border)" title="Click to copy">${pythonSnippet}</pre>
            <div style="font-size:0.75rem;color:var(--text-muted);margin:0.5rem 0 0.35rem">n8n HTTP Request Node <span style="color:var(--text-muted)">(click to copy JSON, paste directly into n8n canvas)</span></div>
            <pre onclick="copyText(\`${n8nSnippet.replace(/`/g,'\\`')}\`,this)" style="background:var(--bg-card);padding:0.75rem;border-radius:6px;font-size:0.72rem;overflow-x:auto;cursor:pointer;border:1px solid var(--border)" title="Click to copy">${n8nSnippet}</pre>
        </div>
    `;
    document.getElementById('model-panel').style.display = 'block';
}

function closePanel() {
    document.getElementById('model-panel').style.display = 'none';
}

function copyText(text, el) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = el.style.borderColor;
        el.style.borderColor = 'var(--gold)';
        setTimeout(() => el.style.borderColor = orig, 800);
    });
}

loadModels();
</script>
```

Commit: `git add resources/views/dashboard.blade.php && git commit -m "feat: searchable model catalog on dashboard with family/type/size filters and usage snippets"`

---

## AGENT 3 — Admin Model Panel

> Start after Agent 1's migration + registry are deployed.

### Task 1: Admin Model Management page

**File:** Create `resources/views/admin/models.blade.php`

Full table of all models from `config/models.php`. Each row has:
- Model ID (monospace)
- Display name
- Family badge
- Parameters
- Type (Local/Cloud)
- Credit multiplier (editable inline)
- Active toggle (checkbox → AJAX POST)

```php
// New route in routes/web.php:
Route::get('/admin/models', [AdminModelController::class, 'index'])->name('admin.models');
Route::post('/admin/models/{modelId}/toggle', [AdminModelController::class, 'toggle'])->name('admin.models.toggle');
Route::post('/admin/models/{modelId}/multiplier', [AdminModelController::class, 'updateMultiplier'])->name('admin.models.multiplier');
```

### Task 2: AdminModelController

**File:** Create `app/Http/Controllers/AdminModelController.php`

```php
<?php
namespace App\Http\Controllers;

use App\Models\ModelConfig;
use Illuminate\Http\Request;

class AdminModelController extends Controller
{
    public function index()
    {
        $registry  = config('models');
        $dbConfigs = ModelConfig::all()->keyBy('model_id');

        $models = collect($registry)->map(function ($meta, $id) use ($dbConfigs) {
            $db = $dbConfigs->get($id);
            return array_merge($meta, [
                'id'                       => $id,
                'is_active'                => $db ? $db->is_active : true,
                'credit_multiplier_active' => $db?->credit_multiplier_override ?? $meta['credit_multiplier'],
            ]);
        });

        return view('admin.models', compact('models'));
    }

    public function toggle(Request $request, string $modelId)
    {
        $config = ModelConfig::firstOrCreate(
            ['model_id' => $modelId],
            ['is_active' => true, 'credit_multiplier_override' => null]
        );
        $config->is_active = !$config->is_active;
        $config->save();

        return response()->json(['is_active' => $config->is_active]);
    }

    public function updateMultiplier(Request $request, string $modelId)
    {
        $request->validate(['multiplier' => 'required|numeric|min:0.1|max:100']);
        $config = ModelConfig::firstOrCreate(
            ['model_id' => $modelId],
            ['is_active' => true]
        );
        $config->credit_multiplier_override = $request->multiplier;
        $config->save();

        return response()->json(['multiplier' => $config->credit_multiplier_override]);
    }
}
```

### Task 3: Admin API Key Creation

**File:** Extend `app/Http/Controllers/AdminController.php`

Add methods:
```php
// Show all keys for a user
public function userKeys(User $user) { ... }

// Create key for a user
public function createKeyForUser(Request $request, User $user) {
    $key = \Str::random(64);
    $user->apiKeys()->create([
        'id'     => \Str::uuid(),
        'name'   => $request->name ?? 'Admin-created key',
        'key'    => $key,
        'prefix' => substr($key, 0, 12),
        'status' => 'active',
    ]);
    return back()->with('key_created', $key);
}

// Revoke any key
public function revokeKey(ApiKey $key) {
    $key->delete();
    return back()->with('success', 'Key revoked');
}
```

Add routes:
```php
Route::get('/admin/users/{user}/keys', [AdminController::class, 'userKeys'])->name('admin.user.keys');
Route::post('/admin/users/{user}/keys', [AdminController::class, 'createKeyForUser'])->name('admin.user.keys.create');
Route::delete('/admin/keys/{key}', [AdminController::class, 'revokeKey'])->name('admin.keys.revoke');
```

### Task 4: Extend User Management — credits, tier, expiry

**File:** Extend `app/Http/Controllers/AdminController.php`

Add methods:
```php
// Set exact credits
public function setCredits(Request $request, User $user) {
    $request->validate(['credits' => 'required|integer|min:0']);
    $user->update(['credits' => $request->credits]);
    return back()->with('success', 'Credits updated to ' . $request->credits);
}

// Set tier directly
public function setTier(Request $request, User $user) {
    $request->validate(['tier' => 'required|in:basic,pro,enterprise']);
    $user->update(['subscription_tier' => $request->tier]);
    return back()->with('success', 'Tier set to ' . $request->tier);
}

// Set subscription expiry
public function setExpiry(Request $request, User $user) {
    $request->validate(['expires_at' => 'required|date|after:today']);
    $user->update(['subscription_expires_at' => $request->expires_at]);
    return back()->with('success', 'Expiry set to ' . $request->expires_at);
}
```

Add routes:
```php
Route::post('/admin/users/{user}/credits', [AdminController::class, 'setCredits'])->name('admin.user.credits');
Route::post('/admin/users/{user}/tier', [AdminController::class, 'setTier'])->name('admin.user.tier');
Route::post('/admin/users/{user}/expiry', [AdminController::class, 'setExpiry'])->name('admin.user.expiry');
```

Update admin user list view to add these action forms inline.

### Task 5: Deploy Agent 3 changes

```bash
ssh whm-server "cd ~/llm.resayil.io && git stash && git pull origin main && git stash pop && /opt/cpanel/ea-php82/root/usr/bin/php artisan config:clear && /opt/cpanel/ea-php82/root/usr/bin/php artisan route:clear && /opt/cpanel/ea-php82/root/usr/bin/php artisan view:clear"
```

**Verify:**
- Visit `https://llm.resayil.io/admin/models` — see full model table with toggles
- Toggle a model off → call `/api/v1/models` → model missing from list
- Create API key for a user from admin → user sees it in their dashboard

---

## Deployment Order

```
1. Agent 1 + Agent 2 run in parallel (no dependencies between them)
2. Agent 1 pushes first → runs: git push + ssh deploy + migrate
3. Agent 2 pushes → runs: git push + ssh deploy (view:clear only)
4. Agent 3 runs after Agent 1 is deployed (needs models table + config)
5. Agent 3 pushes → runs: git push + ssh deploy
```

---

## Testing Checklist

- [ ] `GET /api/v1/models` returns 41 models with full metadata
- [ ] Dashboard catalog loads dynamically, filters work
- [ ] Click model → panel shows snippets
- [ ] Admin disables a model → API no longer returns it
- [ ] Admin re-enables → appears again
- [ ] Admin creates API key for user → user sees it
- [ ] Admin sets credits to exact amount → user balance updated
- [ ] Admin changes tier → user tier updated instantly
- [ ] Admin calls API — no rate limit, no credit deduction
- [ ] Cloud models cost 2× credits, local 1×
