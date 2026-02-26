<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index(Request $request)
    {
        $query = Notification::with('user', 'template');

        // Filter by status
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // Filter by user_id
        if ($userId = $request->query('user_id')) {
            $query->where('user_id', $userId);
        }

        // Filter by template_code
        if ($templateCode = $request->query('template')) {
            $query->where('template_code', $templateCode);
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }

    /**
     * Send a manual notification.
     */
    public function sendManual(Request $request)
    {
        $request->validate([
            'template_code' => 'required|exists:notification_templates,code',
            'user_id' => 'nullable|exists:users,id',
            'phone' => 'nullable|string',
            'language' => 'nullable|in:en,ar',
            'metadata' => 'nullable|array',
        ]);

        $template = NotificationTemplate::where('code', $request->template_code)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found or inactive',
            ], 404);
        }

        $language = $request->language ?? 'ar';
        $content = $template->getTemplate($language);

        // Format message with metadata
        $metadata = $request->metadata ?? [];
        foreach ($metadata as $key => $value) {
            $placeholder = '{' . $key . '}';
            $content = str_replace($placeholder, $value, $content);
        }

        // Determine phone number
        $phone = $request->phone;

        if (!$phone && $request->user_id) {
            $user = \App\Models\User::find($request->user_id);
            if ($user && $user->phone) {
                $phone = $user->phone;
            }
        }

        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'No phone number available',
            ], 400);
        }

        // Create notification record
        $notification = Notification::create([
            'user_id' => $request->user_id,
            'template_code' => $request->template_code,
            'phone' => $phone,
            'language' => $language,
            'status' => 'pending',
            'message' => $content,
            'metadata' => $metadata,
        ]);

        // Dispatch job for async sending
        \App\Jobs\SendWhatsAppNotification::dispatch(
            $notification->id,
            $request->user_id,
            $phone,
            $language,
            $request->template_code,
            $metadata
        );

        return response()->json([
            'success' => true,
            'message' => 'Notification queued for sending',
            'notification_id' => $notification->id,
        ]);
    }

    /**
     * Test a notification template.
     */
    public function testTemplate(Request $request)
    {
        $request->validate([
            'template_code' => 'required|exists:notification_templates,code',
            'language' => 'nullable|in:en,ar',
            'metadata' => 'nullable|array',
        ]);

        $template = NotificationTemplate::where('code', $request->template_code)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found or inactive',
            ], 404);
        }

        $language = $request->language ?? 'ar';
        $content = $template->getTemplate($language);

        // Format message with metadata
        $metadata = $request->metadata ?? [];
        foreach ($metadata as $key => $value) {
            $placeholder = '{' . $key . '}';
            $content = str_replace($placeholder, $value, $content);
        }

        return response()->json([
            'success' => true,
            'message' => 'Template test successful',
            'template' => [
                'code' => $template->code,
                'name' => $template->name,
            ],
            'content' => $content,
            'language' => $language,
        ]);
    }
}
