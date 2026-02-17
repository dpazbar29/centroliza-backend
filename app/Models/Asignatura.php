<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;

    protected $fillable = [
        'curso_id',
        'nombre',
        'codigo',
        'horas_semanales',
        'tipo',
    ];

    // Relaciones
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function profesores()
    {
        return $this->belongsToMany(User::class, 'profesor_asignaturas', 'asignatura_id', 'profesor_id');
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

}
