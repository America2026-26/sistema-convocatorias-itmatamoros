<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DocumentoExpediente;

class ExpedienteAdminController extends Controller
{
    public function index()
    {
        // Obtener todos los usuarios que tienen expedientes o postulaciones (aspirantes)
        $aspirantes = User::with('documentosExpediente', 'postulaciones')
            ->has('documentosExpediente') // Opcional: solo los que hayan subido algo
            ->get();

        return view('admin.expedientes.index', compact('aspirantes'));
    }

    public function actualizarEstatus(Request $request, $id)
    {
        $request->validate([
            'estatus' => 'required|string|in:Aprobado,Rechazado,Pendiente de revisión'
        ]);

        $documento = DocumentoExpediente::findOrFail($id);
        $documento->estatus = $request->estatus;
        $documento->save();

        return back()->with('success', 'Estatus actualizado correctamente.');
    }
}