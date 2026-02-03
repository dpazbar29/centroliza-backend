<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = [
        'centro_id',
        'curso_id',
        'tutor_id',
    ];

    // Relaciones
    public function centro()
    {
        return $this->belongsTo(Centro::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
