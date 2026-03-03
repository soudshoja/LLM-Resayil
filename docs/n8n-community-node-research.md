# n8n Community Node Research: LLM Resayil

**Date:** 2026-03-03
**Goal:** Build an n8n community node for LLM Resayil so it can be used as an AI Agent LLM provider in n8n workflows.

---

## 1. Overview & Use Case

LLM Resayil (`https://llm.resayil.io`) exposes an OpenAI-compatible API at `/api/v1`. n8n's AI Agent node requires a "Chat Model" sub-node that supplies a LangChain `BaseChatModel` instance. Because LLM Resayil is fully OpenAI-compatible, we can wrap `ChatOpenAI` from `@langchain/openai` and point it at our base URL.

**What the node enables:**
- Drag the "LLM Resayil Chat Model" sub-node into any n8n AI Agent workflow.
- Select a model from a dynamically loaded list (fetched from `/api/v1/models`).
- Configure temperature, max tokens, and other parameters.
- Works as a drop-in alternative to the built-in OpenAI Chat Model node.

**API details:**
```
Base URL:  https://llm.resayil.io/api/v1
Auth:      Authorization: Bearer {api_key}
Endpoints: POST /chat/completions
           GET  /models
```

---

## 2. How n8n AI Agent LLM Sub-Nodes Work

### Architecture: Cluster Nodes

n8n AI workflows use a "cluster node" pattern:

- A **root node** (e.g., AI Agent) orchestrates everything.
- **Sub-nodes** (e.g., Chat Model, Memory, Tools) plug into the root node's special inputs.
- Sub-nodes do NOT use an `execute()` method — they use `supplyData()` instead.

When the AI Agent runs, it calls `supplyData()` on each connected sub-node. The Chat Model sub-node returns a LangChain `BaseChatModel` instance. The Agent uses that instance to call the LLM.

### The `ISupplyData` / `supplyData()` Pattern

A Chat Model sub-node implements the `INodeType` interface but instead of `execute()`, it implements `supplyData()`:

```typescript
import { INodeType, INodeTypeDescription, ISupplyDataFunctions, SupplyData } from 'n8n-workflow';
import { ChatOpenAI } from '@langchain/openai';

export class LlmResayilModel implements INodeType {
  description: INodeTypeDescription = { /* ... */ };

  async supplyData(this: ISupplyDataFunctions): Promise<SupplyData> {
    const credentials = await this.getCredentials('llmResayilApi');
    const modelName = this.getNodeParameter('model', 0) as string;
    const temperature = this.getNodeParameter('temperature', 0) as number;

    const model = new ChatOpenAI({
      modelName,
      openAIApiKey: credentials.apiKey as string,
      configuration: {
        baseURL: 'https://llm.resayil.io/api/v1',
      },
      temperature,
    });

    return { response: model };
  }
}
```

### The `NodeConnectionType.AiLanguageModel` Output

For the sub-node to appear in the "Language Model" slot of an AI Agent, the node's `description` must declare its output as `NodeConnectionType.AiLanguageModel`:

```typescript
import { NodeConnectionType } from 'n8n-workflow';

outputs: [NodeConnectionType.AiLanguageModel],
outputNames: ['Model'],
```

**Critical gotcha:** The Tools Agent node validates that the connected model is specifically a `BaseChatModel` (not just `BaseLanguageModel`). `ChatOpenAI` from `@langchain/openai` extends `BaseChatModel`, so this works correctly. If you return a `BaseLanguageModel` subclass instead, you will get the error: "Tools Agent requires Chat Model".

### The `codex` Metadata for Categorization

n8n uses a `codex` field in the node description to place the node in the correct UI category:

```typescript
codex: {
  categories: ['AI'],
  subcategories: {
    AI: ['Language Models'],
    'Language Models': ['Chat Models (Recommended)'],
  },
  resources: {
    primaryDocumentation: [
      { url: 'https://llm.resayil.io/docs' },
    ],
  },
},
```

This places the node in **AI > Language Models > Chat Models (Recommended)** in the n8n node picker.

### How `@langchain/openai` ChatOpenAI Works with Custom Base URLs

