<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailForgotPasswordController extends Controller
{
    public function sendMail() {
        $email = "18521155@gm.uit.edu.vn";

        \Mail::to($email)->send(new \App\Mail\MailForgotPassword(['email' => $email]));
        return true;
    }
}
