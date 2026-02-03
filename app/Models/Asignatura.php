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
    ];

    // Relaciones
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function profesores()
    {
        return $this->belongsToMany(User::class, 'profesor_asignatura', 'asignatura_id', 'profesor_id');
    }
}