`ChatOpenAI` accepts a `configuration` object that is passed directly to the OpenAI SDK client. The `baseURL` field in that configuration overrides the default OpenAI endpoint:

```typescript
const model = new ChatOpenAI({
  modelName: 'llama3.2:3b',
  openAIApiKey: 'sk-your-key',
  configuration: {
    baseURL: 'https://llm.resayil.io/api/v1',
    // defaultHeaders can be added here if needed
  },
  temperature: 0.7,
  maxTokens: 2048,
});
```

This is exactly how the built-in n8n Ollama and OpenRouter nodes work under the hood.

---

## 3. Complete Package Structure

```
n8n-nodes-llm-resayil/
├── package.json                          ← npm metadata + n8n node registration
├── tsconfig.json                         ← TypeScript config
├── .eslintrc.js                          ← linting (optional, needed for npm verification)
├── gulpfile.js                           ← copies SVG icons to dist/
├── src/
│   ├── nodes/
│   │   └── LlmResayil/
│   │       ├── LlmResayilModel.node.ts   ← AI Agent sub-node (supplyData)
│   │       ├── LlmResayil.node.ts        ← Standalone chat completions node (optional)
│   │       └── llm-resayil.svg           ← Node icon
│   └── credentials/
│       └── LlmResayilApi.credentials.ts  ← API key credential type
├── dist/                                 ← Compiled output (auto-generated, gitignored)
│   ├── nodes/
│   │   └── LlmResayil/
│   │       ├── LlmResayilModel.node.js
│   │       ├── LlmResayil.node.js
│   │       └── llm-resayil.svg
│   └── credentials/
│       └── LlmResayilApi.credentials.js
└── README.md
```

The `dist/` folder is what n8n actually loads. The `package.json` `n8n` section points to the compiled JS paths.

---

## 4. Key Code Snippets

### 4.1 Credentials Class (`LlmResayilApi.credentials.ts`)

```typescript
import { ICredentialType, INodeProperties } from 'n8n-workflow';

export class LlmResayilApi implements ICredentialType {
  name = 'llmResayilApi';
  displayName = 'LLM Resayil API';
  documentationUrl = 'https://llm.resayil.io/docs/authentication';
  properties: INodeProperties[] = [
    {
      displayName: 'API Key',
      name: 'apiKey',
      type: 'string',
      typeOptions: { password: true },
      default: '',
      required: true,
      description: 'Your LLM Resayil API key (starts with sk-)',
    },
  ];
}
```

### 4.2 AI Agent Sub-Node (`LlmResayilModel.node.ts`)

This is the primary node — the one that plugs into AI Agent workflows.

