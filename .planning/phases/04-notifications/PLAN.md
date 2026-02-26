---
phase: 04-notifications
plan: 01
type: execute
wave: 1
depends_on:
  - 01-foundation-auth-01
files_modified:
  - database/migrations/2024_02_26_000004_create_notifications_table.php
  - database/migrations/2024_02_26_000005_create_notification_templates_table.php
  - app/Models/Notification.php
  - app/Models/NotificationTemplate.php
  - app/Services/WhatsAppNotificationService.php
  - app/Jobs/SendWhatsAppNotification.php
  - app/Listeners/WelcomeNotificationListener.php
  - app/Listeners/SubscriptionConfirmationListener.php
  - app/Listeners/CreditWarningListener.php
  - app/Listeners/CreditsExhaustedListener.php
  - app/Listeners/TopUpConfirmationListener.php
  - app/Listeners/RenewalReminderListener.php
  - app/Listeners/CloudBudgetAlertListener.php
  - app/Listeners/AdminAlertListener.php
  - app/Listeners/NewEnterpriseCustomerListener.php
  - app/Console/Commands/SendFailedNotifications.php
  - app/Console/Commands/SendScheduledNotifications.php
  - app/Console/Kernel.php
  - routes/web.php
  - routes/api.php
autonomous: true
requirements:
  - NOTIF-01
  - NOTIF-02
  - NOTIF-03
  - NOTIF-04
  - NOTIF-05
  - NOTIF-06
  - NOTIF-07
  - NOTIF-08
  - NOTIF-09
  - NOTIF-10
user_setup:
  - service: Resayil WhatsApp API
    why: "WhatsApp notification delivery"
    env_vars:
      - name: WHATSAPP_API_URL
        source: "Resayil WhatsApp dashboard -> API Settings"
      - name: WHATSAPP_API_KEY
        source: "Resayil WhatsApp dashboard -> API Keys"
    dashboard_config:
      - task: "Create WhatsApp API credentials"
        location: "Resayil WhatsApp dashboard -> API Settings"
        note: "Generate API key and endpoint URL for integration"
must_haves:
  truths:
    - "Welcome WhatsApp message sent in both Arabic and English upon registration"
    - "Subscription confirmation with plan details sent after successful payment"
    - "Credit warning notification sent when credits reach 20% remaining"
    - "Credits exhausted notification includes top-up link in both languages"
    - "Top-up purchase confirmation sent immediately after payment"
    - "Renewal reminder sent 3 days before subscription expiry"
    - "Cloud budget at 80% triggers admin alert"
    - "Cloud budget at 100% triggers admin alert and disables cloud"
    - "IP banned by fail2ban triggers admin alert"
    - "New Enterprise customer registration triggers admin alert"
  artifacts:
    - path: "database/migrations/2024_02_26_000004_create_notifications_table.php"
      provides: "Notifications queue table"
      contains: "create_notifications_table"
    - path: "database/migrations/2024_02_26_000005_create_notification_templates_table.php"
      provides: "Template storage for bilingual messages"
      contains: "create_notification_templates_table"
    - path: "app/Models/Notification.php"
      provides: "Notification model with queue status"
      exports: ["user", "template", "sent_at"]
    - path: "app/Models/NotificationTemplate.php"
      provides: "Template model with Arabic/English versions"
      exports: ["getTemplate"]
    - path: "app/Services/WhatsAppNotificationService.php"
      provides: "Resayil WhatsApp API integration service"
      exports: ["send", "getLanguage"]
    - path: "app/Jobs/SendWhatsAppNotification.php"
      provides: "Async notification sending job"
      exports: ["handle"]
    - path: "app/Listeners/WelcomeNotificationListener.php"
      provides: "Welcome notification on user registration"
      exports: ["handle"]
    - path: "app/Listeners/SubscriptionConfirmationListener.php"
      provides: "Subscription confirmation after payment"
      exports: ["handle"]
    - path: "app/Listeners/CreditWarningListener.php"
      provides: "20% credit warning notification"
      exports: ["handle"]
    - path: "app/Listeners/CreditsExhaustedListener.php"
      provides: "Credits exhausted notification with top-up link"
      exports: ["handle"]
    - path: "app/Listeners/TopUpConfirmationListener.php"
      provides: "Top-up purchase confirmation"
      exports: ["handle"]
    - path: "app/Listeners/RenewalReminderListener.php"
      provides: "3-day renewal reminder"
      exports: ["handle"]
    - path: "app/Listeners/CloudBudgetAlertListener.php"
      provides: "80% cloud budget admin alert"
      exports: ["handle"]
    - path: "app/Listeners/AdminAlertListener.php"
      provides: "Generic admin alert handler"
      exports: ["handle"]
    - path: "app/Listeners/NewEnterpriseCustomerListener.php"
      provides: "New Enterprise customer admin alert"
      exports: ["handle"]
    - path: "app/Console/Commands/SendFailedNotifications.php"
      provides: "Failed notification retry command"
      exports: ["handle"]
    - path: "app/Console/Commands/SendScheduledNotifications.php"
      provides: "Scheduled notification sender command"
      exports: ["handle"]
  key_links:
    - from: "app/Services/WhatsAppNotificationService.php"
      to: "Resayil WhatsApp API"
      via: "HTTP POST request"
      pattern: "file_get_contents.*WHATSAPP_API_URL"
    - from: "app/Jobs/SendWhatsAppNotification.php"
      to: "app/Services/WhatsAppNotificationService.php"
      via: "new WhatsAppNotificationService()->send()"
      pattern: "new WhatsAppNotificationService"
    - from: "app/Listeners/WelcomeNotificationListener.php"
      to: "app/Jobs/SendWhatsAppNotification.php"
      via: "dispatch(new SendWhatsAppNotification())"
      pattern: "SendWhatsAppNotification::dispatch"
    - from: "app/Console/Commands/SendFailedNotifications.php"
      to: "app/Models/Notification.php"
      via: "Notification::where('status', 'failed')->get()"
      pattern: "Notification::where\\('status', 'failed'\\)"
