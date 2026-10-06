<?php

namespace App\Mail;

use App\Models\Clase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClaseCompletadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $clase;

    public function __construct(Clase $clase)
    {
        $this->clase = $clase;
    }

    public function build()
    {
        return $this->subject('🏁 Clase completada - ¡Deja tu reseña! - TutorMatch')
                    ->view('emails.clase-completada');
    }
}