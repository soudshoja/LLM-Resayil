<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Language Lines - Arabic
    |--------------------------------------------------------------------------
    */

    'brand' => 'LLM Resayil',

    // Sidebar navigation
    'admin_dashboard' => 'لوحة التحكم',
    'users' => 'المستخدمين',
    'api_settings' => 'إعدادات API',
    'logout' => 'تسجيل الخروج',

    // Page titles
    'api_settings_page_title' => 'إعدادات API',
    'configure_services' => 'تكوين بيانات اعتماد ونقاط نهاية الخدمات الخارجية',

    // User info
    'administrator' => 'مدير النظام',

    // Alerts
    'success' => 'نجاح',
    'error' => 'خطأ',

    // Section headers - keep technical terms in English
    'ollama' => 'Ollama',
    'ollama_description' => 'إعدادات خادم GPU المحلي و	failover السحابي',
    'myfatoorah' => 'MyFatoorah',
    'myfatoorah_description' => 'بوابة الدفع للتعاملات بالدينار الكويتي',
    'resayil_whatsapp' => 'Resayil WhatsApp',
    'whatsapp_description' => 'خدمة إشعارات WhatsApp للتنبيهات ثنائية اللغة',
    'redis' => 'Redis',
    'redis_description' => 'تخزين الذاكرة المؤقتة وتقييد معدل الطلبات',

    // Form labels - keep environment variable names in English
    'local_gpu_url' => 'عنوان URL لـ GPU المحلي',
    'local_gpu_url_env' => '(OLLAMA_GPU_URL)',
    'cloud_failover_url' => 'عنوان URL للـ Cloud Failover',
    'cloud_failover_url_env' => '(OLLAMA_CLOUD_URL)',
    'cloud_api_key' => 'مفتاح API السحابي',
    'cloud_api_key_env' => '(CLOUD_API_KEY)',
    'myfatoorah_api_key' => 'مفتاح API',
    'myfatoorah_api_key_env' => '(MYFATOORAH_API_KEY)',
    'myfatoorah_base_url' => 'عنوان URL الأساسي',
    'myfatoorah_base_url_env' => '(MYFATOORAH_BASE_URL)',
    'myfatoorah_callback_url' => 'عنوان URL للإعادة التوجيه',
    'myfatoorah_callback_url_env' => '(MYFATOORAH_CALLBACK_URL)',
    'whatsapp_api_url' => 'عنوان URL لـ API',
    'whatsapp_api_url_env' => '(WHATSAPP_API_URL)',
    'admin_phone' => 'هاتف المدير',
    'admin_phone_env' => '(ADMIN_PHONE)',
    'whatsapp_api_key' => 'مفتاح API',
    'whatsapp_api_key_env' => '(WHATSAPP_API_KEY)',
    'redis_host' => 'المضيف',
    'redis_host_env' => '(REDIS_HOST)',
    'redis_port' => 'المنفذ',
    'redis_port_env' => '(REDIS_PORT)',
    'redis_password' => 'كلمة المرور',
    'redis_password_env' => '(REDIS_PASSWORD)',

    // Button labels
    'save_all_settings' => 'حفظ جميع الإعدادات',

    // Placeholder text - keep URLs和技术术语 in English
    'gpu_url_placeholder' => 'http://208.110.93.90:11434',
    'cloud_url_placeholder' => 'https://ollama.com',
    'myfatoorah_base_url_placeholder' => 'https://ap-gateway.myfatoorah.com',
    'myfatoorah_callback_url_placeholder' => 'https://llm.resayil.io/webhooks/payment',
    'whatsapp_url_placeholder' => 'https://api.resayil.io/whatsapp',
    'admin_phone_placeholder' => '96550000000',
    'redis_host_placeholder' => '127.0.0.1',
    'redis_port_placeholder' => '6379',
    'redis_password_placeholder' => 'اتركها فارغة إذا لم تكن هناك',

    // Toast messages
    'settings_saved_successfully' => 'تم حفظ الإعدادات بنجاح',
    'settings_save_error' => 'خطأ في حفظ الإعدادات',

    // Monitoring page
    'monitoring' => 'مراقبة API',
    'platform_overview' => 'تحليلات استخدام المنصة بالكامل',
    'today_calls' => "مكالمات اليوم",
    'calls_7d' => 'المكالمات (7 أيام)',
    'calls_30d' => 'المكالمات (30 يوماً)',
    'tokens_30d' => 'الرموز (30 يوماً)',
    'credits_used_30d' => 'الرموز المستخدمة (30 يوماً)',
    'top_users' => 'أفضل المستخدمين (30 يوماً)',
    'top_models' => 'أفضل النماذج (30 يوماً)',
    'recent_api_calls' => 'استدعاءات API الحديثة',
    'user' => 'المستخدم',
    'model' => 'النموذج',
    'tokens' => 'الرموز',
    'credits' => 'الرموز',
    'time' => 'الوقت',
    'no_api_calls_yet' => 'لا توجد استدعاءات API بعد.',
];
