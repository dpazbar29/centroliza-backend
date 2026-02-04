<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Matricula extends Pivot
{
    protected $table = 'matriculas';
    
    protected $fillable = ['alumno_id', 'curso_id', 'tutor_id'];
    
    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }
    
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }
}
