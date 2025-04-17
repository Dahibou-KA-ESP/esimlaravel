<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InsuranceRenewalReminder extends Notification
{
    use Queueable;

    protected $policy;

    public function __construct($policy)
    {
        $this->policy = $policy;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Renouvellement de votre police d\'assurance')
                    ->line('Votre police d\'assurance avec la compagnie '.$this->policy->compagnie->nom_complet.' approche de la date de renouvellement.')
                    ->line('Date de renouvellement : '.$this->policy->date_echeance)
                    ->action('Renouveler maintenant','https://platineassurances.sn/comparateur-auto')
                    ->line('Merci d\'avoir utilisé notre service!');
    }
}