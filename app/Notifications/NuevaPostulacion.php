<?php

namespace App\Notifications;

use App\Models\Postulacion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NuevaPostulacion extends Notification
{
    use Queueable;

    public function __construct(private readonly Postulacion $postulacion) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'titulo' => 'Nueva postulación',
            'mensaje' => $this->postulacion->user->name.' se postuló a '.$this->postulacion->convocatoria->titulo.'.',
            'url' => route('dictaminador.postulantes.show', $this->postulacion),
            'postulacion_id' => $this->postulacion->id,
        ];
    }
}
