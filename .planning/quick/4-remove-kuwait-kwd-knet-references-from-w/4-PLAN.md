---
phase: quick-4
plan: 01
type: execute
wave: 1
depends_on: []
files_modified:
  - resources/lang/en/welcome.php
  - resources/lang/ar/welcome.php
autonomous: true
requirements: [COPY-01]
must_haves:
  truths:
    - "Welcome page marketing copy contains no 'Kuwait', 'Kuwaiti', 'KNET', or 'كويتي'/'KNET' in prose strings"
    - "KWD currency amounts (five_kwd, ten_kwd, twenty_five_kwd, 15_kwd_month) are preserved unchanged"
    - "Hero headline reads 'The Developer-First LLM API Platform' (EN)"
    - "Trust badge reads 'High-Performance Inference' (EN) / 'استدلال عالي الأداء' (AR)"
    - "Payment text references 'credit card' only — no KNET mention"
  artifacts:
    - path: "resources/lang/en/welcome.php"
      provides: "Updated EN translation strings"
    - path: "resources/lang/ar/welcome.php"
      provides: "Updated AR translation strings"
  key_links:
    - from: "resources/lang/en/welcome.php"
      to: "resources/views/welcome.blade.php"
      via: "__('welcome.key') translation calls"
    - from: "resources/lang/ar/welcome.php"
      to: "resources/views/welcome.blade.php"
      via: "__('welcome.key') translation calls"
---

<objective>
Remove all Kuwait/KWD-in-prose/KNET references from welcome page marketing copy in both English and Arabic translation files. KWD currency amounts in pricing rows must be preserved. The product should read as a globally-positioned LLM API gateway, not a Kuwait-specific product.

Purpose: The platform serves international developers. Marketing copy anchored to Kuwait limits perceived audience and may deter non-Kuwaiti visitors.
Output: Updated en/welcome.php and ar/welcome.php with generalized marketing copy.
</objective>

<execution_context>
@C:/Users/User/.claude/get-shit-done/workflows/execute-plan.md
@C:/Users/User/.claude/get-shit-done/templates/summary.md
</execution_context>

<context>
@D:/Claude/projects/LLM-Resayil/.planning/STATE.md
@D:/Claude/projects/LLM-Resayil/resources/lang/en/welcome.php
@D:/Claude/projects/LLM-Resayil/resources/lang/ar/welcome.php
</context>

<tasks>

<task type="auto">
  <name>Task 1: Update EN welcome.php — remove Kuwait/KNET from marketing prose</name>
  <files>resources/lang/en/welcome.php</files>
  <action>
Edit the following keys in `resources/lang/en/welcome.php`. PRESERVE all other keys exactly as-is. PRESERVE all KWD pricing keys (five_kwd, ten_kwd, twenty_five_kwd, 15_kwd_month) unchanged.

Keys to change (line numbers approximate — verify by key name):

Line 4 — `title`:
  FROM: 'LLM Resayil - OpenAI-Compatible LLM API'
  TO:   'LLM Resayil — OpenAI-Compatible LLM API Gateway'

Line 5 — `subtitle`:
  FROM: 'OpenAI-Compatible LLM API Gateway - Pay Per Use in Kuwaiti Dinar'
  TO:   'OpenAI-Compatible LLM API Gateway — Pay Per Use, Credit-Based'

Line 7 — `hero_subtitle` (the FIRST occurrence, in the top block):
  FROM: 'Access state-of-the-art language models via an OpenAI-compatible API. Credit-based billing, automatic cloud failover, and enterprise team management — all in Kuwait Dinar.'
  TO:   'Access 45+ state-of-the-art language models via a single OpenAI-compatible API. Credit-based billing — pay only for what you use, no subscriptions, no commitments.'

Line 17 — `register_top_up_desc`:
  FROM: 'Create an account, choose a subscription tier, and top up with credits via KNET or credit card.'
  TO:   'Create an account, choose a subscription tier, and top up credits via secure online payment.'

Line 24 — `all_prices_kwd`:
  FROM: 'All prices in Kuwaiti Dinar. Billed monthly. No hidden fees.'
  TO:   'Simple credit-based pricing. Billed monthly. No hidden fees.'

Line 80 — `join_developers` (FIRST occurrence):
  FROM: 'Join developers already using LLM Resayil. Pay with KNET or credit card.'
  TO:   'Join developers building with LLM Resayil. Sign up free and start calling models in minutes.'

Line 123 — `step_1_desc`:
  FROM: 'Create an account, choose a subscription tier, and top up with credits via KNET or credit card.'
  TO:   'Create an account, choose a plan, and top up credits using your preferred payment method.'

Line 124 — `step_1_description`:
  FROM: 'Create an account, choose a subscription tier, and top up with credits via KNET or credit card.'
  TO:   'Create an account, choose a plan, and top up credits using your preferred payment method.'

Line 142 — `card_required_trial`:
  FROM: 'Card required for trial. Cancel anytime. Payments processed securely via KNET / credit card.'
  TO:   'Card required for trial. Cancel anytime. Payments processed securely via credit card.'

