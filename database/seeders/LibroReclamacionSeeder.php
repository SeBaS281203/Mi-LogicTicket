<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\LibroReclamacion;
use Illuminate\Database\Seeder;

class LibroReclamacionSeeder extends Seeder
{
    public function run(): void
    {
        $eventos = Event::published()->take(3)->pluck('id');

        $datos = [
            [
                'tipo_documento' => 'DNI',
                'numero_documento' => '45678901',
                'nombre_completo' => 'Ana García López',
                'direccion' => 'Av. Ejemplo 123, Lima',
                'telefono' => '999111222',
                'email' => 'ana@example.com',
                'tipo_reclamo' => 'reclamo',
                'descripcion' => 'No recibí el correo con las entradas después de la compra. El evento es mañana y necesito el PDF.',
                'pedido_consumidor' => 'Envío urgente del PDF de entradas o reembolso.',
                'estado' => 'pendiente',
            ],
            [
                'tipo_documento' => 'CE',
                'numero_documento' => '001234567',
                'nombre_completo' => 'Carlos Mendoza',
                'direccion' => 'Jr. Los Pinos 456, Arequipa',
                'telefono' => '988777666',
                'email' => 'carlos@example.com',
                'tipo_reclamo' => 'queja',
                'descripcion' => 'La atención al cliente por chat fue lenta.',
                'pedido_consumidor' => null,
                'estado' => 'atendido',
                'respuesta_empresa' => 'Agradecemos su comentario. Hemos reforzado el equipo de soporte.',
                'fecha_respuesta' => now()->subDays(2),
            ],
        ];

        foreach ($datos as $dato) {
            $codigo = LibroReclamacion::generarCodigo();
            LibroReclamacion::firstOrCreate(
                ['codigo_reclamo' => $codigo],
                array_merge($dato, [
                    'codigo_reclamo' => $codigo,
                    'evento_id' => $eventos->isNotEmpty() ? $eventos->random() : null,
                    'user_id' => null,
                ])
            );
        }
    }
}
