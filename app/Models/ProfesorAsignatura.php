<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProfesorAsignatura extends Pivot
{
    use HasFactory;

    protected $table = 'profesor_asignaturas';

    protected $fillable = ['profesor_id', 'asignatura_id', 'ano_academico', 'horas_asignadas'];

    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }
}
