<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Libro de Reclamaciones - INDECOPI Perú.
 * Registro de reclamos y quejas de consumidores.
 */
class LibroReclamacion extends Model
{
    protected $table = 'libro_reclamaciones';

    protected $fillable = [
        'codigo_reclamo',
        'tipo_documento',
        'numero_documento',
        'nombre_completo',
        'direccion',
        'telefono',
        'email',
        'tipo_reclamo',
        'descripcion',
        'pedido_consumidor',
        'evento_id',
        'user_id',
        'estado',
        'respuesta_empresa',
        'fecha_respuesta',
    ];

    protected function casts(): array
    {
        return [
            'fecha_respuesta' => 'datetime',
        ];
    }

    public const TIPOS_DOCUMENTO = ['DNI', 'CE', 'Pasaporte'];
    public const TIPOS_RECLAMO = ['reclamo', 'queja'];
    public const ESTADOS = ['pendiente', 'atendido', 'cerrado'];

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'evento_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Genera código correlativo LR-AÑO-000001 */
    public static function generarCodigo(): string
    {
        $year = date('Y');
        $ultimo = static::where('codigo_reclamo', 'like', "LR-{$year}-%")
            ->orderByDesc('id')
            ->first();
        $num = $ultimo ? (int) substr($ultimo->codigo_reclamo, -6) + 1 : 1;
        return sprintf('LR-%s-%06d', $year, $num);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopePorEstado($query, string $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopeEntreFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('created_at', [$desde, $hasta]);
    }
}