```typescript
import {
  INodeType,
  INodeTypeDescription,
  ISupplyDataFunctions,
  NodeConnectionType,
  SupplyData,
  ILoadOptionsFunctions,
  INodePropertyOptions,
} from 'n8n-workflow';
import { ChatOpenAI } from '@langchain/openai';

const BASE_URL = 'https://llm.resayil.io/api/v1';

export class LlmResayilModel implements INodeType {
  description: INodeTypeDescription = {
    displayName: 'LLM Resayil Chat Model',
    name: 'llmResayilModel',
    icon: 'file:llm-resayil.svg',
    group: ['transform'],
    version: 1,
    description: 'Use LLM Resayil models with n8n AI Agents and Chains',
    defaults: {
      name: 'LLM Resayil Chat Model',
    },
    codex: {
      categories: ['AI'],
      subcategories: {
        AI: ['Language Models'],
        'Language Models': ['Chat Models (Recommended)'],
      },
      resources: {
        primaryDocumentation: [
          { url: 'https://llm.resayil.io/docs' },
        ],
      },
    },
    inputs: [],
    outputs: [NodeConnectionType.AiLanguageModel],
    outputNames: ['Model'],
    credentials: [
      {
        name: 'llmResayilApi',
        required: true,
      },
    ],
    properties: [
      {
        displayName: 'Model',
        name: 'model',
        type: 'options',
        typeOptions: {
          loadOptionsMethod: 'getModels',
        },
        default: 'llama3.2:3b',
        required: true,
        description: 'The model to use for chat completions',
      },
      {
        displayName: 'Options',
        name: 'options',
        type: 'collection',
        placeholder: 'Add Option',
        default: {},
        options: [
          {
            displayName: 'Temperature',
            name: 'temperature',
            type: 'number',
            typeOptions: {
              minValue: 0,
              maxValue: 2,
              numberStepSize: 0.1,
            },
            default: 0.7,
            description: 'Controls randomness. 0 = deterministic, 2 = very random.',
          },
          {
            displayName: 'Max Tokens',
            name: 'maxTokens',
            type: 'number',
            typeOptions: { minValue: 1 },
            default: 1024,
            description: 'Maximum number of tokens to generate',
          },
          {
            displayName: 'Top P',
            name: 'topP',
            type: 'number',
            typeOptions: { minValue: 0, maxValue: 1, numberStepSize: 0.01 },
            default: 1,
            description: 'Nucleus sampling parameter',
          },
          {
            displayName: 'Frequency Penalty',
            name: 'frequencyPenalty',
            type: 'number',
            typeOptions: { minValue: -2, maxValue: 2, numberStepSize: 0.1 },
            default: 0,
            description: 'Reduces repetition of token sequences',
          },
          {
            displayName: 'Timeout (ms)',
            name: 'timeout',
            type: 'number',
            typeOptions: { minValue: 1 },
            default: 60000,
            description: 'Request timeout in milliseconds',
          },
          {
            displayName: 'Max Retries',
            name: 'maxRetries',
            type: 'number',
            default: 2,
            description: 'Number of retries on transient errors',
          },
        ],
      },
    ],
  };

  // Dynamic model list from /api/v1/models
  methods = {
    loadOptions: {
      async getModels(this: ILoadOptionsFunctions): Promise<INodePropertyOptions[]> {
        const credentials = await this.getCredentials('llmResayilApi');
        const response = await this.helpers.request({
          method: 'GET',
          url: `${BASE_URL}/models`,
          headers: {
            Authorization: `Bearer ${credentials.apiKey}`,
            'Content-Type': 'application/json',
          },
          json: true,
        });

        // OpenAI-compatible /models returns { data: [{ id, ... }] }
        const models = (response.data as Array<{ id: string; owned_by?: string }>) ?? [];
        return models.map((m) => ({
          name: m.id,
          value: m.id,
          description: m.owned_by ? `Provider: ${m.owned_by}` : undefined,
        }));
      },
    },
  };

  async supplyData(this: ISupplyDataFunctions): Promise<SupplyData> {
    const credentials = await this.getCredentials('llmResayilApi');
    const modelName = this.getNodeParameter('model', 0) as string;
    const options = this.getNodeParameter('options', 0, {}) as {
      temperature?: number;
      maxTokens?: number;
      topP?: number;
      frequencyPenalty?: number;
      timeout?: number;
      maxRetries?: number;
    };

    const model = new ChatOpenAI({
      modelName,
      openAIApiKey: credentials.apiKey as string,
      configuration: {
        baseURL: BASE_URL,
      },
      temperature: options.temperature ?? 0.7,
      maxTokens: options.maxTokens ?? 1024,
      topP: options.topP ?? 1,
      frequencyPenalty: options.frequencyPenalty ?? 0,
      timeout: options.timeout ?? 60000,
      maxRetries: options.maxRetries ?? 2,
    });

    return { response: model };
  }
}
```

### 4.3 Standalone Chat Node (`LlmResayil.node.ts`) — Optional

This is a standard `execute()` node for users who want a simple chat completions call without the AI Agent pattern.

