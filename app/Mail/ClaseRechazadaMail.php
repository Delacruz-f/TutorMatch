<?php

namespace App\Mail;

use App\Models\Clase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClaseRechazadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $clase;

    public function __construct(Clase $clase)
    {
        $this->clase = $clase;
    }

    public function build()
    {
        return $this->subject('❌ Tu solicitud de clase fue rechazada - TutorMatch')
                    ->view('emails.clase-rechazada');
    }
}