---

# Phase 4 Plan 01: WhatsApp Notification System

## Objective

Build a complete WhatsApp notification system for the LLM Resayil Portal that sends bilingual (Arabic/English) notifications for all user and admin events using the Resayil WhatsApp API.

Purpose: Users need to be informed about key account events (registration, payment, credits) and admins need alerts about system events (cloud budget, IP bans). WhatsApp provides instant delivery and is widely used in the Gulf region.

Output: Complete notification infrastructure with queue system, 10 bilingual notification templates, Resayil WhatsApp API integration, and Laravel scheduler for scheduled notifications.

---

## Execution Context

@/home/soudshoja/LLM-Resayil/.planning/phases/01-foundation-auth/01-foundation-auth-01-SUMMARY.md

**Dependencies:**
- User model from Phase 1 (for user notifications)
- Subscription model from Phase 1 (for subscription events)
- API key infrastructure (for admin notifications via API)

**Notifications to Implement:**
1. Welcome notification on registration (user)
2. Subscription confirmation after payment (user)
3. Credit warning at 20% remaining (user)
4. Credits exhausted with top-up link (user)
5. Top-up purchase confirmation (user)
6. Renewal reminder 3 days before expiry (user)
7. Cloud budget at 80% (admin)
8. Cloud budget at 100% with cloud disable (admin)
9. IP banned by fail2ban (admin)
10. New Enterprise customer registration (admin)

---

## Tasks

<tasks>

<task type="auto">
  <name>Task 1: Create database migrations for notifications</name>
  <files>
    - database/migrations/2024_02_26_000004_create_notifications_table.php
    - database/migrations/2024_02_26_000005_create_notification_templates_table.php
  </files>
  <action>
Create two migration files for the notification system:

1. **create_notifications_table.php**:
   - id (UUID)
   - user_id (foreign to users, nullable for admin notifications)
   - template_code (foreign to notification_templates)
   - phone (recipient phone, nullable if user_id set)
   - language (en/ar)
   - status (pending/sent/failed)
   - message (notification content)
   - metadata (json: plan details, credits remaining, etc.)
   - sent_at (nullable)
   - error_message (nullable)
   - created_at / updated_at
   - Indexes: user_id, template_code, status, created_at

