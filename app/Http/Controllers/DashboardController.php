<?php

namespace App\Http\Controllers;

use App\Models\Convocatoria;
use App\Models\Postulacion;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function show(): View
    {
        $user = request()->user();

        if ($user->role === User::ROLE_ADMIN) {
            return view('admin.dashboard', [
                'convocatoriasCount' => Convocatoria::count(),
                'usersCount' => User::count(),
                'pendingCount' => Postulacion::where('estatus', Postulacion::STATUS_PENDING)->count(),
            ]);
        }

        if ($user->role === User::ROLE_REVIEWER) {
            return view('dictaminador.dashboard', [
                'convocatoriasCount' => Convocatoria::count(),
                'pendingCount' => Postulacion::where('estatus', Postulacion::STATUS_PENDING)->count(),
                'nuevas' => Convocatoria::query()
                    ->where('created_at', '>=', now()->subDays(7))
                    ->latest()
                    ->limit(5)
                    ->get(),
                'porVencer' => Convocatoria::query()
                    ->where('estatus', 'Activa')
                    ->whereBetween('fecha_fin', [today(), today()->addDays(7)])
                    ->orderBy('fecha_fin')
                    ->limit(5)
                    ->get(),
            ]);
        }

        $postulaciones = Postulacion::query()
            ->with('convocatoria:id,titulo,tipo_personal,categoria_nivel')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('dashboard', [
            'postulaciones' => $postulaciones,
            'unreadNotificationsCount' => $user->unreadNotifications()->count(),
        ]);
    }
}