```typescript
import {
  IExecuteFunctions,
  INodeExecutionData,
  INodeType,
  INodeTypeDescription,
  NodeOperationError,
} from 'n8n-workflow';

export class LlmResayil implements INodeType {
  description: INodeTypeDescription = {
    displayName: 'LLM Resayil',
    name: 'llmResayil',
    icon: 'file:llm-resayil.svg',
    group: ['transform'],
    version: 1,
    subtitle: '={{$parameter["operation"]}}',
    description: 'Interact with LLM Resayil API',
    defaults: { name: 'LLM Resayil' },
    inputs: ['main'],
    outputs: ['main'],
    credentials: [{ name: 'llmResayilApi', required: true }],
    properties: [
      {
        displayName: 'Model',
        name: 'model',
        type: 'string',
        default: 'llama3.2:3b',
        description: 'Model ID to use',
      },
      {
        displayName: 'User Message',
        name: 'message',
        type: 'string',
        typeOptions: { rows: 4 },
        default: '',
        required: true,
        description: 'The message to send to the model',
      },
      {
        displayName: 'System Prompt',
        name: 'systemPrompt',
        type: 'string',
        typeOptions: { rows: 3 },
        default: '',
        description: 'Optional system prompt',
      },
      {
        displayName: 'Temperature',
        name: 'temperature',
        type: 'number',
        typeOptions: { minValue: 0, maxValue: 2, numberStepSize: 0.1 },
        default: 0.7,
      },
      {
        displayName: 'Max Tokens',
        name: 'maxTokens',
        type: 'number',
        default: 1024,
      },
    ],
  };

  async execute(this: IExecuteFunctions): Promise<INodeExecutionData[][]> {
    const items = this.getInputData();
    const returnData: INodeExecutionData[] = [];
    const credentials = await this.getCredentials('llmResayilApi');

    for (let i = 0; i < items.length; i++) {
      const model = this.getNodeParameter('model', i) as string;
      const message = this.getNodeParameter('message', i) as string;
      const systemPrompt = this.getNodeParameter('systemPrompt', i) as string;
      const temperature = this.getNodeParameter('temperature', i) as number;
      const maxTokens = this.getNodeParameter('maxTokens', i) as number;

      const messages = [];
      if (systemPrompt) {
        messages.push({ role: 'system', content: systemPrompt });
      }
      messages.push({ role: 'user', content: message });

      try {
        const response = await this.helpers.request({
          method: 'POST',
          url: 'https://llm.resayil.io/api/v1/chat/completions',
          headers: {
            Authorization: `Bearer ${credentials.apiKey}`,
            'Content-Type': 'application/json',
          },
          body: {
            model,
            messages,
            temperature,
            max_tokens: maxTokens,
          },
          json: true,
        });

        returnData.push({
          json: {
            text: response.choices[0].message.content,
            model: response.model,
            usage: response.usage,
            raw: response,
          },
        });
      } catch (error) {
        if (this.continueOnFail()) {
          returnData.push({ json: { error: (error as Error).message } });
          continue;
        }
        throw new NodeOperationError(this.getNode(), error as Error);
      }
    }

    return [returnData];
  }
}
```

### 4.4 Model List from `/api/v1/models`

The `loadOptions.getModels` method above hits the standard OpenAI-compatible `/models` endpoint:

```
GET https://llm.resayil.io/api/v1/models
Authorization: Bearer {api_key}

Response:
{
  "object": "list",
  "data": [
    { "id": "llama3.2:3b", "object": "model", "owned_by": "ollama" },
    { "id": "qwen3-30b", "object": "model", "owned_by": "ollama" },
    ...
  ]
}
```

This populates the Model dropdown dynamically, so users always see the currently available models without hardcoding.

---

## 5. `package.json` Requirements

The `package.json` must contain an `n8n` section that registers all nodes and credentials. The package name must start with `n8n-nodes-` and the keywords must include `"n8n-community-node-package"`.

```json
{
  "name": "n8n-nodes-llm-resayil",
  "version": "0.1.0",
  "description": "n8n community node for LLM Resayil — OpenAI-compatible LLM API",
  "keywords": [
    "n8n-community-node-package",
    "n8n",
    "llm",
    "ai",
    "openai",
    "langchain",
    "llm-resayil"
  ],
  "license": "MIT",
  "homepage": "https://llm.resayil.io",
  "repository": {
    "type": "git",
    "url": "https://github.com/soudshoja/n8n-nodes-llm-resayil.git"
  },
  "main": "dist/index.js",
  "scripts": {
    "build": "tsc && gulp build:icons",
    "dev": "tsc --watch",
    "lint": "eslint nodes credentials --ext .ts",
    "prepublishOnly": "npm run build && npm run lint"
  },
  "files": [
    "dist"
  ],
  "n8n": {
    "n8nNodesApiVersion": 1,
    "credentials": [
      "dist/credentials/LlmResayilApi.credentials.js"
    ],
    "nodes": [
      "dist/nodes/LlmResayil/LlmResayilModel.node.js",
      "dist/nodes/LlmResayil/LlmResayil.node.js"
    ]
  },
  "devDependencies": {
    "@types/node": "^20.0.0",
    "eslint": "^8.0.0",
    "gulp": "^4.0.0",
    "n8n-workflow": "*",
    "typescript": "^5.0.0"
  },
  "peerDependencies": {
    "n8n-workflow": "*"
  },
  "dependencies": {
    "@langchain/openai": "^0.3.0"
  }
}
```

