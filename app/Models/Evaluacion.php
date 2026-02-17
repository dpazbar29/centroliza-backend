<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evaluacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumno_id',
        'asignatura_id',
        'grupo_id', 
        'nota',
        'trimestre',
        'fecha',
        'observaciones',
    ];

    protected $casts = [
        'nota' => 'decimal:1',
        'fecha' => 'date',
    ];

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }
    
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }
    
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
}
