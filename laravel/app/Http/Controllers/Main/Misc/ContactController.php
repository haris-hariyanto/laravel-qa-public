<?php

namespace App\Http\Controllers\Main\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ReCAPTCHA;
use App\Models\Contact;

class ContactController extends Controller
{
    public function contact()
    {
        $breadcrumb = [
            __('Home') => route('index'),
            __('Contact Us') => '',
        ];
        return view('main.misc.pages.contact', compact('breadcrumb'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email'],
            'subject' => ['required', 'max:255'],
            'message' => ['required', 'max:4096'],
            'g-recaptcha-response' => ['required'],
        ]);

        $reCAPTCHA = new ReCAPTCHA($request->{'g-recaptcha-response'});

        if (!$reCAPTCHA->verify()) {
            return redirect()->back()->with('recaptchaInvalid', __('reCAPTCHA is invalid'));
        }

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return back()->with('status', __('Message has been sent!'));
    }
}
