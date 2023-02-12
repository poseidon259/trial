<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Mail\SendMail;

class MailService
{
    /**
     * Send simple mail
     * @param $mailTo - Mail send to
     * @param $data - Data to send
     * @param $subject - Subject of mail
     * @param $view - Name view template of mail
     */
    public function sendEmail($mailTo, $data, $subject, $view)
    {
        Log::info('Send mail for ' . $mailTo . ': ' . $subject);
        Mail::to($mailTo)->send(new SendMail($subject, $data, $view));
    }

}
