<?php

return [
    // Existing keys (docs index page)
    'documentation' => 'الوثائق',
    'everything_need_to_know' => 'كل ما تحتاج معرفته عن استخدام LLM Resayil API، التسعير، الفواتير والميزات.',
    'go_to_dashboard' => 'الذهاب إلى لوحة التحكم',
    'billing_subscription_plans' => 'الفواتير وخطط الاشتراك',
    'complete_guide_subscription' => 'دليل شامل لمستويات الاشتراك (Starter, Basic, Pro)، إضافة الرموز، فترة التجربة، والدفع التلقائي مع بوابة MyFatoorah.',
    'model_catalog_admin_panel' => 'كتالوج النماذج ولوحة التحكم',
    'access_llm_models' => 'الوصول إلى 45+ نموذج LLM (محلي وسحابي)، بيانات تعريف النموذج التفصيلية، خيارات البحث والتصفية، وإدارة النماذج للمسؤول.',
    'recurring_payments_whatsapp' => 'المدفوعات المتكررة وجدول واتساب',
    'myfatoorah_payment_gateway' => 'إعداد بوابة الدفع المتكرر MyFatoorah، جدول إشعارات واتساب (اليوم 1، اليوم 6، اليوم 7)، والتجربة الآلية.',
    'need_more_help' => 'تحتاج إلى مساعدة أكثر؟',
    'check_api_reference' => 'تحقق من مرجع API لدينا والأسئلة الشائعة.',
    'home' => 'الرئيسية',
    'api' => 'API',
    'read_more' => 'اقرأ أكثر',

    // Page header & navigation
    'title' => 'وثائق API',
    'subtitle' => 'بوابة API متوافقة مع OpenAI للنماذج اللغوية الكبيرة',
    'back_to_home' => 'العودة إلى الرئيسية',

    // Sidebar / section navigation
    'overview' => 'نظرة عامة',
    'quick_start' => 'البدء السريع',
    'authentication' => 'المصادقة',
    'error_handling' => 'معالجة الأخطاء',
    'models_endpoint' => 'نقطة نهاية /models',
    'chat_completions' => 'إتمام المحادثة',
    'usage_examples' => 'أمثلة الاستخدام',
    'python_sdk' => 'حزمة Python SDK',
    'node_sdk' => 'حزمة Node.js SDK',
    'curl_examples' => 'أمثلة cURL',
    'response_format' => 'صيغة الاستجابة',
    'streaming' => 'البث المباشر',
    'rate_limits' => 'حدود الاستخدام',
    'billing' => 'الفواتير',
    'error_codes' => 'رموز الأخطاء',
    'api_reference' => 'مرجع API',
    'faq' => 'الأسئلة الشائعة',
    'code_examples' => 'أمثلة الكود',
    'available_models' => 'النماذج المتاحة',
    'reference' => 'المرجع',

    // Common table headers & labels
    'endpoint' => 'نقطة النهاية',
    'method' => 'الطريقة',
    'description' => 'الوصف',
    'parameters' => 'المعاملات',
    'parameter' => 'المعامل',
    'required' => 'مطلوب',
    'optional' => 'اختياري',
    'type' => 'النوع',
    'default' => 'الافتراضي',
    'example_request' => 'مثال على الطلب',
    'example_response' => 'مثال على الاستجابة',

    // Authentication section
    'auth_header' => 'ترويسة التفويض',
    'auth_description' => 'تتطلب جميع طلبات API مصادقة. قم بتضمين مفتاح API الخاص بك في ترويسة Authorization.',
    'api_key' => 'مفتاح API',
    'bearer_token' => 'رمز Bearer',
    'how_authenticate' => 'كيف أصادق طلبات API؟',
    'header_format' => 'صيغة الترويسة',
    'getting_api_key' => 'الحصول على مفتاح API الخاص بك',
    'key_limits' => 'حدود المفاتيح حسب الخطة',
    'security_best_practices' => 'أفضل ممارسات الأمان',
    'authentication_error' => 'خطأ في المصادقة:',
    'missing_invalid_api_key' => 'مفتاح API مفقود أو غير صالح يُرجع',
    'unauthorized' => 'غير مصرح',
    'max_api_keys' => 'الحد الأقصى لمفاتيح API',
    'never_commit_api_keys' => 'لا تقم أبداً بحفظ مفاتيح API في نظام التحكم بالإصدارات — استخدم متغيرات البيئة',
    'rotate_keys' => 'قم بتدوير المفاتيح بانتظام',
    'use_separate_keys' => 'استخدم مفاتيح منفصلة لكل تطبيق أو بيئة',
    'revoke_keys' => 'قم بإلغاء المفاتيح فوراً في حال اختراقها',
    'omit_keys' => 'لا تضمّن المفاتيح في رسائل الخطأ والسجلات',

    // Subscription plan names
    'starter' => 'Starter',
    'basic' => 'Basic',
    'pro' => 'Pro',
    'enterprise' => 'Enterprise',
    'unlimited' => 'غير محدود',

    // Error types
    'invalid_request' => 'طلب غير صالح',
    'rate_limit_exceeded' => 'تم تجاوز حد الاستخدام',
    'server_error' => 'خطأ في الخادم',

    // Models endpoint
    'models_list' => 'عرض النماذج المتاحة',
    'models_description' => 'استرجاع قائمة بجميع النماذج المتاحة. تتضمن الاستجابة معرّفات النماذج وأسمائها وبياناتها الوصفية.',
    'models_desc' => 'عرض جميع النماذج المتاحة',

    // Chat completions endpoint
    'chat_description' => 'إنشاء طلب إتمام محادثة. هذه هي نقطة النهاية الرئيسية للتفاعل مع النماذج اللغوية الكبيرة.',
    'chat_completions_desc' => 'إتمام المحادثة',
    'chat_completion_request' => 'كيف أرسل طلب إتمام محادثة؟',
    'base_url' => 'عنوان URL الأساسي لجميع طلبات API هو:',
    'post_chat_completions' => 'POST /api/v1/chat/completions',
    'send_messages_model' => 'أرسل رسائل إلى نموذج واحصل على استجابة إتمام. يدعم البث المباشر عبر المعامل :code.',
    'request_parameters' => 'معاملات الطلب',

    // Parameter names (kept as-is since they are code identifiers)
    'model_param' => 'model',
    'messages_param' => 'messages',
    'stream_param' => 'stream',
    'temperature_param' => 'temperature',
    'max_tokens_param' => 'max_tokens',

    // Parameter descriptions
    'model_description' => 'معرّف النموذج المراد استخدامه لإتمام المحادثة.',
    'model_id' => 'معرّف النموذج من',
    'messages_description' => 'مصفوفة من كائنات الرسائل تمثل سجل المحادثة.',
    'array_message_objects' => 'مصفوفة من كائنات الرسائل',
    'temperature_description' => 'يتحكم في العشوائية في المخرجات. القيم الأعلى تجعل المخرجات أكثر عشوائية.',
    'sampling_temperature' => 'درجة حرارة أخذ العينات',
    'max_tokens_description' => 'الحد الأقصى لعدد الرموز المُولَّدة في الإتمام.',
    'maximum_tokens' => 'الحد الأقصى للرموز المُولَّدة',
    'stream_description' => 'إذا كانت القيمة true، يتم إرسال الاستجابات كأحداث مُرسلة من الخادم.',
    'boolean' => 'boolean',
    'default_false' => 'الافتراضي :code. اضبط على :code لتفعيل بث أحداث الخادم (Server-Sent Events)',

    // Response & usage
    'usage' => 'الاستخدام',
    'usage_info' => 'معلومات الاستخدام',
    'prompt_tokens' => 'رموز المُدخل',
    'completion_tokens' => 'رموز الإتمام',
    'total_tokens' => 'إجمالي الرموز',
    'cost_estimate' => 'تقدير التكلفة',

    // Billing & credits
    'billing_credits' => 'الفواتير والأرصدة',
    'rate_limits_by_plan' => 'حدود الاستخدام حسب الخطة',
    'free' => 'مجاني',
    'standard' => 'قياسي',
    'premium' => 'متميز',
    'tokens_cost' => 'رموز المُدخل مجانية؛ يتم احتساب رموز المُخرج فقط.',
    'credits_topup' => 'إعادة الشحن: :credits رصيد مقابل :price د.ك',

    // Error codes table
    'error_code' => 'رمز الخطأ',
    'error_401' => 'مفتاح API غير صالح أو مفقود',
    'error_402' => 'رصيد غير كافٍ',
    'error_429' => 'تم تجاوز حد الاستخدام',
    'error_503' => 'النموذج غير متاح مؤقتاً',

    // Error detail types
    'model_not_found' => 'النموذج غير موجود',
    'invalid_api_key' => 'مفتاح API غير صالح',
    'insufficient_credits' => 'رصيد غير كافٍ',

    // Getting started / setup
    'getting_started' => 'البدء',
    'getting_started_desc' => 'LLM Resayil هو API متوافق مع OpenAI يتيح لك الوصول إلى أكثر من 45 نموذجاً مفتوح المصدر وسحابياً مع فواتير مرنة. أي حزمة OpenAI SDK تعمل بسلاسة عن طريق تغيير عنوان URL الأساسي.',
    'setup_steps' => 'الإعداد في :count خطوات',
    'register' => 'التسجيل',
    'subscribe' => 'الاشتراك',
    'create_api_key' => 'إنشاء مفتاح API',
    'make_first_call' => 'إجراء أول استدعاء',
    'track_usage' => 'تتبع الاستخدام',

    // Support
    'contact_support' => 'تواصل مع الدعم',
    'need_help' => 'تحتاج مساعدة؟',
    'last_updated' => '2 مارس 2026',
];
