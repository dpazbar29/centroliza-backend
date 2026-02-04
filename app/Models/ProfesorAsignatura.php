<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProfesorAsignatura extends Pivot
{
    use HasFactory;

    protected $table = 'profesor_asignaturas';
}
