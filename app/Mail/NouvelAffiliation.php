<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NouvelAffiliation extends Mailable
{
    use Queueable, SerializesModels;

    public $patient_nom, $patient_prenom, $patient_telehone, $plainte, $urgence, $contact_nom, $contact_prenom, $contact_phone, $typeSouscription, $paye_par_affilie;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($patient_nom, $patient_prenom, $patient_telehone, $plainte, $urgence, $contact_nom, $contact_prenom, $contact_phone, $typeSouscription, $paye_par_affilie)
    {
        $this->patient_nom = $patient_nom;
        $this->patient_prenom = $patient_prenom;
        $this->patient_telehone = $patient_telehone;
        $this->plainte = $plainte;
        $this->urgence = $urgence;
        $this->contact_nom = $contact_nom;
        $this->contact_prenom = $contact_prenom;
        $this->contact_phone = $contact_phone;
        $this->typeSouscription = $typeSouscription;
        $this->paye_par_affilie = $paye_par_affilie;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from('no-reply@medsurlink.com')->subject("Nouvelle affiliation")
                ->markdown('emails.Souscripteur.envoiNotificationAffiliation');
    }
}