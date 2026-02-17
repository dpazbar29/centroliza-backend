<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolJerarquia extends Model
{
    use HasFactory;

    protected $table = 'roles_jerarquia';

    protected $fillable = [
        'centro_id',
        'user_id',
        'tipo',
        'orden_prioridad',
        'fecha_asignacion',
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'orden_prioridad' => 'integer',
    ];

    // Relaciones
    public function centro()
    {
        return $this->belongsTo(Centro::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden_prioridad')->orderBy('fecha_asignacion');
    }

    public function scopeDelCentro($query, $centroId)
    {
        return $query->where('centro_id', $centroId);
    }
}