Line 184 — `payments_secure`:
  FROM: 'Payments processed securely via KNET / credit card'
  TO:   'Payments processed securely via credit card'

Line 241 — `hero_headline_before`:
  FROM: "Kuwait's Premium"
  TO:   "The Developer-First"

Line 244 — `hero_subtitle` (SECOND occurrence, near bottom):
  FROM: 'Access 45+ state-of-the-art language models via an OpenAI-compatible API. Credit-based billing, no subscriptions, priced in Kuwaiti Dinar.'
  TO:   'Access 45+ state-of-the-art language models via a single OpenAI-compatible API. Credit-based billing — pay only for what you use, no subscriptions, no commitments.'

Line 248 — `trust_kuwait_based`:
  FROM: 'Kuwait-Based'
  TO:   'High-Performance Inference'

Line 259 — `pricing_subtitle`:
  FROM: 'All prices in Kuwaiti Dinar. Billed monthly. No hidden fees.'
  TO:   'Simple credit-based pricing. No hidden fees. Cancel anytime.'

Line 277 — `join_developers` (SECOND / duplicate occurrence at bottom):
  FROM: 'Join developers already using LLM Resayil. Pay with KNET or credit card.'
  TO:   'Join developers building with LLM Resayil. Sign up free and start calling models in minutes.'

DO NOT change: five_kwd, ten_kwd, twenty_five_kwd, 15_kwd_month, or any other key not listed above.
  </action>
  <verify>
    <automated>grep -n "Kuwait\|KNET\|Kuwaiti Dinar" D:/Claude/projects/LLM-Resayil/resources/lang/en/welcome.php | grep -v "five_kwd\|ten_kwd\|twenty_five_kwd\|15_kwd_month\|kwd_month" || echo "CLEAN — no Kuwait/KNET prose found"</automated>
  </verify>
  <done>EN file has zero occurrences of "Kuwait", "Kuwaiti", or "KNET" in marketing prose. KWD pricing rows unchanged.</done>
</task>

<task type="auto">
  <name>Task 2: Update AR welcome.php — remove Kuwait/KNET from marketing prose</name>
  <files>resources/lang/ar/welcome.php</files>
  <action>
Edit the following keys in `resources/lang/ar/welcome.php`. PRESERVE all other keys exactly as-is. PRESERVE all KWD pricing keys (five_kwd → '5 دينار كويتي', ten_kwd → '10 دينار كويتي', twenty_five_kwd → '25 دينار كويتي', 15_kwd_month → '15 دينار كويتي / شهر') unchanged.

Keys to change:

Line 5 — `subtitle`:
  FROM: 'واجهة برمجة تطبيقات LLM متوافقة مع OpenAI - دفع حسب الاستخدام بالدينار الكويتي'
  TO:   'واجهة برمجة تطبيقات LLM متوافقة مع OpenAI — دفع حسب الاستخدام بنظام الرصيد'

Line 7 — `hero_subtitle` (FIRST occurrence):
  FROM: 'وصول إلى نماذج اللغة المتقدمة عبر واجهة برمجة تطبيقات متوافقة مع OpenAI. دفع حسب الرصيد، وتعطيل تلقائي للسحابة، وإدارة فريق المؤسسة — كل ذلك بالدينار الكويتي.'
  TO:   'وصول إلى أكثر من 45 نموذجًا لغويًا متطورًا عبر واجهة برمجية موحدة متوافقة مع OpenAI. فوترة بالرصيد — ادفع فقط مقابل ما تستخدمه، بدون اشتراكات أو التزامات.'

Line 17 — `register_top_up_desc`:
  FROM: 'أنشئ حسابًا واختر خطة اشتراك وأعد شحن الرصيد عبر KNET أو بطاقة الائتمان.'
  TO:   'أنشئ حسابًا واختر خطة اشتراك وأعد شحن الرصيد عبر الدفع الإلكتروني الآمن.'

Line 24 — `all_prices_kwd`:
  FROM: 'جميع الأسعار بالدينار الكويتي. الفاتورة شهرية. بدون رسوم خفية.'
  TO:   'أسعار شفافة بنظام الرصيد. الفاتورة شهرية. بدون رسوم خفية.'

Line 80 — `join_developers` (FIRST occurrence):
  FROM: 'انضم إلى المطورين الذين يستخدمون LLM Resayil بالفعل. ادفع عبر KNET أو بطاقة ائتمان.'
  TO:   'انضم إلى المطورين الذين يبنون مع LLM Resayil. سجّل مجانًا وابدأ استدعاء النماذج في دقائق.'

Line 123 — `step_1_desc`:
  FROM: 'أنشئ حسابًا واختر خطة اشتراك وأعد شحن الرصيد عبر KNET أو بطاقة الائتمان.'
  TO:   'أنشئ حسابًا واختر خطة وأعد شحن الرصيد باستخدام طريقة الدفع المفضلة لديك.'

