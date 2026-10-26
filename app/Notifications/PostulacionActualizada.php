<?php

namespace App\Notifications;

use App\Models\Postulacion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostulacionActualizada extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Postulacion $postulacion,
        private readonly string $mensaje,
    ) {}

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
            'titulo' => 'Actualización de postulación',
            'mensaje' => $this->mensaje,
            'url' => route('postulaciones.show', $this->postulacion),
            'postulacion_id' => $this->postulacion->id,
        ];
    }
}
