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
        sendSMS($telephone, $message, $sender);
    }

    /**
     * Send a SMS to a User
     *
     * @param $user
     * @param null $sender
     */
    function sendSmsToUser($user, $sender = null) {
        if (!is_null($user)){
            try {
//                $nom = (is_null($user->prenom) ? "" : ucfirst($user->prenom) ." ") . "". strtoupper( $user->nom);
                $nom = substr(strtoupper( $user->nom),0,9);
                sendSMS($user->telephone,trans('sms.accountUpdated',['nom'=>$nom],'fr'),$sender);
            }catch (\Exception $exception){
                //$exception
            }
        }
    }

    /**
     * Rappeler des rendez vous à des patients
     *
     * @param $user
     * @param null $sender
     */
    function RappelerRdvViaSMSTo($user, $date,$sender = null) {
        if (!is_null($user)){
            try {

                $nom = substr(strtoupper( $user->nom),0,9);
                sendSMS($user->telephone,trans('sms.rappelerRendezVous',['nom'=>$nom,'date'=>$date],'fr'),$sender);

             }catch (\Exception $exception){
                //$exception
            }
        }
    }

}
