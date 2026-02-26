<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'code' => 'welcome',
                'name' => 'Welcome Message',
                'arabic_content' => 'مرحباً بك في منصة Resayil! لقد تم تفعيل حسابك بنجاح. لديك 0 رصيد. استخدم رموز API للوصول.',
                'english_content' => 'Welcome to Resayil! Your account has been activated. You have 0 credits. Use API keys to access.',
                'default_language' => 'ar',
                'is_active' => true,
            ],
            [
                'code' => 'subscription_confirmed',
                'name' => 'Subscription Confirmation',
                'arabic_content' => 'تم تأكيد اشتراكك بنجاح! لقد تم تفعيل خطة {plan_name} لك. ستنتهي في {expiry_date}. لديك {credits} رصيد.',
                'english_content' => 'Your subscription has been confirmed! Your {plan_name} plan has been activated. It expires on {expiry_date}. You have {credits} credits.',
                'default_language' => 'ar',
                'is_active' => true,
            ],
            [
                'code' => 'credit_warning',
                'name' => 'Credit Warning',
                'arabic_content' => 'تحذير: لديك {remaining}% من الرصيد المتبقي. إعادة شحن الآن لتجنب انقطاع الخدمة.',
                'english_content' => 'Warning: You have {remaining}% of credits remaining. Top up now to avoid service interruption.',
                'default_language' => 'ar',
                'is_active' => true,
            ],
            [
                'code' => 'credits_exhausted',
                'name' => 'Credits Exhausted',
                'arabic_content' => 'انتهى رصيدك بالكامل! قم بإعادة الشحن الآن لاستخدام الخدمات. رابط إعادة الشحن: https://resayil.io/topup',
                'english_content' => 'Your credits have been exhausted! Top up now to continue using services. Top-up link: https://resayil.io/topup',
                'default_language' => 'ar',
                'is_active' => true,
            ],
            [
                'code' => 'topup_confirmed',
                'name' => 'Top-up Confirmation',
                'arabic_content' => 'تمت إعادة الشحن بنجاح! تم إضافة {amount} رصيد إلى حسابك. الرصيد الحالي: {current_credits}.',
                'english_content' => 'Top-up successful! {amount} credits have been added to your account. Current balance: {current_credits}.',
                'default_language' => 'ar',
                'is_active' => true,
            ],
            [
                'code' => 'renewal_reminder',
                'name' => 'Renewal Reminder',
                'arabic_content' => 'تذكير: انتهاء اشتراكك في 3 أيام. قم بالتجديد الآن لاستمرار الخدمة.',
                'english_content' => 'Reminder: Your subscription expires in 3 days. Renew now to continue service.',
                'default_language' => 'ar',
                'is_active' => true,
            ],
            [
                'code' => 'cloud_budget_80',
                'name' => 'Cloud Budget Alert 80%',
                'arabic_content' => 'تحذير: استخدامك ل云 الموارد وصل إلى 80% من الحد الأقصى. الحد الحالي: {limit}، المستخدم: {used}.',
                'english_content' => 'Alert: Cloud resource usage has reached 80% of your limit. Current limit: {limit}, Used: {used}.',
                'default_language' => 'en',
                'is_active' => true,
            ],
            [
                'code' => 'cloud_budget_100',
                'name' => 'Cloud Budget Limit Reached',
                'arabic_content' => 'تحذير حرج: استخدامك ل云 الموارد وصل إلى 100% من الحد الأقصى. تم تعطيل سحابةResayil مؤقتاً.',
                'english_content' => 'Critical Alert: Cloud resource usage has reached 100% of your limit. Resayil Cloud has been temporarily disabled.',
                'default_language' => 'en',
                'is_active' => true,
            ],
            [
                'code' => 'ip_banned',
                'name' => 'IP Banned Alert',
                'arabic_content' => 'تنبيه أمني: تم حظر عنوان IP {ip_address} بواسطة fail2ban. السبب: {reason}.',
                'english_content' => 'Security Alert: IP address {ip_address} has been banned by fail2ban. Reason: {reason}.',
                'default_language' => 'en',
                'is_active' => true,
            ],
            [
                'code' => 'new_enterprise',
                'name' => 'New Enterprise Customer',
                'arabic_content' => 'تنبيه جديد: تم تسجيل عميلEnterprise جديد. الاسم: {customer_name}، البريد الإلكتروني: {email}، عدد الرموز المميزة: {api_keys_count}.',
                'english_content' => 'New Alert: New Enterprise customer registered. Name: {customer_name}, Email: {email}, API Keys: {api_keys_count}.',
                'default_language' => 'en',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::firstOrCreate(
                ['code' => $template['code']],
                array_diff_key($template, ['code' => true])
            );
        }
    }
}
