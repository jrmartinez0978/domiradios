<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo para gestionar los respaldos de datos SEO de las emisoras
 * 
 * @property int $id
 * @property int $radio_id
 * @property array $snapshot Datos SEO respaldados en formato JSON
 * @property string|null $created_by Identifica quien creó el respaldo (API, admin, etc)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class RadioSeoBackup extends Model
{
    /**
     * Atributos asignables masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'radio_id',
        'snapshot',
        'created_by'
    ];
    
    /**
     * Atributos que deben ser convertidos de tipo
     *
     * @var array<string, string>
     */
    protected $casts = [
        'snapshot' => 'json',
    ];
    
    /**
     * Relación con la radio a la que pertenece este respaldo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function radio(): BelongsTo
    {
        return $this->belongsTo(Radio::class);
    }
}
