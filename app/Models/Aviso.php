<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aviso extends Model
{
    use HasFactory;

    protected $fillable = [
        'centro_id',
        'titulo',
        'contenido',
        'tipo', 
        'fecha_publicacion',
        'fecha_expiracion',
        'visible',
    ];

    protected $casts = [
        'fecha_publicacion' => 'date',
        'fecha_expiracion' => 'date',
        'visible' => 'boolean',
    ];

    public function centro()
    {
        return $this->belongsTo(Centro::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('visible', true)->where(fn($q) => $q->whereNull('fecha_expiracion')->orWhere('fecha_expiracion', '>', now()));
    }
}
