<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'centro_id',
        'name',
        'email',
        'dni',
        'fecha_nacimiento',
        'telefono',
        'role',
        'status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'fecha_nacimiento' => 'datetime',
        ];
    }

    // Relaciones
    public function centro()
    {
        return $this->belongsTo(Centro::class);
    }

    public function rolJerarquia()
    {
        return $this->hasOne(RolJerarquia::class, 'user_id');
    }

    public function asignaturas()
    {
        return $this->belongsToMany(Asignatura::class, 'profesor_asignatura', 'profesor_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'alumno_id');
    }

    public function matriculasTutor()
    {
        return $this->hasMany(Matricula::class, 'tutor_id');
    }

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'matriculas')->using(Matricula::class);
    }
}
