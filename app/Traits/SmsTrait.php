<?php

namespace App\Traits;

use App\Notifications\SendSMS;
use App\SMS;

trait SmsTrait
{
    /**
     * This function is a shortcut to send rapidly a SMS to someone
     *
     * @param $telephone
     * @param $message
     * @param null $sender
     */
    public function sendSMS($telephone, $message, $sender = null)
    {
        $sms = new SMS();
        $sms->telephone = $telephone;
        $sms->notify(new SendSMS($message, getFullNameWithoutAccent( is_null($sender) ? 'Medicasure' : $sender)));
    }

}
