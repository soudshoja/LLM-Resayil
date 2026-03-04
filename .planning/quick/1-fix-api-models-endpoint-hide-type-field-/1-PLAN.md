---
phase: quick
plan: 1
type: execute
wave: 1
depends_on: []
files_modified:
  - app/Http/Controllers/Api/ModelsController.php
autonomous: true
requirements: []
must_haves:
  truths:
    - "GET /api/v1/models response does not include a 'type' field in any model object"
    - "Embedding models (nomic-embed, bge, minilm, etc.) do not appear in GET /api/v1/models list"
    - "GET /api/v1/models/{id} for an embedding model still returns full detail"
  artifacts:
    - path: app/Http/Controllers/Api/ModelsController.php
      provides: "Fixed index() — no type field, embeddings filtered"
  key_links:
    - from: "index() $data array_map"
      to: "response JSON"
      via: "remove 'type' key from returned array"
    - from: "index() $models"
      to: "$data array_map"
      via: "filter where inferCategory() !== 'embedding' before mapping"
---

<objective>
Two surgical fixes to ModelsController::index():

1. Remove the `type` field (local/cloud distinction) from the public API model list response.
2. Filter out embedding models so they never appear in the chat model dropdown used by n8n and other clients.

The `show()` endpoint is untouched — embedding detail pages still work.

Purpose: Clients must never see local/cloud routing details; embedding models do not belong in LLM chat dropdowns.
Output: Modified ModelsController.php with both fixes applied.
</objective>

<context>
@app/Http/Controllers/Api/ModelsController.php
</context>

<tasks>

<task type="auto">
  <name>Task 1: Remove type field and filter embeddings from index()</name>
  <files>app/Http/Controllers/Api/ModelsController.php</files>
  <action>
In the `index()` method, make two changes:

**Fix 1 — Remove `type` from $data mapping (line 55):**
Delete the `'type' => $modelData['type'] ?? null,` line entirely from the array_map callback. The returned object must only have: id, object, created, owned_by, family, category, size.

**Fix 2 — Filter embeddings before building $data:**
After the `$models` variable is populated (after the null fallback check on line 46), add a filter to remove embedding models BEFORE the array_map. The `inferCategory()` method is already available on $this.

Insert between the fallback block and the `$data = array_map(...)` line:

```php
// Filter out embedding models — they are not chat LLMs
$models = array_filter($models, function (array $modelData) {
    return ($modelData['category'] ?? 'chat') !== 'embedding';
});
```

This uses the already-inferred `category` key stored in each model's metadata array (set by `inferCategory()` during `fetchModelsFromOllama()`), so no extra method calls are needed.

Do NOT touch the `show()` method — individual model detail must still work for embeddings.
Do NOT touch `fallbackToConfig()` — the fallback path also populates `category` from config.
  </action>
  <verify>
    <automated>php -r "
require 'D:/Claude/projects/LLM-Resayil/vendor/autoload.php';
\$src = file_get_contents('D:/Claude/projects/LLM-Resayil/app/Http/Controllers/Api/ModelsController.php');
// Check type field is gone from array_map
if (strpos(\$src, \"'type' => \\\$modelData\") !== false) { echo 'FAIL: type field still present in index() mapping'; exit(1); }
// Check embedding filter is present
if (strpos(\$src, \"!== 'embedding'\") === false) { echo 'FAIL: embedding filter not found'; exit(1); }
echo 'PASS';
"
    </automated>
  </verify>
  <done>
    - index() response has no 'type' field in any model object
    - index() excludes any model where category === 'embedding'
    - show() method is unchanged and still includes 'type' field in its response
  </done>
</task>

</tasks>

<verification>
After applying fixes, verify manually:
- curl -s -H "Authorization: Bearer {API_KEY}" https://llmdev.resayil.io/api/v1/models | jq '.data[0] | keys'
  Should NOT contain "type"
- curl output should show 0 models with "embedding" in their id (nomic-embed, bge-*, minilm, etc.)
- curl -s .../api/v1/models/nomic-embed-text should still return 200 with full detail including type field
</verification>

<success_criteria>
- 'type' key absent from every object in GET /api/v1/models data array
- No model with category=embedding appears in GET /api/v1/models list
- GET /api/v1/models/{embedding-id} still returns 200 with complete metadata
</success_criteria>

<output>
No SUMMARY file required for quick plans.
</output>
