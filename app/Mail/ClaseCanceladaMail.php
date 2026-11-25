<?php

namespace App\Mail;

use App\Models\Clase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClaseCanceladaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $clase;

    public $esEstudiante;

    public $reembolsoSolicitado;

    public $montoReembolso;

    public function __construct(Clase $clase, bool $esEstudiante, bool $reembolsoSolicitado, $montoReembolso = null)
    {
        $this->clase = $clase;
        $this->esEstudiante = $esEstudiante;
        $this->reembolsoSolicitado = $reembolsoSolicitado;
        $this->montoReembolso = $montoReembolso;
    }

    public function build()
    {
        return $this->subject(__('ui.class_cancelled_email_subject'))
            ->view('emails.clase-cancelada');
    }
}
