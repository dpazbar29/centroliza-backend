<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asistencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumno_id',
        'grupo_id',
        'fecha',
        'estado', 
        'hora_entrada',
        'justificacion',
        'tipo',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_entrada' => 'datetime:H:i',
    ];

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }
    
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
}
