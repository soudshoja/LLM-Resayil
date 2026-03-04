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

    // Dashboard - stats cards
    'total_users' => 'إجمالي المستخدمين',
    'active_subscriptions' => 'الاشتراكات النشطة',
    'total_api_calls' => 'إجمالي طلبات API',
    'cloud_budget_used' => 'ميزانية السحابة المستخدمة',

    // Dashboard - users table
    'all_users' => 'جميع المستخدمين',
    'name_email' => 'الاسم / البريد الإلكتروني',
    'phone' => 'الهاتف',
    'plan' => 'الخطة',
    'joined' => 'تاريخ الانضمام',
    'actions' => 'الإجراءات',

    // Dashboard - action buttons
    'credits_action' => 'الرصيد',
    'tier_action' => 'الخطة',
    'expiry_action' => 'الانتهاء',
    'api_key_action' => 'مفتاح API',

    // Dashboard - credits modal
    'set_credits' => 'تعيين الرصيد',
    'user_id' => 'معرف المستخدم',
    'enter_credits' => 'أدخل عدد النقاط',
    'cancel' => 'إلغاء',
    'save' => 'حفظ',

    // Dashboard - tier modal
    'set_subscription_tier' => 'تعيين مستوى الاشتراك',
    'tier' => 'المستوى',
    'starter' => 'مبتدئ',
    'basic' => 'أساسي',
    'pro' => 'محترف',
    'enterprise' => 'مؤسسي',

    // Dashboard - expiry modal
    'set_subscription_expiry' => 'تعيين تاريخ انتهاء الاشتراك',
    'expiry_date' => 'تاريخ الانتهاء',
    'clear_expiry' => 'إلغاء تاريخ الانتهاء',

    // Dashboard - API key modal
    'create_api_key' => 'إنشاء مفتاح API',
    'key_name' => 'اسم المفتاح',
    'enter_key_name' => 'أدخل اسم المفتاح',
    'admin_created_key' => 'مفتاح منشأ من الإدارة',
    'api_key_created' => 'تم إنشاء مفتاح API',
    'copy_to_clipboard' => 'نسخ إلى الحافظة',
    'close' => 'إغلاق',
    'create_key' => 'إنشاء المفتاح',

    // Dashboard - JS alerts
    'invalid_credits' => 'يرجى إدخال رصيد صحيح',
    'credits_error' => 'فشل تحديث الرصيد',
    'tier_error' => 'فشل تحديث مستوى الاشتراك',
    'expiry_error' => 'فشل تحديث تاريخ الانتهاء',
    'key_error' => 'فشل إنشاء مفتاح API',
    'key_copied' => 'تم نسخ المفتاح',

    // Models page - headers & stats
    'model_management' => 'إدارة النماذج',
    'configure_models' => 'تكوين النماذج المتاحة',
    'total_models' => 'إجمالي النماذج',
    'active_models' => 'النماذج النشطة',
    'local_models' => 'النماذج المحلية',
    'cloud_models' => 'نماذج السحابة',

    // Models page - filter labels
    'family' => 'العائلة',
    'category' => 'الفئة',
    'type' => 'النوع',
    'size' => 'الحجم',
    'all' => 'الكل',
    'chat' => 'محادثة',
    'code' => 'برمجة',
    'embedding' => 'تضمين',
    'vision' => 'رؤية',
    'thinking' => 'تفكير',
    'tools' => 'أدوات',
    'local' => 'محلي',
    'cloud' => 'سحابي',
    'small' => 'صغير',
    'medium' => 'متوسط',
    'large' => 'كبير',
    'search_models' => 'ابحث عن النماذج...',

    // Models page - bulk actions
    'enable' => 'تفعيل',
    'disable' => 'تعطيل',
    'bulk_update' => 'تحديث جماعي',

    // Models page - table headers
    'model_id' => 'معرف النموذج',
    'multiplier' => 'المضاعف',
    'status' => 'الحالة',
    'override_label' => 'تجاوز',
    'edit' => 'تعديل',
    'models' => 'نماذج',

    // Models page - edit modal
    'edit_model_settings' => 'تعديل إعدادات النموذج',
    'credit_multiplier_override' => 'تجاوز مضاعف الرصيد',
    'leave_empty_default' => 'اتركه فارغًا للقيمة الافتراضية',
    'use_default_from_config' => 'استخدام الإعداد الافتراضي',
    'active' => 'نشط',
    'inactive' => 'غير نشط',
    'save_changes' => 'حفظ التغييرات',

    // Models page - JS toast & strings
    'model_enabled' => 'تم تفعيل النموذج',
    'model_disabled' => 'تم تعطيل النموذج',
    'update_failed' => 'فشل التحديث',
    'select_models_update' => 'يرجى اختيار نماذج للتحديث',
    'enable_models' => 'تفعيل النماذج',
    'showing' => 'عرض',
    'of' => 'من',
    'saving' => 'جاري الحفظ...',
    'model_settings_saved' => 'تم حفظ إعدادات النموذج',
    'failed_save' => 'فشل الحفظ',
];
