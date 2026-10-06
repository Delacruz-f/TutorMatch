<?php

namespace App\Mail;

use App\Models\Clase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClaseAceptadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $clase;

    public function __construct(Clase $clase)
    {
        $this->clase = $clase;
    }

    public function build()
    {
        return $this->subject('✅ Tu clase fue aceptada - TutorMatch')
                    ->view('emails.clase-aceptada');
    }
}