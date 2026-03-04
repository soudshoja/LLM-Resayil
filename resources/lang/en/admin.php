<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Language Lines
    |--------------------------------------------------------------------------
    */

    'brand' => 'LLM Resayil',

    // Sidebar navigation
    'admin_dashboard' => 'Dashboard',
    'users' => 'Users',
    'api_settings' => 'API Settings',
    'logout' => 'Logout',

    // Page titles
    'api_settings_page_title' => 'API Settings',
    'configure_services' => 'Configure external service credentials and endpoints',

    // User info
    'administrator' => 'Administrator',

    // Alerts
    'success' => 'Success',
    'error' => 'Error',

    // Section headers - keep technical terms in English
    'ollama' => 'Ollama',
    'ollama_description' => 'Local GPU server and cloud failover configuration',
    'myfatoorah' => 'MyFatoorah',
    'myfatoorah_description' => 'Payment gateway for KWD transactions',
    'resayil_whatsapp' => 'Resayil WhatsApp',
    'whatsapp_description' => 'WhatsApp notification service for bilingual alerts',
    'redis' => 'Redis',
    'redis_description' => 'Cache and rate limiting store',

    // Form labels - keep environment variable names in English
    'local_gpu_url' => 'Local GPU URL',
    'local_gpu_url_env' => '(OLLAMA_GPU_URL)',
    'cloud_failover_url' => 'Cloud Failover URL',
    'cloud_failover_url_env' => '(OLLAMA_CLOUD_URL)',
    'cloud_api_key' => 'Cloud API Key',
    'cloud_api_key_env' => '(CLOUD_API_KEY)',
    'myfatoorah_api_key' => 'API Key',
    'myfatoorah_api_key_env' => '(MYFATOORAH_API_KEY)',
    'myfatoorah_base_url' => 'Base URL',
    'myfatoorah_base_url_env' => '(MYFATOORAH_BASE_URL)',
    'myfatoorah_callback_url' => 'Callback URL',
    'myfatoorah_callback_url_env' => '(MYFATOORAH_CALLBACK_URL)',
    'whatsapp_api_url' => 'API URL',
    'whatsapp_api_url_env' => '(WHATSAPP_API_URL)',
    'admin_phone' => 'Admin Phone',
    'admin_phone_env' => '(ADMIN_PHONE)',
    'whatsapp_api_key' => 'API Key',
    'whatsapp_api_key_env' => '(WHATSAPP_API_KEY)',
    'redis_host' => 'Host',
    'redis_host_env' => '(REDIS_HOST)',
    'redis_port' => 'Port',
    'redis_port_env' => '(REDIS_PORT)',
    'redis_password' => 'Password',
    'redis_password_env' => '(REDIS_PASSWORD)',

    // Button labels
    'save_all_settings' => 'Save All Settings',

    // Placeholder text - keep URLs和技术术语 in English
    'gpu_url_placeholder' => 'http://208.110.93.90:11434',
    'cloud_url_placeholder' => 'https://ollama.com',
    'myfatoorah_base_url_placeholder' => 'https://ap-gateway.myfatoorah.com',
    'myfatoorah_callback_url_placeholder' => 'https://llm.resayil.io/webhooks/payment',
    'whatsapp_url_placeholder' => 'https://api.resayil.io/whatsapp',
    'admin_phone_placeholder' => '96550000000',
    'redis_host_placeholder' => '127.0.0.1',
    'redis_port_placeholder' => '6379',
    'redis_password_placeholder' => 'Leave empty if none',

    // Toast messages
    'settings_saved_successfully' => 'Settings saved successfully',
    'settings_save_error' => 'Error saving settings',

    // Monitoring page
    'monitoring' => 'API Monitoring',
    'platform_overview' => 'Platform-wide usage analytics',
    'today_calls' => "Today's Calls",
    'calls_7d' => 'Calls (7d)',
    'calls_30d' => 'Calls (30d)',
    'tokens_30d' => 'Tokens (30d)',
    'credits_used_30d' => 'Credits Used (30d)',
    'top_users' => 'Top Users (30d)',
    'top_models' => 'Top Models (30d)',
    'recent_api_calls' => 'Recent API Calls',
    'user' => 'User',
    'model' => 'Model',
    'tokens' => 'Tokens',
    'credits' => 'Credits',
    'time' => 'Time',
    'no_api_calls_yet' => 'No API calls yet.',

    // Dashboard - stats cards
    'total_users' => 'Total Users',
    'active_subscriptions' => 'Active Subscriptions',
    'total_api_calls' => 'Total API Calls',
    'cloud_budget_used' => 'Cloud Budget Used',

    // Dashboard - users table
    'all_users' => 'All Users',
    'name_email' => 'Name / Email',
    'phone' => 'Phone',
    'plan' => 'Plan',
    'joined' => 'Joined',
    'actions' => 'Actions',

    // Dashboard - action buttons
    'credits_action' => 'Credits',
    'tier_action' => 'Tier',
    'expiry_action' => 'Expiry',
    'api_key_action' => 'API Key',

    // Dashboard - credits modal
    'set_credits' => 'Set Credits',
    'user_id' => 'User ID',
    'enter_credits' => 'Enter credits amount',
    'cancel' => 'Cancel',
    'save' => 'Save',

    // Dashboard - tier modal
    'set_subscription_tier' => 'Set Subscription Tier',
    'tier' => 'Tier',
    'starter' => 'Starter',
    'basic' => 'Basic',
    'pro' => 'Pro',
    'enterprise' => 'Enterprise',

    // Dashboard - expiry modal
    'set_subscription_expiry' => 'Set Subscription Expiry',
    'expiry_date' => 'Expiry Date',
    'clear_expiry' => 'Clear expiry (no expiration)',

    // Dashboard - API key modal
    'create_api_key' => 'Create API Key',
    'key_name' => 'Key Name',
    'enter_key_name' => 'Enter key name',
    'admin_created_key' => 'Admin-created key',
    'api_key_created' => 'API key created successfully!',
    'copy_to_clipboard' => 'Copy to Clipboard',
    'close' => 'Close',
    'create_key' => 'Create Key',

    // Dashboard - JS alerts
    'invalid_credits' => 'Please enter a valid credits amount.',
    'credits_error' => 'Failed to update credits.',
    'tier_error' => 'Failed to update tier.',
    'expiry_error' => 'Failed to update expiry.',
    'key_error' => 'Failed to create API key.',
    'key_copied' => 'API key copied to clipboard!',

    // Models page - headers & stats
    'model_management' => 'Model Management',
    'configure_models' => 'Configure model availability and credit multipliers',
    'total_models' => 'Total Models',
    'active_models' => 'Active Models',
    'local_models' => 'Local Models',
    'cloud_models' => 'Cloud Models',

    // Models page - filter labels
    'family' => 'Family',
    'category' => 'Category',
    'type' => 'Type',
    'size' => 'Size',
    'all' => 'All',
    'chat' => 'Chat',
    'code' => 'Code',
    'embedding' => 'Embedding',
    'vision' => 'Vision',
    'thinking' => 'Thinking',
    'tools' => 'Tools',
    'local' => 'Local',
    'cloud' => 'Cloud',
    'small' => 'Small',
    'medium' => 'Medium',
    'large' => 'Large',
    'search_models' => 'Search models...',

    // Models page - bulk actions
    'enable' => 'Enable',
    'disable' => 'Disable',
    'bulk_update' => 'Bulk Update',

    // Models page - table headers
    'model_id' => 'Model ID',
    'multiplier' => 'Multiplier',
    'status' => 'Status',
    'override_label' => 'Override:',
    'edit' => 'Edit',
    'models' => 'models',

    // Models page - edit modal
    'edit_model_settings' => 'Edit Model Settings',
    'credit_multiplier_override' => 'Credit Multiplier Override',
    'leave_empty_default' => 'Leave empty for default',
    'use_default_from_config' => 'Leave empty to use the default multiplier from config.',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'save_changes' => 'Save Changes',

    // Models page - JS toast & strings
    'model_enabled' => 'Model enabled successfully.',
    'model_disabled' => 'Model disabled successfully.',
    'update_failed' => 'Update failed. Please try again.',
    'select_models_update' => 'Please select models to update.',
    'enable_models' => 'Are you sure you want to update',
    'showing' => 'Showing',
    'of' => 'of',
    'saving' => 'Saving...',
    'model_settings_saved' => 'Model settings saved successfully.',
    'failed_save' => 'Failed to save model settings.',
];