2. **create_notification_templates_table.php**:
   - id (UUID)
   - code (unique: welcome, subscription_confirmed, credit_warning, etc.)
   - name (verbose name)
   - arabic_content (bilingual message template)
   - english_content (bilingual message template)
   - default_language (ar or en)
   - is_active (boolean)
   - created_at / updated_at

Run migrations after creation.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan migrate:fresh --force 2>&1 | head -20
  </verify>
  <done>
  - Two migration files created in database/migrations/
  - Tables notifications and notification_templates created
  - All foreign keys and indexes in place
  </done>
</task>

<task type="auto">
  <name>Task 2: Create Eloquent models</name>
  <files>
    - app/Models/Notification.php
    - app/Models/NotificationTemplate.php
  </files>
  <action>
Create two Eloquent models:

1. **Notification.php** - app/Models/Notification.php:
   - Fillable: user_id, template_code, phone, language, status, message, metadata
   - Casts: metadata to array, sent_at to datetime
   - BelongsTo relationship: user()
   - BelongsTo relationship: template()
   - Scopes: scopePending($query), scopeFailed($query), scopeSent($query)
   - Relationship: user() returning User::class

2. **NotificationTemplate.php** - app/Models/NotificationTemplate.php:
   - Fillable: code, name, arabic_content, english_content, default_language, is_active
   - HasMany relationship: notifications()
   - Scopes: scopeActive($query), scopeByCode($query, $code)
   - Method: getTemplate($language) returning appropriate language content

All models use UUID primary keys (use Ramsey\Uuid\Uuid as Uuid).
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan tinker --execute="echo json_encode(array_keys(get_class_methods(\App\Models\Notification::class)));"
  </verify>
  <done>
  - Notification model with user(), template() and scope methods
  - NotificationTemplate model with getTemplate() method
  - All scopes for pending/failed/sent status
  </done>
</task>

<task type="auto">
  <name>Task 3: Create WhatsApp notification service</name>
  <files>
    - app/Services/WhatsAppNotificationService.php
  </files>
  <action>
Create WhatsAppNotificationService class with Resayil WhatsApp API integration:

1. **Constructor** - Load WHATSAPP_API_URL and WHATSAPP_API_KEY from .env
2. **send($phone, $message, $language = null)** - Main send method:
   - Determine language from user preference or parameter
   - Format message for WhatsApp (Markdown-like formatting)
   - HTTP POST to Resayil WhatsApp API endpoint
   - Return response array with status and message_id
   - Log failed sends for retry queue

3. **getLanguage($user)** - Detect user language:
   - Check user preferences if stored
   - Default to Arabic for Gulf region
   - Return 'ar' or 'en'

4. **validatePhone($phone)** - Validate phone format:
   - Must be E.164 format (+965xxxxxxxx)
   - Return normalized format

Return error response on API failure with retry indication.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan tinker --execute="echo json_encode(array_keys(get_class_methods(\App\Services\WhatsAppNotificationService::class)));"
  </verify>
  <done>
  - Service exists with send(), getLanguage(), validatePhone() methods
  - Resayil WhatsApp API URL and key loaded from environment
  - Error handling returns retry-indicating responses
  </done>
</task>

<task type="auto">
  <name>Task 4: Create failed notifications retry command</name>
  <files>
    - app/Console/Commands/SendFailedNotifications.php
  </files>
  <action>
Create artisan command to retry failed notifications:

1. **handle()**:
   - Query notifications with status 'failed' and retry_count < 3
   - For each failed notification:
     - Increment retry_count
     - Call WhatsAppNotificationService->send()
     - If successful: update status to 'sent', set sent_at
     - If still failing: update status back to 'failed'
   - Output summary of processed notifications

2. **Register in Kernel**:
   - Add command to $commands array
   - Schedule to run every 15 minutes

Command should handle batch processing gracefully.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan list | grep -i failed
  </verify>
  <done>
  - Command SendFailedNotifications created
  - Registered in Console Kernel
  - Scheduled to run every 15 minutes
  - Command runs successfully
  </done>
</task>

<task type="auto">
  <name>Task 5: Create notification templates in database</name>
  <files>
    - database/seeders/NotificationTemplateSeeder.php
  </files>
  <action>
