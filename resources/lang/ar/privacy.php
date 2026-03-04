<?php

return [
    // Page
    'page_title' => 'سياسة الخصوصية',
    'eyebrow' => 'قانوني',
    'last_updated_label' => 'آخر تحديث:',
    'last_updated_date' => 'مارس 2026',
    'read_time' => '~6 دقائق قراءة',
    'brand_name' => 'LLM Resayil',
    'version_label' => 'الإصدار',
    'version_number' => '1.0',

    // Sidebar TOC
    'toc_header' => 'المحتويات',
    'toc_introduction' => '1. المقدمة',
    'toc_data_collected' => '2. البيانات التي نجمعها',
    'toc_how_used' => '3. كيف نستخدم البيانات',
    'toc_api_content' => '4. محتوى طلبات API',
    'toc_third_party' => '5. خدمات الأطراف الثالثة',
    'toc_retention' => '6. الاحتفاظ بالبيانات',
    'toc_security' => '7. الأمان',
    'toc_rights' => '8. حقوقك',
    'toc_updates' => '9. تحديثات السياسة',
    'toc_contact' => '10. اتصل بنا',

    // Section 01 - Introduction
    'section_introduction' => 'المقدمة',
    'introduction_text' => 'تلتزم LLM Resayil ("نحن"، "لنا"، "خاصتنا") بحماية خصوصيتك. توضح هذه السياسة البيانات التي نجمعها، وكيف نستخدمها، وحقوقك المتعلقة بتلك البيانات عند استخدامك لواجهة API وبوابة الويب على llm.resayil.io.',

    // Section 02 - Data We Collect
    'section_data_collected' => 'البيانات التي نجمعها',
    'table_category' => 'الفئة',
    'table_data' => 'البيانات',
    'table_purpose' => 'الغرض',
    'category_account' => 'الحساب',
    'data_account' => 'الاسم، البريد الإلكتروني، رقم الهاتف',
    'purpose_account' => 'التسجيل، التحقق بـ OTP، الدعم',
    'category_usage_logs' => 'سجلات الاستخدام',
    'data_usage_logs' => 'اسم النموذج، عدد التوكنات، الأرصدة المخصومة، الطابع الزمني',
    'purpose_usage_logs' => 'الفوترة، لوحة التحكم، تحديد المعدل',
    'category_payment' => 'الدفع',
    'data_payment' => 'معرّف المعاملة، حالة الدفع (من MyFatoorah)',
    'purpose_payment' => 'تأكيد شحن الأرصدة',
    'category_session' => 'الجلسة',
    'data_session' => 'ملفات تعريف الارتباط، تفضيل اللغة',
    'purpose_session' => 'المصادقة، لغة الواجهة',
    'no_tracking' => 'نحن <strong style="color:var(--legal-text)">لا</strong> نجمع بيانات إعلانية أو بصمات المتصفح أو معرّفات تتبع الأطراف الثالثة.',

    // Section 03 - How We Use Your Data
    'section_how_used' => 'كيف نستخدم بياناتك',
    'use_authenticate' => 'مصادقة حسابك والتحقق من رقم هاتفك عبر WhatsApp OTP',
    'use_payments' => 'معالجة المدفوعات وشحن الأرصدة عبر MyFatoorah / KNET',
    'use_track_api' => 'تتبع استخدام API للفوترة وتحديد المعدل وعرض لوحة التحكم',
    'use_whatsapp' => 'إرسال رسائل WhatsApp للمعاملات (رموز OTP، تأكيدات الدفع)',
    'use_support' => 'الرد على استفسارات الدعم المقدمة عبر نموذج الاتصال',
    'no_sell_data' => '<strong>نحن لا نبيع بياناتك.</strong> لا نستخدم بياناتك للإعلان أو التنميط أو أي غرض يتجاوز تشغيل الخدمة.',

    // Section 04 - API Request Content
    'section_api_content' => 'محتوى طلبات API',
    'api_content_text_1' => 'نحن <strong style="color:var(--legal-text)">لا</strong> نخزّن محتوى طلبات API (المطالبات) أو ردود النماذج. تسجّل سجلات الاستخدام البيانات الوصفية فقط — اسم النموذج، عدد التوكنات، الأرصدة المخصومة، والطابع الزمني.',
    'api_content_text_2' => 'يتم إعادة توجيه بيانات المطالبة مباشرة إلى خادم الاستدلال ولا يتم حفظها في أي قاعدة بيانات أو ملف سجل.',

    // Section 05 - Third-Party Services
    'section_third_party' => 'خدمات الأطراف الثالثة',
    'tp_myfatoorah_name' => 'MyFatoorah',
    'tp_myfatoorah_desc' => 'معالجة الدفع عبر KNET وبطاقة الائتمان. يتم التعامل مع تفاصيل البطاقة بالكامل بواسطة MyFatoorah — نحن لا نستلمها أو نخزّنها أبداً. تخضع لـ <a href="https://www.myfatoorah.com/privacy-policy" target="_blank" style="color:var(--legal-gold)">سياسة خصوصية MyFatoorah</a>.',
    'tp_resayil_name' => 'Resayil WhatsApp API',
    'tp_resayil_desc' => 'يُستخدم لإرسال رموز OTP وإشعارات الحساب إلى رقم هاتفك المسجل. تتم مشاركة أرقام الهواتف مع هذه الخدمة فقط للرسائل المعاملاتية.',
    'tp_google_fonts_name' => 'Google Fonts',
    'tp_google_fonts_desc' => 'يتم تحميل خطوط IBM Plex Sans و JetBrains Mono من Google Fonts CDN على بوابة الويب. تخضع لسياسة خصوصية Google.',

    // Section 06 - Data Retention
    'section_retention' => 'الاحتفاظ بالبيانات',
    'retention_account' => 'بيانات الحساب — يتم الاحتفاظ بها طالما حسابك نشط',
    'retention_usage' => 'سجلات الاستخدام — يتم الاحتفاظ بها لمدة تصل إلى 12 شهراً للفوترة والدعم',
    'retention_otp' => 'رموز OTP — تنتهي صلاحيتها بعد 10 دقائق ويتم حذفها بعد الاستخدام أو انتهاء الصلاحية',
    'retention_session' => 'بيانات الجلسة — تنتهي بعد ساعتين من عدم النشاط',
    'retention_deletion' => 'يمكنك طلب حذف حسابك والبيانات المرتبطة به عن طريق الاتصال بنا عبر نموذج الاتصال. سنقوم بمعالجة الطلبات خلال 14 يوم عمل.',

    // Section 07 - Security
    'section_security' => 'الأمان',
    'security_text' => 'يتم نقل جميع البيانات عبر HTTPS (TLS 1.2+). يتم تجزئة مفاتيح API باستخدام bcrypt ولا يتم تخزينها بنص عادي أبداً. يقتصر الوصول إلى قاعدة البيانات على عمليات الخادم المصرح بها فقط. نحن لا نخزّن بيانات اعتماد الدفع.',

    // Section 08 - Your Rights
    'section_rights' => 'حقوقك',
    'rights_text' => 'لديك الحق في الوصول إلى بياناتك الشخصية أو تصحيحها أو حذفها في أي وقت. لممارسة هذه الحقوق، استخدم <a href="/contact" style="color:var(--legal-gold)">نموذج الاتصال</a>. سنرد خلال 14 يوم عمل.',

    // Section 09 - Policy Updates
    'section_updates' => 'تحديثات السياسة',
    'updates_text' => 'قد نقوم بتحديث هذه السياسة بشكل دوري. سيعكس تاريخ "آخر تحديث" أعلاه التغييرات. يشكّل الاستمرار في استخدام الخدمة بعد التغييرات قبولاً للسياسة المحدثة. سيتم إبلاغ التغييرات الجوهرية عبر البريد الإلكتروني أو إشعار البوابة.',

    // Section 10 - Contact
    'section_contact' => 'اتصل بنا',
    'contact_text' => 'أسئلة الخصوصية أو طلبات البيانات: <a href="/contact" style="color:var(--legal-gold)">نموذج الاتصال</a>.',

    // Footer
    'footer_about' => 'حول',
    'footer_terms' => 'شروط الخدمة',
    'footer_contact' => 'اتصل بنا',
];
