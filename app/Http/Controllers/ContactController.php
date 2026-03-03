<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:50',
            'message' => 'required|string|min:10|max:5000',
        ]);

        // Send email to soud@alphia.net
        try {
            Mail::send('emails.contact', [
                'fullName' => $validated['full_name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'message' => $validated['message'],
            ], function ($message) {
                $message->to('soud@alphia.net')
                        ->subject('New Contact Form Submission - LLM Resayil');
                $message->from($validated['email'], $validated['full_name']);
            });
        } catch (\Exception $e) {
            // Log error but still show success to user
            \Log::error('Contact form email failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Message sent! We\'ll get back to you at ' . $validated['email'] . ' within 24 hours.');
    }
}
