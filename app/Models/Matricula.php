<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Matricula extends Pivot
{
    protected $table = 'matriculas';
    
    protected $fillable = ['alumno_id', 'curso_id', 'tutor_id', 'grupo_id', 'fecha_matricula', 'estado', 'fecha_baja'];

    protected function casts()
    {
        return [
            'fecha_matricula' => 'date',
            'fecha_baja' => 'date',
        ];
    }
    
    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
    
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

}