Line 124 — `step_1_description`:
  FROM: 'أنشئ حسابًا واختر خطة اشتراك وأعد شحن الرصيد عبر KNET أو بطاقة الائتمان.'
  TO:   'أنشئ حسابًا واختر خطة وأعد شحن الرصيد باستخدام طريقة الدفع المفضلة لديك.'

Line 142 — `card_required_trial`:
  FROM: 'بطاقة مطلوبة للتجربة. إلغاء في أي وقت. المدفوعات مؤمنة عبر KNET أو بطاقة الائتمان.'
  TO:   'بطاقة مطلوبة للتجربة. إلغاء في أي وقت. المدفوعات مؤمنة عبر بطاقة الائتمان.'

Line 184 — `payments_secure`:
  FROM: 'المدفوعات مؤمنة عبر KNET / بطاقة الائتمان'
  TO:   'المدفوعات مؤمنة عبر بطاقة الائتمان'

Lines 241-243 — `hero_headline_before` / `hero_headline_gold` / `hero_headline_after`:
  The current AR headline reads "منصة / API للنماذج اللغوية / المميزة في الكويت" (Kuwait-specific).
  Update to mirror the EN "The Developer-First / LLM API / Platform" structure:
  'hero_headline_before' => 'المنصة الأولى',
  'hero_headline_gold' => 'API للمطورين',
  'hero_headline_after' => 'عالي الأداء',

Line 244 — `hero_subtitle` (SECOND occurrence):
  FROM: 'وصول إلى أكثر من 45 نموذجًا لغويًا متطورًا عبر واجهة برمجية متوافقة مع OpenAI. فوترة بالرصيد بدون اشتراكات، بالدينار الكويتي.'
  TO:   'وصول إلى أكثر من 45 نموذجًا لغويًا متطورًا عبر واجهة برمجية موحدة متوافقة مع OpenAI. فوترة بالرصيد — ادفع فقط مقابل ما تستخدمه، بدون اشتراكات أو التزامات.'

Line 248 — `trust_kuwait_based`:
  FROM: 'مقره الكويت'
  TO:   'استدلال عالي الأداء'

Line 259 — `pricing_subtitle`:
  FROM: 'جميع الأسعار بالدينار الكويتي. الفاتورة شهرية. بدون رسوم خفية.'
  TO:   'أسعار شفافة بنظام الرصيد. بدون رسوم خفية. إلغاء في أي وقت.'

Line 278 (last `join_developers` — check for it near end of file):
  No separate duplicate `join_developers` key exists at bottom of AR file. Skip if not present.

DO NOT change: five_kwd, ten_kwd, twenty_five_kwd, 15_kwd_month keys.
  </action>
  <verify>
    <automated>grep -n "كويتي\|KNET\|الكويت\|Kuwait" D:/Claude/projects/LLM-Resayil/resources/lang/ar/welcome.php | grep -v "five_kwd\|ten_kwd\|twenty_five_kwd\|15_kwd_month\|kwd_month" || echo "CLEAN — no Kuwait/KNET prose found"</automated>
  </verify>
  <done>AR file has zero occurrences of "كويت", "كويتي", "KNET" in marketing prose. KWD pricing rows preserved. Arabic headline no longer references Kuwait.</done>
</task>

</tasks>

<verification>
After both tasks complete, run:

1. Check EN for any remaining Kuwait/KNET prose:
   grep -n "Kuwait\|KNET\|Kuwaiti" D:/Claude/projects/LLM-Resayil/resources/lang/en/welcome.php

2. Check AR for any remaining Kuwait/KNET prose:
   grep -n "كويتي\|KNET\|الكويت" D:/Claude/projects/LLM-Resayil/resources/lang/ar/welcome.php

3. Verify KWD pricing amounts are intact in both files:
   grep -n "five_kwd\|ten_kwd\|twenty_five_kwd\|15_kwd_month" D:/Claude/projects/LLM-Resayil/resources/lang/en/welcome.php D:/Claude/projects/LLM-Resayil/resources/lang/ar/welcome.php

Expected EN hero_headline_before: "The Developer-First"
Expected EN trust_kuwait_based: "High-Performance Inference"
Expected AR trust_kuwait_based: "استدلال عالي الأداء"
</verification>

<success_criteria>
- `grep "Kuwait\|KNET\|Kuwaiti" resources/lang/en/welcome.php` returns 0 marketing prose hits (only KWD pricing keys if any)
- `grep "كويتي\|KNET\|الكويت" resources/lang/ar/welcome.php` returns 0 marketing prose hits (only KWD pricing keys if any)
- KWD amounts (five_kwd, ten_kwd, twenty_five_kwd, 15_kwd_month) unchanged in both files
- EN `hero_headline_before` = "The Developer-First"
- EN `trust_kuwait_based` = "High-Performance Inference"
- AR `trust_kuwait_based` = "استدلال عالي الأداء"
- AR `hero_headline_after` no longer contains "الكويت"
</success_criteria>

<output>
After completion, create `.planning/quick/4-remove-kuwait-kwd-knet-references-from-w/4-SUMMARY.md` with:
- What was changed in each file
- Grep verification output confirming no Kuwait/KNET prose remains
- KWD pricing keys confirmed intact
</output>
