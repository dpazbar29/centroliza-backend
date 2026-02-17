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
        'codigo_curso',
        'ano_academico',
    ];

    protected $casts = [
        'ano_academico' => 'date:Y',
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

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function alumnos()
    {
        return $this->belongsToMany(User::class, 'matriculas', 'curso_id', 'alumno_id');
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

}