**Key fields explained:**

| Field | Why It Matters |
|-------|---------------|
| `keywords` must include `"n8n-community-node-package"` | n8n's discovery system searches npm for this keyword |
| `name` must start with `n8n-nodes-` | Required naming convention for n8n community nodes |
| `n8n.n8nNodesApiVersion: 1` | Required for compatibility |
| `n8n.credentials[]` | Paths to compiled credential JS files |
| `n8n.nodes[]` | Paths to compiled node JS files |
| `peerDependencies: n8n-workflow: "*"` | n8n-workflow is provided by the host n8n instance |
| `@langchain/openai` in `dependencies` | Must be bundled since it's not part of n8n core |
| `"files": ["dist"]` | Only publish compiled output to npm |

---

## 6. `tsconfig.json`

```json
{
  "compilerOptions": {
    "strict": true,
    "module": "commonjs",
    "target": "ES2019",
    "lib": ["ES2019"],
    "moduleResolution": "node",
    "outDir": "./dist",
    "rootDir": "./src",
    "declaration": true,
    "declarationMap": true,
    "sourceMap": true,
    "esModuleInterop": true,
    "skipLibCheck": true,
    "resolveJsonModule": true
  },
  "include": ["src/**/*"],
  "exclude": ["dist", "node_modules", "**/*.test.ts"]
}
```

### `gulpfile.js` (for copying SVG icons to dist)

```javascript
const { src, dest } = require('gulp');

function buildIcons() {
  return src('src/**/*.svg').pipe(dest('dist'));
}

exports['build:icons'] = buildIcons;
```

---

## 7. Publishing to npm

### Step 1: Bootstrap the project

```bash
# Option A: Use n8n's official CLI scaffolder
npm create @n8n/node n8n-nodes-llm-resayil

# Option B: Manual setup from the structure above
mkdir n8n-nodes-llm-resayil && cd n8n-nodes-llm-resayil
npm init
```

### Step 2: Install dependencies

```bash
npm install @langchain/openai
npm install --save-dev typescript gulp @types/node n8n-workflow
```

### Step 3: Build

```bash
npm run build
# Output: dist/ folder with compiled JS + SVG icons
```

### Step 4: Test locally in n8n

```bash
# Install n8n globally
npm install -g n8n

# Link your package locally
npm link
cd ~/.n8n
npm link n8n-nodes-llm-resayil

# Start n8n
n8n start
# Visit http://localhost:5678
# Add node → search "LLM Resayil" — it should appear
```

Alternatively, with Docker:
```bash
docker run -it --rm \
  -p 5678:5678 \
  -v ~/.n8n:/home/node/.n8n \
  -v $(pwd):/home/node/.n8n/custom \
  n8nio/n8n
```

### Step 5: Lint

```bash
npm run lint
# Fix any errors before publishing
```

### Step 6: Publish to npm

```bash
npm login
npm publish --access public
```

### Step 7: Install in n8n

Once published to npm, users install it via:

**n8n GUI:** Settings → Community Nodes → Install → `n8n-nodes-llm-resayil`

**Self-hosted CLI:**
```bash
n8n community-nodes install n8n-nodes-llm-resayil
```

**Docker .env:**
```
N8N_COMMUNITY_PACKAGES_ENABLED=true
```

### Step 8: Submit for n8n Verification (Optional)

