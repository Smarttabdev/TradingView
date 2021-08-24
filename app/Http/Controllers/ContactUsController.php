<?php

namespace App\Http\Controllers;

use App\Mail\ContactUs;
use Illuminate\Http\Request;
use Mail;

class ContactUsController extends Controller
{
    public function sendEmail(Request $request)
    {
        $detail = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        Mail::to(env('MAIL_USERNAME', 'admin@eaglefx.co.uk'))->send(new ContactUs($detail));
        return response()->json([
            'response' => [
                'api_status' => 1,
                'code' => 200,
                'message' => 'Your message has been sent successfully!',
            ],
        ]);

    }
}