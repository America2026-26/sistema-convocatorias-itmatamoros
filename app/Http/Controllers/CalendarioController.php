<?php

namespace App\Http\Controllers;

use App\Models\CalendarioEvento;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class CalendarioController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $validated = $request->validate([
            'mes' => ['nullable', 'date_format:Y-m'],
        ]);
        $month = $validated['mes'] ?? today()->format('Y-m');
        $calendarData = $this->calendarData($month, $this->canManageEvents($request));

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('calendario._content', $calendarData)->render(),
            ]);
        }

        return view('calendario.index', $calendarData);
    }

    /**
     * @return array<string, mixed>
     */
    private function calendarData(string $month, bool $canManageEvents): array
    {
        $mesActual = Carbon::parse($month.'-01');
        $mesActual->startOfMonth();

        $hoy = today();
        $inicioCalendario = $mesActual->copy()->startOfMonth()->startOfWeek();
        $finCalendario = $mesActual->copy()->endOfMonth()->endOfWeek();
        $diasCalendario = collect();

        for ($dia = $inicioCalendario->copy(); $dia->lte($finCalendario); $dia->addDay()) {
            $diasCalendario->push($dia->copy());
        }

        $eventosPorDia = CalendarioEvento::query()
            ->whereBetween('fecha', [
                $inicioCalendario->toDateString(),
                $finCalendario->toDateString(),
            ])
            ->orderBy('titulo')
            ->get(['id', 'fecha', 'titulo', 'descripcion'])
            ->groupBy(fn (CalendarioEvento $evento): string => $evento->fecha->toDateString());

        $eventos = CalendarioEvento::query()
            ->whereDate('fecha', '>=', $hoy->toDateString())
            ->orderBy('fecha')
            ->orderBy('titulo')
            ->get();

        return [
            'diasCalendario' => $diasCalendario,
            'eventosPorDia' => $eventosPorDia,
            'eventos' => $eventos,
            'mesActual' => $mesActual,
            'mesAnterior' => $mesActual->copy()->subMonth()->format('Y-m'),
            'mesSiguiente' => $mesActual->copy()->addMonth()->format('Y-m'),
            'hoy' => $hoy,
            'puedeAdministrarEventos' => $canManageEvents,
        ];
    }

    private function canManageEvents(Request $request): bool
    {
        $user = $request->user();

        return $user instanceof User
            && \in_array($user->role, [User::ROLE_ADMIN], true);
    }
}
