<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = [
        'curso_id',
        'asignatura_id',
        'profesor_tutor_id', 
        'nombre_grupo',
        'capacidad_maxima',
    ];

    public function curso()
    { 
        return $this->belongsTo(Curso::class); 
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }
    
    public function profesorTutor()
    {
        return $this->belongsTo(User::class, 'profesor_tutor_id');
    }
    
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'grupo_id');
    }
    
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class);
    }
}
