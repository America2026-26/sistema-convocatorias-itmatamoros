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
        // Obtenemos todos los usuarios registrados en el sistema
        $aspirantes = User::all();

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