<?php

namespace App\Http\Controllers;

use App\Models\CalendarioEvento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CalendarioEventoController extends Controller
{
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $this->validateEvent($request);
        $month = $validated['mes'] ?? null;
        unset($validated['mes']);

        CalendarioEvento::query()->create($validated);

        return $this->calendarResponse($request, $month, 'El evento se creó correctamente.');
    }

    public function update(Request $request, CalendarioEvento $evento): RedirectResponse|JsonResponse
    {
        $validated = $this->validateEvent($request);
        $month = $validated['mes'] ?? null;
        unset($validated['mes']);

        $evento->update($validated);

        return $this->calendarResponse($request, $month, 'El evento se actualizó correctamente.');
    }

    public function destroy(Request $request, CalendarioEvento $evento): RedirectResponse|JsonResponse
    {
        $month = $request->validate([
            'mes' => ['nullable', 'date_format:Y-m'],
        ])['mes'] ?? null;

        $evento->delete();

        return $this->calendarResponse($request, $month, 'El evento se eliminó correctamente.');
    }

    /**
     * @return array{titulo: string, fecha: string, descripcion?: string|null, mes?: string|null}
     */
    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'titulo' => ['required', 'string', 'max:150'],
            'fecha' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'mes' => ['nullable', 'date_format:Y-m'],
        ], [
            'fecha.after_or_equal' => 'La fecha del evento no puede ser anterior a hoy.',
        ]);
    }

    private function calendarResponse(Request $request, ?string $month, string $message): RedirectResponse|JsonResponse
    {
        $month ??= today()->format('Y-m');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
            ]);
        }

        return redirect()
            ->route('calendario.institucional', ['mes' => $month])
            ->with('status', $message);
    }
}
