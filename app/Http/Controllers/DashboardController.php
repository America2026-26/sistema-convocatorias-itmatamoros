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
                'convocatoriasActivasCount' => Convocatoria::query()
                    ->where('estatus', 'Activa')
                    ->whereDate('fecha_inicio', '<=', today())
                    ->whereDate('fecha_fin', '>=', today())
                    ->count(),
                'postulantesCount' => Postulacion::query()
                    ->distinct()
                    ->count('user_id'),
                'pendingCount' => Postulacion::where('estatus', Postulacion::STATUS_PENDING)->count(),
                'postulacionesPendientes' => Postulacion::query()
                    ->with([
                        'user:id,name,email,tipo_usuario',
                        'convocatoria:id,titulo,tipo_personal',
                    ])
                    ->where('estatus', Postulacion::STATUS_PENDING)
                    ->latest()
                    ->limit(5)
                    ->get(),
                'nuevas' => Convocatoria::query()
                    ->where('created_at', '>=', now()->subDays(7))
                    ->withCount('postulaciones')
                    ->latest()
                    ->limit(5)
                    ->get(),
                'porVencer' => Convocatoria::query()
                    ->withCount('postulaciones')
                    ->where('estatus', 'Activa')
                    ->whereDate('fecha_inicio', '<=', today())
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
            'plazaStatuses' => $this->teachingPlazaStatuses($user),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function teachingPlazaStatuses(User $user): array
    {
        $occupiedPlazaCodes = User::query()
            ->where('role', User::ROLE_USER)
            ->where('tipo_usuario', 'aspirante_docente')
            ->whereNotNull('plaza_jornada')
            ->pluck('plaza_jornada')
            ->all();

        $availablePlazaCodes = Convocatoria::query()
            ->where('tipo_personal', 'Docente')
            ->get(['detalles_especificos'])
            ->flatMap(static function (Convocatoria $convocatoria): array {
                $selectedPlazaCodes = $convocatoria->detalles_especificos['plazas_seleccionadas'] ?? [];

                return is_array($selectedPlazaCodes) ? $selectedPlazaCodes : [];
            })
            ->filter(static fn (mixed $code): bool => is_string($code)
                && array_key_exists($code, Convocatoria::TEACHING_POSITIONS))
            ->unique()
            ->all();

        $upcomingVacancyPlazaCodes = Postulacion::query()
            ->join('users', 'users.id', '=', 'postulaciones.user_id')
            ->join('convocatorias', 'convocatorias.id', '=', 'postulaciones.convocatoria_id')
            ->whereIn('postulaciones.estatus', [
                Postulacion::STATUS_PENDING,
                Postulacion::STATUS_APPROVED,
            ])
            ->where('convocatorias.tipo_personal', 'Docente')
            ->where('users.role', User::ROLE_USER)
            ->where('users.tipo_usuario', 'aspirante_docente')
            ->whereNotNull('users.plaza_jornada')
            ->whereNull('users.deleted_at')
            ->distinct()
            ->pluck('users.plaza_jornada')
            ->filter(static fn (mixed $code): bool => is_string($code)
                && array_key_exists($code, Convocatoria::TEACHING_POSITIONS))
            ->all();

        $occupiedPlazaCodes = array_fill_keys($occupiedPlazaCodes, true);
        $availablePlazaCodes = array_fill_keys($availablePlazaCodes, true);
        $upcomingVacancyPlazaCodes = array_fill_keys($upcomingVacancyPlazaCodes, true);
        $plazaStatuses = [];

        foreach (array_keys(Convocatoria::TEACHING_POSITIONS) as $code) {
            $plazaStatuses[$code] = match (true) {
                isset($upcomingVacancyPlazaCodes[$code]) => 'upcoming',
                $user->plaza_jornada === $code => 'own',
                isset($occupiedPlazaCodes[$code]) => 'occupied',
                isset($availablePlazaCodes[$code]) => 'available',
                default => 'none',
            };
        }

        return $plazaStatuses;
    }
}