Create seeder with all 10 notification templates (bilingual):

1. **welcome** - New user registration
   - Arabic: "مرحباً بك في منصة Resayil! لقد تم تفعيل حسابك بنجاح. لديك 0 رصيد. استخدم رموز API للوصول."
   - English: "Welcome to Resayil! Your account has been activated. You have 0 credits. Use API keys to access."

2. **subscription_confirmed** - After successful payment
   - Include plan name, expiry date, credits
   - Arabic and English versions

3. **credit_warning** - 20% credits remaining
   - Arabic: "تحذير: لديك 20% من الرصيد المتبقي. إعادة شحن الآن."
   - English: "Warning: You have 20% of credits remaining. Top up now."

4. **credits_exhausted** - No credits left
   - Include top-up link: https://resayil.io/topup
   - Bilingual versions

5. **topup_confirmed** - After purchase
   - Include amount purchased
   - Bilingual versions

6. **renewal_reminder** - 3 days before expiry
   - Arabic: "تذكير: انتهاء اشتراكك في 3 أيام. قم بالتجديد الآن."
   - English: "Reminder: Your subscription expires in 3 days. Renew now."

7. **cloud_budget_80** - Admin alert at 80%
   - Include current usage and limit
   - Admin phone config

8. **cloud_budget_100** - Admin alert at 100%
   - Include cloud disabled notice
   - Admin phone config

9. **ip_banned** - IP banned by fail2ban
   - Include IP address and reason
   - Admin phone config

10. **new_enterprise** - New Enterprise customer
    - Include customer name and contact
    - Admin phone config

Create seeder and run: php artisan db:seed --class=NotificationTemplateSeeder
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan db:seed --class=NotificationTemplateSeeder && echo "Templates created"
  </verify>
  <done>
  - All 10 templates created with Arabic and English versions
  - Templates stored in notification_templates table
  - All codes match requirements (welcome, subscription_confirmed, etc.)
  </done>
</task>

<task type="auto">
  <name>Task 6: Create notification sending job</name>
  <files>
    - app/Jobs/SendWhatsAppNotification.php
  </files>
  <action>
Create Laravel job for async WhatsApp notification sending:

1. **Properties**:
   - notification_id (UUID)
   - user_id (nullable)
   - phone (nullable, for admin alerts)
   - language (en/ar)
   - template_code (string)
   - metadata (array, optional)

2. **handle()**:
   - Fetch notification by ID
   - Fetch template by code
   - Get template content for language
   - Format message with metadata (credits, plan name, etc.)
   - Call WhatsAppNotificationService->send()
   - Update notification status: 'sent' on success, 'failed' on error
   - Log any errors

Make job queueable with delay support.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan tinker --execute="echo json_encode(array_keys(get_class_methods(\App\Jobs\SendWhatsAppNotification::class)));"
  </verify>
  <done>
  - Job class created with proper properties
  - handle() method implemented
  - Status updates on send success/failure
  </done>
</task>

<task type="auto">
  <name>Task 7: Create notification event listeners</name>
  <files>
    - app/Listeners/WelcomeNotificationListener.php
    - app/Listeners/SubscriptionConfirmationListener.php
    - app/Listeners/CreditWarningListener.php
    - app/Listeners/CreditsExhaustedListener.php
    - app/Listeners/TopUpConfirmationListener.php
    - app/Listeners/RenewalReminderListener.php
    - app/Listeners/CloudBudgetAlertListener.php
    - app/Listeners/AdminAlertListener.php
    - app/Listeners/NewEnterpriseCustomerListener.php
  </files>
  <action>
Create 9 event listeners for notification dispatch:

1. **WelcomeNotificationListener** - On User created:
   - Dispatch SendWhatsAppNotification job
   - Template: welcome
   - Language: user's preference or default

2. **SubscriptionConfirmationListener** - On subscription payment success:
   - Dispatch with plan details in metadata
   - Template: subscription_confirmed

3. **CreditWarningListener** - On credit check (runs via scheduler):
   - Check if credits < 20% of total
   - Dispatch notification with remaining credits
   - Template: credit_warning

4. **CreditsExhaustedListener** - On API call with no credits:
   - Check credits == 0 before processing
   - Dispatch notification with top-up link
   - Template: credits_exhausted

