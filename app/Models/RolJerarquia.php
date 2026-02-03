<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolJerarquia extends Model
{
    use HasFactory;

    protected $table = 'roles_jerarquia';

    protected $fillable = [
        'centro_id',
        'user_id',
        'tipo',
    ];

    // Relaciones
    public function centro()
    {
        return $this->belongsTo(Centro::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
