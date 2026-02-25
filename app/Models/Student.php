<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'tipo_documento',
        'cedula',
        'correo',
        'celular',
        'fecha_nacimiento',
        'direccion',
        'estado',
        'genero',
        'grupo_etnico',
        'eps',
        'acudiente',
        'telefono_acudiente',
        'user_id',
        'group_id'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