5. **TopUpConfirmationListener** - On payment success:
   - Dispatch with amount and credits added
   - Template: topup_confirmed

6. **RenewalReminderListener** - On daily scheduler:
   - Check subscriptions expiring in 3 days
   - Dispatch reminder
   - Template: renewal_reminder

7. **CloudBudgetAlertListener** - On daily scheduler:
   - Check cloud budget usage at 80% and 100%
   - Dispatch admin alerts

8. **AdminAlertListener** - Generic admin notification:
   - For IP ban, new enterprise
   - Uses admin_configured phones

9. **NewEnterpriseCustomerListener** - On Enterprise subscription:
   - Dispatch with customer details
   - Template: new_enterprise

Each listener dispatches job with appropriate template and metadata.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && ls app/Listeners/*Listener.php | wc -l
  </verify>
  <done>
  - All 9 listeners created
  - Each listener dispatches SendWhatsAppNotification job
  - Listeners include appropriate template codes and metadata
  </done>
</task>

<task type="auto">
  <name>Task 8: Register listeners in EventServiceProvider</name>
  <files>
    - app/Providers/EventServiceProvider.php
  </files>
  <action>
Update EventServiceProvider to register notification listeners:

1. **Add events and listeners**:
   - 'App\Events\UserRegistered' => ['App\Listeners\WelcomeNotificationListener']
   - 'App\Events\SubscriptionPaid' => ['App\Listeners\SubscriptionConfirmationListener']
   - 'App\Events\SubscriptionPaid' => ['App\Listeners\TopUpConfirmationListener']
   - 'App\Events\CreditsLow' => ['App\Listeners\CreditWarningListener']
   - 'App\Events\CreditsExhausted' => ['App\Listeners\CreditsExhaustedListener']
   - 'App\Events\SubscriptionExpiring' => ['App\Listeners\RenewalReminderListener']
   - 'App\Events\CloudBudgetWarning' => ['App\Listeners\CloudBudgetAlertListener']
   - 'App\Events\CloudBudgetExceeded' => ['App\Listeners\CloudBudgetAlertListener']
   - 'App\Events\IPBanned' => ['App\Listeners\AdminAlertListener']
   - 'App\Events\NewEnterpriseCustomer' => ['App\Listeners\AdminAlertListener']
   - 'App\Events\NewEnterpriseCustomer' => ['App\Listeners\NewEnterpriseCustomerListener']

2. **Register events** in $events array

Listeners will be auto-discovered by Laravel's event system.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan event:clear && php artisan cache:clear
  </verify>
  <done>
  - EventServiceProvider updated with all listeners
  - Each event mapped to appropriate listener(s)
  - Cache cleared for new configuration
  </done>
</task>

<task type="auto">
  <name>Task 9: Update Kernel scheduler</name>
  <files>
    - app/Console/Kernel.php
  </files>
  <action>
Update Kernel.php schedule method with notification-related tasks:

1. **Credit warning check** (runs every hour):
   - Check users with credits < 20% of threshold
   - Dispatch CreditWarningListener

2. **Renewal reminder** (runs daily at 9 AM):
   - Find subscriptions expiring in 3 days
   - Dispatch RenewalReminderListener

3. **Failed notifications retry** (every 15 minutes):
   - Run SendFailedNotifications command

4. **Cloud budget check** (every 6 hours):
   - Check daily cloud budget usage
   - Dispatch CloudBudgetAlertListener at 80% and 100%

5. **New enterprise check** (runs hourly):
   - Find Enterprise subscriptions created in last hour
   - Dispatch NewEnterpriseCustomerListener

Add all scheduled tasks to Kernel schedule() method.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan schedule:list
  </verify>
  <done>
  - All 4 scheduled tasks configured
  - Credit warning runs hourly
  - Renewal reminder runs daily at 9 AM
  - Failed notifications retry every 15 minutes
  - Cloud budget check runs every 6 hours
  </done>
</task>

<task type="auto">
  <name>Task 10: Update API routes for admin notifications</name>
  <files>
    - routes/api.php
  </files>
  <action>
Update routes/api.php to add admin notification endpoints:

1. **Add Admin Notification Controller**:
```php
use App\Http\Controllers\Admin\NotificationController;
```

2. **Admin notification routes** (protected by admin middleware):
```php
Route::middleware(['auth:sanctum', 'api.key.auth'])->group(function () {
    // Existing routes...

    // Admin notification endpoints
    Route::get('/admin/notifications', [NotificationController::class, 'index']);
    Route::post('/admin/notifications/send', [NotificationController::class, 'sendManual']);
    Route::post('/admin/notifications/test', [NotificationController::class, 'testTemplate']);
});
```

3. **Create NotificationController**:
   - index() - List recent notifications
   - sendManual() - Send manual notification
   - testTemplate() - Test template with sample data

All admin routes require api.key.auth middleware with admin permission.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan route:list --path=admin/notifications
  </verify>
  <done>
  - Admin notification routes added to api.php
  - NotificationController created with index, sendManual, testTemplate
  - Routes properly protected with api.key.auth middleware
  </done>
</task>

</tasks>

---

## Verification

### Phase 4 Complete When:
- [ ] All migrations created and ran successfully
- [ ] Notification model and template model created
- [ ] WhatsAppNotificationService integrated with Resayil WhatsApp API
- [ ] Failed notifications retry command created and scheduled
- [ ] All 10 notification templates created (bilingual)
- [ ] SendWhatsAppNotification job created
- [ ] All 9 event listeners created and registered
- [ ] Scheduler configured for all periodic tasks
- [ ] Admin notification endpoints created
- [ ] Test notification sends successfully

### Success Criteria from Phase 4:
1. Welcome WhatsApp message sent in both Arabic and English upon registration ✓
2. Subscription confirmation with plan details sent after successful payment ✓
3. Credit warning notification sent when credits reach 20% remaining ✓
4. Credits exhausted notification includes top-up link in both languages ✓
5. Top-up purchase confirmation sent immediately after payment ✓
6. Renewal reminder sent 3 days before subscription expiry ✓
7. Cloud budget at 80% triggers admin alert ✓
8. Cloud budget at 100% triggers admin alert and disables cloud ✓
9. IP banned by fail2ban triggers admin alert ✓
10. New Enterprise customer registration triggers admin alert ✓

---

## Wave Structure

| Wave | Plans | Tasks | Notes |
|------|-------|-------|-------|
| 1 | 04-01 | 10 | Foundation - migrations, models, service, jobs, listeners, scheduler |

---

## Output

After completion, create `.planning/phases/04-notifications/04-notifications-01-SUMMARY.md` documenting:

```markdown
# Phase 4 Plan 01 Summary

## What Was Built
- Database schema (notifications, notification_templates tables)
- Eloquent models with relationships and scopes
- WhatsAppNotificationService for Resayil WhatsApp API integration
- SendWhatsAppNotification job for async sending
- 10 bilingual notification templates in database
- 9 event listeners for notification dispatch
- Scheduled tasks for periodic notifications
- Admin notification endpoints
- Failed notification retry system

## Files Created
- database/migrations/2024_02_26_000004_create_notifications_table.php
- database/migrations/2024_02_26_000005_create_notification_templates_table.php
- app/Models/Notification.php
- app/Models/NotificationTemplate.php
- app/Services/WhatsAppNotificationService.php
- app/Jobs/SendWhatsAppNotification.php
- app/Listeners/WelcomeNotificationListener.php
- app/Listeners/SubscriptionConfirmationListener.php
- app/Listeners/CreditWarningListener.php
- app/Listeners/CreditsExhaustedListener.php
- app/Listeners/TopUpConfirmationListener.php
- app/Listeners/RenewalReminderListener.php
- app/Listeners/CloudBudgetAlertListener.php
- app/Listeners/AdminAlertListener.php
- app/Listeners/NewEnterpriseCustomerListener.php
- app/Console/Commands/SendFailedNotifications.php
- app/Console/Commands/SendScheduledNotifications.php
- app/Console/Kernel.php (updated)
- app/Providers/EventServiceProvider.php (updated)
- routes/api.php (updated)

## Next Steps
- Phase 4 Plan 02: Testing and verification
- Phase 4 Plan 03: Documentation
```
