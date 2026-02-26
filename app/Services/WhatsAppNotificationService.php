<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected ?string $apiUrl;

    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->apiKey = config('services.whatsapp.api_key');
    }

    /**
     * Send WhatsApp notification to phone number.
     *
     * @param string $phone Recipient phone number (E.164 format)
     * @param string $message Message content
     * @param string|null $language Language override (ar/en)
     * @return array Response with status and message_id
     */
    public function send(string $phone, string $message, ?string $language = null): array
    {
        try {
            // Validate phone format
            $validatedPhone = $this->validatePhone($phone);
            if (!$validatedPhone) {
                return [
                    'status' => 'error',
                    'message' => 'Invalid phone format',
                    'retry' => false,
                ];
            }

            // Format message (basic Markdown-like formatting for WhatsApp)
            $formattedMessage = $this->formatMessage($message, $language);

            // Prepare API payload
            $payload = json_encode([
                'phone' => $validatedPhone,
                'message' => $formattedMessage,
                'language' => $language ?? 'ar',
            ]);

            // Initialize cURL
            $ch = curl_init($this->apiUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey,
            ]);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                Log::error('WhatsApp API cURL Error: ' . $error);
                return [
                    'status' => 'error',
                    'message' => $error,
                    'retry' => true,
                ];
            }

            $result = json_decode($response, true);

            if ($httpCode >= 200 && $httpCode < 300) {
                return [
                    'status' => 'success',
                    'message_id' => $result['message_id'] ?? null,
                    'phone' => $validatedPhone,
                ];
            }

            Log::error('WhatsApp API Error', [
                'http_code' => $httpCode,
                'response' => $response,
            ]);

            return [
                'status' => 'error',
                'message' => $response ?? 'Unknown error',
                'retry' => true,
            ];
        } catch (\Throwable $e) {
            Log::error('WhatsApp Notification Service Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'retry' => true,
            ];
        }
    }

    /**
     * Detect user language preference.
     *
     * @param \App\Models\User $user User model
     * @return string Language code (ar/en)
     */
    public function getLanguage($user): string
    {
        // Check if user has language preference stored
        if ($user && property_exists($user, 'language')) {
            return $user->language ?? 'ar';
        }

        // Default to Arabic for Gulf region
        return 'ar';
    }

    /**
     * Validate phone number format (E.164).
     *
     * @param string $phone Phone number
     * @return string|null Validated phone or null if invalid
     */
    public function validatePhone(string $phone): ?string
    {
        // Remove any spaces, dashes, or parentheses
        $cleaned = preg_replace('/[\s\-\(\)]/', '', $phone);

        // Check for valid E.164 format: + followed by 1-15 digits
        if (!preg_match('/^\+[1-9]\d{1,14}$/', $cleaned)) {
            // Try to add + if missing (common in Gulf region)
            if (preg_match('/^[1-9]\d{8,14}$/', $cleaned)) {
                // Check for common Gulf country codes
                $commonPrefixes = ['965', '966', '971', '974', '968', '973'];
                foreach ($commonPrefixes as $prefix) {
                    if (str_starts_with($cleaned, $prefix)) {
                        return '+' . $cleaned;
                    }
                }
                // Default to Kuwait code if no match
                return '+965' . $cleaned;
            }
            return null;
        }

        return $cleaned;
    }

    /**
     * Format message content for WhatsApp.
     *
     * @param string $message Raw message content
     * @param string|null $language Language override
     * @return string Formatted message
     */
    protected function formatMessage(string $message, ?string $language = null): string
    {
        // WhatsApp supports basic Markdown (bold, italic, strikethrough)
        // Convert **text** to bold
        $formatted = $message;

        // Basic formatting for WhatsApp
        $formatted = str_replace('**', '*', $formatted);

        return $formatted;
    }
}