Submit a PR to the [n8n community nodes registry](https://github.com/n8n-io/n8n/blob/master/packages/nodes-base/nodes/CONTRIBUTING.md) to have your node featured in the n8n UI. This requires passing the linter and review checklist.

---

## 8. Critical Technical Notes & Gotchas

### 8.1 `BaseChatModel` vs `BaseLanguageModel` — Must Return Chat Model

The Tools Agent node validates the model type at runtime. It checks that the supplied model is a `BaseChatModel`, not merely a `BaseLanguageModel`. `ChatOpenAI` from `@langchain/openai` correctly extends `BaseChatModel` with tool-calling support. Do NOT wrap in a custom class that extends `BaseLanguageModel` — this will cause "Tools Agent requires Chat Model" errors.

Reference: [GitHub Issue #18561](https://github.com/n8n-io/n8n/issues/18561)

### 8.2 `@langchain/openai` Version Pinning

n8n's built-in langchain nodes use specific versions. The `@langchain/openai` package in your node must be compatible with the version n8n has loaded. If versions conflict, you'll get runtime errors. Use `^0.3.0` or check the current n8n-nodes-langchain `package.json` for the exact version they pin.

Check: `https://github.com/n8n-io/n8n/blob/master/packages/@n8n/nodes-langchain/package.json`

### 8.3 `supplyData` vs `execute`

- Sub-nodes that provide models/tools use `supplyData()`.
- Regular workflow nodes use `execute()`.
- You cannot mix both in the same node in a meaningful way.

### 8.4 `outputs` Must Be `[NodeConnectionType.AiLanguageModel]`

```typescript
// CORRECT — appears in AI Agent's "Chat Model" slot
outputs: [NodeConnectionType.AiLanguageModel],
outputNames: ['Model'],

// WRONG — appears as a regular node with a data output
outputs: ['main'],
```

### 8.5 Dynamic `loadOptions` Needs Credentials Access

The `loadOptions.getModels` method runs in the n8n editor UI context (not workflow execution). It uses `this.getCredentials()` to fetch the API key and `this.helpers.request()` to call the `/models` endpoint. The credentials must already be configured in the n8n credential store before the dropdown will populate.

### 8.6 `configuration.baseURL` vs Legacy `basePath`

Older LangChain versions used `baseUrl` or `basePath` as a top-level ChatOpenAI option. Modern `@langchain/openai` passes it via the `configuration` object which is forwarded to the `openai` JS SDK:

```typescript
// MODERN (correct for @langchain/openai >= 0.1.x)
new ChatOpenAI({
  configuration: { baseURL: 'https://llm.resayil.io/api/v1' },
  openAIApiKey: 'sk-...',
});

// LEGACY (do NOT use — may not work)
new ChatOpenAI({
  baseUrl: 'https://llm.resayil.io/api/v1',
  openAIApiKey: 'sk-...',
});
```

Reference: [n8n GitHub Issue #16121](https://github.com/n8n-io/n8n/issues/16121) (example with custom baseUrl working)

### 8.7 `n8n-workflow` as `peerDependency`

`n8n-workflow` provides the TypeScript interfaces (`INodeType`, `ISupplyDataFunctions`, etc.). It MUST be a `peerDependency`, not a regular `dependency`. The host n8n instance provides it. Bundling it yourself causes version conflicts and type mismatch errors.

---

## 9. Reference: Existing Community Nodes to Study

| Package | What to learn from it |
|---------|----------------------|
| [n8n-nodes-straico-chat](https://github.com/markus-ertel/n8n-nodes-straico-chat) | OpenAI-compatible API wrapped as Chat Model sub-node |
| [n8n-nodes-azure-openai-ms-oauth2](https://github.com/wtyeung/n8n-nodes-azure-openai-ms-oauth2) | Azure ChatOpenAI with custom auth + supplyData |
| [n8n-custom-chat-node](https://github.com/gabriel-x/n8n-custom-chat-node) | OpenAI-compatible chat with removed unsupported options |
| [n8n-nodes-starter](https://github.com/n8n-io/n8n-nodes-starter) | Official n8n starter template (credentials + node structure) |
| Built-in LmChatOpenAi | `packages/@n8n/nodes-langchain/nodes/llms/LMChatOpenAi/LmChatOpenAi.node.ts` in n8n repo |
| Built-in LmChatOllama | `packages/@n8n/nodes-langchain/nodes/llms/LMChatOllama/` — another OpenAI-compatible example |

---

## 10. Effort Estimate

| Task | Estimated Time |
|------|---------------|
| Scaffold project from n8n-nodes-starter or @n8n/create-node | 30 min |
| Write credentials class | 30 min |
| Write LlmResayilModel.node.ts (AI Agent sub-node) | 2–3 hours |
| Write LlmResayil.node.ts (standalone node, optional) | 1–2 hours |
| Design SVG icon | 30 min |
| Local testing with n8n | 1–2 hours |
| Write README + publish to npm | 1 hour |
| **Total** | **~6–9 hours** |

The AI Agent sub-node is the priority. The standalone node is optional and can be added later.

---

## 11. Quick Start Checklist

- [ ] `npm create @n8n/node n8n-nodes-llm-resayil` — scaffold project
- [ ] `npm install @langchain/openai` — install LangChain OpenAI
- [ ] Write `LlmResayilApi.credentials.ts` — API key field with `password: true`
- [ ] Write `LlmResayilModel.node.ts` — `supplyData()` returning `ChatOpenAI` with `configuration.baseURL`
- [ ] Set `outputs: [NodeConnectionType.AiLanguageModel]` and `outputNames: ['Model']`
- [ ] Set `codex.subcategories` so node appears under "Chat Models (Recommended)"
- [ ] Add `loadOptions.getModels` fetching `/api/v1/models` dynamically
- [ ] Set `n8n.n8nNodesApiVersion: 1` in `package.json`
- [ ] Add `"n8n-community-node-package"` to `package.json` keywords
- [ ] Set `peerDependencies: { "n8n-workflow": "*" }`
- [ ] Test locally with `npm link` + `n8n start`
- [ ] `npm publish --access public`
- [ ] Install in n8n via Settings → Community Nodes

---

## Sources

- [n8n Custom AI chat model issue #16121 (supplyData + baseURL example)](https://github.com/n8n-io/n8n/issues/16121)
- [n8n-nodes-starter (official template)](https://github.com/n8n-io/n8n-nodes-starter)
- [n8n Building community nodes](https://docs.n8n.io/integrations/community-nodes/build-community-nodes/)
- [n8n Submit community nodes](https://docs.n8n.io/integrations/creating-nodes/deploy/submit-community-nodes/)
- [n8n AI and LangChain Nodes (DeepWiki)](https://deepwiki.com/n8n-io/n8n/4.4-ai-and-langchain-nodes)
- [n8n-nodes-azure-openai-ms-oauth2](https://github.com/wtyeung/n8n-nodes-azure-openai-ms-oauth2)
- [n8n-custom-chat-node (OpenAI-compatible)](https://github.com/gabriel-x/n8n-custom-chat-node)
- [n8n-nodes-straico-chat](https://github.com/markus-ertel/n8n-nodes-straico-chat)
- [Custom LLM Node AiLanguageModel fails Tools Agent](https://community.n8n.io/t/custom-llm-node-with-ailanguagemodel-output-fails-with-tools-agent-requires-chat-model/169596)
- [Creating a custom llm chat model node](https://community.n8n.io/t/creating-a-custom-llm-chat-model-node/74926)
- [OpenAI Chat Model node docs](https://docs.n8n.io/integrations/builtin/cluster-nodes/sub-nodes/n8n-nodes-langchain.lmchatopenai/)
- [LangChain in n8n overview](https://docs.n8n.io/advanced-ai/langchain/overview/)
- [@n8n/node-cli on npm](https://www.npmjs.com/package/@n8n/node-cli)
- [n8n nodes-langchain package.json](https://github.com/n8n-io/n8n/blob/master/packages/@n8n/nodes-langchain/package.json)
- [Model Selector node docs (for understanding connection types)](https://docs.n8n.io/integrations/builtin/cluster-nodes/sub-nodes/n8n-nodes-langchain.modelselector/)
- [LmChatOllama description.ts (reference for structure)](https://github.com/n8n-io/n8n/blob/master/packages/@n8n/nodes-langchain/nodes/llms/LMOllama/description.ts)
- [Model Selector node not recognized as ChatModel issue #18561](https://github.com/n8n-io/n8n/issues/18561)
