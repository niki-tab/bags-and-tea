<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

class TestController extends Controller
{
    public function index()
    {   

        return view('test');

    }

    public function sendTestEmail()
    {
        try {
            Mail::to('test@example.com')->send(new TestEmail('Hello World! This is a test email.'));
            
            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
}
