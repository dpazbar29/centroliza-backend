<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'slug',
        'email_director',
        'telefono',
    ];

    // Relaciones
    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function rolesJerarquia()
    {
        return $this->hasMany(RolJerarquia::class);
    }

    public function etapas()
    {
        return $this->hasMany(Etapa::class);
    }

    public function avisos()
    {
        return $this->hasMany(Aviso::class);
    }
}
