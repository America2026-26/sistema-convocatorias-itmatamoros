<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Postulacion;
use App\Models\DocumentoExpediente; // Asegúrate de usar tu modelo o tabla de documentos

class ExpedienteController extends Controller
{
    public function estado()
    {
        $user = Auth::user();
        
        $postulaciones = Postulacion::where('user_id', $user->id)
            ->with('convocatoria')
            ->latest()
            ->get();

        // Obtener los documentos del usuario organizados por tipo
        // Si usas una tabla llamada 'documento_expedientes' con columnas 'user_id', 'tipo', 'nombre_original', 'ruta'
        $documentos = DocumentoExpediente::where('user_id', $user->id)->get()->keyBy('tipo');

        return view('expediente.estado', compact('user', 'postulaciones', 'documentos'));
    }

    public function subirDocumento(Request $request)
    {
        $request->validate([
            'tipo_documento' => 'required|string|in:titulo_cedula,constancia_servicios,cv_soporte,identificacion',
            'archivo' => 'required|mimes:pdf|max:5120', // Máximo 5MB
        ]);

        $user = Auth::user();
        $file = $request->file('archivo');
        $nombreOriginal = $file->getClientOriginalName();
        
        // Guardar el archivo en storage/app/public/expedientes/{user_id}
        $path = $file->store("expedientes/{$user->id}", 'public');

        // Guardar o actualizar el registro en la base de datos
        DocumentoExpediente::updateOrCreate(
            ['user_id' => $user->id, 'tipo' => $request->tipo_documento],
            [
                'nombre_original' => $nombreOriginal,
                'ruta' => $path,
                'estatus' => 'Pendiente de revisión'
            ]
        );

        return response()->json([
            'success' => true,
            'nombre_archivo' => $nombreOriginal,
            'url' => Storage::url($path)
        ]);
    }
}