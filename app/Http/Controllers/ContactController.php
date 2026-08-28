<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function send(Request $request, string $recipient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:40'],
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages([
                'email' => 'Contact recipient is not configured correctly. Please try again later.',
            ]);
        }

        try {
            Mail::send('emails.contact', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'subject' => $validated['subject'],
                'userMessage' => $validated['message'],
            ], function ($mail) use ($validated, $recipient) {
                $mail->to($recipient)
                    ->subject('New Contact Form Submission: ' . $validated['subject'])
                    ->replyTo($validated['email'], $validated['name']);
            });
        } catch (\Throwable $e) {
            Log::error('Contact form mail failed', [
                'error' => $e->getMessage(),
                'recipient' => $recipient,
            ]);

            return back()
                ->withInput()
                ->with('error', 'We could not send your message right now. Please email us directly or try again shortly.');
        }

        return back()->with('success', 'Your message has been sent. We will get back to you shortly.');
    }
}
