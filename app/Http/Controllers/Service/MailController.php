<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    static function sendmail($username, $email, $data=[])
    {
         Mail::send('welcome', $data,function($message) use ($email, $username)
        {
            $message->to($email)->subject('Welcome '.$username.', to Game-Badrweb!');
            $message->from('no_reply@badrweb.es', 'Rock, paper or scissors?');
        });

        return response()->json(["enviado" => true, "mensaje"=>"Enviado"],200);
    }
}
