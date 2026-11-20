<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Convocatoria extends Model
{
    public const TEACHING_POSITIONS = [
        'E3617' => 'Nivel 17 · ½ Tiempo (+6a9 hrs)',
        'E3717' => 'Nivel 17 · ¾ Tiempo (+6a9 hrs)',
        'E3817' => 'Nivel 17 · Tiempo Completo',
        'E3615' => 'Nivel 15 · ½ Tiempo',
        'E3715' => 'Nivel 15 · ¾ Tiempo',
        'E3815' => 'Nivel 15 · Tiempo Completo',
        'E3613' => 'Nivel 13 · ½ Tiempo',
        'E3713' => 'Nivel 13 · ¾ Tiempo',
        'E3813' => 'Nivel 13 · Tiempo Completo',
        'E3611' => 'Nivel 11 · ½ Tiempo',
        'E3711' => 'Nivel 11 · ¾ Tiempo',
        'E3811' => 'Nivel 11 · Tiempo Completo',
        'E3609' => 'Nivel 09 · ½ Tiempo',
        'E3709' => 'Nivel 09 · ¾ Tiempo',
        'E3809' => 'Nivel 09 · Tiempo Completo',
        'E3607' => 'Nivel 07 · ½ Tiempo',
        'E3707' => 'Nivel 07 · ¾ Tiempo',
        'E3807' => 'Nivel 07 · Tiempo Completo',
    ];

    public const EVALUATION_TESTS = [
        'Crítica escrita de programas',
        'Exposición oral o didáctica',
        'Interrogatorio',
        'Proyecto de investigación',
    ];

    protected $fillable = [
        'titulo',
        'tipo_concurso',
        'tipo_personal',
        'categoria_nivel',
        'plaza_disponible',
        'jornada_horario',
        'salario',
        'fecha_inicio',
        'fecha_fin',
        'estatus',
        'requisitos_especificos',
        'detalles_especificos',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'salario' => 'decimal:2',
            'detalles_especificos' => 'array',
        ];
    }

    public function postulaciones(): HasMany
    {
        return $this->hasMany(Postulacion::class);
    }

    public function isOpen(): bool
    {
        return $this->estatus === 'Activa'
            && now()->betweenIncluded(
                $this->fecha_inicio->copy()->startOfDay(),
                $this->fecha_fin->copy()->endOfDay(),
            );
    }

    /**
     * Obtiene las claves de plazas docentes ocupadas.
     *
     * @return list<string>
     */
    public function occupiedTeachingPositions(): array
    {
        $details = $this->detalles_especificos ?? [];
        $occupied = [];

        foreach ((array) ($details['plazas_ocupadas'] ?? []) as $code) {
            if (is_string($code) && array_key_exists($code, self::TEACHING_POSITIONS)) {
                $occupied[] = $code;
            }
        }

        return array_values(array_unique($occupied));
    }

    /**
     * @return array<string, array{label: string, description: string}>
     */
    public function applicantDocumentRequirements(): array
    {
        return match ([$this->tipo_personal, $this->tipo_concurso]) {
            ['Docente', 'Abierta'] => [
                'cv_documentado' => [
                    'label' => 'Currículum Vitae documentado en PDF',
                    'description' => 'Debe incluir constancias de experiencia docente y profesional.',
                ],
                'titulos_grados' => [
                    'label' => 'Título(s) profesional(es) y/o grado(s) académico(s) en PDF',
                    'description' => 'Copia del título de licenciatura, maestría o doctorado acorde a la disciplina y nivel a concursar.',
                ],
                'cedula_profesional' => [
                    'label' => 'Cédula profesional en PDF',
                    'description' => 'Documento legal que avale el ejercicio de su profesión.',
                ],
                'identificacion_oficial' => [
                    'label' => 'Identificación oficial vigente en PDF',
                    'description' => 'Credencial para votar (INE) o pasaporte.',
                ],
            ],
            ['Docente', 'Cerrada'] => [
                'antiguedad_definitiva' => [
                    'label' => 'Constancia de antigüedad definitiva en PDF',
                    'description' => 'Documento que avale al menos un año de servicio ininterrumpido en la categoría y nivel actual.',
                ],
                'cv_actualizacion_merito' => [
                    'label' => 'Currículum de actualización docente y mérito académico en PDF',
                    'description' => 'Comprobantes de cursos de superación, elaboración de material didáctico, publicaciones o proyectos recientes.',
                ],
                'evaluaciones_desempeno' => [
                    'label' => 'Evaluaciones del desempeño docente en PDF',
                    'description' => 'Constancias o historial de evaluaciones satisfactorias en su periodo activo.',
                ],
            ],
            ['No docente', 'Abierta'] => [
                'solicitud_cv' => [
                    'label' => 'Solicitud de empleo o Curriculum Vitae en PDF',
                    'description' => 'Detalle su historial laboral y sus datos generales.',
                ],
                'comprobante_estudios' => [
                    'label' => 'Comprobante de estudios en PDF',
                    'description' => 'Certificado, título o diploma que acredite la escolaridad mínima requerida para el puesto.',
                ],
                'identificacion_curp' => [
                    'label' => 'Identificación oficial y CURP en PDF',
                    'description' => 'Adjunte ambos documentos en un solo archivo PDF.',
                ],
                'certificado_medico' => [
                    'label' => 'Certificado médico general en PDF',
                    'description' => 'Documento requerido institucionalmente para descartar impedimentos físicos para el puesto.',
                ],
            ],
            ['No docente', 'Cerrada'] => [
                'constancia_base_antiguedad' => [
                    'label' => 'Constancia de base y antigüedad en PDF',
                    'description' => 'Documento oficial que avale un mínimo de seis meses de base en la categoría inmediata inferior.',
                ],
                'comprobantes_capacitacion_escolaridad' => [
                    'label' => 'Comprobantes de capacitación y escolaridad en PDF',
                    'description' => 'Diplomas o constancias de cursos internos que aporten puntos al factor de conocimientos.',
                ],
                'historial_laboral' => [
                    'label' => 'Constancia de historial laboral o expediente en PDF',
                    'description' => 'Emitida por Recursos Humanos para validar disciplina y puntualidad.',
                ],
            ],
        };
    }
}