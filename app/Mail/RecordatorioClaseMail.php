<?php

namespace App\Mail;

use App\Models\Clase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioClaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $clase;

    public function __construct(Clase $clase)
    {
        $this->clase = $clase;
    }

    public function build()
    {
        return $this->subject('⏰ Tu clase empieza en 30 minutos - TutorMatch')
                    ->view('emails.recordatorio-clase');
    }
}