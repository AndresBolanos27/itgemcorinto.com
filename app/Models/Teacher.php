<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AcademicLoad;
use App\Models\Group;
use App\Models\Subject;
use App\Models\User;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'tipo_documento',
        'cedula',
        'correo',
        'celular',
        'titulo',
        'fecha_nacimiento',
        'direccion',
        'sexo',
        'estado',
        'eps',
        'pension',
        'caja_compensacion',
        'user_id'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'academic_loads', 'teacher_id', 'group_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'academic_loads', 'teacher_id', 'subject_id');
    }

    /**
     * Get the academic loads for the teacher.
     */
    public function academicLoads()
    {
        return $this->hasMany(AcademicLoad::class);
    }
    
    /**
     * Get the groups through academic loads.
     * This will return groups that are specifically assigned to this teacher via academic loads.
     */
    public function groupsViaLoads()
    {
        return $this->belongsToMany(Group::class, 'academic_loads', 'teacher_id', 'group_id')
                    ->where('academic_loads.estado', 'activo');
    }
    
    /**
     * Get the subjects through academic loads.
     * This will return subjects that are specifically assigned to this teacher via academic loads.
     */
    public function subjectsViaLoads()
    {
        return $this->belongsToMany(Subject::class, 'academic_loads', 'teacher_id', 'subject_id')
                    ->where('academic_loads.estado', 'activo');
    }
}
