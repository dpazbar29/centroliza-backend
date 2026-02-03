<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'etapa_id',
        'nombre',
    ];

    // Relaciones
    public function etapa()
    {
        return $this->belongsTo(Etapa::class);
    }

    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class);
    }

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }
}
