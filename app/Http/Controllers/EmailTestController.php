<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class EmailTestController extends Controller
{
    public function index()
    {
        return view('emails.test-page');
    }

    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            Mail::to($request->email)->send(new TestMail());

            return back()->with('success', 'Email berhasil dikirim ke ' . $request->email . '! Silakan cek inbox atau spam folder.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}

