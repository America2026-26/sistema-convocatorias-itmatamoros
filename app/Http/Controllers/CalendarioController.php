<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function index()
    {
        $eventos = [
            [
                'categoria' => 'Inscripciones y Reinscripciones',
                'eventos' => [
                    ['nombre' => 'Inscripciones para estudiantes de nuevo ingreso', 'fecha' => '03 al 17 de agosto de 2026'],
                    ['nombre' => 'Reinscripciones del semestre (Licenciatura y Posgrado)', 'fecha' => '10 y 11 de agosto de 2026'],
                    ['nombre' => 'Reinscripciones extemporáneas', 'fecha' => '12 y 13 de agosto de 2026'],
                ]
            ],
            [
                'categoria' => 'Actividades Académicas y Claves',
                'eventos' => [
                    ['nombre' => 'Inicio de actividades institucionales', 'fecha' => '03 de agosto de 2026'],
                    ['nombre' => 'Inicio de clases período agosto - diciembre 2026', 'fecha' => '24 de agosto de 2026'],
                    ['nombre' => '54 Aniversario del Instituto Tecnológico de Matamoros', 'fecha' => '02 de septiembre de 2026'],
                    ['nombre' => 'Encuentro Académico 2026', 'fecha' => '26 al 30 de octubre de 2026'],
                    ['nombre' => 'Fin de clases', 'fecha' => '11 de diciembre de 2026'],
                    ['nombre' => 'Fecha límite de captura de calificaciones en el SII2', 'fecha' => '14 de diciembre de 2026'],
                ]
            ],
            [
                'categoria' => 'Días No Laborables y Conmemorativos',
                'eventos' => [
                    ['nombre' => 'Día de la Independencia de México', 'fecha' => '16 de septiembre de 2026'],
                    ['nombre' => 'Día de Muertos', 'fecha' => '02 de noviembre de 2026'],
                    ['nombre' => 'Aniversario de la Revolución Mexicana (Suspensión)', 'fecha' => '16 de noviembre de 2026'],
                    ['nombre' => 'Periodo vacacional de diciembre', 'fecha' => '21 de dic de 2026 al 05 de ene de 2027'],
                ]
            ]
        ];

        return view('calendario.index', compact('eventos'));
    }
}